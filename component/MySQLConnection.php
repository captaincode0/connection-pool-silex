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
	// 
	
	namespace Captaincode\ConnectionPool\Components;

	use Captaincode\ConnectionPool\Components\DatabaseConfigurator;
	use Captaincode\ConnectionPool\Components\Connection;


	class MySQLConnection extends Connection{
		/**
		 * @Override
		 */
		public function build(DatabaseConfigurator $configurator){
			//get the current connection string
			$connection_string = $configurator->buildConnectionString();

			if(!empty($connection_string)
				& is_string($connection_string)){
				try{
					//build the connection
					$database_handler = new \PDO(
						$connection_string, 
						$configurator->getUserName(), 
						$configurator->getPassword(), [
							//for persistents connections
							\PDO::ATTR_PERSISTENT => true
						]);
					
					$this->setConnection($connection);
				}
				catch(\PDOException $ex){
					//do something with the exception
				}
			}
		}
	}