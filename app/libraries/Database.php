<?php
/**
 * Class Database
 *
 * @author Ceytec <david@nani-games.net>
 */
class Database {
    private $dbHost = DB_HOST;
    private $dbUser = DB_USER;
    private $dbPass = DB_PASS;
    private $dbName = DB_NAME;

    private $statement;
    private $dbHandler;
    private $error;

    /**
     * Database constructor.
     */
    public function __construct()
    {
        $connection = 'mysql:host=' . $this->dbHost . ';dbname=' . $this->dbName;
        $options = array(
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        );
        try {
            $this->dbHandler = new PDO($connection, $this->dbUser, $this->dbPass, $options);
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            echo $this->error;
        }
    }

    /**
     * Execute query
     *
     * @param $sql
     */
    public function query($sql) {
        $this->statement = $this->dbHandler->prepare($sql);
    }

    /**
     * Bind query parameters
     *
     * @param $param
     * @param $value
     * @param null $type
     */
    public function bind($param, $value, $type = null) {
        switch(is_null($type)) {
            case is_int($value):
                $type = PDO::PARAM_INT;
                break;
            case is_bool($value):
                $type = PDO::PARAM_BOOL;
                break;
            case is_null($value):
                $type = PDO::PARAM_NULL;
                break;
            default:
                $type = PDO::PARAM_STR;
        }
        $this->statement->bindValue($param, $value, $type);
    }

    /**
     * Execute prepared statement
     *
     * @return boolean
     */
    public function execute() {
        return $this->statement->execute();
    }

    /**
     * Returns array of objects
     *
     * @return array
     */
    public function resultSet() {
        $this->execute();
        return $this->statement->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Return single object
     *
     * @return object
     */
    public function single() {
        $this->execute();
        return $this->statement->fetch(PDO::FETCH_OBJ);
    }

    /**
     * Return count of rows
     *
     * @return integer
     */
    public function rowCount() {
        return $this->statement->rowCount();
    }
}