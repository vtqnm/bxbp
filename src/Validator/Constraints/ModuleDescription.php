<?php

declare(strict_types=1);

namespace Vtqnm\Bxbp\Validator\Constraints;

class ModuleDescription extends Constraint
{
    public function validate(mixed $value): bool
    {
        $moduleDescription = (string) $value;

        if (strlen($moduleDescription) > 1000) {
            $this->error('Module description must not exceed 1000 characters');
            return false;
        }

        $this->error = null;
        return true;
    }
}
