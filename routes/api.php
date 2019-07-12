<?php

// 360 防止拦截报毒接口 禁止了360ip段 禁止ua不合法 禁止请求频率过高(可设置)
Route::post('/disallow/spider','Api\ApiController@spider');

// 判断城市 进行跳转
Route::post('/city/skip','Api\CityController@skip');

