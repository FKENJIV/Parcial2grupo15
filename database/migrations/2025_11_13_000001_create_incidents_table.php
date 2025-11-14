<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('incidents', function (Blueprint $table) {
            $table->id();
            $table->string('aula', 50);
            $table->date('incident_date');
            $table->enum('type', ['daño', 'mantenimiento', 'limpieza', 'otro'])->default('otro');
            $table->text('description');
            $table->enum('status', ['reportado', 'en_proceso', 'resuelto'])->default('reportado');
            $table->foreignId('reported_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            $table->text('resolution_notes')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
            
            $table->index(['aula', 'incident_date']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('incidents');
    }
};
