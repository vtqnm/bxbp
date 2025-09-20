<?php

declare(strict_types=1);

namespace Vtqnm\Bxbp\Validator\Constraints;

class LanguageCode extends Constraint
{
    public function validate(mixed $value): bool
    {
        $languageCode = (string) $value;

        if (empty(trim($languageCode))) {
            $this->error('Module language code is required');
            return false;
        }

        if (!preg_match('/^[a-z]{2}$/', $languageCode)) {
            $this->error('Module language code must contain only lowercase letters and consist of exactly 2 characters');
            return false;
        }

        $this->error = null;
        return true;
    }
}
