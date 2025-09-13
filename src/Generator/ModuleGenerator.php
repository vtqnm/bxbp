<?php

namespace Vtqnm\BxbpCli\Generator;

use Symfony\Component\Filesystem\Filesystem;
use Vtqnm\BxbpCli\Config\ModuleConfig;
use Vtqnm\BxbpCli\Filesystem\Directory;
use Vtqnm\BxbpCli\Filesystem\TempDirectoryFactory;

class ModuleGenerator
{
    protected string $templatePath;
    protected array $replacements;

    protected Filesystem $filesystem;
    protected TempDirectoryFactory $tempDirectoryFactory;

    public function __construct(
        string $templatePath,
        $replacements
    )
    {
        $this->templatePath = $templatePath;

        if (is_array($replacements)) {
            $this->replacements = $replacements;
        } elseif ($replacements instanceof ModuleConfig) {
            $this->replacements = $replacements->toReplacementMap();
        } else {
            throw new \InvalidArgumentException('Replacements must be an array or ModuleConfig class.');
        }

        $this->filesystem = new Filesystem();
        $this->tempDirectoryFactory = new TempDirectoryFactory();
    }

    public function generate(): Directory
    {
        $directory = $this->tempDirectoryFactory->create();

        $this->filesystem->mirror(
            $this->templatePath,
            $directory->getPath()
        );

        $this->replacePlaceholders($directory->getPath());
        return $directory;
    }

    private function replacePlaceholders(string $workPath): void
    {
        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($workPath),
            \RecursiveIteratorIterator::LEAVES_ONLY
        );

        /** @var \SplFileInfo $file */
        foreach ($files as $file) {
            if (in_array($file->getFilename(), ['.', '..'])) {
                continue;
            }

            file_put_contents(
                $file->getPathname(),
                str_replace(
                    array_keys($this->replacements),
                    array_values($this->replacements),
                    file_get_contents($file->getPathname())
                )
            );
        }
    }
}