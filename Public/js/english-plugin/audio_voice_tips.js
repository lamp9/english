var audio_voice_tips = {
	cookies_voice_tips: 'voice_tips',//提示音cookie-key
	path_voice_tips:'',//提示音播放路径

	init:function(){
		this.voice_tips = get_cookie(this.cookies_voice_tips);//提示音标志值
	},

	voice_tips_play://提示音播放
		function () {
			var audio_play_tips = 'audio_play_tips_';
			var audio_play_tips_index = 0;
			if (this.voice_tips == 1) $('#' + audio_play_tips + audio_play_tips_index)[0].play();
		},

	voice_tips_load://提示音加载
		function () {
			var html = '';
			var path = this.path_voice_tips;
			var voice = new Array('iphone-tips.mp3', 'win8-tips.mp3', 'win8-tips2.mp3');
			for (var i = 0; i < voice.length; i++) {
				html += '<audio id="audio_play_tips_' + i + '"><source src="' + path + voice[i] + '" type="audio/mpeg"></audio>';
			}
			$(html).appendTo('body');
		},

	voice_tips_set://提示音设置
		function (sym) {
			if (this.voice_tips == null) {
				set_cookie(this.cookies_voice_tips, 1, init.cookies_tmp_day);
				this.voice_tips = get_cookie(this.cookies_voice_tips);
			}
			var button = $($('[title=Voice_tips]').find('i:nth-child(2)'));
			if (sym) {
				var vclass = (this.voice_tips == 1) ? 'fa fa-volume-off' : 'fa fa-volume-up';
				this.voice_tips = (this.voice_tips == 1) ? 0 : 1;
				set_cookie(this.cookies_voice_tips, this.voice_tips, init.cookies_tmp_day);
			} else {
				var vclass = (this.voice_tips == 1) ? 'fa fa-volume-up' : 'fa fa-volume-off';
			}
			button.attr('class', vclass);
		},
};