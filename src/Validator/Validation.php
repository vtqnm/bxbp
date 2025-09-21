<?php

declare(strict_types=1);

namespace Vtqnm\Bxbp\Validator;

use Vtqnm\Bxbp\Validator\Constraints\Constraint;

class Validation
{
    public static function createValidator(): Validator
    {
        return new Validator();
    }
}