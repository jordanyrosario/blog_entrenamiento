<?php

namespace Core\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;

class LoadData extends Command
{
    /**
     * @see Console\Command\Command
     */
    protected function configure()
    {
        $this
            ->setName('load-data')
            ->setDescription(
                'Find and run all SQL files in the fixtures directory.'
            )
            ->setDefinition([
                new InputArgument(
                    'fixtures-path',
                    InputArgument::OPTIONAL,
                    'The path to the fixtures directory. If none is provided, the default (application/fixtures) will be used.'
                ),
            ])
            ->setHelp(
                <<<'EOT'
Processes the schema and either create it directly on EntityManager Storage Connection or generate the SQL output.
EOT
            )
        ;
    }

    /**
     * @see Console\Command\Command
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getHelper('em')->getEntityManager();

        // Process destination directory

        $fixtures_path = 'App/Fixtures/';

        $fixtures_path = realpath($fixtures_path);

        if (!is_dir($fixtures_path)) {
            throw new \InvalidArgumentException(
                sprintf("Fixtures directory '<info>%s</info>' does not exist.", $fixtures_path)
            );
        }

        $loader = new Loader();
        $loader->loadFromDirectory($fixtures_path);

        try {
            $executor = new ORMExecutor($em, new ORMPurger());
            $executor->execute($loader->getFixtures());
            $output->write('Successfully loaded !'.PHP_EOL.PHP_EOL);

            return Command::SUCCESS;
        } catch (\PDOException $e) {
            $output->write(PHP_EOL."\t \tError!\t");
            $output->write('Caught a PDOException. ('.$e->getMessage().')'.PHP_EOL);
            $output->write("\t \tAttempting to recover... ");
            $output->write(PHP_EOL.PHP_EOL);
        }

        $output->write('Finished loading data  files processed)'.PHP_EOL);

        return Command::FAILURE;
    }
}
