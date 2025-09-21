<?php

declare(strict_types=1);

namespace Vtqnm\Bxbp\Validator\Constraints;

abstract class Constraint implements ConstraintInterface
{
    protected ?string $error;

    public function getError(): ?string
    {
        return $this->error;
    }

    protected function error(string $message): void
    {
        $this->error = $message;
    }
}