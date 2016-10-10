<?php
//获得配置
function get_config(){
	return C('server_config');
}

//获取网络资源
function get_content_curl($url, $referer, $ip, $cookie){

	/*$opts = array(
		'https' => array(
			'method' => 'GET',
			'timeout' => 30, //设置超时，单位是秒，可以试0.1之类的float类型数字
		)
	);
	$context = stream_context_create($opts);
	$contents = file_get_contents($url,false,$context);

	if($contents == '') return false;
	else return $contents;*/


	$ssl = substr($url, 0, 8) == "https://" ? TRUE : FALSE;

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
	curl_setopt($ch, CURLOPT_TIMEOUT, 20);
	if($ip) curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-FORWARDED-FOR:'.$ip, 'CLIENT-IP:'.$ip));//构造IP
	if($referer) curl_setopt($ch, CURLOPT_REFERER, $referer);//构造来路
	if($ssl){
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($ch, CURLOPT_CAINFO, $_SERVER['DOCUMENT_ROOT'].'/cacert.pem');
		//$cookie_jar = 'E:\wamp\virtualhosts\nh.com\shanbay.cookie';//存放COOKIE的文件
		if($cookie){
			$data = array (
				'username' => 'ssss',
				'password' => 'ssss',
				'token' => 'ssss',
			);
			curl_setopt ( $ch, CURLOPT_POSTFIELDS, $data);

			curl_setopt($ch, CURLOPT_COOKIEFILE,$cookie);//发送cookie文件
			curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);  //保存cookie信息
		}
	} else {
		if($cookie){
			curl_setopt($ch, CURLOPT_COOKIE, $cookie);
			/*curl_setopt($ch, CURLOPT_COOKIEFILE,'E:\wamp\virtualhosts\nh.com\ninghao.cookie');//发送cookie文件
			curl_setopt($ch, CURLOPT_COOKIEJAR, 'E:\wamp\virtualhosts\nh.com\ninghao.cookie');  //保存cookie信息*/
		}
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	}
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	$out = curl_exec($ch);
	curl_close($ch);
	if($out === false){
		//var_dump(curl_error($ch));  //查看报错信息
		return false;
	}
	return $out;
}

//json字符串转数组并合并到新数组
function json_arr_merge($main_arr, $slave){
	$json = json_decode($slave);
	$json = json_to_array($json);

	return array_merge($main_arr, $json);
}
//json对象转数组
function json_to_array($web){
	$arr=array();
	foreach($web as $k=>$w){
		if(is_object($w)) $arr[$k]=json_to_array($w);
		else $arr[$k]=$w;
	}
	return $arr;
}
?>