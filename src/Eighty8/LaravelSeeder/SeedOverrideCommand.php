<?php

namespace Eighty8\LaravelSeeder;

use Symfony\Component\Console\Input\InputOption;

class SeedOverrideCommand extends SeedCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'db:seed';

    /**
     * Constructor.
     *
     * @param SeedMigrator $migrator [description]
     */
    public function __construct(SeedMigrator $migrator)
    {
        parent::__construct($migrator);
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        $options = parent::getOptions();
        $options[] = [
            'class',
            null,
            InputOption::VALUE_OPTIONAL,
            "There is no rootseeder in the LaravelSeeder package, but we need this to override Laravel's behavior.",
            null
        ];

        return $options;
    }
}
