<?php

declare(strict_types=1);

namespace Vtqnm\Bxbp\Validator\Constraints;

class ModuleIdentify extends Constraint
{
    public function validate(mixed $value): bool
    {
        $moduleId = (string) $value;

        if (empty(trim($moduleId))) {
            $this->error('Module ID is required');
            return false;
        }

        if (mb_strlen($moduleId) > 50) {
            $this->error('Module ID must not exceed 50 characters');
            return false;
        }

        if (preg_match('/^\d/', $moduleId)) {
            $this->error('Module ID must not start with a digit');
            return false;
        }

        if (!preg_match('/^[a-z0-9.]+$/', $moduleId)) {
            $this->error('Module ID must contain only lowercase letters and numbers');
            return false;
        }

        if (!preg_match('/^[^.]+\.[^.]+$/', $moduleId)) {
            $this->error('Module ID must be in format <partner_name>.<module_name>');
            return false;
        }

        $this->error = null;
        return true;
    }
}