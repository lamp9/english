var light_tips = {
	cookies_light_tips: 'light_tips',//提示光cookie-key

	init:function(){
		this.light_tips = get_cookie(this.cookies_light_tips);//提示光标志值
	},

	light_tips_show://提示光活动
		function () {
			if (this.light_tips == 1) {
				$("body").animate({backgroundColor: "#cccccc",}, 600);
				$("body").animate({backgroundColor: "#303030",}, 400);
			}
		},

	light_tips_set://提示光设置
		function (sym) {
			if (this.light_tips == null) {
				set_cookie(this.cookies_light_tips, 1, init.cookies_tmp_day);
				this.light_tips = get_cookie(this.cookies_light_tips);
			}
			var button = $($('[title=Light_tips]').find('i:nth-child(2)'));
			//<i class="fa fa-toggle-on"></i><i class="fa fa-toggle-off"></i>
			if (sym) {
				var vclass = (this.light_tips == 1) ? 'fa fa-toggle-off' : 'fa fa-toggle-on';
				this.light_tips = (this.light_tips == 1) ? 0 : 1;
				set_cookie(this.cookies_light_tips, this.light_tips, init.cookies_tmp_day);
			} else {
				var vclass = (this.light_tips == 1) ? 'fa fa-toggle-on' : 'fa fa-toggle-off';
			}
			button.attr('class', vclass);
		},
};






