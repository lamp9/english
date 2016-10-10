//所有cookie值有效期时间，单位天
var cookies_tmp_day = 30;
//放置单词的容器
var div_word = $('#div_word');
$(function(){
	data_init();
	voice_tips_load();
	voice_tips_set(false);

	voice_en_type_set(false);
	voice_en_set(false);

	time_en_play_second_set(false);

	light_tips_set(false);

	time_reset(false);

	time_go();
});