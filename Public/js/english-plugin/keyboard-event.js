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
		if(code == 82) $('[title=Voice_en_pause]').click();//R
		if(code == 76) $('[title=Light_tips]').click();//L
		console.log(code);
	}
}