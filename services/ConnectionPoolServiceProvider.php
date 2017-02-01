<?php
	// Copyright (C) 2017  captaincode
	
	// This program is free software: you can redistribute it and/or modify it
	// under the terms of the GNU General Public License as published by the Free
	// Software Foundation, either version 3 of the License, or (at your option)
	// any later version.
	
	// This program is distributed in the hope that it will be useful, but WITHOUT
	// ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
	// FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for
	// more details.
	
	// You should have received a copy of the GNU General Public License along
	// with this program.  If not, see <http://www.gnu.org/licenses/>.
	
	namespace Captaincode\ConnectionPool\Services;

	use Silex\Application;
	use Silex\ServiceProviderInterface;
	use Captaincode\ConnectionPool\Components\DatabaseConfigurator;
	use Captaincode\ConnectionPool\Services\ConnectionPoolService;

	/**
	 * ConnectionPoolServiceProvider
	 * 		parameters:
	 * 			-connection-pool.connections = [
	 * 				[
	 * 					"pool.configuration" => $configurator,
	 * 					"pool.connections" => 20, //number of connections
	 * 					"pool.name" => "main-pool" //the name of the pool
	 * 				]
	 * 			]
	 */
	class ConnectionPoolServiceProvider implements ServiceProviderInterface{
		/**
		 * [register defines the adding operation]
		 * @param  Application $app [current application]
		 * @return [void]
		 * @override
		 */
		public function register(Application $app){
			$app["connection-pool.service"] = $app->share(function() use($app){
				$connection_pool_service = new ConnectionPoolService();

				$connection_pool_service->build($app["connection-pool.connections"]);

				return $connection_pool_service;
			});
		}

		/**
		 * [boot check all the parameters before create the provider]
		 * @param  Application $app [current application]
		 * @return [void]
		 * @override
		 */
		public function boot(Application $app){

		}
	}