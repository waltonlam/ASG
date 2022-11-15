<?php

class MyDB {

    private $_Con;

    const MYSQL_HOST = 'localhost'; // Database Host

    const MYSQL_USER = 'root'; // Database User

    const MYSQL_PASS = ''; // Database Password

    const MYSQL_NAME = 'taps'; // Database Name


    function __construct() {

        $this->_Con = mysqli_connect(self::MYSQL_HOST,self::MYSQL_USER,self::MYSQL_PASS,self::MYSQL_NAME);

        if (!$this->_Con) {
            exit('Connection failed to MySQL host <b>' . self::MYSQL_HOST . '</b>');
        }

        // mysqli_select_db(self::MYSQL_NAME,$this->_Con);

        if (mysqli_error( $this->_Con)) {
            exit('Connection to <b>' . self::MYSQL_NAME . '</b> failed.');
        }

    }


    public function selectData($sqlStatement){
        $returnarray = null;
        $SQL = $sqlStatement;
        $exec = mysqli_query($this->_Con,$SQL);
        $result = array();
        $num = mysqli_num_rows($exec);
        if ($num != 0) {
            $result = array();
            while (NULL != ($row = mysqli_fetch_assoc($exec))) {
                $thisset = array();
                foreach ($row as $key => $val) {
                        $thisset[$key] = $val;
                }
                $result[] = $thisset;
            }
        }


        $returnarray = array( 'result' => $result);


        return $returnarray;

    }
  

    public function selectFrom($table, $columns = null, $where = null, $like = false, $orderby = null, $direction = null, $limit = null, $offset = null) {

        $SQL = "SELECT ";
        if ($columns != null) {
            if(is_array($columns)) { $cols = implode(", ",$columns); }
            else { $cols = $columns; }
            $SQL.= $cols." ";
        }
        else {
            $SQL.= "* ";
        }
        $SQL.= "FROM ".$table;

        if ($where != null) {
            $SQL.=" WHERE";
            if ($like == true) {
                $whe = $this->_helperWhereLikeGenerate($where);
            }
            else {
                $whe = $this->_helperWhereEqualsGenerate($where);
            }
            $SQL.= $whe;
        }
        if ($direction != null && $orderby != null) {
            if (strtolower($direction) == "asc" || strtolower($direction) == "desc") {
                $order = " ORDER BY ".$orderby." ".$direction;
                $SQL .= $order;
            }
        }
        if ($limit != null) {
            $lim = null;
            if ($offset == null) {
                $lim = " LIMIT ".$limit;
                $SQL .= $lim;
            }
            else {
                $lim = " LIMIT ".$offset.", ".$limit;
                $SQL .= $lim;
            }
        }

        $exec = mysqli_query($this->_Con,$SQL);

        $num = mysqli_num_rows($exec);
        if ($num != 0) {
            $result = array();
            while (NULL != ($row = mysqli_fetch_assoc($exec))) {
                $thisset = array();
                foreach ($row as $key => $val) {
                        $thisset[$key] = $val;
                }
                $result[] = $thisset;
            }
        }

        // $returnarray = array('sql' => $SQL, 'num' => $num, 'result' => $result);

        $returnarray = array('result' => $result);


        return $returnarray;

    }



    public function insertInto($table = null, $fields) {

        $cleanfields = $this->_helperCleanFields($fields);

        $inserts = $this->_helperExtractFieldsValues($cleanfields);

        $SQL = "INSERT INTO ";
        $SQL.= $table . " ";
        $SQL.= $inserts['fields'] . " ";
        $SQL.= "VALUES ";
        $SQL.= $inserts['values'] . " ";

        mysql_query($SQL, $this->_Con);

        $id = mysql_insert_id($this->_Con);

        if (isset($id) && is_numeric($id) && $id != 0) {
            $status = "success";
        }
        else {
            $status = "failure";
        }

        $insertid = $id;

        $return = array('status' => $status, 'id' => $insertid);

        return $return;

    }

 

    public function updateTable($table, $fields, $where = null, $like = false) {

        $cleanfields = $this->_helperCleanFields($fields);

        if ($where != null) {
            $wherebegin = " WHERE";
            if ($like == false) {
                $wherecommand = $this->_helperWhereEqualsGenerate($where);
            }
            else {
                $wherecommand = $this->_helperWhereLikeGenerate($where);
            }
            $wherecommand = $wherebegin.$wherecommand;
        }
        else {
            $wherecommand = "";
        }


        $updates = $this->_helperExtractUpdateValues($cleanfields);

        $SQL = "UPDATE ";
        $SQL.= $table . " ";
        $SQL.= $updates . "";
        $SQL.= $wherecommand . " ";

        mysql_query($SQL, $this->_Con);

        $num = mysql_affected_rows($this->_Con);

        if (isset($num) && is_numeric($num) && $num != 0 && $num != -1) {
            $status = "success";
        }
        else {
            $status = "failure";
        }

        $affected = $num;

        $return = array('sql' => $SQL, 'status' => $status, 'affected' => $affected);

        return $return;

    }


