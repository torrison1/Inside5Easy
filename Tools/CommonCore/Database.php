<?php

namespace Tools\CommonCore;

//i--- Database Class based on PDO Object ; inside_core ; torrison ; 01.05.2020 ; 1 ---/
use \PDO;

Class Database
{

    var $db_host;
    var $db_user;
    var $db_database;
    var $db_password;

    var $conn;

    function init($db = false, $host = false, $user = false, $password = false) {

        if (!$db) $this->db_database = $GLOBALS['config']['Database']['db_database'];
        else $this->db_database = $db;

        if (!$host) $this->db_host = $GLOBALS['config']['Database']['db_host'];
        else $this->db_host = $host;

        if (!$user) $this->db_user = $GLOBALS['config']['Database']['db_user'];
        else $this->db_user = $user;

        if (!$password) $this->db_password = $GLOBALS['config']['Database']['db_password'];
        else $this->db_password = $password;

        $res = Array();

        try {
            $conn = new PDO("mysql:host=$this->db_host;dbname=".$this->db_database.";", $this->db_user, $this->db_password);
            // set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $conn->query("SET NAMES utf8mb4;");

            $res['message'] = "Connected successfully";
            $res['status'] = "success";
        }
        catch(PDOException $e)
        {
            $res['message'] = $e->getMessage();
            $res['status'] = "error";

            $conn = false;

            // For Debug
            print_r($e->getMessage());

        }

        $res['conn'] = $conn;
        $GLOBALS['app']['db'] = $conn;
        $this->conn = $conn;

        return $res;

    }

    function init_2 ($db_host, $db_database, $db_user, $db_password) {

        $this->db_host = $db_host;
        $this->db_database = $db_database;
        $this->db_user = $db_user;
        $this->db_password = $db_password;

        $res = Array();

        try {
            $conn = new PDO("mysql:host=$this->db_host;dbname=".$this->db_database.";", $this->db_user, $this->db_password);
            // set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $conn->query("SET NAMES utf8mb4;");

            $res['message'] = "Connected successfully";
            $res['status'] = "success";
        }
        catch(PDOException $e)
        {
            $res['message'] = $e->getMessage();
            $res['status'] = "error";

            $conn = false;

            // For Debug
            print_r($e->getMessage());

        }

        $res['conn'] = $conn;
        $GLOBALS['app']['db'] = $conn;
        $this->conn = $conn;

        return $res;

    }

    //i--- $this->db->sql_get_data($sql) for SELECT Requests ; inside_core ; torrison ; 01.05.2020 ; 2 ---/
    function sql_get_data($query) {

        $sql = $this->conn->prepare($query);
        $sql->execute();
        $sql->setFetchMode(PDO::FETCH_ASSOC);
        $data = $sql->fetchAll();
        return($data);
    }

    //i--- $this->db->run_sql($sql) for Any Requests ; inside_core ; torrison ; 01.05.2020 ; 3 ---/
    function run_sql($query) {

        $sql = $this->conn->prepare($query);
        $res = $sql->execute();
        return $res;
    }

    //i--- $this->db->insert($table, $arr) for Insert Requests ; inside_core ; torrison ; 01.05.2020 ; 4 ---/
    function insert($table, $arr=array())
    {
        if (!is_array($arr) || !count($arr)) return false;

        $bind = ':'.implode(',:', array_keys($arr));
        $sql  = 'insert into '.$table.'('.implode(',', array_keys($arr)).') '.
            'values ('.$bind.')';
        $stmt = $this->conn->prepare($sql);
        $res = $stmt->execute(array_combine(explode(',',$bind), array_values($arr)));

        if ($stmt->rowCount() > 0)
        {
            return $res;
        }

        return false;
    }

    //i--- $this->db->update($table, $arr, $where) for Update Requests ; inside_core ; torrison ; 01.05.2020 ; 5 ---/
    function update($table, $arr=array(), $where)
    {
        $updates = array_filter($arr, function ($value) {
            return null !== $value;
        });
        $query = 'UPDATE '.$table.' SET';
        $values = array();

        foreach ($updates as $name => $value) {
            $query .= ' '.$name.' = :'.$name.','; // the :$name part is the placeholder, e.g. :zip
            $values[':'.$name] = $value; // save the placeholder
        }

        $query = substr($query, 0, -1); // remove last , and add a ;

        $query = $query." ".$where.";";

        // print_r($query); print_r($values);
        $sth = $this->conn->prepare($query);

        $res = $sth->execute($values); // bind placeholder array to the query and execute everything

        return $res;
    }

    //i--- $this->db->delete($table, $arr, $where) for Delete Requests ; inside_core ; torrison ; 01.05.2020 ; 5a ---/
    function delete($table, $where)
    {
        $query = 'DELETE FROM '.$table.' ';
        $query = $query." ".$where.";";
        // print_r($query); print_r($values);
        $sth = $this->conn->prepare($query);
        $res = $sth->execute();

        return $res;
    }

    //i--- $this->db->quote($data) for Defend string from SQL injection attack and quote string ; inside_core ; torrison ; 01.05.2020 ; 6 ---/
    function quote($data) {
        return $this->conn->quote($data);
    }
    function escape($data) { // Synonym
        return $this->conn->quote($data);
    }

    //i--- $this->db->last_id() get ID after the last insert ; inside_core ; torrison ; 01.05.2020 ; 7 ---/
    function last_id() {
        return $this->conn->lastInsertId();
    }
    function insert_id() { // Synonym
        return $this->conn->lastInsertId();
    }

}