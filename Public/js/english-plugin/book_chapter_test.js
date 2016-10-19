var book_chapter_test = {
	id  : 0,
	sid : 0,
	submitTestUrl : '',
	showProcessButton : function(){
		var obj = $('#showProcessButton');
		obj.html('(' + (this.index + 1) + ' / ' + this.count + ') 下一题');
	},
	showProblem : function(){
		var data = this.obj[this.index];
		var obj = $('#showProblem');
		var html = $('#showProblemTpl').html();
		obj.html(juicer(html, data));

		$('#showProblemSelect');

		var arrIndex = [this.index];
		for(var i = 1; i < this.select_count; i++){
			var position = 0;
			while(1){
				var sym = true;
				position = common.GetRandomNum(1, this.count) - 1;
				for(var j = 0; j < arrIndex.length; j++){
					if(position == arrIndex[j]) sym = false;
				}
				if(sym) break;
			}
			arrIndex.push(position);
		}
		common.GetRandomArr(arrIndex);
		var html = $('#showProblemSelectTpl').html();
		var str = '';
		for(var i = 0; i < arrIndex.length; i++){
			str += html.replace('${cn}', this.obj[arrIndex[i]].cn).replace('${index}', arrIndex[i]);
		}
		$('#showProblemSelect').html(str);
	},
	checkAnswer : function(index){
		//判断答案
		var obj = this.obj[this.index];
		if(obj.score) return;
		obj.score = (index == this.index) ? 'T' : 'F';
		//显示答案
		$('#showProblem .list-group-item-text').html(this.obj[this.index].cn).css('color', (index == this.index) ? '#000' : '#f00').show();
	},
	nextProblem : function(){
		if(1 == this.testFinish) return;

		if(this.index > this.count){
			this.testFinish = 1;
			this.index = 0;
			//提交成绩
			this.submitTest();
			this.showAnswer();
		} else {
			if(-1 != this.index){
				var obj = this.obj[this.index];
				if(!obj.score) return;
			}
			this.index++;

			this.showProblem();
			this.showProcessButton();
			this.play_word_set_init();
		}
	},
	showAnswer : function(){
		if(1 != this.testFinish) return;
		$('#showTest').hide();
		$('#showTestScore').show();
		book_chapter_obj.init(this.obj);
	},
	submitTest : function(){
		$.ajax({
			url: this.submitTestUrl,
			type: 'post',
			data: {
				id	: this.id,
				sid	: this.sid,
				count	: this.count,
				answer	: this.obj,
			},
			dataType: 'json',
			timeout: 10000,
			cache: false,//不缓存数据
			async: false,//同步：false,异步：true,默认true
			success: function(data){
				switch(data.code){
					case 'T':
						alert('交卷成功，本轮测试成绩为：' + data.result.score);
						break;
					case 'F':
						alert('交卷失败！');
						break;
					default:;
				}
			},
		});
	},
	obj : '',
	count : '',
	select_count : '',
	index : -1,
	testFinish : 0,
	init : function(){
		this.count = this.obj.length;
		common.GetRandomArr(this.obj);
		for(var i = 0; i < this.count; i++){
			var sym = this.obj[i].symbol.split('$$');
			this.obj[i].sym = {en : sym[0], us : sym[1]};
			this.obj[i].index = i;
			this.obj[i].search = common.sprintf(english_rand_word.en_search_from, this.obj[i].en);
			this.obj[i].score = null;
		}
		if(this.select_count > this.count) this.select_count = this.count;
	},
	play_word_set_init : function (){
		$('.voice').click(function(){
			var en = $(this);
			var word = en.attr('data');
			var type = en.attr('type');
			var index = en.attr('index');
			audio_english.play_word(word, type, index);
		});
		$('.voice').hover(function(){
			$(this).css('color', '#ccc');
		},function(){
			$(this).css('color', '#337ab7');
		});
	},
};