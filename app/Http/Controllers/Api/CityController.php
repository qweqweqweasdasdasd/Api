<?php

namespace App\Http\Controllers\Api;

use App\City\SkipCity;
use Illuminate\Http\Request;
use App\Response\ApiResponse;
use App\Http\Controllers\Controller;

class CityController extends Controller
{
	// 运营服务商
	protected $banOperation = ['腾讯云','阿里云'];
	// 城市
	protected $banCity = ['北京','上海','广州','深圳','杭州','成都','武汉','香港'];

	// ip 必须前端传来
	protected $ip = '218.205.64.108';

	// redis 开关
	protected $ONOFF = true;

	// 拒绝不同的城市访问
	public function skip(Request $request)
	{
		$data = [
			'banCity' => !empty($request->get('banCity')) ? $request->get('banCity') : $this->banCity,
			'banOperation' => $this->banOperation,
			'ip' => !empty($request->get('ip')) ? $request->get('ip') :$this->ip,
			'ONOFF' => $this->ONOFF
		];

		try {
			(new SkipCity($data))->BanCity($this->ONOFF);
		} catch (\Exception $e) {
			return ApiResponse::JsonData($e->getCode(),$e->getMessage());
		}

		// 成功返回
  		return ApiResponse::ResponseSuccess([]);
	}

}
