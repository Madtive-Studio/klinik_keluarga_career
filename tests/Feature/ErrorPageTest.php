<?php

it('renders custom 404 page for web requests', function () {
    config(['app.debug' => false]);

    $response = $this->get('/halaman-tidak-ada');

    $response->assertNotFound()
        ->assertSee('Halaman Tidak Ditemukan');
});

it('returns json 404 for api-like requests', function () {
    config(['app.debug' => false]);

    $response = $this->getJson('/halaman-tidak-ada');

    $response->assertNotFound()
        ->assertJson(['message' => 'Halaman tidak ditemukan.']);
});

it('health check endpoint is available', function () {
    $response = $this->get('/up');

    $response->assertOk();
});
