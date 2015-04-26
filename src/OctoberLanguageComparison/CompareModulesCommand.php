<?php namespace krisawzm\OctoberLanguageComparison;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CompareModulesCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('compare:modules')
            ->setDescription('Compares everything in the modules directory. Must be executed from the project\'s root directory.')
            ->addArgument(
                'devLang',
                InputArgument::REQUIRED,
                'Language code to compare. Eg: de or nb-no'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            foreach ([
                'modules/backend/lang',
                'modules/cms/lang',
                'modules/system/lang',
            ] as $langDir) {
                $output->writeln('<info>- '.$langDir.'</info>');

                $compare = new Compare(
                    $langDir,
                    $input->getArgument('devLang')
                );

                foreach ($compare->getNotes() as $note) {
                    $output->writeln('<fg='.$note[1].'>'.$note[0].'</fg='.$note[1].'>');
                }

                $output->write("\n");
            }
        }
        catch (Exception $ex) {
            $output->writeln('<fg=red>'.$ex->getMessage().'</fg=red>');
        }
    }
}
