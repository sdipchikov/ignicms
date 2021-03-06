<?php

namespace Despark\Cms\Console\Commands\Admin;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'igni:admin:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Installs the application by setting up all the necessary resources.';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (env('CACHE_DRIVER') !== 'array') {
            $path = base_path('.env');
            if (file_exists($path)) {
                file_put_contents($path, str_replace(
                    'CACHE_DRIVER='.env('CACHE_DRIVER'),
                    'CACHE_DRIVER=array',
                    file_get_contents($path)
                ));
            }
        }

        // Generate the Application Encryption key
        $this->call('key:generate');

        // Publish the packages.
        $this->call('vendor:publish', [
            '--force' => true,
            '--provider' => 'Despark\Cms\Providers\AdminServiceProvider',
        ]);

        // Run the Migrations
        $this->call('migrate');

        exec('composer dumpautoload');
        $this->call('clear-compiled');
        $this->call('optimize');

        $feCommands = [
            'npm install --silent',
            'bower install',
            'gulp dev',
        ];

        $this->info('Install front-end (npm and bower) dependencies and runing gulp.');
        $bar = $this->output->createProgressBar(count($feCommands));
        $bar->start();

        foreach ($feCommands as $command) {
            exec($command);

            $bar->advance();
        }

        $bar->finish();
        $this->info('');
        $this->info('Everything has been set up! Now you can start developing.');
    }
}
