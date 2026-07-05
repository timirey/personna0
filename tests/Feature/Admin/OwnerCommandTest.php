<?php

use App\Models\User;

it('creates the single owner account', function () {
    $this->artisan('personna:owner', [
        'email' => 'owner@personna0.com',
        'password' => 'secret-pass',
    ])->assertSuccessful();

    $this->assertDatabaseHas('users', ['email' => 'owner@personna0.com']);
    expect(User::where('email', 'owner@personna0.com')->count())->toBe(1);
});

it('is idempotent and updates the password on re-run', function () {
    $this->artisan('personna:owner', ['email' => 'o@e.com', 'password' => 'first']);
    $first = User::where('email', 'o@e.com')->first()->password;

    $this->artisan('personna:owner', ['email' => 'o@e.com', 'password' => 'second']);

    expect(User::where('email', 'o@e.com')->count())->toBe(1)
        ->and(User::where('email', 'o@e.com')->first()->password)->not->toBe($first);
});
