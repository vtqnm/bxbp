<?php

declare(strict_types=1);

namespace Vtqnm\Bxbp\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Vtqnm\Bxbp\Validator\Validation;
use Vtqnm\Bxbp\Validator\Validator;
use Vtqnm\Bxbp\Validator\Constraints;
use Vtqnm\Bxbp\Config\ModuleConfig;
use Vtqnm\Bxbp\Generator\ModuleGenerator;
use Vtqnm\Bxbp\Export\ModuleExporter;
use Vtqnm\Bxbp\Export\Strategy\RawModuleExport;
use function Laravel\Prompts\confirm;
use function Laravel\Prompts\error;
use function Laravel\Prompts\info;
use function Laravel\Prompts\text;

class NewCommand extends Command
{
    private Validator $validator;

    public function __construct(?string $name = null)
    {
        parent::__construct($name);
        $this->validator = Validation::createValidator();
    }

    protected function configure(): void
    {
        $this
            ->setName('new')
            ->setDescription('Create a new Bitrix module')
            ->addArgument('id', InputArgument::REQUIRED, 'Module id')
            ->addArgument('name', InputArgument::OPTIONAL, 'Module name')
            ->addArgument('description', InputArgument::OPTIONAL, 'Module description')
            ->addArgument('partner', InputArgument::OPTIONAL, 'Module partner')
            ->addArgument('partner_url', InputArgument::OPTIONAL, 'Partner url')
            ->addArgument('version', InputArgument::OPTIONAL, 'Version')
            ->addArgument('version_date', InputArgument::OPTIONAL, 'Version date');
    }

