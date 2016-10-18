var book_chapter_obj = {
	obj : '',
	english_list : '',
	count : 0,
	pagesize : 0,
	pagetotal : 0,
	init : function(obj){
		this.obj = $('#english_list');
		this.english_list = obj;
		this.count = this.english_list.length;
		this.pagesize = 20;
		this.pagetotal = parseInt(this.count / this.pagesize);
		if(this.count % this.pagesize > 0) this.pagetotal++;

		this.show_page(1);
	},
	show_page : function(page){
		this.showPagination(page);
		this.showPageList(page);
		this.play_word_set_init();
	},
	showPagination : function(page){
		var html = '<li><a href="#"><font color="#009900"><b>' + this.count + '</b></font> 词 </a></li>';
		for(var i = 1; i <= this.pagetotal; i++){
			if(page == i) html += '<li class=active>';
			else html += '<li>';
			html += '<a href="javascript:book_chapter_obj.show_page(' + i + ');">';
			html += i;
			html += '</a></li>';
		}
		$('#paginationList').html(html);
	},
	showPageList : function(page){
		var max = page * this.pagesize;
		var min = max - this.pagesize;

		if(min < 0) min = 0;
		var page_list = (max >= this.count) ? this.english_list.slice(min) : this.english_list.slice(min, max);

		var html = '';
		for(var i = 0; i < page_list.length; i++){
			var en = page_list[i];

			var style = (en.score == 'F' || en.score == null) ? 'style="color:#f00;"' : '';

			html += '<tr ' + style + '><td>' + (i + 1) + '</td>';
			html += '<td><a href="' + sprintf(en_search_from, en.en) + '" target=_blank>' + en.en + '</a></td>';
			var symbol = en.symbol.split('$$');

			html += '<td><a href="javascript:;" class=voice index=' + i + ' data="' + en.en + '" type=1>' + symbol[0] + '</a>&nbsp;&nbsp;';
			html += '<a href="javascript:;" class=voice index=' + i + ' data="' + en.en + '" type=2>' + symbol[1] + '</a></td>';
			html += '<td>' + en.cn + '</td></tr>';
		}
		this.obj.html(html);
	},
	play_word_set_init : function(){
		$('.voice').click(function(){
			var en = $(this);
			var word = en.attr('data');
			var type = en.attr('type');
			var index = en.attr('index');
			play_word(word, type, index);
		});
		$('.voice').hover(function(){
			$(this).css('color', '#ccc');
		},function(){
			$(this).css('color', '#337ab7');
		});
	},
};