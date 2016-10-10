//播放单词每组数量cookie-key
var cookies_count = 'count_init';
//播放每组单词的秒数
var cookies_time = 'time_set_tmp';

//播放单词每组数量
var count_init = get_cookie(cookies_count);
//播放单词每组秒数
var time_set_tmp = get_cookie(cookies_time);
//播放单词每组数量/秒数设置
function time_reset(sym){
	if(sym){
		count_init = $('input[name=count]').val();
		time_set_tmp = $('input[name=time]').val();

		set_cookie(cookies_count, count_init, cookies_tmp_day);
		set_cookie(cookies_time, time_set_tmp, cookies_tmp_day);

		show_set_tips();
	} else {
		if(count_init == null){
			set_cookie(cookies_count, 7, cookies_tmp_day);
			count_init = get_cookie(cookies_count);
		}
		if(time_set_tmp == null){
			set_cookie(cookies_time, 35, cookies_tmp_day);
			time_set_tmp = get_cookie(cookies_time);
		}
		$('input[name=count]').val(count_init);
		$('input[name=time]').val(time_set_tmp);
	}
}