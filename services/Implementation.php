<?php

	use Silex\Application;
	use Captaincode\ConnectionPool\Components\MySQLDatabaseConfigurator;
	use Captaincode\ConnectionPool\Components\ConnectionPoolServiceProvider;

	class MyApplication extends Application{
		public function __construct($parameters=[]){
			parent::__construct($parameters);
		}
	}

	$myapp = new MyApplication();

	$main_database_configuration = new MySQLDatabaseConfigurator(
			"usercp2341",
			"mypassword",
			"0ffwfs.domain.com",
			"main-app-database",
			"9845"
		);

	$backup_database_configuration = new MySQLDatabaseConfigurator(
			"usercp2341",
			"mypassword",
			"0ffwfs.domain.com",
			"backup-app-database",
			"9845"
		);

	$app->register(new ConnectionPoolServiceProvider(), [
			"connection-pool.connections" => [
				[
					"pool.configuration" => $main_database_configuration,
					"pool.connections" => 30,
					"pool.name" => "main-pool"
				],
				[
					"pool.configuration" => $backup_database_configuration,
					"pool.connections" => 20,
					"pool.name" => "backup-pool"
				]
			]	
		]);
