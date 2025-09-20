<?php

declare(strict_types=1);

namespace Vtqnm\BxbpCli\Validator\Constraints;

class ModuleName extends Constraint
{
    public function validate(mixed $value): bool
    {
        $moduleName = (string) $value;

        if (strlen($moduleName) > 255) {
            $this->error('Module name must not exceed 255 characters');
            return false;
        }

        $this->error = null;
        return true;
    }
}
