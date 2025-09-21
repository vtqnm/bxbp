<?php

namespace Vtqnm\Bxbp\Validator;

use Vtqnm\Bxbp\Validator\Constraints\Constraint;

class Validator
{
    /** @var array<string> */
    protected array $errors = [];

    /**
     * @param mixed $value
     * @param Constraint|Constraint[] $constraints
     * @return bool
     */
    public function validate(mixed $value, Constraint|array $constraints): bool
    {
        $this->flushErrors();

        if (!is_array($constraints)) {
            $constraints = [$constraints];
        }

        foreach ($constraints as $constraint) {
            if (!$constraint->validate($value)) {
                $this->addError($constraint->getError());
            }
        }

        return empty($this->getErrors());
    }

    protected function addError(string $error): static
    {
        $this->errors[] = $error;
        return $this;
    }

    /**
     * @return array<string>
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    public function flushErrors(): void
    {
        $this->errors = [];
    }
}