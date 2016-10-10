//提示音cookie-key
var cookies_voice_tips = 'voice_tips';
//提示音标志值
var voice_tips = get_cookie(cookies_voice_tips);
//提示音播放
function voice_tips_play(){
	var audio_play_tips = 'audio_play_tips_';
	var audio_play_tips_index = 0;
	if(voice_tips == 1) $('#' + audio_play_tips + audio_play_tips_index)[0].play();
}
//提示音加载
function voice_tips_load(){
	var html = '';
	var path = path_voice_tips;
	var voice = new Array('iphone-tips.mp3', 'win8-tips.mp3', 'win8-tips2.mp3');
	for(var i = 0; i < voice.length; i++){
		html += '<audio id="audio_play_tips_' + i + '"><source src="' + path + voice[i] + '" type="audio/mpeg"></audio>';
	}
	$(html).appendTo('body');
}
//提示音设置
function voice_tips_set(sym){
	if(voice_tips == null){
		set_cookie(cookies_voice_tips, 1, cookies_tmp_day);
		voice_tips = get_cookie(cookies_voice_tips);
	}
	var button = $($('[title=Voice_tips]').find('i:nth-child(2)'));
	if(sym){
		var vclass = (voice_tips == 1) ? 'fa fa-volume-off' : 'fa fa-volume-up';
		voice_tips = (voice_tips == 1) ? 0 : 1;
		set_cookie(cookies_voice_tips, voice_tips, cookies_tmp_day);
	} else {
		var vclass = (voice_tips == 1) ? 'fa fa-volume-up' : 'fa fa-volume-off';
	}
	button.attr('class', vclass);
}