<?php /** @noinspection ALL */


use App\Domain\User\User;

test('User - Password', function () {
    $password = password_hash('teste123', PASSWORD_ARGON2ID);
    $user = new User(['password' => $password]);
    expect($user->comparePassword('teste123'))->toBeTrue();
    $user = new User(['password' => $password]);
    expect($user->comparePassword('teste'))->toBeFalse();

});

