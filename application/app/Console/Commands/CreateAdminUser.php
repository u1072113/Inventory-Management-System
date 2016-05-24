<?php namespace App\Console\Commands;

use App\User;
use Hash;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;

class CreateAdminUser extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'stock:admins';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates admin user for login for new users';

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
     * @return mixed
     */
    public function fire()
    {

        $name = $this->option('name');
        $password = $this->option('password');
        $email = $this->option('email');
        if ($this->confirm('Do you wish to create this user? [yes|no]', true)) {
            $user = User::create(array(
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
                'role_id' => 1,
            ));
        }
        $this->info('User has been successfully been created');
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            //      ['example', InputArgument::REQUIRED, 'An example argument.'],
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
            ['name', null, InputOption::VALUE_REQUIRED, 'Admin', null],
            ['password', null, InputOption::VALUE_REQUIRED, '$#$^%$45dsnff', null],
            ['email', null, InputOption::VALUE_REQUIRED, 'admin@Inventory.com', null],
        ];
    }

}