    protected function interact(InputInterface $input, OutputInterface $output): void
    {
        parent::interact($input, $output);

        $this->writeLogo($output);

        $isModulesFolder = str_ends_with(getcwd() ?: '', '/modules');
        if (!$isModulesFolder && !$this->confirmProceedWithoutModulesFolder()) {
            error('"Module creation process has been canceled.');
            exit(1);
        }

        $this->askModuleIdIfNotValid($input);
        $this->askModuleNameIfNotFilled($input);
        $this->askModuleDescriptionIfNotFilled($input);
        $this->askPartnerNameIfNotFilled($input);
        $this->askPartnerUrlIfNotFilled($input);
        $this->askVersionIfNotFilled($input);
        $this->askVersionDateIfNotFilled($input);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $moduleConfig = new ModuleConfig(
                $input->getArgument('id'),
                $input->getArgument('name'),
                $input->getArgument('description'),
                $input->getArgument('partner'),
                $input->getArgument('partner_url'),
                $input->getArgument('version'),
                $input->getArgument('version_date'),
                'ru'
            );

            $generator = ModuleGenerator::fromConfig(
                dirname(__DIR__, 2) . '/stubs/module',
                $moduleConfig
            );

            $generatedDirectory = $generator->generate();

            $moduleExporter = new ModuleExporter(
                new RawModuleExport()
            );

            $destinationPath = getcwd() . DIRECTORY_SEPARATOR . $moduleConfig->getModuleId();
            $moduleExporter->export($generatedDirectory, $destinationPath);

            info("<fg=green>Module '{$moduleConfig->getModuleId()}' successfully created at path: {$destinationPath}</>");

            return Command::SUCCESS;
        } catch (\Exception $e) {
            error("Error creating module: " . $e->getMessage());
            return Command::FAILURE;
        }
    }

    protected function writeLogo(OutputInterface $output): void
    {
        $output->write(PHP_EOL . '<fg=blue>
   ______     __  __     ______     ______  
  /\  == \   /\_\_\_\   /\  == \   /\  == \ 
  \ \  __<   \/_/\_\/_  \ \  __<   \ \  _-/ 
   \ \_____\   /\_\/\_\  \ \_____\  \ \_\   
    \/_____/   \/_/\/_/   \/_____/   \/_/</>' . PHP_EOL . PHP_EOL);
    }

    protected function confirmProceedWithoutModulesFolder(): bool
    {
        return confirm('You are not in the modules directory. Continue?');
    }

    protected function askModuleIdIfNotValid(InputInterface $input): void
    {
        $moduleId = $input->getArgument('id');

        if (!empty($moduleId)) {
            $errors = $this->getModuleIdValidationErrors($moduleId);
            if (empty($errors)) {
                return;
            }
            info('<fg=yellow>' . $this->formatValidationErrors($errors) . '</>');
        }

        $input->setArgument('id', text(
            'Enter the module ID',
            'vendor.module',
            required: true,
            validate: function ($value) {
                $errors = $this->getModuleIdValidationErrors($value);
                return empty($errors) ? null : $this->formatValidationErrors($errors);
            }
        ));
    }

    /**
     * @return array<string>
     */
    private function getModuleIdValidationErrors(string $moduleId): array
    {
        $constraint = new Constraints\ModuleIdentify();
        $errors = [];

        if (!$this->validator->validate($moduleId, $constraint)) {
            $errors = array_merge($errors, $this->validator->getErrors());
        }

        if ($this->moduleExistsInCurrentDirectory($moduleId)) {
            $errors[] = "Module with ID '{$moduleId}' already exists in current directory";
        }

        return $errors;
    }

    protected function askModuleNameIfNotFilled(InputInterface $input): void
    {
        $constraint = new Constraints\ModuleName();

        $moduleName = $input->getArgument('name');
        if (!empty($moduleName) && $this->validator->validate($moduleName, $constraint)) {
            return;
        }

        if (!empty($moduleName)) {
            info('<fg=yellow>' . $this->formatValidationErrors($this->validator->getErrors()) . '</>');
        }

        $input->setArgument('name', text(
            'Enter the module name',
            'Module name <fg=blue>(optional)</>',
            validate: fn($value) => $this->validator->validate($value, $constraint)
                ? null
                : $this->formatValidationErrors($this->validator->getErrors())
        ));
    }

    protected function askModuleDescriptionIfNotFilled(InputInterface $input): void
    {
        $constraint = new Constraints\ModuleDescription();

        $moduleDescription = $input->getArgument('description');
        if (!empty($moduleDescription) && $this->validator->validate($moduleDescription, $constraint)) {
            return;
        }

        if (!empty($moduleDescription)) {
            info('<fg=yellow>' . $this->formatValidationErrors($this->validator->getErrors()) . '</>');
        }

        $input->setArgument('description', text(
            'Enter the module description',
            'Module description <fg=blue>(optional)</>',
            validate: fn($value) => $this->validator->validate($value, $constraint)
                ? null
                : $this->formatValidationErrors($this->validator->getErrors())
        ));
    }

    protected function askPartnerNameIfNotFilled(InputInterface $input): void
    {
        $constraint = new Constraints\PartnerName();

        $partnerName = $input->getArgument('partner');
        if (!empty($partnerName) && $this->validator->validate($partnerName, $constraint)) {
            return;
        }

        if (!empty($partnerName)) {
            info('<fg=yellow>' . $this->formatValidationErrors($this->validator->getErrors()) . '</>');
        }

        $input->setArgument('partner', text(
            'Enter the partner name',
            'Partner name <fg=blue>(optional)</>',
            validate: fn($value) => $this->validator->validate($value, $constraint)
                ? null
                : $this->formatValidationErrors($this->validator->getErrors())
        ));
    }

    protected function askPartnerUrlIfNotFilled(InputInterface $input): void
    {
        $constraint = new Constraints\PartnerUri();

        $partnerUrl = $input->getArgument('partner_url');
        if (!empty($partnerUrl) && $this->validator->validate($partnerUrl, $constraint)) {
            return;
        }

        if (!empty($partnerUrl)) {
            info('<fg=yellow>' . $this->formatValidationErrors($this->validator->getErrors()) . '</>');
        }

        $input->setArgument('partner_url', text(
            'Enter the partner url',
            'https://example.com <fg=blue>(optional)</>',
            validate: fn($value) => $this->validator->validate($value, $constraint)
                ? null
                : $this->formatValidationErrors($this->validator->getErrors())
        ));
    }

    protected function askVersionIfNotFilled(InputInterface $input): void
    {
        $constraint = new Constraints\ModuleVersion();

        $version = $input->getArgument('version');
        if (!empty($version) && $this->validator->validate($version, $constraint)) {
            return;
        }

        if (!empty($version)) {
            info('<fg=yellow>' . $this->formatValidationErrors($this->validator->getErrors()) . '</>');
        }

        $input->setArgument('version', text(
            'Enter the version',
            '1.0.0',
            '1.0.0',
            validate: fn($value) => $this->validator->validate($value, $constraint)
                ? null
                : $this->formatValidationErrors($this->validator->getErrors())
        ));
    }

    protected function askVersionDateIfNotFilled(InputInterface $input): void
    {
        $constraint = new Constraints\ModuleVersionDate();

        $versionDate = $input->getArgument('version_date');
        if (!empty($versionDate) && $this->validator->validate($versionDate, $constraint)) {
            return;
        }

        if (!empty($versionDate)) {
            info('<fg=yellow>' . $this->formatValidationErrors($this->validator->getErrors()) . '</>');
        }

        $now = time();
        $dateFormat = 'Y-m-d H:i:s';

        $input->setArgument('version_date', text(
            'Enter the version date',
            date($dateFormat, $now),
            date($dateFormat, $now),
            validate: fn($value) => $this->validator->validate($value, $constraint)
                ? null
                : $this->formatValidationErrors($this->validator->getErrors())
        ));
    }

    protected function moduleExistsInCurrentDirectory(string $moduleId): bool
    {
        $currentDir = getcwd();
        if ($currentDir === false) {
            return false;
        }

        return is_dir($currentDir . DIRECTORY_SEPARATOR . $moduleId);
    }

    /**
     * @param array<string> $errors
     */
    private function formatValidationErrors(array $errors): string
    {
        return implode(PHP_EOL, $errors);
    }
}