    public function updateReview($sqlStatement){
         
          mysqli_query($this->_Con,$sqlStatement);

        $num = mysqli_affected_rows($this->_Con);

        if (isset($num) && is_numeric($num) && $num != 0 && $num != -1) {
            $status = "success";
        }
        else {
            $status = "failure";
        }

        $affected = $num;

        $return = array('sql' => $sqlStatement, 'status' => $status, 'affected' => $affected);

        return $return;


    }


   


    public function deleteFrom($table, $where = null, $like = false, $limit = 1) {

        $SQL = "DELETE ";
        $SQL.= "FROM ".$table;

        if ($where != null) {
            $SQL.=" WHERE";
            if ($like == true) {
                $whe = $this->_helperWhereLikeGenerate($where);
            }
            else {
                $whe = $this->_helperWhereEqualsGenerate($where);
            }
            $SQL.= $whe;
        }

        if ($limit != null) {
            $lim = " LIMIT ".$limit;
            $SQL .= $lim;
        }

        mysql_query($SQL,$this->_Con);

        $num = mysql_affected_rows($this->_Con);

        if (isset($num) && is_numeric($num) && $num != 0 && $num != -1) {
            $status = "success";
        }
        else {
            $status = "failure";
        }

        $affected = $num;

        $return = array('sql' => $SQL, 'status' => $status, 'affected' => $affected);

        return $return;

    }





    private function _helperWhereLikeGenerate($where) {

        if (!empty($where)) {
            $whe = "";
            $i = 0;
            foreach ($where as $wKey => $wVal) {
                $i++;
                if ($i != 1) { $wheAdd = " AND "; }
                else { $wheAdd = " "; }
                $whe .= $wheAdd.$wKey . " LIKE '%".mysql_real_escape_string($wVal,$this->_Con)."%'";
            }
        }
        else {
            $whe = "";
        }

        return $whe;

    }

    /**
     * A helper function to generate x = y AND y = z
     * @param array $where
     * @return str
     */
    private function _helperWhereEqualsGenerate($where) {

        if (!empty($where)) {
            $whe = "";
            $i = 0;
            foreach ($where as $wKey => $wVal) {
                $i++;
                if ($i != 1) { $wheAdd = " AND "; }
                else { $wheAdd = " "; }
                $whe .= $wheAdd.$wKey . "='".mysql_real_escape_string($wVal,$this->_Con)."'";
            }
        }
        else {
            $whe = "";
        }

        return $whe;

    }

    /**
     * A helper function to cleanse values
     * @param array $fields
     * @return array clean key => values
     */
    private function _helperCleanFields($fields) {

        $cleanfields = array();

        foreach ($fields as $fkey => $fval) {

            $cleanfields[$fkey] = mysql_real_escape_string($fval,$this->_Con);

        }

        return $cleanfields;

    }

    /**
     * A helper function to extract field names
     * and values from an array of clean variables.
     * @param array $cleanfields
     * @return array ( fields, values )
     */
    private function _helperExtractFieldsValues($cleanfields) {

        $return = array();

        if (!empty($cleanfields)) {
            $keys = array_keys($cleanfields);
            $vals = array_values($cleanfields);

            $insertFields = implode(", ",$keys);
            $insertFields = "(".$insertFields.")";
            $insertValues = implode("', '",$vals);
            $insertValues = "('".$insertValues."')";

            $return['fields'] = $insertFields;
            $return['values'] = $insertValues;
        }

        return $return;

    }

    /**
     * A helper function to extract the cleansed fields
     * into a string for the UPDATE command
     * @param array $cleanfields
     */
    private function _helperExtractUpdateValues($cleanfields) {

        $return = "";

        if (!empty($cleanfields)) {
            $return .= "SET";
            $i = 0;
            foreach ($cleanfields as $key => $value) {
                $i++;
                if ($i != 1){ $begin = ","; }
                else { $begin = ""; }
                $return .= $begin." " . $key . "='".$value."'";

            }
        }

        return $return;

    }

}