<?php

declare(strict_types=1);

namespace Vtqnm\Bxbp\Validator\Constraints;

class PartnerName extends Constraint
{
    public function validate(mixed $value): bool
    {
        $partnerName = (string) $value;

        if (strlen($partnerName) > 255) {
            $this->error('Partner name must not exceed 255 characters');
            return false;
        }

        $this->error = null;
        return true;
    }
}
