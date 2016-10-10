//语音类型cookie-key
var cookies_voice_en_type = 'voice_en_type';
//是否开启语音cookie-key
var cookies_voice_en = 'voice_en';

//暂停时的秒数
var current_second = 0;
//自动播放语音方法
function voice_en_play(time_now, time_total, state){
	if(voice_en == 1){
		var count = count_init;
		var data_len = data.length;
		if(data_len == 0) count = $('#div_word>.list-group').length;
		if(state == 'pause'){
			if(time_now == 0) return;
			if(time_en_play_second_switch == 1){
				var second = time_en_play_second;
				var total_second = count * second;
				if(time_now > total_second + time_en_play_second_delay) return;
				time_now = time_now - time_en_play_second_delay;
				if(time_now == 0) return;
			} else {
				var second = parseInt(time_total / count);
			}

			if(time_now % second == 0) var index = time_now / second;
			else return;
		} else {
			current_second++;
			var total_second = (time_en_play_second_switch == 1) ? count * time_en_play_second : count * 2;
			var second = parseInt(total_second / count);
			if(current_second % second == 0) var index = current_second / second;
			else return;
			if(current_second >= total_second) current_second = 0;
		}
		if (index > count) return;
		var show_en = $('#div_word>.list-group:nth-child(' + index + ')>.list-group-item>h4>a').text();
		var play_audio = $('#audio_play_en_' + index);
		var play_en = play_audio.attr('en');
		if(!play_audio.length > 0 || play_en != show_en){
			voice_en_load();
			return;
		}
		$('#audio_play_en_' + index)[0].play();
		$('#div_word>.list-group>.list-group-item').css('background-color', '#fff');
		$('#div_word>.list-group:nth-child(' + index + ')>.list-group-item').css('background-color', '#ccc');
	}
}

//语音类型：EN:1,US:2
var voice_en_type = get_cookie(cookies_voice_en_type);
//语音类型设置
function voice_en_type_set(sym){
	if(voice_en_type == null){
		set_cookie(cookies_voice_en_type, 1, cookies_tmp_day);
		voice_en_type = get_cookie(cookies_voice_en_type);
	}
	var button = $('[title=Voice_en_type]');
	if(sym){
		var vclass = (voice_en_type == 1) ? 'US' : 'EN';
		voice_en_type = (voice_en_type == 1) ? 2 : 1;
		set_cookie(cookies_voice_en_type, voice_en_type, cookies_tmp_day);
	} else {
		var vclass = (voice_en_type == 1) ? 'EN' : 'US';
	}
	button.text(vclass);
}

//是否开启语音
var voice_en = get_cookie(cookies_voice_en);
//语音播放开启设置
function voice_en_set(sym){
	if(voice_en == null){
		set_cookie(cookies_voice_en, 1, cookies_tmp_day);
		voice_en = get_cookie(cookies_voice_en);
	}
	var button = $($('[title=Voice_en]').find('i:nth-child(1)'));
	if(sym){
		var vclass = (voice_en == 1) ? 'fa fa-volume-off' : 'fa fa-volume-up';
		voice_en = (voice_en == 1) ? 0 : 1;
		set_cookie(cookies_voice_en, voice_en, cookies_tmp_day);
	} else {
		var vclass = (voice_en == 1) ? 'fa fa-volume-up' : 'fa fa-volume-off';
	}
	button.attr('class', vclass);
}

//加载语音
function voice_en_load(){
	if(voice_en == 1){
		var div_voice_en = $('#voice_en_load');
		var div_en = $('#div_word>.list-group>.list-group-item>h4>a');
		var html = ''
		div_en.each(function(index){
			var obj = $(this);
			var url = sprintf(voice_en_url, obj.text(), voice_en_type);
			html += '<audio id="audio_play_en_' + (index + 1) + '" en="' + obj.text() + '"><source src="' + url + '" type="audio/mpeg"></audio>';
		});
		div_voice_en.html(html);
		$('#voice_en_load_tmp').empty();
	}
}

//加载语音后的html元素重新定义
function play_word_set(){
	$('.voice').click(function(){
		var en = $(this).parent().find('.en');
		var en_obj = $(en);
		var index = en_obj.attr('index');
		var type = $(this).attr('data');
		play_word(en_obj.text(), type, index);
	});
	$('.list-group span').hover(function(){
		$(this).css('color', '#ccc');
	},function(){
		$(this).css('color', '#000');
	});
}

//点击时播放
function play_word(word, type, index){
	var sym = true;
	if(type == voice_en_type){
		var obj = $('#audio_play_en_' + index);
		if(obj.length > 0){

			sym = false;
		}
	}
	if(sym){
		var id = 'audio_play_' + type + '_' + index;
		var obj = $('#' + id);
		if(obj.length > 0){
		} else {
			var url = sprintf(voice_en_url, word, type);
			var html = '<audio id="' + id + '"><source src="' + url + '" type="audio/mpeg"></audio>';
			$('#voice_en_load_tmp').append(html);
			var obj = $('#' + id);
		}
	}
	obj[0].play();
}



//语音播放间隔

//语音播放间隔开启cookie-key
var cookies_time_en_play_second_switch = 'time_en_play_second_switch';
var time_en_play_second_switch = get_cookie(cookies_time_en_play_second_switch);
//语音播放间隔秒数cookie-key
var cookies_time_en_play_second = 'time_en_play_second';
var time_en_play_second = get_cookie(cookies_time_en_play_second);
//语音播放开始时秒数推迟cookie-key
var cookies_time_en_play_second_delay = 'time_en_play_second_delay';
var time_en_play_second_delay = get_cookie(cookies_time_en_play_second_delay);


//语音播放间隔设置
function time_en_play_second_set(sym){
	var checkbox = $('[name=' + cookies_time_en_play_second_switch + ']');
	var val = $('[name=' + cookies_time_en_play_second + ']');
	var val_delay = $('[name=' + cookies_time_en_play_second_delay + ']');
	if(sym){
		set_cookie(cookies_time_en_play_second_switch, (checkbox.attr("checked")) ? 1 : 0, cookies_tmp_day);
		time_en_play_second_switch = get_cookie(cookies_time_en_play_second_switch);

		time_en_play_second = val.val();
		set_cookie(cookies_time_en_play_second, time_en_play_second, cookies_tmp_day);

		time_en_play_second_delay = val_delay.val();
		set_cookie(cookies_time_en_play_second_delay, time_en_play_second_delay, cookies_tmp_day);

		show_set_tips();
	} else {
		if(time_en_play_second_switch == null){
			set_cookie(cookies_time_en_play_second_switch, 1, cookies_tmp_day);
			time_en_play_second_switch = get_cookie(cookies_time_en_play_second_switch);
		}

		if(time_en_play_second_switch == 1) checkbox.attr("checked", true);
		else checkbox.attr("checked", false);

		if(time_en_play_second == null){
			set_cookie(cookies_time_en_play_second, 2, cookies_tmp_day);
			time_en_play_second = get_cookie(cookies_time_en_play_second);
		}
		val.val(time_en_play_second);

		if(time_en_play_second_delay == null){
			set_cookie(cookies_time_en_play_second_delay, 2, cookies_tmp_day);
			time_en_play_second_delay = get_cookie(cookies_time_en_play_second_delay);
		}
		val_delay.val(time_en_play_second_delay);
	}
}