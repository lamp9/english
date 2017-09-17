var audio_english = {
	cookies_voice_en_type: 'voice_en_type',//语音类型cookie-key
	cookies_voice_en: 'voice_en',//是否开启语音cookie-key
	cookies_voice_en_pause: 'voice_en_pause',//是否开启语音cookie-key(暂停时)
	cookies_time_en_play_second_switch: 'time_en_play_second_switch',//语音播放间隔开启cookie-key
	cookies_time_en_play_second: 'time_en_play_second',//语音播放间隔秒数cookie-key
	cookies_time_en_play_second_delay: 'time_en_play_second_delay',//语音播放开始时秒数推迟cookie-key
	play_id: '',//当前播放单词ID

	current_second: 0,//暂停时的秒数
	voice_en_url:'',//语音播放url

	init:function(){
		this.voice_en_type = get_cookie(this.cookies_voice_en_type);//语音类型：EN:1,US:2
		this.voice_en = get_cookie(this.cookies_voice_en);//是否开启语音
		this.voice_en_pause = get_cookie(this.cookies_voice_en_pause);//是否开启语音(暂停时)
		this.time_en_play_second_switch = get_cookie(this.cookies_time_en_play_second_switch);
		this.time_en_play_second = get_cookie(this.cookies_time_en_play_second);
		this.time_en_play_second_delay = get_cookie(this.cookies_time_en_play_second_delay);
	},

	voice_en_play://自动播放语音方法
		function (time_now, time_total, state) {
			var count = time_reset.count_init;
			var data_len = english_rand_word.data.length;
			if (data_len == 0) count = $('#div_word>.list-group').length;
			if (state == 'pause') {
				if (time_now == 0) return;
				if (this.time_en_play_second_switch == 1) {
					var second = this.time_en_play_second;
					var total_second = count * second;
					if (time_now > total_second + this.time_en_play_second_delay) return;
					time_now = time_now - this.time_en_play_second_delay;
					if(time_now <= 0) return;
				} else {
					var second = parseInt(time_total / count);
				}

				if (time_now % second == 0) var index = time_now / second;
				else return;
			} else {
				if (this.voice_en_pause != 1) return;
				this.current_second++;
				var total_second = (this.time_en_play_second_switch == 1) ? count * this.time_en_play_second : count * 2;
				var second = parseInt(total_second / count);
				if (this.current_second % second == 0) var index = this.current_second / second;
				else return;
				if (this.current_second >= total_second) this.current_second = 0;
			}
			if (index > count) return;

			try {
				var show_en = $('#div_word>.list-group:nth-child(' + index + ')>.list-group-item>h4>a').text();
				var play_id = '#audio_play_en_' + index + '_' + this.voice_en_type;
				this.play_id = play_id;
				var play_audio = $(play_id);
				var play_en = play_audio.attr('en');

				var scrollElement = $('#div_word>.list-group:nth-child(' + index + ')>.list-group-item');
				$('#div_word>.list-group>.list-group-item').css('background-color', '#fff');
				scrollElement.css('background-color', '#ccc');
				scroll_en_list.scroll(scrollElement);//是否滚动列表

				if(this.voice_en == 1){
					if(init.isPC) {
						//可行方法
						$(play_id)[0].play();
						//$('.voice[data=' + this.voice_en_type + '][index=' + index + ']').click();
						//this.play_word($('a[index=' + index + ']').text(), this.voice_en_type, index);
					} else {
						//可行方法
						this.play_word($('a[index=' + index + ']').text(), this.voice_en_type, index);
						//$('.voice[data=' + this.voice_en_type + '][index=' + index + ']').touch();//待定
					}
				}
			} catch (e) {}
		},

	voice_en_type_set://语音类型设置
		function (sym) {
			if (this.voice_en_type == null) {
				set_cookie(this.cookies_voice_en_type, 1, init.cookies_tmp_day);
				this.voice_en_type = get_cookie(this.cookies_voice_en_type);
			}
			var button = $('[title=Voice_en_type]');
			if (sym) {
				var vclass = (this.voice_en_type == 1) ? 'US' : 'EN';
				this.voice_en_type = (this.voice_en_type == 1) ? 2 : 1;
				set_cookie(this.cookies_voice_en_type, this.voice_en_type, init.cookies_tmp_day);
			} else {
				var vclass = (this.voice_en_type == 1) ? 'EN' : 'US';
			}
			button.text(vclass);
		},

	voice_en_set://语音播放开启设置
		function (sym) {
			if (this.voice_en == null) {
				set_cookie(this.cookies_voice_en, 1, init.cookies_tmp_day);
				this.voice_en = get_cookie(this.cookies_voice_en);
			}
			var button = $($('[title=Voice_en]').find('i:nth-child(1)'));
			if (sym) {
				var vclass = (this.voice_en == 1) ? 'fa fa-volume-off' : 'fa fa-volume-up';
				this.voice_en = (this.voice_en == 1) ? 0 : 1;
				set_cookie(this.cookies_voice_en, this.voice_en, init.cookies_tmp_day);
			} else {
				var vclass = (this.voice_en == 1) ? 'fa fa-volume-up' : 'fa fa-volume-off';
			}
			button.attr('class', vclass);
		},

	voice_en_pause_set://语音播放开启设置(暂停时)
		function (sym) {
			if (this.voice_en_pause == null) {
				set_cookie(this.cookies_voice_en_pause, 1, init.cookies_tmp_day);
				this.voice_en_pause = get_cookie(this.cookies_voice_en_pause);
			}
			var button = $($('[title=Voice_en_pause]').find('i:nth-child(1)'));
			if (sym) {
				var vclass = (this.voice_en_pause == 1) ? 'fa fa-volume-off' : 'fa fa-volume-up';
				this.voice_en_pause = (this.voice_en_pause == 1) ? 0 : 1;
				set_cookie(this.cookies_voice_en_pause, this.voice_en_pause, init.cookies_tmp_day);
			} else {
				var vclass = (this.voice_en_pause == 1) ? 'fa fa-volume-up' : 'fa fa-volume-off';
			}
			button.attr('class', vclass);
		},


	voice_en_load://加载语音
		function () {
			var div_voice_en = $('#voice_en_load');
			div_voice_en.empty();
			var div_en = $('#div_word>.list-group>.list-group-item>h4>a');
			var html = '';
			div_en.each(function (index) {
				var obj = $(this);
				var otherType = (1 == audio_english.voice_en_type) ? 2 : 1;
				var idPre = 'audio_play_en_' + (index + 1) + '_';

				var url = urlLoad = common.sprintf(audio_english.voice_en_url, obj.text(), audio_english.voice_en_type);
				if (audio_english.voice_en != 1) url = '';
				var urlOther = common.sprintf(audio_english.voice_en_url, obj.text(), otherType);
				html += '<audio id="' + idPre + audio_english.voice_en_type + '" en="' + obj.text() + '"><source src="' + url + '" srcLoad="' + urlLoad + '" type="audio/mpeg"></audio>';
				html += '<audio id="' + idPre + otherType + '" en="' + obj.text() + '"><source src="" srcLoad="' + urlOther + '" type="audio/mpeg"></audio>';
			});
			div_voice_en.html(html);
		},

	voice_en_load_for_chapter://加载语音
		function () {
			var div_voice_en = $('#voice_en_load');
			div_voice_en.empty();
			var div_en = book_chapter_obj.obj.find('tr>td>a[target=_blank]');
			var html = '';
			div_en.each(function (index) {
				var obj = $(this);
				var idPre = 'audio_play_en_' + index + '_';

				var url = common.sprintf(audio_english.voice_en_url, obj.text(), 1);
				var url2 = common.sprintf(audio_english.voice_en_url, obj.text(), 2);

				html += '<audio id="' + idPre + '1" en="' + obj.text() + '"><source src="" srcLoad="' + url + '" type="audio/mpeg"></audio>';
				html += '<audio id="' + idPre + '2" en="' + obj.text() + '"><source src="" srcLoad="' + url2 + '" type="audio/mpeg"></audio>';
			});
			div_voice_en.html(html);
		},

	play_word_set://加载语音后的html元素重新定义
		function () {
			$('.list-group span').hover(function () {
				$(this).css('color', '#ccc');
			}, function () {
				$(this).css('color', '#000');
			});
		},

	play_word://点击单词音标时触发播放
		function (type, index) {
			var id = '#audio_play_en_' + index + '_' + type;
			var obj = $(id);
			var objSrc = $(id + '> source');

			if('' == objSrc.attr('src')){
				var src = objSrc.attr('srcLoad');
				obj.empty();
				obj.append('<source src="' + src + '" type="audio/mpeg">');
			}
			try{
				obj[0].play();
			} catch (e) {}

			window.event.stopPropagation();
		},
	play_word_current://播放当前单词
		function(){
			if('' == this.play_id) return;
			var obj = $(this.play_id);
			var objSrc = $(this.play_id + '> source');

			if('' == objSrc.attr('src')){
				var src = objSrc.attr('srcLoad');
				obj.empty();
				obj.append('<source src="' + src + '" type="audio/mpeg">');
			}
			try{
				obj[0].play();
			} catch (e) {}
		},

	play_word_common://常规通用播放
		function(en, type, index){
			console.info(333);
			var div_voice_en = $('#voice_en_load');
			var id = 'audio_play_en_' + index + '_' + type;

			var url = common.sprintf(audio_english.voice_en_url, en, type);
			var html = '<audio id="' + id + '" en="' + en + '"><source src="' + url + '" type="audio/mpeg"></audio>';
			div_voice_en.append(html);

			try {
				var obj = $('#' + id);
				if (obj.length > 0) obj[0].play();
			} catch (e) {}
		},


	//语音播放间隔
	time_en_play_second_set://语音播放间隔设置
		function (sym) {
			var checkbox = $('[name=' + this.cookies_time_en_play_second_switch + ']');
			var val = $('[name=' + this.cookies_time_en_play_second + ']');
			var val_delay = $('[name=' + this.cookies_time_en_play_second_delay + ']');
			if (sym) {
				set_cookie(this.cookies_time_en_play_second_switch, (checkbox.attr("checked")) ? 1 : 0, init.cookies_tmp_day);
				this.time_en_play_second_switch = get_cookie(this.cookies_time_en_play_second_switch);

				this.time_en_play_second = val.val();
				set_cookie(this.cookies_time_en_play_second, this.time_en_play_second, init.cookies_tmp_day);

				this.time_en_play_second_delay = val_delay.val();
				set_cookie(this.cookies_time_en_play_second_delay, this.time_en_play_second_delay, init.cookies_tmp_day);

				init.show_set_tips();
			} else {
				if (this.time_en_play_second_switch == null) {
					set_cookie(this.cookies_time_en_play_second_switch, 1, init.cookies_tmp_day);
					this.time_en_play_second_switch = get_cookie(this.cookies_time_en_play_second_switch);
				}

				if (this.time_en_play_second_switch == 1) checkbox.attr("checked", true);
				else checkbox.attr("checked", false);

				if (this.time_en_play_second == null) {
					set_cookie(this.cookies_time_en_play_second, 2, init.cookies_tmp_day);
					this.time_en_play_second = get_cookie(this.cookies_time_en_play_second);
				}
				val.val(this.time_en_play_second);

				if (this.time_en_play_second_delay == null) {
					set_cookie(this.cookies_time_en_play_second_delay, 2, init.cookies_tmp_day);
					this.time_en_play_second_delay = get_cookie(this.cookies_time_en_play_second_delay);
				}
				val_delay.val(this.time_en_play_second_delay);
			}
		},
};