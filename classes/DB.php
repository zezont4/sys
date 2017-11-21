<?php
/**
 *  DB - A simple database class
 *
 * @author        Author: Vivek Wicky Aswal. (https://twitter.com/#!/VivekWickyAswal)
 * @git        https://github.com/indieteq/PHP-MySQL-PDO-Database-Class
 * @version      0.2ab
 *
 */
require("Log.php");

class DB
{
    # @object, The PDO object
    private $pdo;

    # @object, PDO statement object
    private $sQuery;

    # @array,  The database settings
//	private $settings;

    # @bool ,  Connected to the database
    private $bConnected = false;

    # @object, Object for logging exceptions
    private $log;

    # @array, The parameters of the SQL query
    private $parameters;

    /**
     * Default Constructor
     *
     * 1. Instantiate Log class.
     * 2. Connect to database.
     * 3. Creates the parameter array.
     */
    public function __construct()
    {
        $this->log = new Log();
        $this->Connect();
        $this->parameters = array();
    }

    /**
     *    This method makes connection to the database.
     *
     *    1. Reads the database settings from a ini file.
     *    2. Puts  the ini content into the settings array.
     *    3. Tries to connect to the database.
     *    4. If connection failed, exception is displayed and a log file gets created.
     */
    private function Connect()
    {
        //host=".Config::get('host'),Config::get('user'),Config::get('pass')
//		$this->settings = parse_ini_file("../../config.php");
        if (Config::get('db_name')) {
            $db_name = Config::get('db_name');
        } else {
            $db_name = Session::get('db_name');
        }

        $dsn = 'mysql:dbname=' . $db_name . ';host=' . Config::get('host') . '';
        try {
            # Read settings from INI file, set UTF8
            $this->pdo = new PDO($dsn, Config::get('user'), Config::get('pass'), array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));

            # We can now log any exceptions on Fatal error.
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            # Disable emulation of prepared statements, use REAL prepared statements instead.
            $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

            # Connection succeeded, set the boolean to true.
            $this->bConnected = true;
        } catch (PDOException $e) {
            # Write into log
            echo $this->ExceptionLog($e->getMessage());
            die();
        }
    }

    /**
     * You can use this little method if you want to close the PDO connection
     *
     */
    public function CloseConnection()
    {
        # Set the PDO object to null to close the connection
        # http://www.php.net/manual/en/pdo.connections.php
        $this->pdo = null;
    }

    /**
     * Every method which needs to execute a SQL query uses this method.
     *
     * 1. If not connected, connect to the database.
     * 2. Prepare Query.
     * 3. Parameterize Query.
     * 4. Execute Query.
     * 5. On exception : Write Exception into the log + SQL query.
     * 6. Reset the Parameters.
     * @param String $query [[Description]]
     * @param Array [$parameters = ""] [[Description]]
     */
    private function Init($query, $parameters = "")
    {
        # Connect to database
        if (!$this->bConnected) {
            $this->Connect();
        }
        try {
            # Prepare query
            $this->sQuery = $this->pdo->prepare($query);

            # Add parameters to the parameter array
            $this->bindMore($parameters);

            # Bind parameters
            if (!empty($this->parameters)) {
                foreach ($this->parameters as $param) {
                    $parameters = explode("\x7F", $param);
                    $this->sQuery->bindValue($parameters[0], $parameters[1]);
                }
            }

            # Execute SQL
            $this->succes = $this->sQuery->execute();
        } catch (PDOException $e) {
            # Write into log and display Exception
            echo $this->ExceptionLog($e->getMessage(), $query);
            die();
        }

        # Reset the parameters
        $this->parameters = array();
    }

    /**
     * @void
     *
     * Add single parameter to the parameter array
     * @param string $para [[Description]]
     * @param string $value [[Description]]
     */
    public function bind($para, $value)
    {
        //            $this->parameters[sizeof($this->parameters)] = ":" . $para . "\x7F" . $value;
        $this->parameters[sizeof($this->parameters)] = $para . "\x7F" . $value;
    }

    /**
     * @void
     *
     * Add multiple parameters to the parameter array
     * @param array $parray [[Description]]
     */
    public function bindMore($parray)
    {
        if (empty($this->parameters) && is_array($parray)) {
            $columns = array_keys($parray);
            foreach ($columns as $i => &$column) {
                $this->bind($column, $parray[$column]);
            }
        }
    }


    /**
     * If the SQL query  contains a SELECT or SHOW statement it returns an array containing all of the result set row
     * If the SQL statement is a DELETE, INSERT, or UPDATE statement it returns the number of affected rows
     * @param   String $query [[Description]]
     * @param   Array [$params    = null]                [[Description]]
     * @param   Number [$fetchmode = PDO::FETCH_OBJ] [[Description]]
     * @returns Mix    [[Description]]
     */
    public function query($query, $params = null, $fetchmode = PDO::FETCH_OBJ)
    {

        $query = trim($query);

        $this->Init($query, $params);

        $rawStatement = explode(" ", $query);

        # Which SQL statement is used
        $statement = strtolower($rawStatement[0]);

        if ($statement === 'select' || $statement === 'show') {
            return $this->sQuery->fetchAll($fetchmode);
            //            return = $this->sQuery->rowCount();
        } elseif ($statement === 'insert' || $statement === 'update' || $statement === 'delete') {
            return $this->sQuery->rowCount();
        } else {
            return NULL;
        }
    }

    /**
     * MakeS update easy
     * @param   String $tableName name of the table
     * @param   Array $set array like : $set['field1']='data';$set['field2']='data2';
     * @param   String $whereString like : "id=:id and name=:name";
     * @param   Array $whereArray array like $where[':id']='123';$where[':name']='zezo';
     * @returns Number returns the number of affected rows
     */
    public function zUpdate($tableName, $set, $whereString, $whereArray)
    {
        $data = array();
        $fieldsvals = '';
        //convert $set array to be part of the UPDATE string
        foreach ($set as $column => $value) {
            $fieldsvals .= $column . " = :" . $column . ",";
            $data[':' . $column] = $value;
        }
        $fieldsvals = substr_replace($fieldsvals, '', -1);

        foreach ($whereArray as $column2 => $value2) {
            $data[$column2] = $value2;
            //make sure that the user must put (:) in where array
            if (strpos($column2, ':') === false) {
                echo '<hr>zezo MSG : <br> Please put (:) before ' . $column2 . ' In Where array from the (zUpdate) method(function) <br><hr>';
            }
        }

        if (count($set) > 0 && count($whereArray) > 0) {
            $sql = "UPDATE " . $tableName . " SET " . $fieldsvals . " WHERE " . $whereString;
            return $this->query($sql, $data);
        }
    }

    /**
     * MakeS update easy
     * @param   String $tableName name of the table
     * @param   Array $field_value array like : $insrt['name']='zezo';$insrt['mobile_no']='055555555';
     * @returns Number returns the number of affected rows
     */
    public function zInsert($tableName, $field_value)
    {
        $fieldsvals = '';
        //convert $field_value array to be part of the INSERT string
        if (!empty($field_value)) {
            $fields = array_keys($field_value);
            $fieldsvals = array(implode(",", $fields), ":" . implode(",:", $fields));
            $sql = "INSERT INTO " . $tableName . " (" . $fieldsvals[0] . ") VALUES (" . $fieldsvals[1] . ")";
            return $this->query($sql, $field_value);
        }
    }

    /**
     * Returns the last inserted id.
     * @returns string string
     */
    public function lastInsertId()
    {
        return $this->pdo->lastInsertId();
    }

    /**
     * Returns an array which represents a column from the result set
     * @param   string $query [[Description]]
     * @param   [[Type]] [$params = null] [[Description]]
     * @returns array    array
     */
    public function column($query, $params = null)
    {
        $this->Init($query, $params);
        $Columns = $this->sQuery->fetchAll(PDO::FETCH_NUM);

        $column = null;

        foreach ($Columns as $cells) {
            $column[] = $cells[0];
        }

        return $column;

    }

    /**
     * Returns an array which represents a row from the result set
     * @param   string $query [[Description]]
     * @param   [[Type]] [$params = null]                [[Description]]
     * @param   [[Type]] [$fetchmode = PDO::FETCH_OBJ] [[Description]]
     * @returns array    array
     */
    public function row($query, $params = null, $fetchmode = PDO::FETCH_OBJ)
    {
        $this->Init($query, $params);
        return $this->sQuery->fetch($fetchmode);
    }

    /**
     * Returns the value of one single field/column
     * @param   string $query [[Description]]
     * @param   [[Type]] [$params = null] [[Description]]
     * @returns string   string
     */
    public function single($query, $params = null)
    {
        $this->Init($query, $params);
        return $this->sQuery->fetchColumn();
    }

    /**
     * Writes the log and returns the exception
     * @param   string $message [[Description]]
     * @param   [[Type]] [$sql = ""] [[Description]]
     * @returns string   string
     */

    function queryToArray($sqlString, $where = NULL)
    {
//        $pdoQuery = new DB();
        $queryArray = [];
        $queryResult = $this->query($sqlString, $where, PDO:: FETCH_NUM);
        foreach ($queryResult as $row) {
            $queryArray[$row[0]] = $row[1];
        }
        return $queryArray;
    }

    private function ExceptionLog($message, $sql = "")
    {
        $exception = 'Unhandled Exception. <br />';
        $exception .= $message;
        $exception .= "<br /> You can find the error back in the log.";

        if (!empty($sql)) {
            # Add the Raw SQL to the Log
            $message .= "\r\nRaw SQL : " . $sql;
        }
        # Write into log
        $this->log->write($message);

        return $exception;
    }

    public function SqlString(){
        return $this->sQuery->queryString;
    }
}


