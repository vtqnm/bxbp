<?php

namespace Vtqnm\BxbpCli\Filesystem;

class Directory
{
    private string $path;

    public function __construct(string $path) {
        $this->path = $path;
    }

    public function getPath(): string {
        return $this->path;
    }
}