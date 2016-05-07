<?php

/**
 * Lib - less is more in PHP
 * @copyright   Bill Rocha - http://plus.google.com/+BillRocha
 * @license     MIT
 * @author      Bill Rocha - prbr@ymail.com
 * @version     0.0.1
 * @package     Neos
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

use Lib;

class Debug
{
	private $logPath 	= _APP.'Logs/';
	private $dir 		= null;
	private $file 		= null;
	private $size 		= 1000; //in bytes [ 10000 = 10Kb]
	private $name 		= null;
	private $obj 		= null;


	/**
	 * Construtor do Debug
	 * @param mixed $obj     	Dados a servem gravados em log
	 * @param string $context 	Contexto (normalmente o NAMESPACE) do objeto de origem
	 */
	function __construct($obj, $context = 'default')
	{
		//File
		$this->file = $this->logPath.trim(str_replace('\\', '/', $context).'.log', '\\/ ');
		$this->dir = dirname($this->file);		
		$this->name = basename($this->file);

		//verificando/criando diretório
		if(!$this->createDir($this->dir))
			trigger_error("Can't permission to create log path!\n Path:".$this->dir);

		//Object ...
		$this->obj = $obj;
	}

	/**
	 * Verifica se existe o caminho indicado, criando se necessário e tiver permissão
	 * @param  string $dir 	Caminho a ser checado/criado
	 * @return boll      	True/false
	 */
	private function createDir($dir)
	{
		//Verificando o caminho
		if(!is_dir($dir) || !is_writable($dir)){
			@mkdir($dir, 0777);
			@chmod($dir, 0777);
			
			//Checando
			if(!is_dir($dir) || !is_writable($dir)) return false;
		}
		return true;
	}

	//Log to Router
	function log()
	{
		//Config
		$type = FILE_APPEND;

		$debug = '['.date('Y/m/d H:i:s').'] '
			 .$this->obj->getMethod().' | '
			 .$this->obj->getController().$this->obj->getSeparator().$this->obj->getAction().' | '
			 .$this->obj->getUrl().$this->obj->getRequest();

		foreach($this->obj->getParams() as $p){
			$debug .= "\n\t+ ".(is_array($p) || is_object($p) ? print_r($p, true) : $p);
		}
		$debug .= "\n";

		//Verificando o tamanho do arquivo de log - limitando
		if(file_exists($this->file) && filesize($this->file) > $this->size){
			rename($this->file, $this->file.'_'.date('YdmHis').'_'.uniqid(rand(0,10)).'.bkp');
			$type = null;
		}
		
		//gravando o arquivo de log
		file_put_contents($this->file, $debug, $type);
	}


	//Error handler function
	static function errorHandler($errno, $errstr, $errfile, $errline) 
	{
	    switch ($errno) {

	    case E_WARNING:
	        return true;
	        break;

	    default:
	    	$table = static::tracer($errfile);
	    	$errstr = static::getErrorName($errno).': '.$errstr;
	    	$errfile = 'File: '.$errfile.' ['.$errline.']';
	        include _HTML.'error.html';
	        exit(1);
	        break;
	    }

	    /* Don't execute PHP internal error handler */
	    return true;
	}

	private static function getErrorName($errno)
	{
		$lst[E_ERROR] = 		'E_ERROR';
		$lst[E_WARNING] = 		'E_WARNING';
		$lst[E_PARSE] = 		'E_PARSE';
		$lst[E_NOTICE] = 		'E_NOTICE';
		$lst[E_CORE_ERROR] = 		'E_CORE_ERROR';
		$lst[E_CORE_WARNING] = 		'E_CORE_WARNING';
		$lst[E_COMPILE_ERROR] = 	'E_COMPILE_ERROR';
		$lst[E_COMPILE_WARNING] = 	'E_COMPILE_WARNING';
		$lst[E_USER_ERROR] = 		'E_USER_ERROR';
		$lst[E_USER_WARNING] = 		'E_USER_WARNING';
		$lst[E_USER_NOTICE] = 		'E_USER_NOTICE';
		$lst[E_ALL] = 			'E_ALL';
		$lst[E_STRICT] = 		'E_STRICT';
		$lst[E_RECOVERABLE_ERROR] = 	'E_RECOVERABLE_ERROR';
		
		return $ret = $lst[$errno] ? $lst[$errno] : 'Error ('.$errno.')';
	} 

	//Exception function
	static function exceptionHandler($e) 
	{
	    if(get_class($e) == 'PDOException'){
	        exit($e->getMessage().'<br>code: '.$e->getCode());
	        
	    } else {

	    	$table = static::tracer($e->getFile(), $e->getTrace());
	    	$errstr = static::getErrorName($e->getCode()).': '.$e->getMessage();
	    	$errfile = 'File: '.$e->getFile().' ['.$e->getLine().']';
	    	include _HTML.'error.html';
	    	exit(1);       
	    }
	}

	//Tracer
	static private function tracer($efile = null, $trace = null)
	{
		if($trace === null){
			$trace = array_reverse(debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT));
		} 
		$ret = '';
		$efile = str_replace(_WWW, '', $efile);
		
		foreach($trace as $tc){
			
			$function = isset($tc['function']) ? $tc['function'] : '';

			if($function == 'trigger_error' || $function == 'errorHandler') break;
			if(isset($tc['file']) && $tc['file'] == __FILE__) break;
			if($function == '{closure}') continue;

			$class = isset($tc['class']) ? $tc['class'] : '';
			$type = isset($tc['type']) ? $tc['type'] : '';
			$file = isset($tc['file']) ? str_replace(_WWW, '', $tc['file']) : '';
			$line = isset($tc['line']) ? "$tc[line]" : '';
			$args = isset($tc['args']) ? $tc['args'] : '';

			$a = '(';
			foreach($args as $arg){
				@$a .= '"'.str_replace(_WWW, '', $arg).'", ';
			}
			if(strlen($a) > 1) $a = substr($a, 0, -2); //retirando ultima virgula :)
			$a .= ')';

			$hi = ($efile == $file) ? ' class="up"' : '';
			$ret .= "<tr$hi><td>$class$type$function$a</td><td>$file</td><td>$line</td></tr>";
		}
		return $ret;		
	}

}
