<?php

namespace Eighty8\LaravelSeeder;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;

class SeedInstallCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'seeder:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create the Seeder repository';

    /**
     * The repository instance.
     *
     * @var \Illuminate\Database\Migrations\MigrationRepositoryInterface
     */
    protected $repository;

    /**
     * Constructor.
     *
     * @param SeederRepository $repository
     */
    public function __construct(SeederRepository $repository)
    {
        parent::__construct();

        $this->repository = $repository;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $this->repository->setSource($this->input->getOption('database'));

        $this->repository->createRepository();

        $this->info('Seeders table created successfully.');
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['database', null, InputOption::VALUE_OPTIONAL, 'The database connection to use.'],
        ];
    }
}
