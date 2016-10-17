//数据初始存放变量
var data = '';
//临时数据存放变量
var data_tmp = new Array();
//随机抽取单词
function rand_word(){
	var count = count_init;
	var html = '';
	var data_len = data.length;
	if(count >= data_len) count = data_len;
	var arr_index = new Array();
	var arr_word = new Array();
	for(var i = 0; i < count; i++){
		var position = 0;
		while(1){
			var sym = true;
			position = GetRandomNum(1, data_len) - 1;
			for(var j = 0; j < arr_index.length; j++){
				if(position == arr_index[j]) sym = false;
			}
			if(sym) break;
		}
		arr_index[i] = position;
		arr_word[i] = data[arr_index[i]];
		data_tmp[data_tmp.length] = data[arr_index[i]];
		delete data[arr_index[i]];
		var obj = arr_word[i];

		var en_symbol = obj.symbol.split('$$');
		html += '<div class="list-group">';
		html += '<div href="#" class="list-group-item">';
		html += '<h4 class="list-group-item-heading">';

		html += '<a href="' + sprintf(en_search_from, obj.en) + '" target="_blank" class=en index=' + (i + 1) + '>' + obj.en + '</a>&nbsp;&nbsp;';
		html += '<span class=voice data=1>' + en_symbol[0] + '</span>&nbsp;&nbsp;';
		html += '<span class=voice data=2>' + en_symbol[1] + '</span>';
		html += '</h4>';
		html += '<p class="list-group-item-text">' + obj.cn + '</p></div></div>';
	}
	div_word.html(html);
	var del_count = 0;
	while(del_count < count){
		for(var i = 0; i < data.length; i++){
			if(!data[i]){
				data.splice(i, 1);
				del_count++;
			}
		}
	}
	play_word_set();
}

//数据初始化
function data_init(){
	$.ajax({
		url: data_url,
		type: 'POST',
		data: {
			data_position	: data_position
		},
		dataType: 'json',
		timeout: 25000,//1000毫秒后超时
		cache: false,//不缓存数据
		async: true,//同步：false,异步：true,默认true
		success: function(data_return){
			data = data_return;
			setTimeout('$("#en_load_animation").css("display", "none");', 3000);
		},
	});
}