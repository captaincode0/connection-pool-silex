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

	use Captaincode\ConnectionPool\Components\Connection\MySQLConnection;

	class ConnectionPoolService{
		/**
		 * [$connections description]
		 * @var [array]
		 */
		private $connections;

		public function __construct(){
			/**
			 * The structures of connections is the following
			 * 	["connection collection name" => [
			 * 		//connections an array of objects of Connection Type
			 * 	]]
			 */
			$this->connections = []
		}

		/**
		 * [build creates the connection with the service configuration]
		 * @param  array  $configurations [the set of collections to configure]
		 * @return void
		 */
		public function build($configurations){
			//build the connections with the service provider configuration
			foreach($configurations as $configuration){
				//get the pool access configuration
				$pool_configuration = $configuration["pool.configuration"];

				//get the number of connections
				$pool_connections = $configuration["pool.connections"];

				//get the pool
				$pool_name = $configuration["pool_name"];

				//set the collection
				$this->connections[$pool_name] = [];

				for($pool_connection_index = 0; $pool_connection_index < $pool_connections; $pool_connection_index++){
					//get the current connection
					$current_connection = null;

					switch($pool_configuration->getDriverName()){
						case "mysql":
								//generate the current connection
								$current_connection = new MySQLConnection();
								$current_connection->build($pool_configuration);
							break;
						default 
							break;
					}

					if($current_connection)
						$this->connections[$pool_name][] = $current_connection;
				}
			}
		}		

		/**
		 * [getConnections get all the connections by one collection name]
		 * @param  string $collection_name [the collection name]
		 * @return array                  [an array of collection objects]
		 */
		public function getConnections($collection_name){
			return (array_key_exists($collection_name, $this->connections))?$this->connections[$collection_name]?null;
		}

		/**
		 * [getConnection get one available connection by the collection name]
		 * @param  string $collection_name [the connection name]
		 * @return array                  [an array of collection objectss]
		 */
		public function getConnection($collection_name){
			if(array_key_exists($collection_name, $this->connections)){
				//get the current collection of collections
				$collection = $this->connections[$collection_name];

				//get the current unlocked connection
				$connection_unlocked = null;

				//infinite loop cicle to get one available connection
				for(;;){
					foreach($collection as $connection){
						if($connection->isUnlocked()){
							//set the unlocked connection pass by reference
							$connection_unlocked = $connection;

							//set the connection locked
							$connection_unlocked->setLocked();

							//break if one connection available was founded
							break;
						}
					}

					if($connection_unlocked)
						return $connection_unlocked;
				}
			}

			return null;
		}
	}