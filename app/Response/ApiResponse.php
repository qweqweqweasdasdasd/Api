<?php

namespace App\Response;

/**
 * api 相应
 */
class ApiResponse
{
	// 返回错误的 相应
	public static function JsonData($code,$msg,$data=[])
	{
		$response = [
			'code' => $code,
			'msg'  => $msg,
			'data' => $data
		];
		return self::ToJson($response);
	}

	// 返回成功的 相应 
	public static function ResponseSuccess($data)
	{
		$response = [
			'code' => 1,
			'msg'  => 'success',
			'data' => $data
		];
		return self::ToJson($response);
	}

	// 返回 json 数据
	public static function ToJson(array $data)
	{
		$response = [
			'code' => $data['code'],
			'msg'  => $data['msg'],
			'data' => $data['data']
		];
		return json_encode($response);
	}
}