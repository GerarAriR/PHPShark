<?php 
namespace orm {
	if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 *  Qmvc - Quick PHP MVC 
 *  Developed by Contempative Radical Solutions Consulting Private Ltd. 
 *  Project Initiative By Ankit Kumar
 *  http://www.contemplativeradicals.com
 *  
 *  @copyright  Contempative Radical Solutions Consulting Private Ltd. 
 *  @link
 *  @since      1.0.0
 *  @license
 *  
 * Change Logs
 * *******************************************************************
 * 
 * *******************************************************************
 **/

 
	use core\orm\mysql as mysql;
	use core\lib\files as files;
	use core\lib\imgs as imgs;
	use core\lib\errors as errs;
	use core\lib\forms as forms;
	use core\lib\utilities as utils;
	use core\lib\json as json;
	use core\lib\pages as pages;
	use \PDO;


	class Truncate
	{
		private static $_table = '';
		private $_query = '';
		private $_data = array();

		public function __construct(string $db = null, string $dbname = 'mysql')
		{
			try { ($db !== null) ? $this->_db = $db : $this->_db = pdo();
				 $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				} catch (\PDOException $e) {
				//Application Log
			}
		}

		public function __destruct()
		{
			$this->_db = null;
		}

		public static function table(string $table)
		{
            self::$_table = $table;
			return new self;
        }
        
		public function drop()
		{
            $table = self::$_table;
			$this->query = "DROP TABLE {$table} ";
        }
        
		public function delete()
		{
            $table = self::$_table;
			$this->query = "TRUNCATE TABLE {$table} ";
		}

		public function execute(string $binder = null)
		{
			try {
				$statement = $this->_db->prepare($this->_query);
				foreach ($data as $key => $value) {
					if($binder !== null){
						$PDO_BINDER = $binder;
					}else{
						if(is_int($value)) $PDO_BINDER = PDO::PARAM_INT;
						else if(is_string($value)) $PDO_BINDER = PDO::PARAM_STR;
						else if(is_null($value)) $PDO_BINDER = PDO::PARAM_NULL;
						else if(is_bool($value)) $PDO_BINDER = PDO::PARAM_BOOL;
						else $PDO_BINDER = PDO::PARAM_INT;
					}
					$statement->bindParam(":$key", $value, $PDO_BINDER);
				}
				$statement->execute();
				if ($statement->execute()) {
					//Audit Log
					return true;
				}
				else {
					//Application Log
				}
			} catch (\PDOException $e) {
				writeDBLog($e, $query);
			}
		}

	}

}