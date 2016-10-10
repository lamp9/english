//仿PHP sprintf函数
function sprintf(){
	var arg = arguments, str = arg[0] || '', i, n;
	for (i = 1, n = arg.length; i < n; i++) {
		str = str.replace(/%s/, arg[i]);
	}
	return str;
}

//获取最大与最小之间的随机数
function GetRandomNum(Min,Max){
	var Range = Max - Min;
	var Rand = Math.random();
	return (Min + Math.round(Rand * Range));
}

//时间格式换算
function show_time(time){
	//time = parseInt(time / 1000);
	var second = time % 60;
	if(second.toString().length == 1) second = '0' + second;

	var minute = time / 60;
	minute = parseInt(minute % 60);
	if(minute.toString().length == 1) minute = '0' + minute;

	var hour = parseInt(time / 3600);
	if(hour.toString().length == 1) hour = '0' + hour;

	return hour + ":" + minute + ":" + second;
}

//键盘事件
window.document.onkeydown = disableRefresh;
function disableRefresh(evt){
	var evt = (evt) ? evt : window.event;
	var code = evt.keyCode;
	if (code) {
		if(code == 13) $('#play').click();//Enter
		if(code == 32) $('[title=Next]').click();//空格
		if(code == 86) $('[title=Voice_tips]').click();//V
		if(code == 84) $('[title=Voice_en_type]').click();//T
		if(code == 69) $('[title=Voice_en]').click();//E
		if(code == 76) $('[title=Light_tips]').click();//L
		console.log(code);
	}
}