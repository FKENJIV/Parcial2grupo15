<?php

it('returns a successful response', function () {
    $response = $this->get('/');

    $response->assertStatus(302); // Cambiar 200 por 302 (Redirección)
});
