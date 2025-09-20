<?php

declare(strict_types=1);

use Vtqnm\Bxbp\Validator\Validator;
use Vtqnm\Bxbp\Validator\Constraints\Constraint;

beforeEach(function () {
    $this->validator = new Validator();
});

it('validates single constraint successfully', function () {
    $constraint = new class extends Constraint {
        public function validate(mixed $value): bool
        {
            $this->error = null;
            return true;
        }
    };

    $result = $this->validator->validate('test', $constraint);

    expect($result)->toBeTrue();
    expect($this->validator->getErrors())->toBeEmpty();
});

it('validates single constraint with failure', function () {
    $constraint = new class extends Constraint {
        public function validate(mixed $value): bool
        {
            $this->error('Test error message');
            return false;
        }
    };

    $result = $this->validator->validate('test', $constraint);

    expect($result)->toBeFalse();
    expect($this->validator->getErrors())->toHaveCount(1);
    expect($this->validator->getErrors())->toContain('Test error message');
});

it('validates multiple constraints successfully', function () {
    $constraint1 = new class extends Constraint {
        public function validate(mixed $value): bool
        {
            $this->error = null;
            return true;
        }
    };

    $constraint2 = new class extends Constraint {
        public function validate(mixed $value): bool
        {
            $this->error = null;
            return true;
        }
    };

    $result = $this->validator->validate('test', [$constraint1, $constraint2]);

    expect($result)->toBeTrue();
    expect($this->validator->getErrors())->toBeEmpty();
});

it('validates multiple constraints with some failures', function () {
    $constraint1 = new class extends Constraint {
        public function validate(mixed $value): bool
        {
            $this->error = null;
            return true;
        }
    };

    $constraint2 = new class extends Constraint {
        public function validate(mixed $value): bool
        {
            $this->error('Error from constraint 2');
            return false;
        }
    };

    $constraint3 = new class extends Constraint {
        public function validate(mixed $value): bool
        {
            $this->error('Error from constraint 3');
            return false;
        }
    };

    $result = $this->validator->validate('test', [$constraint1, $constraint2, $constraint3]);

    expect($result)->toBeFalse();
    expect($this->validator->getErrors())->toHaveCount(2);
    expect($this->validator->getErrors())->toContain('Error from constraint 2');
    expect($this->validator->getErrors())->toContain('Error from constraint 3');
});

it('flushes errors before validation', function () {
    $constraint1 = new class extends Constraint {
        public function validate(mixed $value): bool
        {
            $this->error('First error');
            return false;
        }
    };

    $constraint2 = new class extends Constraint {
        public function validate(mixed $value): bool
        {
            $this->error('Second error');
            return false;
        }
    };

    // First validation
    $this->validator->validate('test', $constraint1);
    expect($this->validator->getErrors())->toHaveCount(1);

    // Second validation should flush previous errors
    $this->validator->validate('test', $constraint2);
    expect($this->validator->getErrors())->toHaveCount(1);
    expect($this->validator->getErrors())->toContain('Second error');
    expect($this->validator->getErrors())->not()->toContain('First error');
});

it('can manually flush errors', function () {
    $constraint = new class extends Constraint {
        public function validate(mixed $value): bool
        {
            $this->error('Test error');
            return false;
        }
    };

    $this->validator->validate('test', $constraint);
    expect($this->validator->getErrors())->toHaveCount(1);

    $this->validator->flushErrors();
    expect($this->validator->getErrors())->toBeEmpty();
});
