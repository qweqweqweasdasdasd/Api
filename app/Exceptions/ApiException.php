<?php 

namespace App\Exceptions;

/**
 * 自定义 api 错误跑错 
 */
class ApiException extends \Exception
{
	public function __construct(array $ApiErrDesc)
    {
        $code  = $ApiErrDesc[0];
        $msg = $ApiErrDesc[1];
        
        parent::__construct($msg, $code);
    }
}