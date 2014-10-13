<?php

namespace Smartcms\Core\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Sentry;

class UpdateCommand extends Command 
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'core:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Core update command';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $this->info('## Core Update ##');

        // publish core assets
        $this->call('asset:publish', array('package' => 'smartcms/core' ) );

        // run migrations
        $this->call('migrate', array('--env' => $this->option('env'), '--package' => 'cartalyst/sentry' ) );
        $this->call('migrate', array('--env' => $this->option('env'), '--package' => 'smartcms/core' ) );
    }
}
