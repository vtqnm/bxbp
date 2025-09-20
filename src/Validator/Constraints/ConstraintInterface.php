<?php

declare(strict_types=1);

namespace Vtqnm\BxbpCli\Validator\Constraints;

interface ConstraintInterface
{
    public function validate(mixed $value): bool;

    public function getError(): ?string;
}