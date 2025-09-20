<?php

namespace Vtqnm\Bxbp\Generator;

use RuntimeException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Vtqnm\Bxbp\Config\ModuleConfig;
use Vtqnm\Bxbp\Filesystem\Directory;
use Vtqnm\Bxbp\Filesystem\TempDirectoryFactory;

class ModuleGenerator
{
    protected string $templatePath;
    protected string $languageCode;
    protected array $replacements;

    protected Filesystem $filesystem;
    protected TempDirectoryFactory $tempDirectoryFactory;

    public function __construct(
        string $templatePath,
        string $languageCode,
        $replacements
    )
    {
        $this->templatePath = $templatePath;
        $this->languageCode = $languageCode;

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

    public static function fromConfig(
        string $templatePath,
        ModuleConfig $moduleConfig
    ): self {
        return new self(
            $templatePath,
            $moduleConfig->getLanguageCode(),
            $moduleConfig
        );
    }

    public function generate(): Directory
    {
        $directory = $this->tempDirectoryFactory->create();

        $this->filesystem->mirror(
            $this->templatePath,
            $directory->getPath()
        );

        $this->replacePlaceholders($directory->getPath());
        $this->changeLangFolderCode($directory->getPath(), $this->languageCode);

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

    private function changeLangFolderCode(string $workPath, string $languageCode): void
    {
        if ($this->filesystem->exists($workPath . '/lang/' . $languageCode)) {
            return;
        }

        $templateLangFolderCode = $this->getTemplateLangFolderCode($workPath);
        if ($templateLangFolderCode === $languageCode) {
            return;
        }

        $this->filesystem->rename(
            $workPath . '/install/lang/' . $templateLangFolderCode,
            $workPath . '/install/lang/' . $languageCode
        );
    }

    private function getTemplateLangFolderCode(string $workPath): string
    {
        $langPath = $workPath . '/install/lang/';
        
        if (!$this->filesystem->exists($langPath)) {
            throw new RuntimeException('Language directory does not exist: ' . $langPath);
        }

        $finder = new Finder();
        $directories = $finder->directories()->in($langPath)->depth(0);

        if ($directories->count() === 0) {
            throw new RuntimeException('No language directories found in: ' . $langPath);
        }

        foreach ($directories as $directory) {
            return $directory->getFilename();
        }

        throw new RuntimeException('No language directories found in: ' . $langPath);
    }
}