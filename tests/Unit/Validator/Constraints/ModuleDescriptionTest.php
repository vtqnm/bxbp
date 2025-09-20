<?php

declare(strict_types=1);

use Vtqnm\BxbpCli\Validator\Constraints\ModuleDescription;

beforeEach(function () {
    $this->constraint = new ModuleDescription();
});

it('validates module description within length limit', function () {
    $validDescriptions = [
        'Short description',
        'This is a longer description that explains what the module does in more detail.',
        str_repeat('a', 1000), // exactly 1000 characters
        '',  // empty is allowed
        'Описание модуля на русском языке с поддержкой Unicode символов'
    ];

    foreach ($validDescriptions as $description) {
        $result = $this->constraint->validate($description);
        
        expect($result)->toBeTrue("Module description should be valid");
        expect($this->constraint->getError())->toBeNull();
    }
});

it('rejects module description exceeding 1000 characters', function () {
    $longDescription = str_repeat('a', 1001); // 1001 characters

    $result = $this->constraint->validate($longDescription);

    expect($result)->toBeFalse();
    expect($this->constraint->getError())->toBe('Module description must not exceed 1000 characters');
});

it('handles non-string values by casting to string', function () {
    $result = $this->constraint->validate(123);

    expect($result)->toBeTrue(); // '123' is valid as it's under 1000 chars
    expect($this->constraint->getError())->toBeNull();
});
