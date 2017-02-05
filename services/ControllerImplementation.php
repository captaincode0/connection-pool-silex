<?php

	use Silex\Application;
	use Silex\ControllerProviderInterface;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\HttpFoundation\Response;
	use Symfony\Component\HttpFoundation\JsonResponse;

	class ControllerImplementation implements ControllerProviderInterface{	
		/**
		 * @Override
		 */
		public function connect(Application $app){
			$controllers =	$app["controllers_factory"];

			$controllers->get("/user/all", function() use($app){
				//get one unlocked connection
				$connection_pool = $app["connection-pool.service"]->getConnection("main-pool");

				//use the connection
				$main_database_connection = $connection_pool->getConnection();

				//do something with the connection
				$statement = $main_database_connection->query("select users.id, users.email, users.name users.facebookapitoken from users left join articles as ar on articles.userid = users.id and articles.number>5");

				$content = [];

				foreach($statement->fetchAll(\PDO::FETCH_NAMED) as $row)
					$content[] = $row;

				//set the connection to unlocked status
				$connection_pool->setUnlocked();

				return $app->json($content);
			});

			$controllers->after(function(Request $request, Response $response) use($app){
				$response->headers->set("content-type", "application/json");
			});

			return $controllers;
		}
	}