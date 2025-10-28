<?php

use App\Models\Group;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Support\Str;

test('docente cannot create group with a block longer than 3 hours', function () {
    $user = User::factory()->create([
        'role' => 'docente',
        'api_token' => hash('sha256', $plain = Str::random(60)),
    ]);

    $payload = [
        'name' => 'Largo',
        'schedules' => [
            ['day_of_week' => 'Mon', 'start_time' => '08:00', 'end_time' => '12:30'], // 4.5 hours
        ],
    ];

    $response = $this->postJson('/api/grupos', $payload, ['Authorization' => 'Bearer ' . $plain]);
    $response->assertStatus(422);
    $response->assertJsonFragment(['message' => 'Cada bloque no puede exceder 3 horas']);
});

test('docente cannot create group assigned to more than 3 distinct days', function () {
    $user = User::factory()->create([
        'role' => 'docente',
        'api_token' => hash('sha256', $plain = Str::random(60)),
    ]);

    $payload = [
        'name' => 'MuchosDias',
        'schedules' => [
            ['day_of_week' => 'Mon', 'start_time' => '08:00', 'end_time' => '09:00'],
            ['day_of_week' => 'Tue', 'start_time' => '08:00', 'end_time' => '09:00'],
            ['day_of_week' => 'Wed', 'start_time' => '08:00', 'end_time' => '09:00'],
            ['day_of_week' => 'Thu', 'start_time' => '08:00', 'end_time' => '09:00'],
        ],
    ];

    $response = $this->postJson('/api/grupos', $payload, ['Authorization' => 'Bearer ' . $plain]);
    $response->assertStatus(422);
    $response->assertJsonFragment(['message' => 'No se pueden asignar a más de 3 días por grupo']);
});

test('only superusuario can create docentes', function () {
    // create a docente and try to create another teacher
    $user = User::factory()->create([
        'role' => 'docente',
        'api_token' => hash('sha256', $plain = Str::random(60)),
    ]);

    $payload = [
        'name' => 'Nuevo Docente',
        'email' => 'nuevo@docente.test',
        'password' => 'clave123',
    ];

    $response = $this->postJson('/api/docentes', $payload, ['Authorization' => 'Bearer ' . $plain]);
    $response->assertStatus(403);
    $response->assertJsonFragment(['message' => 'Permiso denegado']);
});
