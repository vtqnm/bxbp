<?php

declare(strict_types=1);

use Vtqnm\BxbpCli\Validator\Validation;
use Vtqnm\BxbpCli\Validator\Validator;

it('creates validator instance', function () {
    $validator = Validation::createValidator();

    expect($validator)->toBeInstanceOf(Validator::class);
});

it('creates new validator instance each time', function () {
    $validator1 = Validation::createValidator();
    $validator2 = Validation::createValidator();

    expect($validator1)->toBeInstanceOf(Validator::class);
    expect($validator2)->toBeInstanceOf(Validator::class);
    expect($validator1)->not()->toBe($validator2);
});
