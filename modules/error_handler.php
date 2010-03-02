<?php                                                        
// set the user error handler method to be error_handler 
set_error_handler("error_handler", E_ALL);               
                                   
function error_handler($errNo, $errStr, $errFile, $errLine)
{                                                            
	// the first two elements of the backtrace array are irrelevant: (-DBG_Backtrace, -outErrorHandler)                                  
	$backtrace = dbg_get_backtrace(2);                         
	
	// error message to be displayed, logged or mailed         
	$error_message = "\nERRNO: $errNo 
										\nTEXT: " . $errStr . " 
										\nLOCATION: " . $errFile . ", line " . $errLine . ", 
										\n          at " .  date("F j, Y, g:i a") . "
										\n\n==================
										\n\nShowing backtrace:
										\n\n\n" . $backtrace . "\n\n";
			   


	// email the error details, in case SEND_ERROR_MAIL is true
	if (SEND_ERROR_MAIL == true)    
	  	error_log($error_message, 1, ADMIN_ERROR_MAIL, "From: " . 
				 SENDMAIL_FROM . "\r\nTo: " . ADMIN_ERROR_MAIL);  
	
	// log the error, in case LOG_ERRORS is true
	if (LOG_ERRORS == true)
	{
		$log = new Logger('errors');
		$log->enter($error_message);
	}
	
	// warnings don't abort execution if IS_WARNING_FATAL is false
	// E_NOTICE and E_USER_NOTICE errors don't abort execution  
	if (($errNo == E_WARNING && IS_WARNING_FATAL == false) ||
	  ($errNo == E_NOTICE || $errNo == E_USER_NOTICE)) 
	// if the error is non-fatal ...
	{               
		// show message only if DEBUGGING is true
		if (DEBUGGING == true)
		   	echo "<pre>" . $error_message . "</pre>";
	}               
	else            
	// if error is fatal ... 
	{               
		// show error message 
		if (DEBUGGING == true) 
		   	echo "<pre>" . $error_message . "</pre>";
		else          
		   	echo SITE_GENERIC_ERROR_MESSAGE;
		// stop processing the request
		exit;         
	}               
}

// builds backtrace message
function dbg_get_backtrace($irrelevantFirstEntries)
{                 
	$s = '';        
	$MAXSTRLEN = 64;
	$traceArr = debug_backtrace();
	
	for ($i = 0; $i < $irrelevantFirstEntries; $i++) array_shift($traceArr);
	
	$tabs = sizeof($traceArr) - 1;
	
	foreach($traceArr as $arr)
	{               
		$tabs -= 1;                                              
		if (isset($arr['class']))                                
		   $s .= $arr['class'] . '.';                            
		$args = array();                                         
		if (!empty($arr['args']))                                
		foreach($arr['args']as $v)                               
		{                                                        
			if (is_null($v))                                      
			 	$args[] = 'null';                                   
			else if (is_array($v))                                
			 	$args[] = 'Array[' . sizeof($v).']';                
			else if (is_object($v))                               
			 	$args[] = 'Object:' . get_class($v);                
			else if (is_bool($v))                                 
			 	$args[] = $v ? 'true' : 'false';                    
			else                                                  
			{                                                     
				$v = (string)@$v;                                   
				$str = htmlspecialchars(substr($v, 0, $MAXSTRLEN)); 
				if (strlen($v) > $MAXSTRLEN)                        
				$str .= '...';                                    
				$args[] = "\"" . $str . "\"";                       
			}                                                     
		}                                                        
		$s .= $arr['function'] . '(' . implode(', ', $args) . ')';
		$Line = (isset($arr['line']) ? $arr['line']: "unknown"); 
		$File = (isset($arr['file']) ? $arr['file']: "unknown"); 
		$s .= sprintf(" # line %4d, file: %s", $Line, $File, $File);
		$s .= "\n\n";                                              
	}                                                          
	return $s;                                                 
}   
?>                                                         