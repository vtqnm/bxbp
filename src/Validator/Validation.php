<?php

declare(strict_types=1);

namespace Vtqnm\BxbpCli\Validator;

use Vtqnm\BxbpCli\Validator\Constraints\Constraint;

class Validation
{
    public static function createValidator(): Validator
    {
        return new Validator();
    }
}