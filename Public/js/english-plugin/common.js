var common = {
	sprintf://仿PHP sprintf函数
		function(){
			var arg = arguments, str = arg[0] || '', i, n;
			for (i = 1, n = arg.length; i < n; i++) {
				str = str.replace(/%s/, arg[i]);
			}
			return str;
		},

	GetRandomNum://获取最大与最小之间的随机数
		function(Min,Max){
			var Range = Max - Min;
			var Rand = Math.random();
			return (Min + Math.round(Rand * Range));
		},

	GetRandomArr://获取随机排列的数组
		function(Arr){
			Arr.sort(function(){ return 0.5 - Math.random() });
		},

	ArrEmptyDelete://删除数组中的空元素
		function(Arr){
			for (var i = 0; i < Arr.length; i++) {
				if (!Arr[i]) {
					Arr.splice(i--, 1);
				}
			}
		},

	IsPC://判断客户端是否为PC
		function() {
			var userAgentInfo = navigator.userAgent;
			var Agents = ["Android", "iPhone",
				"SymbianOS", "Windows Phone",
				"iPad", "iPod"];
			var flag = true;
			for (var v = 0; v < Agents.length; v++) {
				if (userAgentInfo.indexOf(Agents[v]) > 0) {
					flag = false;
					break;
				}
			}
			return flag;
		},

	show_time://时间格式换算
		function(time){
			//time = parseInt(time / 1000);
			var second = time % 60;
			if(second.toString().length == 1) second = '0' + second;

			var minute = time / 60;
			minute = parseInt(minute % 60);
			if(minute.toString().length == 1) minute = '0' + minute;

			var hour = parseInt(time / 3600);
			if(hour.toString().length == 1) hour = '0' + hour;

			return hour + ":" + minute + ":" + second;
		},
};