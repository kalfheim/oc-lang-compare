<?php namespace krisawzm\OctoberLanguageComparison;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CompareDirCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('compare:dir')
            ->setDescription('Blabla')
            ->addArgument(
                'langDir',
                InputArgument::REQUIRED,
                'Base lang directory. Eg: modules/lang'
            )
            ->addArgument(
                'devLang',
                InputArgument::REQUIRED,
                'Dev lang code. Eg: de or nb-no'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $output->writeln('<info>- '.$input->getArgument('langDir').'</info>');

            $compare = new Compare(
                $input->getArgument('langDir'),
                $input->getArgument('devLang')
            );

            foreach ($compare->getNotes() as $note) {
                $output->writeln('<fg='.$note[1].'>'.$note[0].'</fg='.$note[1].'>');
            }
        }
        catch (Exception $ex) {
            $output->writeln('<fg=red>'.$ex->getMessage().'</fg=red>');
        }
    }
}
