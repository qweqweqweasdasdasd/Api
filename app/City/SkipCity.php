<?php

namespace App\City;

use App\ErrDesc\ApiErrDesc;
use App\Exceptions\ApiException;
use Illuminate\Support\Facades\Redis;

class SkipCity
{
	// 禁止的城市
	protected $banCity;

	// 禁止运行商
	protected $banOperation;

	// 访客的ip
	protected $ip;

	// redis 缓存前缀
	protected $prefix = 'city';

	// redis 缓存开关
	protected $ONOFF;


	// 接口数据
	protected $datatype = 'jsonp';
	protected $url = 'http://api.ip138.com/query/';
	protected $city;

	// 初始化
	public function __construct(array $d)
	{
		$this->banCity = $d['banCity'];
		$this->banOperation = $d['banOperation'];
		$this->ip = $d['ip'];
		$this->ONOFF = $d['ONOFF'];
		$this->init();
	}
	

	public function BanCity()
	{	
		$data = $this->city;
		$city = !empty($data['2']) ? $data['2'] :'';
		$operation = !empty($data['3']) ? $data['3'] :'';
		
		// 比对城市
		if(in_array($city, $this->banCity)){
			throw new ApiException(ApiErrDesc::BAN_LIST);
		}
		
		// 比对运营商
		if(in_array($operation, $this->banOperation)){
			throw new ApiException(ApiErrDesc::BAN_OPERATION);
		}
		return true;
	}


	// 初始化 接口
	protected function init()
	{
		// 缓存开关
		if($this->ONOFF){
			$this->ReadRedis();
		}else{
			$this->GetCityByIP();
		}
	}

	// redis 缓存
	protected function ReadRedis()
	{
		$info = Redis::get($this->prefix.'-'.$this->ip);
		if(is_null($info)){
			$this->GetCityByIP();
			Redis::set($this->prefix.'-'.$this->ip, json_encode($this->city));
		}else{
			//读取缓存
	    	$this->city = json_decode($info,true);
		}
		return true;
	}

	// 请求接口
	protected function GetCityByIP()
    {
        $url = $this->url.'?ip='.$this->ip.'&datatype='.$this->datatype;
        $header = array('token:74456a7ee350a008832ce8b66bd5f722');
        $res = json_decode($this->myCurl($url,$header),true);
        // 判断接口是否没有费用了或者出现了异常
        if($res['ret'] == 'ok'){
        	$this->city = $res['data'];

        	return true;
        }
        app('log')->info('138-ip,时间'.date('Y-m-d H:i:s',time()).' 异常信息:'.json_encode($res));
        return false;
    }

	// 封装 curl
	protected function myCurl($url,$header)
    {
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_HTTP_VERSION,CURL_HTTP_VERSION_1_1);
        curl_setopt($ch,CURLOPT_HTTPHEADER,$header);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1); 
        curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,3);
        $handles = curl_exec($ch);
        curl_close($ch); 
        return $handles; 
    }
}