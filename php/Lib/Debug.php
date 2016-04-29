<?php

/**
 * Lib - less is more in PHP
 * @copyright   Bill Rocha - http://plus.google.com/+BillRocha
 * @license     MIT
 * @author      Bill Rocha - prbr@ymail.com
 * @version     0.0.1
 * @package     Lib
 * @access      public
 * @since       0.3.0
 *
 */

namespace Lib;

use Lib;

class Debug
{


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
		$lst[E_ERROR] = 			'E_ERROR';
		$lst[E_WARNING] = 			'E_WARNING';
		$lst[E_PARSE] = 			'E_PARSE';
		$lst[E_NOTICE] = 			'E_NOTICE';
		$lst[E_CORE_ERROR] = 		'E_CORE_ERROR';
		$lst[E_CORE_WARNING] = 		'E_CORE_WARNING';
		$lst[E_COMPILE_ERROR] = 	'E_COMPILE_ERROR';
		$lst[E_COMPILE_WARNING] = 	'E_COMPILE_WARNING';
		$lst[E_USER_ERROR] = 		'E_USER_ERROR';
		$lst[E_USER_WARNING] = 		'E_USER_WARNING';
		$lst[E_USER_NOTICE] = 		'E_USER_NOTICE';
		$lst[E_ALL] = 				'E_ALL';
		$lst[E_STRICT] = 			'E_STRICT';
		$lst[E_RECOVERABLE_ERROR] = 'E_RECOVERABLE_ERROR';
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