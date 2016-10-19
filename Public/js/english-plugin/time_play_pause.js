var time_play_pause = {
	time_play_pause://单词播放开始/暂停
		function () {
			var obj = $('#play');
			var state = obj.attr('state');
			var i = $(obj.find('i'));
			if (state == 'play') {
				obj.attr('state', 'pause');
				i.attr('class', 'fa fa-pause');
			} else {
				obj.attr('state', 'play');
				i.attr('class', 'fa fa-play');
			}
		},
};

