<?php

use App\Domain\ValueObject\Document;

test('Valid Document', function () {
    $documentNumbers = new Document('09955452943');

    expect((string)$documentNumbers)->
    toEqual('09955452943');

    expect($documentNumbers->jsonSerialize())->
    toEqual('099.554.529-43');


    $formatedDocument = new Document('099.554.529-43');

    expect((string)$formatedDocument)->
    toEqual('09955452943');

    expect($formatedDocument->jsonSerialize())->
    toEqual('099.554.529-43');
});


test('Invalid Document lenght', function () {
    new Document('0995545294');
})->throws(
    Exception::class,
    'CPF invalido'
);

test('Invalid Document number', function () {
    new Document('09955452944');
})->throws(
    Exception::class,
    'CPF invalido'
);
test('Invalid Document equal number', function () {
    new Document('11111111111');
})->throws(
    Exception::class,
    'CPF invalido'
);
