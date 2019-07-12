<?php

namespace App\Spider\Disallow;

use App\ErrDesc\ApiErrDesc;
use App\Exceptions\ApiException;

class RequestHeader
{
	protected $spiderUA;

	// 初始化
	public function __construct()
	{
		$this->spiderUA = config('spiderIp.spiderUA');
	}

	public function filterUA($ua)
	{	
		// ua 为空判断为爬虫
		if(!empty($ua)){
			// 是否存在于拒绝 ua 列表内
			foreach ($this->spiderUA as $k => $v) {
				$spiderUA = strtolower($v);
				if(strpos($ua,$spiderUA) !== false){
					// 写日志
					app('log')->info( '拦截ua不合法,时间:'.date('Y-m-d H:i:s',time()) .' ua:'. $ua );
					throw new ApiException(ApiErrDesc::UA_ROBOT);
				}
			}
			return true;
		}
		throw new ApiException(ApiErrDesc::UA_EMPEY);
	}
}