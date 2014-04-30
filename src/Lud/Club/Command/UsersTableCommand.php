<?php namespace Lud\Club\Command;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Config;
use Lud\Club\Club;

class UsersTableCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'club:users-table';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Create a migration for your user\'s table';

	/**
	 * The filesystem instance.
	 *
	 * @var \Illuminate\Filesystem\Filesystem
	 */
	protected $files;

	public function __construct(Filesystem $files)
	{
		parent::__construct();

		$this->files = $files;
	}

	/**
	 * Execute the console command.
	 *
	 * @return void
	 */
	public function fire()
	{

		$tname = Club::modelTableName();
		$mname = Club::modelName();

		$fullPath = $this->createBaseMigration();

		$this->files->put($fullPath, $this->getMigrationStub());

		$this->info('Migration created successfully !');
		$this->info("Check $fullPath to add your own columns to the table.");

		$this->call('dump-autoload');
	}

	/**
	 * Create a base migration file for the users.
	 *
	 * @return string
	 */
	protected function createBaseMigration()
	{
		$name = 'create_club_users_table';

		$path = $this->laravel['path'].'/database/migrations';

		return $this->laravel['migration.creator']->create($name, $path);
	}

	/**
	 * Get the contents of the users migration stub.
	 *
	 * @return string
	 */
	protected function getMigrationStub()
	{
		$stub = $this->files->get(__DIR__.'/stubs/users.stub');
		return str_replace('tablename_placeholder', Club::modelTableName(), $stub);
	}

}
