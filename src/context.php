<?php
namespace Vbt;

/**
 * Contex class
 */
class Context
{
	/**
	  * Saves the error message
	  *
	  * @var string
	  */
	public $_message = '';

	/**
	  * The class constructor
	  *
	  * @param array parametros del error
	  */
	public function __construct($params)
	{
		$this->_message = '
			<div style="background:#eee; padding: 10px; border: 1px solid #333; color: #333;">
				<b style="color:#333">' . $this->getErrorType($params['errno']) . ':</b> ' . $params['errstr'] . '<br><hr>
				<b style="color:#333">File:</b> ' . $params['errfile'] . '<br><hr>
				<b style="color:#333">Line:</b> ' . $params['errline'] . '<br><hr>
				<b style="color:#333">Code Context:</b><br><pre>' .
				$this->getCodeContext($params['errfile'], (int) $params['errline']) . '</pre><hr>
			</div>';
	}
	/**
	  * Get error type
	  *
	  * @param int error number
	  * @return string
	  */
	private function getErrorType($errno)
	{
		switch ($errno) {
			case E_ERROR:
				$error_type = 'Error';
				break;
			case E_WARNING:
				$error_type = 'Warning';
				break;
			case E_PARSE:
				$error_type = 'Parsing Error';
				break;
			case E_NOTICE:
				$error_type = 'Notice';
				break;
			case E_CORE_ERROR:
				$error_type = 'Core Error';
				break;
			case E_CORE_WARNING:
				$error_type = 'Core Warning';
				break;
			case E_COMPILE_ERROR:
				$error_type = 'Compile Error';
				break;
			case E_COMPILE_WARNING:
				$error_type = 'Compile Warning';
				break;
			case E_USER_ERROR:
				$error_type = 'User Error';
				break;
			case E_USER_WARNING:
				$error_type = 'User Warning';
				break;
			case E_USER_NOTICE:
				$error_type = 'User Notice';
				break;
			case E_STRICT:
				$error_type = 'Rutine Notice';
				break;
			case E_RECOVERABLE_ERROR:
				$error_type = 'Catchable Fatal Error';
				break;
			default:
				$error_type = 'Unknown Error';
				break;
		}
		return $error_type;
	}
	/**
	  * Get code context
	  *
	  * @param string Error file.
	  * @param integer Error line
	  * @return string Context
	  */
	private static function getCodeContext($file, $line)
	{
		if(!file_exists($file)) {
			return 'The error context coul not be shown - (' . $file . ') does not exist';
		} elseif((!is_int($line)) OR ($line <= 0)) {
			return 'The context could not be shown - (' . $line . ') There is an invalid line number';
		} else {
			// Get the file context
			$code = file($file);
			// Count lines
			$lines = count($code);
			// Lines Before error
 			$init = $line - 5;
 			// Lineas depues del error
			$end = $line + 5;

			// If error is on the first line
			if($init < 0) $init = 0;
			// if Error is on the last file
			if($end >= $lines) $end = $lines;

			$long_end = strlen($end) + 2;

			for($i = $init - 1; $i < $end; $i++) {
				$color = ($i == $line - 1 ? "red" : "black");

				$output[] = '<span style="background-color:lightgrey">' . ($i + 1) . str_repeat("&nbsp;", $long_end - strlen($i + 1)) . '</span><span style="color:' . $color . '">'. htmlentities($code[$i]) . '</span>';
 			}
 			
			return trim(join('', $output));
		}
	}
	/**
	  * Get error message
	  *
	  * @return string error context
	  */
	public function getMessage()
	{
		header('Content-Type: text/html; charset=UTF-8');
		return $this->_message;
	}
}
?>