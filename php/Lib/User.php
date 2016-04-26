<?php
/**
 * Lib - less is more in PHP
 * @copyright   Bill Rocha - http://google.com/+BillRocha
 * @license     MIT
 * @author      Bill Rocha - prbr@ymail.com
 * @version     0.0.1
 * @package     Util
 * @access      public
 * @since       0.3.0
 *
 * The MIT License
 *
 * Copyright 2015 http://google.com/+BillRocha.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace Lib;

use Lib\Db;
use Config\Database;


class User
{
	static $node = null;

	private $login = false;
	private $data = ['id'=>null,
					 'name'=>null,
					 'token'=>null,
					 'life'=>null,
					 'login'=>null,
					 'password'=>null,
					 'level'=>null,
					 'status'=>null];

	private $db = null;
	private $dbConfig = ['table'=>'user',
					     'id'=>'id',
					     'name'=>'name',
					     'token'=>'token',
					     'life'=>'life',
					     'login'=>'login',
					     'password'=>'password',
					     'level'=>'level',
					     'status'=>'status'];


	function __construct($config = null)
	{
		if($config !== null){
			foreach($config as $i=>$d){
				if(isset($this->dbConfig[$i])) $this->dbConfig[$i] = $d;
			}
		} else $this->dbConfig = Database::getUserConfig();

		$this->db = new Db(Database::get());		
	}

	/**
     * Singleton instance
     *
     */
    static function this()
    {
        if(is_object(static::$node)) return static::$node;
        //else...
        list($config) = array_merge(func_get_args(), [null]);
        return static::$node = new static($config);
    }

	/**
     * Initialize user
     */
    function doLogin($login, $password)
    {
    	$this->db->query('SELECT * FROM '.$this->dbConfig['table']
    					  .' WHERE '.$this->dbConfig['login'].' = :lg AND '.$this->dbConfig['password'].' = :pw', [':lg'=>$login, ':pw'=>$password]);

    	$row = $this->db->result();
    	if(isset($row[0])){
    		$row = $row[0]->getAll();

    		foreach($this->data as $i=>$d){
    			if(isset($row[$this->dbConfig[$i]])) $this->data[$i] = $row[$this->dbConfig[$i]];
    		}
    		$this->login = true;
    		return true;
    	}
    	$this->login = false;
    	return false;
    }

    /**
     * Performer LOGOUT
     */
    function logout($id = null){
    	if($id !== null) $this->data['id'] = $id;

    	//Reset
    	$this->data['life'] = 0;
    	$this->data['token'] = md5(microtime());
    	
    	$this->db->query('UPDATE '.$this->dbConfig['table']
    					 .' SET '.$this->dbConfig['token'].'="'
    					 .$this->data['token'].'", '
    					 .$this->dbConfig['life'].' = "'
    					 .$this->data['life'].'" WHERE '
    					 .$this->dbConfig['id'].' = '
    					 .$this->data['id']);
    }

    /**
     * Initialize user by ID
     */
    function getById($id)
    {
    	$this->db->query('SELECT * FROM '.$this->dbConfig['table']
    					  .' WHERE '.$this->dbConfig['id'].' = :id', 
    					  [':id'=>$id]);
    	$row = $this->db->result();
    	if(isset($row[0])){
    		$row = $row[0]->getAll();

            $this->login = true; //Setando LOGIN como válido/logado

    		foreach($this->data as $i=>$d){
    			if(isset($row[$this->dbConfig[$i]])) $this->data[$i] = $row[$this->dbConfig[$i]];
    		}
    		return $this->data;
    	}
    	return false;
    }


    /**
     * Set TOKEN data key
     */
    function saveToken($token)
    {
    	$rows = $this->db->query('UPDATE '.$this->dbConfig['table']
    					.' SET '.$this->dbConfig['token'].' = :tk'
    					.' WHERE '.$this->dbConfig['id'].' = :id',
    					[':tk'=>$token, ':id'=>$this->data['id']]);
    	if($rows > 0){
    		$this->data['token'] = $token;
    		return true;
    	}
    	return false;
    }

    /**
     * Universal GET
     * If $node == null return ALL data (array)
     */
    function get($node = null)
    {
        if($node === null) return $this->data;
    	return (isset($this->data[$node])) ? $this->data[$node] : false; 
    }

    /**
     * Universal SET
     *
     * @param array|string $node Se for um array, grava todos os dados
     *                           Se for um string e existir, grava $value
     * @param string|integer $value [optional] valor a ser gravado
     *
     * @return  object (this)
     */
    function set($node, $value = null)
    {
    	if(!is_array($node)) $node[$node] = $value;
    	foreach($node as $i=>$d){
    		if(isset($this->data[$i])) $this->data[$i] = $d;    	
    	}
    	return $this;
    }


    /**
     * Save this USER on DataBase
     * 
     * @param Integer $id [optional] set a ID from this user
     *                    $id = "null" (or none) gera INSERT new user	
     * @return  Array action = INSERT/UPDATE 
     *                rows   = 0/1 (0 => indica não salvo)
     */
    function save($id = null){
    	//update this user id value
    	if($id !== null) $this->data['id'] = $id;

    	if($this->data['id'] !== null){
            $action = 'UPDATE ';
            $where = ' WHERE '.$this->dbConfig['id'].' = :id';
        } else {
            $action = 'INSERT INTO ';
            $where = '';
        }

        $cols = '';
        $vals = [];
        foreach($this->data as $k=>$v){     
            if($k !== 'id') $cols .= $this->dbConfig[$k].' = :'.$k.',';        
            $vals[':'.$k] = $v;          
        }
        
        $cols = substr($cols, 0, -1); //tirando a ultima vírgula

        $this->db->query($action.$this->dbConfig['table']
        				.' SET '.$cols.$where, $vals);
        return ['action'=>substr($action, 0, 5),
        		'rows'=>$this->db->getRows()];
    }




}