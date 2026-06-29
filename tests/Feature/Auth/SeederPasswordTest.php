<?php

use App\Models\Candidate;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;

it('seeds login accounts with verifiable passwords', function () {
    $this->seed(DatabaseSeeder::class);

    $admin = User::where('level', 'admin')->first();
    $candidate = Candidate::where('email', 'usertest@gmail.com')->first();

    expect($admin)->not->toBeNull()
        ->and(Hash::check('12345678', $admin->password))->toBeTrue()
        ->and($candidate)->not->toBeNull()
        ->and(Hash::check('12345678', $candidate->password))->toBeTrue()
        ->and($candidate->email_verified_at)->not->toBeNull();
});

it('allows candidate login with seeded credentials', function () {
    $this->seed(DatabaseSeeder::class);

    $response = $this->post(route('candidate.login.process'), [
        'email' => 'usertest@gmail.com',
        'password' => '12345678',
    ]);

    $response->assertRedirect(route('candidate.home'));
    $this->assertAuthenticatedAs(Candidate::where('email', 'usertest@gmail.com')->first(), 'candidate');
});

it('allows admin login with seeded credentials', function () {
    $this->seed(DatabaseSeeder::class);

    $admin = User::where('level', 'admin')->first();

    $response = $this->post(route('admin.process'), [
        'email' => $admin->email,
        'password' => '12345678',
    ]);

    $response->assertRedirect(route('admin.dashboard'));
    $this->assertAuthenticatedAs($admin, 'admin');
});

it('stores registration password without double hashing', function () {
    Notification::fake();

    $response = $this->post(route('candidate.register.verify'), [
        'name' => 'Test User',
        'email' => 'newcandidate@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'phone' => '081234567890',
        'birth_date' => '2000-01-01',
        'address' => 'Cianjur',
    ]);

    $response->assertRedirect();

    $candidate = Candidate::where('email', 'newcandidate@example.com')->first();

    expect($candidate)->not->toBeNull()
        ->and(Hash::check('password123', $candidate->password))->toBeTrue();
});
