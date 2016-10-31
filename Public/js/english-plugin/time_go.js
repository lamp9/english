var time_go = {
	time_set: 0,//临时时间值

	time_go://程序执行核心，时间控制器
		function () {
			var state = $('#play').attr('state');
			if (state == 'pause') {
				if (this.time_set == 1 || this.time_set == 2) {
					light_tips.light_tips_show();
					if (this.time_set == 1) audio_voice_tips.voice_tips_play();
				}

				if (this.time_set == 0) {
					this.data_reset();
				} else if (this.time_set >= time_reset.time_set_tmp) {
					this.time_set = 0;
					this.data_reset();
				}
				this.time_set++;
			}
			audio_english.voice_en_play(this.time_set, time_reset.time_set_tmp, state);

			this.time_tips_show();
			setTimeout('time_go.time_go();', 1000);
		},

	data_reset://下一组随机单词
		function () {
			if (english_rand_word.data.length == 0) {
				english_rand_word.data = english_rand_word.data_tmp;
				common.GetRandomArr(english_rand_word.data);
				english_rand_word.data_tmp = [];
			}
			english_rand_word.rand_word();
			window_onresize();
			audio_english.voice_en_load();
		},

	time_tips_show://时间信息显示
		function () {
			var total = english_rand_word.data.length + english_rand_word.data_tmp.length;
			var group_total = parseInt(total / time_reset.count_init);
			var group_current = parseInt(english_rand_word.data_tmp.length / time_reset.count_init);
			if (total % time_reset.count_init != 0) group_total++;
			if (english_rand_word.data_tmp.length % time_reset.count_init != 0) group_current++;

			var total_time = group_total * time_reset.time_set_tmp;
			var now_time = group_current * time_reset.time_set_tmp + this.time_set - time_reset.time_set_tmp;
			var over_time = common.show_time(total_time - now_time);

			$('#time_process').text(
				this.time_set + ' / ' + time_reset.time_set_tmp + '秒(' + over_time + ') - ' +
				group_current + ' / ' + group_total + '轮(' +
				english_rand_word.data_tmp.length + ' / ' + total + '单词)');
		},

	time_reload://切换下一组随机单词
		function () {
			var state = $('#play').attr('state');
			if (state == 'pause') this.time_set = 0;
			else {
				this.time_set = 1;
				this.data_reset();
				audio_voice_tips.voice_tips_play();
			}
		},
};










