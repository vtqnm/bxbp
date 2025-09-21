<?php

declare(strict_types=1);

namespace Vtqnm\Bxbp\Console;

use Symfony\Component\Console\Application as BaseApplication;
use Vtqnm\Bxbp\Console\Command\NewCommand;

class Application extends BaseApplication
{
    public const NAME = 'BXBP';
    public const VERSION = '1.0.0';

    public function __construct()
    {
        parent::__construct(self::NAME, self::VERSION);

        $this->add(new NewCommand());
    }
}