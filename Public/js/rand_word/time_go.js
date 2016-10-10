//下一组随机单词
function data_reset(){
	if(data.length == 0){
		data = data_tmp;
		data_tmp = new Array();
	}
	rand_word();
	window_onresize();
	voice_en_load();
}

//临时时间值
var time_set = 0;
//程序执行核心，时间控制器
function time_go(){
	var state = $('#play').attr('state');
	if(state == 'pause'){
		if(time_set == 1 || time_set == 2){
			light_tips_show();
			if(time_set == 1) voice_tips_play();
		}

		if(time_set == 0){
			data_reset();
		} else if(time_set >= time_set_tmp){
			time_set = 0;
			data_reset();
		}
		time_set++;
	}
	voice_en_play(time_set, time_set_tmp, state);

	time_tips_show();
	setTimeout('time_go();', 1000);
}

function time_tips_show(){
	var total = data.length + data_tmp.length;
	var group_total = parseInt(total / count_init);
	var group_current = parseInt(data_tmp.length / count_init);
	if(total % count_init != 0) group_total++;
	if(data_tmp.length % count_init != 0) group_current++;

	var total_time = group_total * time_set_tmp;
	var now_time = group_current * time_set_tmp + time_set - time_set_tmp;
	var over_time = show_time(total_time - now_time);

	$('#time_process').text(
		time_set + ' / ' + time_set_tmp + '秒(' + over_time + ') - ' +
		group_current + ' / ' + group_total + '轮(' +
		data_tmp.length + ' / ' + total + '单词)');
}

//切换下一组随机单词
function time_reload(){
	var state = $('#play').attr('state');
	if(state == 'pause') time_set = 0;
	else {
		time_set = 1;
		data_reset();
		voice_tips_play();
	}
}