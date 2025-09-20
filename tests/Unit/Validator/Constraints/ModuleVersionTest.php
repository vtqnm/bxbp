<?php

declare(strict_types=1);

use Vtqnm\BxbpCli\Validator\Constraints\ModuleVersion;

beforeEach(function () {
    $this->constraint = new ModuleVersion();
});

it('validates correct version format', function () {
    $validVersions = [
        '1.0.0',
        '24.0.100',
        '10.5.3',
        '0.0.1',
        '999.999.999'
    ];

    foreach ($validVersions as $version) {
        $result = $this->constraint->validate($version);
        
        expect($result)->toBeTrue("Version {$version} should be valid");
        expect($this->constraint->getError())->toBeNull();
    }
});

it('rejects version without patch number', function () {
    $result = $this->constraint->validate('1.0');

    expect($result)->toBeFalse();
    expect($this->constraint->getError())->toBe('Invalid version format. Version must follow the pattern \'X.Y.Z\' where X, Y, and Z are numeric values.');
});

it('rejects version with only major number', function () {
    $result = $this->constraint->validate('1');

    expect($result)->toBeFalse();
    expect($this->constraint->getError())->toBe('Invalid version format. Version must follow the pattern \'X.Y.Z\' where X, Y, and Z are numeric values.');
});

it('rejects version with non-numeric parts', function () {
    $invalidVersions = [
        'a.0.0',
        '1.b.0',
        '1.0.c',
        '1.0.0-alpha',
        '1.0.0+build',
        'v1.0.0'
    ];

    foreach ($invalidVersions as $version) {
        $result = $this->constraint->validate($version);
        
        expect($result)->toBeFalse("Version {$version} should be invalid");
        expect($this->constraint->getError())->toBe('Invalid version format. Version must follow the pattern \'X.Y.Z\' where X, Y, and Z are numeric values.');
    }
});

it('rejects version with extra dots', function () {
    $result = $this->constraint->validate('1.0.0.0');

    expect($result)->toBeFalse();
    expect($this->constraint->getError())->toBe('Invalid version format. Version must follow the pattern \'X.Y.Z\' where X, Y, and Z are numeric values.');
});

it('rejects empty version', function () {
    $result = $this->constraint->validate('');

    expect($result)->toBeFalse();
    expect($this->constraint->getError())->toBe('Invalid version format. Version must follow the pattern \'X.Y.Z\' where X, Y, and Z are numeric values.');
});

it('rejects version with spaces', function () {
    $result = $this->constraint->validate(' 1.0.0 ');

    expect($result)->toBeFalse();
    expect($this->constraint->getError())->toBe('Invalid version format. Version must follow the pattern \'X.Y.Z\' where X, Y, and Z are numeric values.');
});

it('handles non-string values by casting to string', function () {
    $result = $this->constraint->validate(123);

    expect($result)->toBeFalse();
    expect($this->constraint->getError())->toBe('Invalid version format. Version must follow the pattern \'X.Y.Z\' where X, Y, and Z are numeric values.');
});
