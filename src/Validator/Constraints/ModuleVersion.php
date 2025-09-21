<?php

namespace Vtqnm\Bxbp\Validator\Constraints;

class ModuleVersion extends Constraint
{
    private string $message = 'Invalid version format. Version must follow the pattern \'X.Y.Z\' where X, Y, and Z are numeric values.';

    public function validate(mixed $value): bool
    {
        if (preg_match('/^\d+\.\d+\.\d+$/', (string) $value)) {
            $this->error = null;
            return true;
        }

        $this->error($this->message);
        return false;
    }
}