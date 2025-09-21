<?php

declare(strict_types=1);

use Vtqnm\Bxbp\Validator\Constraints\PartnerUri;

beforeEach(function () {
    $this->constraint = new PartnerUri();
});

it('validates partner URI within length limit', function () {
    $validUris = [
        'https://example.com',
        'http://partner-company.org/modules',
        str_repeat('a', 255), // exactly 255 characters
        '',  // empty is allowed
    ];

    foreach ($validUris as $uri) {
        $result = $this->constraint->validate($uri);
        
        expect($result)->toBeTrue("Partner URI '{$uri}' should be valid");
        expect($this->constraint->getError())->toBeNull();
    }
});

it('rejects partner URI exceeding 255 characters', function () {
    $longUri = 'https://example.com/' . str_repeat('a', 240); // 256+ characters

    $result = $this->constraint->validate($longUri);

    expect($result)->toBeFalse();
    expect($this->constraint->getError())->toBe('Partner URI must not exceed 255 characters');
});

it('handles non-string values by casting to string', function () {
    $result = $this->constraint->validate(123);

    expect($result)->toBeTrue(); // '123' is valid as it's under 255 chars
    expect($this->constraint->getError())->toBeNull();
});
