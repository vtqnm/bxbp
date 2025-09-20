<?php

namespace Vtqnm\Bxbp\Export;

use Vtqnm\Bxbp\Filesystem\Directory;

interface ModuleExportStrategy
{
    public function export(Directory $source, string $destination): string;
}