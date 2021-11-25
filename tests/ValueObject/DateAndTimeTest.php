<?php

use App\Domain\ValueObject\DateAndTime;

test('Valid Date - tooString()', function () {
    $date = new DateAndTime('2021-10-10 10:10:10');
    expect((string)$date)->
    toEqual('2021-10-10 10:10:10');
});

test('Valid Date - jsonSerialize()', function () {
    $date = new DateAndTime('2021-10-10 10:10:10');
    expect($date->jsonSerialize())->
    toEqual('10-10-21 10:10:10');
});

test('Invalid Date', function () {
    new DateAndTime('2021--10-10 10:10:10');
})->throws(
    Exception::class,
    'Formatado de data invaliado. Precisa ser no formtado AAAA-MM-DD HH:MM:SS'
);
