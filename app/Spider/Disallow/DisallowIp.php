<?php

namespace App\Spider\Disallow;

use App\ErrDesc\ApiErrDesc;
use App\Exceptions\ApiException;
use Illuminate\Support\Facades\Redis;

class DisallowIp
{
	// 360 爬虫ip
	protected $spider_360;
	// 限制访问次数
	protected $limit  = 5;
	// 限制生命期
	protected $expire = 60;
	// 爬虫 开关
	protected $ONOFF = true;

	// redis 前缀
	protected $prefix = 'spider';

	// 初始化
	public function __construct()
	{
		$this->spider_360 = config('spiderIp.360spiderIp');
	}

	public function disallowIp($clientIp)
	{
		if($this->ONOFF){
			// 360 爬虫
			if(in_array($clientIp, $this->spider_360)){
				// 写日志
				app('log')->info( '拦截360爬虫ip段,时间:'.date('Y-m-d H:i:s',time()) .' ip:'. $clientIp );
				throw new ApiException(ApiErrDesc::SPIDER_IP);
			}
		}

		// 其他的爬虫 
		$check = Redis::exists($this->prefix.'-'.$clientIp);
		if($check){
			Redis::incr($this->prefix.'-'.$clientIp);
			$count = Redis::get($this->prefix.'-'.$clientIp);
			if($count > $this->limit){
				// 写日志
				app('log')->info( '拦截频率高ip段,时间:'.date('Y-m-d H:i:s',time()) .' ip:'. $clientIp .' 频率:'. $this->limit);
				throw new ApiException(ApiErrDesc::APP_REQUEST_HZ);
			}
		}else{
			Redis::set($this->prefix.'-'.$clientIp,1);				// 设置 1 
			Redis::expire($this->prefix.'-'.$clientIp,$this->expire);	// 60秒
		}
		
		return true;
	}
}