<?php

namespace Eighty8\LaravelSeeder;

use Config;
use File;
use Illuminate\Console\Command;
use Illuminate\Console\DetectsApplicationNamespace;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class SeedMakeCommand extends Command
{
    use DetectsApplicationNamespace;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'seeder:make';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Makes a seeder';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $model = ucfirst($this->argument('model'));
        $path = $this->option('path');
        $env = $this->option('env');
        $stub = File::get(__DIR__ . '/stubs/DatabaseSeeder.stub');

        // Check path
        if (empty($path)) {
            $path = database_path(config('seeders.dir'));
        } else {
            $path = base_path($path);
        }

        // Check env
        if (!empty($env)) {
            $path .= "/$env";
        }

        if (!File::exists($path)) {
            File::makeDirectory($path, 0755, true);
        }

        // File name
        $created = date('Y_m_d_His');
        $path .= "/seed_{$created}_{$model}Seeder.php";

        // Content
        $namespace = rtrim($this->getAppNamespace(), '\\');
        $stub = str_replace('{{model}}', "seed_{$created}_" . $model . 'Seeder', $stub);
        $stub = str_replace('{{namespace}}', " namespace $namespace;", $stub);
        $stub = str_replace('{{class}}', $model, $stub);

        // Create file
        File::put($path, $stub);

        // Output message
        $message = "Seeder created for $model";

        if (!empty($env)) {
            $message .= " in environment: $env";
        }

        $this->line($message);
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['model', InputArgument::REQUIRED, 'The name of the model you wish to seed.'],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['env', null, InputOption::VALUE_OPTIONAL, 'The environment to seed to.', null],
            [
                'path',
                null,
                InputOption::VALUE_OPTIONAL,
                'The relative path to the base path to generate the seed to.',
                null
            ],
        ];
    }
}
