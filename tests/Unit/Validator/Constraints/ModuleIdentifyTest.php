<?php

declare(strict_types=1);

use Vtqnm\BxbpCli\Validator\Constraints\ModuleIdentify;

beforeEach(function () {
    $this->constraint = new ModuleIdentify();
});

it('validates correct module ID', function () {
    $result = $this->constraint->validate('partner.module');

    expect($result)->toBeTrue();
    expect($this->constraint->getError())->toBeNull();
});

it('validates module ID with numbers', function () {
    $result = $this->constraint->validate('partner123.module456');

    expect($result)->toBeTrue();
    expect($this->constraint->getError())->toBeNull();
});

it('rejects empty module ID', function () {
    $result = $this->constraint->validate('');

    expect($result)->toBeFalse();
    expect($this->constraint->getError())->toBe('Module ID is required');
});

it('rejects whitespace-only module ID', function () {
    $result = $this->constraint->validate('   ');

    expect($result)->toBeFalse();
    expect($this->constraint->getError())->toBe('Module ID is required');
});

it('rejects module ID with uppercase letters', function () {
    $result = $this->constraint->validate('Partner.module');

    expect($result)->toBeFalse();
    expect($this->constraint->getError())->toBe('Module ID must contain only lowercase letters and numbers');
});

it('rejects module ID with special characters', function () {
    $result = $this->constraint->validate('partner-module.test');

    expect($result)->toBeFalse();
    expect($this->constraint->getError())->toBe('Module ID must contain only lowercase letters and numbers');
});

it('rejects module ID starting with digit', function () {
    $result = $this->constraint->validate('1partner.module');

    expect($result)->toBeFalse();
    expect($this->constraint->getError())->toBe('Module ID must not start with a digit');
});

it('rejects module ID without dot separator', function () {
    $result = $this->constraint->validate('partnermodule');

    expect($result)->toBeFalse();
    expect($this->constraint->getError())->toBe('Module ID must be in format <partner_name>.<module_name>');
});

it('rejects module ID with multiple dots', function () {
    $result = $this->constraint->validate('partner.module.extra');

    expect($result)->toBeFalse();
    expect($this->constraint->getError())->toBe('Module ID must be in format <partner_name>.<module_name>');
});

it('rejects module ID with empty partner name', function () {
    $result = $this->constraint->validate('.module');

    expect($result)->toBeFalse();
    expect($this->constraint->getError())->toBe('Module ID must be in format <partner_name>.<module_name>');
});

it('rejects module ID with empty module name', function () {
    $result = $this->constraint->validate('partner.');

    expect($result)->toBeFalse();
    expect($this->constraint->getError())->toBe('Module ID must be in format <partner_name>.<module_name>');
});

it('rejects module ID exceeding 50 characters', function () {
    $longModuleId = str_repeat('a', 25) . '.' . str_repeat('b', 26); // 51 characters total

    $result = $this->constraint->validate($longModuleId);

    expect($result)->toBeFalse();
    expect($this->constraint->getError())->toBe('Module ID must not exceed 50 characters');
});

it('accepts module ID with exactly 50 characters', function () {
    $moduleId = str_repeat('a', 24) . '.' . str_repeat('b', 25); // 50 characters total

    $result = $this->constraint->validate($moduleId);

    expect($result)->toBeTrue();
    expect($this->constraint->getError())->toBeNull();
});

it('handles non-string values by casting to string', function () {
    $result = $this->constraint->validate(123);

    expect($result)->toBeFalse();
    expect($this->constraint->getError())->toContain('Module ID must be in format <partner_name>.<module_name>');
});
