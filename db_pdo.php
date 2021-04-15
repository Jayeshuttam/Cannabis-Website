<?php
/**
 * class for database using PDO to connect to any type of SQL servers, including MySQL.
 */
 class DB_PDO
 {
     public $connection = null;
     public $db_host = '127.0.0.1';
     public $db_user_name = 'root';
     public $db_user_password = '';
     public $db_name = 'cannabis_db';

     public function __construct()
     {
         // connection options
         //https://www.php.net/manual/en/pdo.setattribute.php
         $options = [
            // throw exception on SQL errors
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            // return records with associative keys only, no numeric index
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            //Enables or disables emulation of prepared statements. Some drivers do not support native prepared statements or have .
            //(if FALSE) to try to use native prepared statements
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
         try {
             $this->connection = new PDO('mysql:host='.$this->db_host.';dbname='.$this->db_name.';charset=utf8mb4', $this->db_user_name, $this->db_user_password, $options);
         } catch (PDOException $e) {
             http_response_code(500);

             throw new PDOException($e->getMessage(), (int) $e->getCode());
             exit;
         }
     }

     /**
      * query() for INSERT, UPDATE, DELETE that returns no records.
      */
     public function query($sql_str)
     {
         try {
             $result = $this->connection->query($sql_str);
             if (!$result) {
                 exit('SQL query error!');
             }
         } catch (PDOException $e) {
             throw new PDOException($e->getMessage(), (int) $e->getCode());
         }

         return $result;
     }

     /**
      * query() for INSERT, UPDATE, DELETE that return no records.
      */
     public function queryParam($sql_str, $params)
     {
         $stmt = $this->connection->prepare($sql_str);
         $stmt->execute($params);

         return true;
     }
     public function queryInsert($sql_str)
     {
         $stmt = $this->connection->prepare($sql_str);
         $stmt->execute();

         return true;
     }

     /**
      * querySelect for SELECT queries returning records converted in PHP array.
      */
     public function querySelect($sql_str)
     {
         $records = $this->query($sql_str)->fetchAll();

         return $records;
     }

     public function table($table)
     {
         $queryStr = 'select * from '.$table;

         return $this->querySelect($queryStr);
     }

     private function addCommas($value, $arrayCount, $counter)
     {
         $insertQuery = '';
         if (!is_int($value)) {
             $insertQuery .= '"';
         }
         $insertQuery .= $value;
         if (!is_int($value)) {
             $insertQuery .= '"';
         }
         if ($arrayCount != $counter) {
             $insertQuery .= ', ';
         }

         return $insertQuery;
     }

     public function selectAll($table, $conditions = [])
     {
         $queryStr = 'select * from '.$table;
         if (!empty($conditions)) {
             $queryStr .= ' where ';
             foreach ($conditions as $type => $valueCondition) {
                 $i = 1;
                 $arrayCount = count($valueCondition);
                 foreach ($valueCondition as $column => $value) {
                     $queryStr .= $column.'='.$this->addCommas($value, $arrayCount, $i).' ';
                     if ($arrayCount != $i) {
                         $queryStr .= $type.' ';
                     }
                     ++$i;
                 }
             }
         }

         return $this->querySelect($queryStr);
     }

     public function insert($table, $columns, $values)
     {
         $insertQuery = 'insert into '.$table;
         if (!empty($columns)) {
             $insertQuery .= '('.implode($columns, ',').')';
         }

         if (!empty($values)) {
             $insertQuery .= 'VALUES (';
             $i = 1;
             foreach ($values as $value) {
                 $insertQuery .= $this->addCommas($value, count($values), $i);
                 ++$i;
             }
             $insertQuery .= ')';
         }

         return $this->query($insertQuery);
     }

     public function selectLikeColumn($table, $column = '', $like = '')
     {
         $queryStr = 'select * from '.$table;
         $params = [];
         if (trim($like) != '') {
             $queryStr .= ' where '.$column.' like :like_input';

             $params = [':like_input' => '%'.$like.'%'];
         }

         return $this->querySelectParam($queryStr, $params);
     }

     public function querySelectParam($sql_str, $params)
     {
         $stmt = $this->connection->prepare($sql_str);
         $stmt->execute($params);

         return $stmt->fetchAll();
     }

     public function disconnect()
     {
         $this->connection = null;
     }
 }
