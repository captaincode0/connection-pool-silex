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
	
	namespace Captaincode\ConnectionPool\Components;

	use Captaincode\ConnectionPool\Components\DatabaseConfigurator;

	abstract class Connection{
		public static const CONNECTION_LOCKED = false;
		public static const CONNECTION_UNLOCKED = true;
		
		/**
		 * [$status the current status of connection]
		 * @var bool
		 */
		private $status;

		/**
		 * [$connection the current connection object]
		 * @var object
		 */
		private $connection;

		public function __construct(){
			$this->status = self::CONNECTION_UNLOCKED;
		}

		/**
		 * [build makes the current connection with one configurator]
		 * @param  DatabaseConfigurator $configurator [description]
		 * @return type                             [description]
		 */
		public abstract function build(DatabaseConfigurator $configurator);

		public function setConnection($connection){
			$this->connection = $connection;
		}

		public function getConnection(){
			return $this->connection;
		}

		public function setLocked(){
			$this->status = self::CONNECTION_LOCKED;
		}

		public function setUnlocked(){
			$this->status = self::CONNECTION_UNLOCKED;
		}

		public function isLocked(){
			return !$this->status;
		}

		public function isUnlocked(){
			return $this->status;
		}
	}