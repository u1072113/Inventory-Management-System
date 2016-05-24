<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use DB;
use Illuminate\Database\Eloquent\Model;
use  App\Role;
class Setup extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'setup';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Command description.';

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
		$this->info('Creating Database');
		$this->call('migrate');
		$this->info('Database Created');
		$this->info('Creating User Roles');
		Model::unguard();
		DB::table('roles')->truncate();
		Role::create([
			'id' => 1,
			'name' => 'Root',
			'description' => 'Use this account with extreme caution. When using this account it is possible to cause irreversible damage to the system.'
		]);

		Role::create([
			'id' => 2,
			'name' => 'Administrator',
			'description' => 'Full access to create, edit, Delete Stock Items, Users and Departments'
		]);

		Role::create([
			'id' => 3,
			'name' => 'Dispatcher',
			'description' => 'Ability to Dispach and Restock Items can also delete Dispatches and Restocks but not users and departments'
		]);

		Role::create([
			'id' => 4,
			'name' => 'Elevated User',
			'description' => 'User who can see items that have been dispatched to him'
		]);

		Role::create([
			'id' => 5,
			'name' => 'User',
			'description' => 'A standard user with No administrative features and no Login.'
		]);
		$this->info('User Roles Created');
		if ($this->confirm('Do you want me to create the default admin user? [yes|no]'))
		{
			$name = $this->ask('Name of admin?');
			$email = $this->ask('Email of admin?');
			$password = $this->ask('Password for admin ?');
			$this->call('stock:admin', ['--name' => $name,'--email'=>$email,'--password'=>$password]);
		}
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return [
			//['example', InputArgument::REQUIRED, 'An example argument.'],
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
		//	['example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null],
		];
	}

}
