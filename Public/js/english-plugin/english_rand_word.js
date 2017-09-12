var english_rand_word = {
	//单词数据文件
	data_url:'',
	data_position:'',
	data: '',//数据初始存放变量
	data_tmp: new Array(),//临时数据存放变量
	en_search_from:'',//单词官网url

	rand_word://随机抽取单词
		function () {
			var count = time_reset.count_init;
			var html = '';
			var data_len = this.data.length;
			if (count >= data_len) count = data_len;
			var clickType = (init.isPC) ? 'onclick' : 'ontouchstart';
			for (var i = 0; i < count; i++) {
				var index = i + 1;
				var obj = this.data_tmp[this.data_tmp.length] = this.data[i];
				delete this.data[i];

				var en_symbol = obj.symbol.split('$$');
				html += '<div class="list-group">';
				html += '<div href="#" class="list-group-item">';
				html += '<h4 class="list-group-item-heading">';

				html += '<a href="' + common.sprintf(this.en_search_from, obj.en) + '" target="_blank" class=en index=' + (i + 1) + '>' + obj.en + '</a>&nbsp;&nbsp;';
				html += '<span class=voice ' + clickType + '="audio_english.play_word(1, ' + index + ');" data=1 index=' + index + '>' + en_symbol[0] + '</span>&nbsp;&nbsp;';
				html += '<span class=voice ' + clickType + '="audio_english.play_word(2, ' + index + ');" data=2 index=' + index + '>' + en_symbol[1] + '</span>';

				html += '</h4>';
				html += '<p class="list-group-item-text">' + obj.cn + '</p></div></div>';
			}
			init.div_word.html(html);
			common.ArrEmptyDelete(this.data);

			audio_english.play_word_set();
		},

	data_init://数据初始化
		function () {
			var dataReturn = [];
			var idList = this.data_position;
			idList = idList.split(',');
			for(var i = 0; i < idList.length; i++) {
				$.ajax({
					url: this.data_url + idList[i] + '.json',
					type: 'GET',
					data: {},
					dataType: 'json',
					timeout: 25000,//1000毫秒后超时
					cache: false,//不缓存数据
					async: false,//同步：false,异步：true,默认true
					success: function (data) {
						//合并数据
						dataReturn = dataReturn.concat(data);
					},
				});
			}
			common.GetRandomArr(dataReturn);
			english_rand_word.data = dataReturn;
			setTimeout('$("#en_load_animation").css("display", "none");', 1000);
		},
};








