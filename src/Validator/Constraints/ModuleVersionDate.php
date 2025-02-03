<?php

namespace Vtqnm\BxbpCli\Validator\Constraints;

class ModuleVersionDate extends Constraint
{
    protected const VERSION_DATE_FORMAT = 'Y-m-d H:i:s';

    private string $message = 'Invalid version date format. Date must follow the pattern \'Y-m-d H:i:s\'.';

    public function validate($value): bool
    {
        if (\DateTime::createFromFormat(self::VERSION_DATE_FORMAT, $value)) {
            $this->error = null;
            return true;
        }

        $this->error($this->message);
        return false;
    }
}