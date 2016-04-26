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
	    case E_USER_ERROR:
	    	$table = static::tracer();
	        include _HTML.'error.html';
	        exit(1);
	        break;

	    case E_USER_WARNING:
	        echo "<p>WARNING: [$errno] $errstr | File: $errfile [$errline]</p>";
	        break;

	    case E_USER_NOTICE:
	        echo "<p>NOTICE: [$errno] $errstr | File: $errfile [$errline]</p>";
	        break;

	    default:
	        echo "<p>Unknown error type: [$errno] $errstr | File: $errfile [$errline]</p>";
	        break;
	    }

	    /* Don't execute PHP internal error handler */
	    return true;
	}

	//Exception function
	static function exceptionHandler($e) 
	{
	    if(get_class($e) == 'PDOException'){
	        $err = $e->getMessage().'<br>code: '.$e->getCode();
	    } else {
	        $err = 
	        '<b>Code:</b>'.$e->getCode().'<br>'.
	        '<b>Message:</b> <i>'.$e->getMessage().'</i><br>'.
	        '<b>Thrown in: </b>'.$e->getFile().' ['.$e->getLine().']<br>'.
	        '<b>Stack trace:</b><pre>'.$e->getTraceAsString().'</pre>';
	        
	    }
	    exit($err);
	}

	//Tracer
	static private function tracer()
	{
		$trace  = array_reverse(debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT));
		$ret = '';
		
		foreach($trace as $tc){
			
			$function = isset($tc['function']) ? $tc['function'] : '';

			if($function == 'trigger_error' || $function == 'errorHandler') break;
			if($function == '{closure}') continue;

			$class = isset($tc['class']) ? $tc['class'] : '';
			$type = isset($tc['type']) ? $tc['type'] : '';
			$file = isset($tc['file']) ? str_replace(_WWW, '', $tc['file']) : '';
			$line = isset($tc['line']) ? "$tc[line]" : '';
			$args = isset($tc['args']) ? $tc['args'] : '';

			$a = '(';
			foreach($args as $arg){
				$a .= '"'.str_replace(_WWW, '', $arg).'", ';
			}
			if(strlen($a) > 1) $a = substr($a, 0, -2); //retirando ultima virgula :P
			$a .= ')';

			$ret .= "<tr><td>$class$type$function$a</td><td>$file</td><td>$line</td></tr>";
		}
		return $ret;		
	}

}