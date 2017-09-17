var scroll_en_list = {
	cookies_is_scroll: 'Scroll_en_list',//是否开启滚动英语列表key
	is_scroll: '',

	init: function(){
		this.is_scroll = get_cookie(this.cookies_is_scroll);
	},

	set: function (sym) {
		if (this.is_scroll == null) {
			set_cookie(this.cookies_is_scroll, 1, init.cookies_tmp_day);
			this.is_scroll = get_cookie(this.cookies_is_scroll);
		}
		var button = $($('[title=' + this.cookies_is_scroll +  ']').find('i:nth-child(1)'));
		if (sym) {
			var vclass = (this.is_scroll == 1) ? 'fa fa-toggle-off' : 'fa fa-toggle-on';
			this.is_scroll = (this.is_scroll == 1) ? 0 : 1;
			set_cookie(this.cookies_is_scroll, this.is_scroll, init.cookies_tmp_day);
		} else {
			var vclass = (this.is_scroll == 1) ? 'fa fa-toggle-on' : 'fa fa-toggle-off';
		}
		button.attr('class', vclass);
	},

	scroll:function(scrollElement){
		if(1 != this.is_scroll) return;
		var elementTop = scrollElement.offset().top - $(window).scrollTop();//播放元素离显示区域顶端的高度
		var winHeight = $(window).height();
		var positionPer = elementTop / winHeight;
		if(positionPer > 0.7 || positionPer < 0){
			$('html, body').animate({
				scrollTop: scrollElement.offset().top
			}, 650);
		}
	},
};