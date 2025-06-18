<?php
	require_once(__DIR__ . '/constant.php');
	
	class Config
	{
		var $_hostname;
		var $_username;
		var $_password;
		var $_database;
		var $_table_users;

		function __construct()
		{
			$this->_hostname        = 	HOSTDB;
			$this->_username        = 	USERNAME;
			$this->_password        = 	PASSWORD;
			$this->_database        = 	DATABASE;
			$this->_table_users		= 	array();  // the name of a table
		}

		function getHostname()
		{
			return $this->_hostname;
		}
		
		function getUsername()
		{
			return $this->_username;
		}
		
		function getPassword()
		{
			return $this->_password;
		}
		
		function getDatabase()
		{	
			return $this->_database;
		}
		
		function getStudentTable()
		{
			return $this->_table_users[0];
		}
	}

	class DB_connection extends Config
    {
        var $_connection;
        var $_result;
        
        function __construct()
        {
            //$this->Config();
            $this->_connection = mysqli_connect(HOSTDB, USERNAME, PASSWORD,DATABASE)
            or die("Unable to initialize connection to mysql database: ".mysqli_connect_error());
			//$this->_connection->set_charset("utf8");
        }
        
        
        function query($query)
        {
            $this->_result = mysqli_query($this->_connection,$query)
            or die("Unable to query database1: ".mysqli_error($this->_connection));
            return $this->_result;
        }
        
        function done()
        {
            //mysql_free_result($this->_result);
            mysqli_close($this->_connection);
        }

        function prepare($query)
        {
            return mysqli_prepare($this->_connection, $query);
        }

        function begin_transaction()
        {
            return mysqli_begin_transaction($this->_connection);
        }

        function commit()
        {
            return mysqli_commit($this->_connection);
        }

        function rollback()
        {
            return mysqli_rollback($this->_connection);
        }
    }

	$connection = new DB_connection();
?>
