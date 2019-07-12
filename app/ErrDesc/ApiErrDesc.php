<?php

namespace App\ErrDesc;

/**
 * Api 自定义错误
 */
class ApiErrDesc
{
	const SERVER_ERR = ['1000','服务器内部错误!'];

	const NOT_KNOW_ERR = ['2000','API接口出现错误!'];

	// 请求频率不在规则范围之内
	const APP_REQUEST_HZ = ['2000','API请求频率过于频繁,拒绝'];

	// 判断是游览器爬虫
	const SPIDER_IP = ['2000','从数据库内查询为游览器爬虫IP,拒绝'];

	// 判断UA不是用户进行访问
	const UA_ROBOT = ['2000','UA只能为标准的游览器,否则拒绝'];

	// 判断UA为空
	const UA_EMPEY = ['2000','UA不得为空,当为空时,拒绝'];




	// 城市在拒绝名单里面
	const BAN_LIST = ['3000','城市在拒绝名单里面!'];

	// 运营商在拒绝名单里面
	const BAN_OPERATION = ['3000','运营商在拒绝名单里面']; 
}