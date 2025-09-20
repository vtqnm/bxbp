<?php

declare(strict_types=1);

use Vtqnm\BxbpCli\Validator\Constraints\LanguageCode;

beforeEach(function () {
    $this->constraint = new LanguageCode();
});

it('validates correct language codes', function () {
    $validCodes = [
        'en',
        'ru'
    ];

    foreach ($validCodes as $code) {
        $result = $this->constraint->validate($code);
        
        expect($result)->toBeTrue("Language code '{$code}' should be valid");
        expect($this->constraint->getError())->toBeNull();
    }
});

it('rejects empty language code', function () {
    $result = $this->constraint->validate('');

    expect($result)->toBeFalse();
    expect($this->constraint->getError())->toBe('Module language code is required');
});

it('rejects whitespace-only language code', function () {
    $result = $this->constraint->validate('   ');

    expect($result)->toBeFalse();
    expect($this->constraint->getError())->toBe('Module language code is required');
});

it('rejects language code with uppercase letters', function () {
    $result = $this->constraint->validate('EN');

    expect($result)->toBeFalse();
    expect($this->constraint->getError())->toBe('Module language code must contain only lowercase letters and consist of exactly 2 characters');
});

it('rejects language code with mixed case', function () {
    $result = $this->constraint->validate('En');

    expect($result)->toBeFalse();
    expect($this->constraint->getError())->toBe('Module language code must contain only lowercase letters and consist of exactly 2 characters');
});

it('rejects language code with numbers', function () {
    $result = $this->constraint->validate('e1');

    expect($result)->toBeFalse();
    expect($this->constraint->getError())->toBe('Module language code must contain only lowercase letters and consist of exactly 2 characters');
});

it('rejects language code with special characters', function () {
    $result = $this->constraint->validate('e-');

    expect($result)->toBeFalse();
    expect($this->constraint->getError())->toBe('Module language code must contain only lowercase letters and consist of exactly 2 characters');
});

it('rejects language code longer than 2 characters', function () {
    $result = $this->constraint->validate('eng');

    expect($result)->toBeFalse();
    expect($this->constraint->getError())->toBe('Module language code must contain only lowercase letters and consist of exactly 2 characters');
});

it('rejects language code shorter than 2 characters', function () {
    $result = $this->constraint->validate('e');

    expect($result)->toBeFalse();
    expect($this->constraint->getError())->toBe('Module language code must contain only lowercase letters and consist of exactly 2 characters');
});

it('handles non-string values by casting to string', function () {
    $result = $this->constraint->validate(123);

    expect($result)->toBeFalse();
    expect($this->constraint->getError())->toBe('Module language code must contain only lowercase letters and consist of exactly 2 characters');
});
