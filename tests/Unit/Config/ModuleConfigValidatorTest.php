<?php

use Vtqnm\Bxbp\Config\ModuleConfigValidator;
use Vtqnm\Bxbp\Exceptions\ModuleValidationException;

beforeEach(function () {
    $this->validator = new ModuleConfigValidator();
});

describe('validateModuleId', function () {
    it('passes validation for valid module id', function () {
        expect(fn() => $this->validator->validateModuleId('partner.module'));
    })->throwsNoExceptions();

    it('throws exception for empty module id', function () {
        expect(fn() => $this->validator->validateModuleId(''))
            ->toThrow(ModuleValidationException::class);
    });

    it('throws exception for module id with invalid characters', function () {
        expect(fn() => $this->validator->validateModuleId('test-module'))
            ->toThrow(ModuleValidationException::class);
    });

    it('throws exception for module id with uppercase letters', function () {
        expect(fn() => $this->validator->validateModuleId('TestModule'))
            ->toThrow(ModuleValidationException::class);
    });

    it('throws exception for module id starting with digit', function () {
        expect(fn() => $this->validator->validateModuleId('1test.module'))
            ->toThrow(ModuleValidationException::class);
    });

    it('throws exception for module id not in partner.module format', function () {
        expect(fn() => $this->validator->validateModuleId('testmodule'))
            ->toThrow(ModuleValidationException::class);
    });

    it('throws exception for module id exceeding 50 characters', function () {
        $longId = str_repeat('a', 25) . '.' . str_repeat('b', 26);
        expect(fn() => $this->validator->validateModuleId($longId))
            ->toThrow(ModuleValidationException::class);
    });
});

describe('validateModuleName', function () {
    it('passes validation for valid module name', function () {
        expect(fn() => $this->validator->validateModuleName('Test Module'));
    })->throwsNoExceptions();

    it('throws exception for module name exceeding 255 characters', function () {
        $longName = str_repeat('a', 256);
        expect(fn() => $this->validator->validateModuleName($longName))
            ->toThrow(ModuleValidationException::class);
    });
});

describe('validateVersion', function () {
    it('passes validation for valid version', function () {
        expect(fn() => $this->validator->validateVersion('1.0.0'));
    })->throwsNoExceptions();

    it('throws exception for empty version', function () {
        expect(fn() => $this->validator->validateVersion(''))
            ->toThrow(ModuleValidationException::class);
    });

    it('throws exception for invalid version format', function () {
        expect(fn() => $this->validator->validateVersion('1.0'))
            ->toThrow(ModuleValidationException::class);
    });
});

describe('validateVersionDate', function () {
    it('passes validation for valid date', function () {
        expect(fn() => $this->validator->validateVersionDate('2024-01-01 12:00:00'));
    })->throwsNoExceptions();

    it('throws exception for empty date', function () {
        expect(fn() => $this->validator->validateVersionDate(''))
            ->toThrow(ModuleValidationException::class);
    });

    it('throws exception for invalid date format', function () {
        expect(fn() => $this->validator->validateVersionDate('01-01-2024'))
            ->toThrow(ModuleValidationException::class);
    });

    it('throws exception for date without time', function () {
        expect(fn() => $this->validator->validateVersionDate('2024-01-01'))
            ->toThrow(ModuleValidationException::class);
    });
});

describe('validateModuleLangCode', function () {
    it('passes validation for valid module language code', function () {
        expect(fn() => $this->validator->validateModuleLangCode('ru'));
    })->throwsNoExceptions();

    it('throws exception for empty module language code', function () {
        expect(fn() => $this->validator->validateModuleLangCode(''))
            ->toThrow(ModuleValidationException::class);
    });

    it('throws exception for invalid module language code format', function () {
        expect(fn() => $this->validator->validateModuleLangCode('example'))
            ->toThrow(ModuleValidationException::class);
    });
});

describe('validateModuleDescription', function () {
    it('passes validation for valid module description', function () {
        expect(fn() => $this->validator->validateModuleDescription('Test module description'));
    })->throwsNoExceptions();

    it('throws exception for module description exceeding 1000 characters', function () {
        $longDescription = str_repeat('a', 1001);
        expect(fn() => $this->validator->validateModuleDescription($longDescription))
            ->toThrow(ModuleValidationException::class);
    });
});

describe('validatePartnerName', function () {
    it('passes validation for valid partner name', function () {
        expect(fn() => $this->validator->validatePartnerName('Test Partner'));
    })->throwsNoExceptions();

    it('throws exception for partner name exceeding 255 characters', function () {
        $longName = str_repeat('a', 256);
        expect(fn() => $this->validator->validatePartnerName($longName))
            ->toThrow(ModuleValidationException::class, 'Partner name must not exceed 255 characters');
    });
});

describe('validatePartnerUri', function () {
    it('passes validation for valid partner URI', function () {
        expect(fn() => $this->validator->validatePartnerUri('https://example.com'));
    })->throwsNoExceptions();
});