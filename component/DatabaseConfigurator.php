<?php
	// Copyright (C) 2017  captaincode
	
	// This library is free software; you can redistribute it and/or modify it
	// under the terms of the GNU Lesser General Public License as published by
	// the Free Software Foundation; either version 3.0 of the License, or (at
	// your option) any later version.
	
	// This library is distributed in the hope that it will be useful, but WITHOUT
	// ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
	// FITNESS FOR A PARTICULAR PURPOSE.  See the GNU Lesser General Public
	// License for more details.
	
	// You should have received a copy of the GNU Lesser General Public License
	// along with this library.  If not, see <http://www.gnu.org/licenses/>.
	
	namespace captaincode\ConnectionPool\Components;

	abstract class DatabaseConfigurator{
		/**
		 * [$driver_name database driver name]
		 * @var string
		 */
		private $driver_name;

		/**
		 * [$username database username]
		 * @var string
		 */
		private $username;

		/**
		 * [$password database user password]
		 * @var string
		 */
		private $password;

		/**
		 * [$host database host]
		 * @var string
		 */
		private $host;

		/**
		 * [$schema database schema or collection]
		 * @var string
		 */
		private $schema;

		/**
		 * [$port database server port]
		 * @var string
		 */
		private $port;

		public function __construct($username="", $password="", $host="", $schema="", $port=""){
			$this->username = $username;
			$this->password = $password;
			$this->host = $host;
			$this->schema = $schema;
			$this->port = $port;
		}

		/**
		 * [buildConnectionString make the connection string for the current database configurator]
		 * @return [string] [the connection string]
		 */
		public abstract function buildConnectionString();

		public function getDriverName(){
			return $this->driver_name;
		}

		public function setDriverName($driver_name){
			$this->driver_name = $driver_name;
		}

		public function getUserName(){
			return $this->username;
		}

		public function setUserName($username){
			$this->username = $username;
		}

		public function getPassword(){
			return $this->password;
		}

		public function setPassword($password){
			$this->password = $password;
		}

		public function getHost(){
			return $this->host;
		}

		public function setHost($host){
			$this->host = $host;
		}

		public function getSchema(){
			return $this->schema;
		}

		public function setSchema($schema){
			$this->schema = $schema;
		}

		public function getPort(){
			return $this->port;
		}

		public function setPort($port){
			$this->port = $port;
		}
	}