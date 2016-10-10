//提示光cookie-key
var cookies_light_tips = 'light_tips';
//提示光标志值
var light_tips = get_cookie(cookies_light_tips);
//提示光活动
function light_tips_show(){
	if(light_tips == 1){
		$("body").animate({backgroundColor:"#28ff28",}, 600);
		$("body").animate({backgroundColor:"#303030",},400);
	}
}
//提示光设置
function light_tips_set(sym){
	if(light_tips == null){
		set_cookie(cookies_light_tips, 1, cookies_tmp_day);
		light_tips = get_cookie(cookies_light_tips);
	}
	var button = $($('[title=Light_tips]').find('i:nth-child(2)'));
	//<i class="fa fa-toggle-on"></i><i class="fa fa-toggle-off"></i>
	if(sym){
		var vclass = (light_tips == 1) ? 'fa fa-toggle-off' : 'fa fa-toggle-on';
		light_tips = (light_tips == 1) ? 0 : 1;
		set_cookie(cookies_light_tips, light_tips, cookies_tmp_day);
	} else {
		var vclass = (light_tips == 1) ? 'fa fa-toggle-on' : 'fa fa-toggle-off';
	}
	button.attr('class', vclass);
}