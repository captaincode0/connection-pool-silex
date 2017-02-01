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
	
	namespace Captaincode\ConnectionPool\Components;

	use Captaincode\ConnectionPool\Components\DatabaseConfigurator;

	class MySQLDatabaseConfigurator extends DatabaseConfigurator{
		public function __construct($username="", $password="", $host="", $schema="", $port=""){
			parent::__construct($username, $password, $host, $schema, $port);
			$this->setDriverName("mysql");
		}

		public function buildConnectionString(){
			return $this->getDriverName().":host=".$this->getHost().";dbname=".$this->getSchema().";port=".$this->getPort().";charset=utf8";
		}
	}