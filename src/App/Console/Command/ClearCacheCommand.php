<?php

declare(strict_types=1);

namespace App\Console\Command;

use App\Service\FileManager;
use Symfony\Component\Console as Console;
use Symfony\Component\Console\Input\InputArgument;

class ClearCacheCommand extends Console\Command\Command
{
    private array $paths;
    private FileManager $files;

    public function __construct(array $paths, FileManager $files)
    {
        $this->paths = $paths;
        $this->files = $files;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('cache:clear')
            ->setDescription('Clear cache')
            ->addArgument('alias', InputArgument::OPTIONAL, 'The alias of available paths.')
        ;
    }

    protected function execute(Console\Input\InputInterface $input, Console\Output\OutputInterface $output): int
    {
        $output->writeln('<comment>Clearing cache</comment>');

        $alias = $input->getArgument('alias');

        if (empty($alias)) {
            /** @var Console\Helper\QuestionHelper $helper */
            $helper = $this->getHelper('question');
            $options = array_merge(array_keys($this->paths), ['all']);
            $question = new Console\Question\ChoiceQuestion('Choose path', $options, 0);
            $alias = $helper->ask($input, $output, $question);
        }

        if ($alias === 'all') {
            $paths = $this->paths;
        } else {
            if (!array_key_exists($alias, $this->paths)) {
                throw new \InvalidArgumentException("Unknown path alias \"$alias\"");
            }
            $paths = [$alias => $this->paths[$alias]];
        }

        foreach ($paths as $path) {
            if ($this->files->exists($path)) {
                $output->writeln('Remove ' . $path);
                $this->files->delete($path);
            } else {
                $output->writeln('Skip ' . $path);
            }
        }

        $output->writeln('<info>Done!</info>');

        return 0;
    }
}
