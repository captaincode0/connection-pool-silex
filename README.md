#Connection Pool Silex

One connection pool is one set of opened connections to one database, but this connections remains opened, thus persistent and each connection has the next status:

1. **Locked:** The connection is beign used by other component in the application.
2. **Unlocked:** The connection is available to be used in this moment.

##Modeling

![uml class diagram](https://raw.githubusercontent.com/captaincode0/connection-pool-silex/master/diagram.jpg)

##How it works?

The class `Connection` has two status, `CONNECTION_LOCKED` and `CONNECTION_UNLOCKED`, but when the connection is locked, the service checks if can get other available connection.

The abstract method `build` needs the be configured for each connection in concrete classes.

```php
    class MyConnection extends Connection{
        /**
         * @Override
         */
        public function build(DatabaseConfigurator $configurator){
            //BUILD YOUR CONNECTION HERE
            //EXAMPLE
            try{
                $connection_string = $configurator->buildConnectionString();
                $dao = new \PDO($connection_string, $configurator->getUserName(), $configurator->getPassword());
                $this->setConnection($dao);
            }
            catch(\PDOException $ex){
                throw new \RuntimeException($ex);
            }
        }
    }
```

The class `DatabaseConfigurator` encapsulates all the information to build one connection like: host, port, user, password, schema or database.

The abstract method `buildConnectionString` needs to be overrided to make the connection string knowed as DSN.

```php
    class MyDatabaseConfigurator extends DatabaseConfigurator{
        /**
         * @Override
         */
        public function buildConnectionString(){
            //BUILD YOUR CURRENT CONNECTION STRING
        }
    }
```

##How to implement this connection pool in Silex?

Firstly you need to create the configuration for databases in your application

```php
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
```

The second steep is to configure the `ConnectionPoolServiceProvider`, and pass your configurations:

```php
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
```

The parameters of the service parameters are the following:
    1. connection-pool.connections: an array that contains all the connections to the database.
    2. pool.configuration: the configuration object to build the connection to the database.
    2. pool.connections: the number of connections to open.
    3. pool.name: the name of pool.

The implementation in one controller is the following, you need to call the servicer inside the controller, using a closure the pass by value the current application:

```php
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
            $controllers =  $app["controllers_factory"];

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
```

The previous example is the use of one connection inside the main-pool, and the service will get one available connetion using $connection_pool->getConnection() method, just if the connection is unlocked, the controller uses the unlocked connection and interact with database, retrieves data and unlocks the connection, finally returns JSON data.