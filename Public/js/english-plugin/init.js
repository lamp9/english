var init = {
	cookies_tmp_day:30,//所有cookie值有效期时间，单位天
	div_word:$('#div_word'),//放置单词的容器
	isPC:'',
	init:function(){
		this.isPC = common.IsPC;
		this.show_set_tips_init();
		english_rand_word.data_init();

		audio_voice_tips.init();
		audio_voice_tips.voice_tips_load();
		audio_voice_tips.voice_tips_set(false);

		audio_english.init();
		audio_english.voice_en_type_set(false);
		audio_english.voice_en_set(false);
		audio_english.voice_en_pause_set(false);

		audio_english.time_en_play_second_set(false);

		light_tips.init();
		light_tips.light_tips_set(false);

		time_reset.init();
		time_reset.time_reset(false);

		time_go.time_go();
	},
	show_set_tips_init:function(){
		var left_position = (document.body.clientWidth / 2) - 28;
		var top_position = 100;
		$('#show_ok').css('left', left_position).css('top', top_position);
	},
	show_set_tips:function (){
		var obj = $('#show_ok');
		obj.show('normal');
		setTimeout("$('#show_ok').hide('slow');", 1000);
	},
};