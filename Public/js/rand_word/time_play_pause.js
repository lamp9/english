//单词播放开始/暂停
function time_play_pause(){
	var obj = $('#play');
	var state = obj.attr('state');
	var i = $(obj.find('i'));
	if(state == 'play'){
		obj.attr('state', 'pause');
		i.attr('class', 'fa fa-pause');
	} else {
		obj.attr('state', 'play');
		i.attr('class', 'fa fa-play');
	}
}