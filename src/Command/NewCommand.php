<?php

declare(strict_types=1);

namespace Vtqnm\BxbpCli\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Vtqnm\BxbpCli\Validator\Constraints\ModuleIdentify;
use Vtqnm\BxbpCli\Validator\Constraints\ModuleVersion;
use Vtqnm\BxbpCli\Validator\Constraints\ModuleVersionDate;
use Vtqnm\BxbpCli\Validator\Validation;
use function Laravel\Prompts\confirm;
use function Laravel\Prompts\error;
use function Laravel\Prompts\info;
use function Laravel\Prompts\text;

class NewCommand extends Command
{
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

        $isModulesFolder = str_ends_with(getcwd(), '/modules');
        if (!$isModulesFolder && !$this->confirmProceedWithoutModulesFolder()) {
            error('"Module creation process has been canceled.');
            exit(1);
        }

        $this->askModuleIdIfNotFilled($input);
        $this->askModuleNameIfNotFilled($input);
        $this->askModuleDescriptionIfNotFilled($input);
        $this->askPartnerNameIfNotFilled($input);
        $this->askPartnerUrlIfNotFilled($input);
        $this->askVersionIfNotFilled($input);
        $this->askVersionDateIfNotFilled($input);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        parent::execute($input, $output);
    }

    protected function writeLogo(OutputInterface $output): void
    {
        $output->write(PHP_EOL . '  <fg=blue>
 ______     __  __     __    __     ______     ______  
/\  == \   /\_\_\_\   /\ "-./  \   /\  == \   /\  == \ 
\ \  __<   \/_/\_\/_  \ \ \-./\ \  \ \  __<   \ \  _-/ 
 \ \_____\   /\_\/\_\  \ \_\ \ \_\  \ \_____\  \ \_\   
  \/_____/   \/_/\/_/   \/_/  \/_/   \/_____/   \/_/</>' . PHP_EOL . PHP_EOL);
    }

    protected function confirmProceedWithoutModulesFolder(): bool
    {
        return confirm('You are not in the modules directory. Continue?');
    }

    protected function askModuleIdIfNotFilled(InputInterface $input): void
    {
        $validator = Validation::createValidator();
        $constraint = new ModuleIdentify();

        $isValid = true;
        if (!empty($input->getArgument('id')) && !$validator->validate($input->getArgument('id'), $constraint)) {
            $isValid = false;
        }

        if (!empty($validator->getErrors())) {
            info('<fg=yellow>' . implode(PHP_EOL, $validator->getErrors()) . '</>');
            $validator->flushErrors();
        }

        if (!$isValid) {
            $input->setArgument('id', text(
                'Enter the module ID',
                'vendor.module',
                required: true,
                validate: fn($value) => $validator->validate($value, $constraint)
                    ? null
                    : implode(PHP_EOL, $validator->getErrors())
            ));
        }
    }

    protected function askModuleNameIfNotFilled(InputInterface $input): void
    {
        if ($input->hasArgument('name') && !is_null($input->getArgument('name'))) {
            return;
        }

        $input->setArgument('name', text(
            'Enter the module name',
            'Module name <fg=blue>(optional)</>'
        ));
    }

    protected function askModuleDescriptionIfNotFilled(InputInterface $input): void
    {
        if ($input->hasArgument('description') && !is_null($input->getArgument('description'))) {
            return;
        }

        $input->setArgument('description', text(
            'Enter the module description',
            'Module description <fg=blue>(optional)</>'
        ));
    }

    protected function askPartnerNameIfNotFilled(InputInterface $input): void
    {
        if ($input->hasArgument('partner') && !is_null($input->getArgument('partner'))) {
            return;
        }

        $input->setArgument('partner', text(
            'Enter the partner name',
            'Partner name <fg=blue>(optional)</>'
        ));
    }

    protected function askPartnerUrlIfNotFilled(InputInterface $input): void
    {
        if (
            empty($input->getArgument('partner')) ||
            ($input->hasArgument('partner_url') && !is_null($input->getArgument('partner_url')))
        ) {
            return;
        }

        $input->setArgument('partner_url', text(
            'Enter the partner url',
            'https://example.com <fg=blue>(optional)</>'
        ));
    }

    protected function askVersionIfNotFilled(InputInterface $input): void
    {
        $validator = Validation::createValidator();
        $constraint = new ModuleVersion();

        $isValid = true;
        if (empty($input->getArgument('version')) || !$validator->validate($input->getArgument('version'), $constraint)) {
            $isValid = false;
        }

        if (!empty($validator->getErrors())) {
            info('<fg=yellow>' . implode(PHP_EOL, $validator->getErrors()) . '</>');
        }

        if (!$isValid) {
            $input->setArgument('version', text(
                'Enter the version',
                '1.0.0',
                '1.0.0',
                validate: fn($value) => $validator->validate($value, $constraint)
                    ? null
                    : implode(PHP_EOL, $validator->getErrors())
            ));
        }
    }

    protected function askVersionDateIfNotFilled(InputInterface $input): void
    {
        $validator = Validation::createValidator();
        $constraint = new ModuleVersionDate();

        $isValid = true;
        if (empty($input->getArgument('version_date')) || !$validator->validate($input->getArgument('version_date'), $constraint)) {
            $isValid = false;
        }

        if (!empty($validator->getErrors())) {
            info('<fg=yellow>' . implode(PHP_EOL, $validator->getErrors()) . '</>');
        }

        if (!$isValid) {
            $now = time();
            $dateFormat = 'Y-m-d H:i:s';

            $input->setArgument('version_date', text(
                'Enter the version date',
                date($dateFormat, $now),
                date($dateFormat, $now),
                validate: fn($value) => $validator->validate($value, $constraint)
                    ? null
                    : implode(PHP_EOL, $validator->getErrors())
            ));
        }
    }
}