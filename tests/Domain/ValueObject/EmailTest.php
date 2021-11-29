<?php

use App\Domain\ValueObject\Email;

test('Valid Email', function () {
    $email = new Email('arthur.stuepp@gmail.com');
    expect((string)$email)->
    toEqual('arthur.stuepp@gmail.com');
    expect($email->jsonSerialize())->
    toEqual('art****@gmail.com');
});

test('Invalid Email', function () {
    new Email('arthurelasdasdsa.com');
})->throws(
    Exception::class,
    'Email invalido'
);
