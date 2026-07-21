<?php

Class DBConnection{
    protected $db;
    function __construct(){
        $this->db= new mysqli('localhost:3307','root','','bms'); //inserting port number, username, password and database name
        if(!$this->db){
            die('Database Connection Failes. Error: '.$this->db->error);  // error message
        }

    }
    function db_connect(){
        return $this->db; //database connection
    }
    function __destruct(){
        //  $this->db->close();
    }
}

function format_num($number = '',$decimal=''){ //to format a number with decimal points
    if(is_numeric($number)){
        $ex = explode(".",$number);
        $dec_len = isset($ex[1]) ? strlen($ex[1]) : 0;
        if(!empty($decimal) || is_numeric($decimal)){
            return number_format($number,$decimal);
        }else{
            return number_format($number,$dec_len);
        }
    }else{
        return 'Invalid input.';
    }
}

$db = new DBConnection();
$conn = $db->db_connect(); //called to get the database connection object.