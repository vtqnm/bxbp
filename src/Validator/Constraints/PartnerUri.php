<?php

declare(strict_types=1);

namespace Vtqnm\BxbpCli\Validator\Constraints;

class PartnerUri extends Constraint
{
    public function validate(mixed $value): bool
    {
        $partnerUri = (string) $value;

        if (strlen($partnerUri) > 255) {
            $this->error('Partner URI must not exceed 255 characters');
            return false;
        }

        $this->error = null;
        return true;
    }
}
