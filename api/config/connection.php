<?php 
      class Connection{
            public function getConnection(){
                  $config = parse_ini_file("config.ini",true);
                  $server = $config['db']['host'];
                  $user = $config['db']['user'];
                  $pass = $config['db']['password'];
                  $database = $config['db']['dbname'];
                  $connection = new mysqli($server, $user, $pass, $database);

                  return $connection;
            }
      }
?>