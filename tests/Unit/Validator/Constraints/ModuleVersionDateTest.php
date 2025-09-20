<?php

declare(strict_types=1);

use Vtqnm\BxbpCli\Validator\Constraints\ModuleVersionDate;

beforeEach(function () {
    $this->constraint = new ModuleVersionDate();
});

it('validates correct date format', function () {
    $validDates = [
        '2024-01-01 00:00:00',
        '2024-12-31 23:59:59',
        '2020-02-29 12:30:45', // leap year
        '1999-01-01 01:01:01',
        '2025-06-15 14:22:33'
    ];

    foreach ($validDates as $date) {
        $result = $this->constraint->validate($date);
        
        expect($result)->toBeTrue("Date {$date} should be valid");
        expect($this->constraint->getError())->toBeNull();
    }
});

it('rejects date without time', function () {
    $result = $this->constraint->validate('2024-01-01');

    expect($result)->toBeFalse();
    expect($this->constraint->getError())->toBe('Invalid version date format. Date must follow the pattern \'Y-m-d H:i:s\'.');
});

it('rejects time without date', function () {
    $result = $this->constraint->validate('12:30:45');

    expect($result)->toBeFalse();
    expect($this->constraint->getError())->toBe('Invalid version date format. Date must follow the pattern \'Y-m-d H:i:s\'.');
});

it('rejects invalid date formats', function () {
    $invalidDates = [
        '01-01-2024 00:00:00', // wrong date format
        '2024/01/01 00:00:00', // wrong separator
        '2024-1-1 00:00:00',   // missing leading zeros
        '2024-01-01 0:0:0',    // missing leading zeros in time
        '2024-01-01T00:00:00', // ISO format with T
        '2024-01-01 00:00',    // missing seconds
        '24-01-01 00:00:00'    // short year
    ];

    foreach ($invalidDates as $date) {
        $result = $this->constraint->validate($date);
        
        expect($result)->toBeFalse("Date {$date} should be invalid");
        expect($this->constraint->getError())->toBe('Invalid version date format. Date must follow the pattern \'Y-m-d H:i:s\'.');
    }
});

it('rejects invalid dates', function () {
    $invalidDates = [
        '2024-02-30 00:00:00', // February 30th doesn't exist
        '2024-13-01 00:00:00', // Month 13 doesn't exist
        '2024-01-32 00:00:00', // January 32nd doesn't exist
        '2024-01-01 25:00:00', // Hour 25 doesn't exist
        '2024-01-01 00:61:00', // Minute 61 doesn't exist
        '2024-01-01 00:00:61', // Second 61 doesn't exist
        '2023-02-29 00:00:00'  // February 29th in non-leap year
    ];

    foreach ($invalidDates as $date) {
        $result = $this->constraint->validate($date);
        
        expect($result)->toBeFalse("Date {$date} should be invalid");
        expect($this->constraint->getError())->toBe('Invalid version date format. Date must follow the pattern \'Y-m-d H:i:s\'.');
    }
});

it('rejects empty date', function () {
    $result = $this->constraint->validate('');

    expect($result)->toBeFalse();
    expect($this->constraint->getError())->toBe('Invalid version date format. Date must follow the pattern \'Y-m-d H:i:s\'.');
});

it('rejects date with extra spaces', function () {
    $result = $this->constraint->validate(' 2024-01-01 00:00:00 ');

    expect($result)->toBeFalse();
    expect($this->constraint->getError())->toBe('Invalid version date format. Date must follow the pattern \'Y-m-d H:i:s\'.');
});

it('handles non-string values by casting to string', function () {
    $result = $this->constraint->validate(20240101);

    expect($result)->toBeFalse();
    expect($this->constraint->getError())->toBe('Invalid version date format. Date must follow the pattern \'Y-m-d H:i:s\'.');
});
