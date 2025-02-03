<?php

declare(strict_types=1);

namespace Vtqnm\BxbpCli\Validator\Constraints;

class ModuleIdentify extends Constraint
{
    private string $message = 'Invalid module ID format. It must follow the pattern \'vendor.module\' and contain only alphanumeric characters.';

    public function validate($value): bool
    {
        if (preg_match('/^[a-z_]+\.[a-z_]+$/', (string) $value)) {
            $this->error = null;
            return true;
        }

        $this->error($this->message);
        return false;
    }
}