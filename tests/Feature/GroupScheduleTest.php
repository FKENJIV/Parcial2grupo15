<?php

use App\Models\Group;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Support\Str;

test('docente can create group when there is no schedule overlap', function () {
    // create docente
    $user = User::factory()->create([
        'role' => 'docente',
        'api_token' => hash('sha256', $plain = Str::random(60)),
    ]);

    // existing group on Monday 08:00-10:00
    $group = Group::create([
        'name' => 'Existente',
        'subject' => 'Mat',
        'capacity' => 30,
        'teacher_id' => $user->id,
    ]);

    Schedule::create([
        'group_id' => $group->id,
        'day_of_week' => 'Mon',
        'start_time' => '08:00',
        'end_time' => '10:00',
    ]);

    // attempt to create new group on Tue (no overlap)
    $payload = [
        'name' => 'Nuevo',
        'subject' => 'Fis',
        'capacity' => 25,
        'schedules' => [
            [ 'day_of_week' => 'Tue', 'start_time' => '08:00', 'end_time' => '10:00' ]
        ],
    ];

    $response = $this->postJson('/api/grupos', $payload, ['Authorization' => 'Bearer ' . $plain]);

    $response->assertStatus(201);
    $this->assertDatabaseHas('groups', ['name' => 'Nuevo', 'teacher_id' => $user->id]);
});

test('docente cannot create group with overlapping schedule', function () {
    $user = User::factory()->create([
        'role' => 'docente',
        'api_token' => hash('sha256', $plain = Str::random(60)),
    ]);

    $group = Group::create([
        'name' => 'Existente',
        'subject' => 'Mat',
        'capacity' => 30,
        'teacher_id' => $user->id,
    ]);

    Schedule::create([
        'group_id' => $group->id,
        'day_of_week' => 'Mon',
        'start_time' => '08:00',
        'end_time' => '10:00',
    ]);

    // new group overlaps on Monday 09:00-11:00
    $payload = [
        'name' => 'Choque',
        'subject' => 'Bio',
        'capacity' => 20,
        'schedules' => [
            [ 'day_of_week' => 'Mon', 'start_time' => '09:00', 'end_time' => '11:00' ]
        ],
    ];

    $response = $this->postJson('/api/grupos', $payload, ['Authorization' => 'Bearer ' . $plain]);

    $response->assertStatus(422);
    $response->assertJsonFragment(['message' => 'Conflicto de horario con otro grupo asignado al docente']);
    $this->assertDatabaseMissing('groups', ['name' => 'Choque']);
});

test('cannot create group when aula is occupied by another group', function () {
    // existing teacher and group occupying aula A101 on Mon 08:00-10:00
    $other = User::factory()->create([
        'role' => 'docente',
        'api_token' => hash('sha256', $plainOther = Str::random(60)),
    ]);

    $group = Group::create([
        'name' => 'ConAula',
        'subject' => 'Mat',
        'capacity' => 30,
        'teacher_id' => $other->id,
    ]);

    Schedule::create([
        'group_id' => $group->id,
        'day_of_week' => 'Mon',
        'start_time' => '08:00',
        'end_time' => '10:00',
        'aula' => 'A101',
    ]);

    // attempt by a different docente to create overlapping group in same aula
    $user = User::factory()->create([
        'role' => 'docente',
        'api_token' => hash('sha256', $plain = Str::random(60)),
    ]);

    $payload = [
        'name' => 'ChoqueAula',
        'subject' => 'Bio',
        'capacity' => 20,
        'schedules' => [
            [ 'day_of_week' => 'Mon', 'start_time' => '09:00', 'end_time' => '11:00', 'aula' => 'A101' ]
        ],
    ];

    $response = $this->postJson('/api/grupos', $payload, ['Authorization' => 'Bearer ' . $plain]);
    $response->assertStatus(422);
    $response->assertJsonFragment(['message' => 'Aula ocupada en ese horario']);
});

test('docente cannot create group if another docente has overlapping schedule', function () {
    // existing teacher with a schedule Mon 08:00-10:00
    $other = User::factory()->create([
        'role' => 'docente',
        'api_token' => hash('sha256', $plainOther = Str::random(60)),
    ]);

    $group = Group::create([
        'name' => 'OtroDoc',
        'subject' => 'Mat',
        'capacity' => 30,
        'teacher_id' => $other->id,
    ]);

    Schedule::create([
        'group_id' => $group->id,
        'day_of_week' => 'Mon',
        'start_time' => '08:00',
        'end_time' => '10:00',
    ]);

    $user = User::factory()->create([
        'role' => 'docente',
        'api_token' => hash('sha256', $plain = Str::random(60)),
    ]);

    $payload = [
        'name' => 'ChoqueDocente',
        'subject' => 'Bio',
        'capacity' => 20,
        'schedules' => [
            [ 'day_of_week' => 'Mon', 'start_time' => '09:00', 'end_time' => '11:00' ]
        ],
    ];

    $response = $this->postJson('/api/grupos', $payload, ['Authorization' => 'Bearer ' . $plain]);
    $response->assertStatus(422);
    $response->assertJsonFragment(['message' => 'Conflicto de horario con otro docente']);
});
