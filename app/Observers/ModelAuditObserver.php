<?php

namespace App\Observers;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;

class ModelAuditObserver
{
    /**
     * Handle the Model "created" event.
     */
    public function created(Model $model): void
    {
        // No auditar los propios AuditLogs para evitar recursión
        if ($model instanceof AuditLog) {
            return;
        }

        $this->logAction('created', $model, null, $model->getAttributes());
    }

    /**
     * Handle the Model "updated" event.
     */
    public function updated(Model $model): void
    {
        // No auditar los propios AuditLogs para evitar recursión
        if ($model instanceof AuditLog) {
            return;
        }

        $this->logAction('updated', $model, $model->getOriginal(), $model->getChanges());
    }

    /**
     * Handle the Model "deleted" event.
     */
    public function deleted(Model $model): void
    {
        // No auditar los propios AuditLogs para evitar recursión
        if ($model instanceof AuditLog) {
            return;
        }

        $this->logAction('deleted', $model, $model->getAttributes(), null);
    }

    /**
     * Log the action to audit_logs
     */
    protected function logAction(string $action, Model $model, ?array $oldValues, ?array $newValues): void
    {
        try {
            if (!auth()->check()) {
                return;
            }

            // Usar DB directo para evitar disparar eventos
            \DB::table('audit_logs')->insert([
                'user_id' => auth()->id(),
                'action' => $action,
                'model_type' => get_class($model),
                'model_id' => $model->id,
                'old_values' => $oldValues ? json_encode($oldValues) : null,
                'new_values' => $newValues ? json_encode($newValues) : null,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Exception $e) {
            \Log::error('Error en ModelAuditObserver: ' . $e->getMessage());
        }
    }
}
