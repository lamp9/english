<?php
function get_db_config($from){
	switch($from){
		//本地
		case 'local' :
			$data['user']	= 'root';
			$data['pwd']	= 'root';
			$data['db']		= 'english';
			break;
		
		//服务器
		case 'server' :
			$data['user']	= 'user';
			$data['pwd']	= 'pwd';
			$data['db']		= 'db';
			break;
	}
	return $data;
}
$data = get_db_config('local');
//$data = get_db_config('server');

return array(
	'DB_DSN'=>"mysql://{$data['user']}:{$data['pwd']}@localhost:3306/{$data['db']}",
	'DB_PREFIX'=>'',
	'TMPL_L_DELIM'=>'<{',
	'TMPL_R_DELIM'=>'}>',
	'SHOW_PAGE_TRACE'=>false,//开启页面Trace
	'VAR_FILTERS'=>'stripslashes,strip_tags',
	'TMPL_STRIP_SPACE'=>false,
	/*'SESSION_OPTIONS'=> array(
		'expire'=>'1800'
	),*/
	'URL_PATHINFO_DEPR'=>'-',
	'URL_HTML_SUFFIX' => 'html',
	//'URL_CASE_INSENSITIVE' =>true,


	'LOG_RECORD'			=>  false,   // 默认不记录日志
	'LOG_TYPE'				=>  'File', // 日志记录类型 默认为文件方式
	'LOG_LEVEL'				=>  'EMERG,ALERT,CRIT,ERR',// 允许记录的日志级别
	'LOG_EXCEPTION_RECORD'	=>  false,    // 是否记录异常信息日志


	'server_config'	=> array(
		'search_word_from_url'			=> 'http://dict.youdao.com/search?q=%s',
		'search_word_voice_from_url'	=> 'http://dict.youdao.com/dictvoice?audio=%s&type=%s',
		'english_chapter_json'			=> './Public/en_chapter/',
		'english_chapter_test' => [
			'select_count' => 10,
		],
	),
);
?>