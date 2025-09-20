<?php

declare(strict_types=1);

use Vtqnm\BxbpCli\Validator\Constraints\PartnerName;

beforeEach(function () {
    $this->constraint = new PartnerName();
});

it('validates partner name within length limit', function () {
    $validNames = [
        'Partner Company',
        'Company Name Inc.',
        str_repeat('a', 255), // exactly 255 characters
        '',  // empty is allowed
        'Компания Партнер ООО'
    ];

    foreach ($validNames as $name) {
        $result = $this->constraint->validate($name);
        
        expect($result)->toBeTrue("Partner name '{$name}' should be valid");
        expect($this->constraint->getError())->toBeNull();
    }
});

it('rejects partner name exceeding 255 characters', function () {
    $longName = str_repeat('a', 256); // 256 characters

    $result = $this->constraint->validate($longName);

    expect($result)->toBeFalse();
    expect($this->constraint->getError())->toBe('Partner name must not exceed 255 characters');
});

it('handles non-string values by casting to string', function () {
    $result = $this->constraint->validate(123);

    expect($result)->toBeTrue(); // '123' is valid as it's under 255 chars
    expect($this->constraint->getError())->toBeNull();
});
