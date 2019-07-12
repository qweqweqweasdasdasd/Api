<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Response\ApiResponse;
use App\Spider\Disallow\DisallowIp;
use App\Http\Controllers\Controller;
use App\Spider\Disallow\RequestHeader;

class ApiController extends Controller
{
    // 360 预防拦截报毒接口
    public function spider(Request $request)
    {
        $data = [
            'ua' => !empty($request->get('ua'))?$request->get('ua'):'',
            'ip' => !empty($request->get('ip'))?$request->get('ip'):''
        ];
    	// 项目跟目录写一个 robots.txt (手动写)
    	try {
	    	// 请求头过滤
	    	(new RequestHeader)->filterUA($data['ua']);
	    	// 拒绝爬虫 ip 段
	    	(new DisallowIp)->disallowIp($data['ip']);
    	} catch (\Exception $e) {
    		
    		return ApiResponse::JsonData($e->getCode(),$e->getMessage());
    	}

    	// 成功返回
  		return ApiResponse::ResponseSuccess([]);

    }

    
    
}
