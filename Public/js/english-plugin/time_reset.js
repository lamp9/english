var time_reset = {
	cookies_count: 'count_init',//播放单词每组数量cookie-key
	cookies_time: 'time_set_tmp',//播放每组单词的秒数

	init:function(){
		this.count_init = get_cookie(this.cookies_count);//播放单词每组数量
		this.time_set_tmp = get_cookie(this.cookies_time);//播放单词每组秒数
	},

	time_reset://播放单词每组数量/秒数设置
		function (sym) {
			if (sym) {
				this.count_init = $('input[name=count]').val();
				this.time_set_tmp = $('input[name=time]').val();

				set_cookie(this.cookies_count, this.count_init, init.cookies_tmp_day);
				set_cookie(this.cookies_time, this.time_set_tmp, init.cookies_tmp_day);

				init.show_set_tips();
			} else {
				if (this.count_init == null) {
					set_cookie(this.cookies_count, 7, init.cookies_tmp_day);
					this.count_init = get_cookie(this.cookies_count);
				}
				if (this.time_set_tmp == null) {
					set_cookie(this.cookies_time, 35, init.cookies_tmp_day);
					this.time_set_tmp = get_cookie(this.cookies_time);
				}
				$('input[name=count]').val(this.count_init);
				$('input[name=time]').val(this.time_set_tmp);
			}
		},
};







