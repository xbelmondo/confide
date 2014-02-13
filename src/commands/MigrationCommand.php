<?php namespace Zizaco\Confide;

use Zizaco\Confide\Support\GenerateCommand;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

/**
 * This command renders the package view generator.migration and also
 * within the application directory in order to save some time.
 *
 * @license MIT
 * @package  Zizaco\Confide
 */
class MigrationCommand extends GenerateCommand
{
    /**
     * Laravel application
     *
     * @var \Illuminate\Foundation\Application
     */
    public $app;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'confide:migration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a migration following the Confide especifications.';

    /**
     * Create a new command instance.
     *
     * @param  \Illuminate\Foundation\Application $app Laravel application object
     * @return void
     */
    public function __construct($app = null)
    {
        if (! is_array($app))
            parent::__construct();

        $this->app = $app ?: app();
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return array(
            array('table', null, InputOption::VALUE_OPTIONAL, 'Table name.', 'users'),
        );
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        // Prepare variables
        $table = lcfirst($this->option('table'));

        $viewVars = compact(
            'table'
        );

        // Prompt
        $this->line('');
        $this->info( "Table name: $table" );
        $this->comment("A migration that creates the $table table will".
        " be created in app/database/migrations directory");
        $this->line('');

        if ( $this->confirm("Proceed with the migration creation? [Yes|no]") )
        {
            $this->info( "Creating migration..." );
            // Generate
            $filename = 'database/migrations/'.
                date('Y_m_d_His')."_confide_setup_$table.php";
            $this->generateFile($filename, 'generators.migration', $viewVars);

            $this->info( "Migration successfully created!" );
        }
    }
}