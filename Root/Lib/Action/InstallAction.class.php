<?php
class InstallAction extends Action {
	
	public function _initialize(){
		ToolsAction::check_login();
		ToolsAction::check_level('admin');
	}
	
	public function index(){
		echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
		$mysql = M();
		
		//用户表
		$arr = $mysql->query("create table admin(
			id int not null auto_increment,
			name char(32),
			pwd char(32),
			rname char(50),
			contact char(100),
			level char(20),
			time date,
			boot char(1),
			primary key(id),
			UNIQUE KEY `name` (`name`)
		) engine = MyISAM default charset = utf8");
		
		//词库
		$arr = $mysql->query("create table english(
			id int not null auto_increment,
			en char(50) COMMENT '单词',
			cn varchar(500) COMMENT '中文',
			symbol varchar(200) COMMENT '音标',
			primary key(id),
			UNIQUE KEY `en` (`en`),
			KEY `cn` (`cn`)
		) engine = MyISAM default charset = utf8");
		
		//词集
		$arr = $mysql->query("create table english_set(
			id int not null auto_increment,
			title char(50) COMMENT '标题',
			en_set text COMMENT '词集',
			primary key(id)
		) engine = MyISAM default charset = utf8");

		//词库添加日志
		$arr = $mysql->query("create table english_add_queue_log(
			id int not null auto_increment,
			time datetime COMMENT '记录时间',
			queue_str text COMMENT '添加词',
			queue_str_id text COMMENT '添加词+ID',
			primary key(id)
		) engine = MyISAM default charset = utf8");

		//英语书类
		$arr = $mysql->query("create table english_sort(
			id int not null auto_increment,
			title varchar(30) DEFAULT '0' COMMENT '标题',
			url varchar(255) COMMENT '链接',
			primary key(id),
			UNIQUE KEY `title` (`title`)
		) engine = MyISAM default charset = utf8");

		//英语书
		$arr = $mysql->query("create table english_book(
			id int not null auto_increment,
			sid int DEFAULT '0' COMMENT '所属书类',
			title varchar(100) DEFAULT '0' COMMENT '标题',
			url varchar(255) COMMENT '链接',
			en_chapter_count int COMMENT '数量',
			en_count int COMMENT '数量',
			sym char(1) COMMENT '标志值',
			primary key(id),
			KEY `title` (`title`),
			KEY `sid` (`sid`),
			UNIQUE KEY `url` (`url`)
		) engine = MyISAM default charset = utf8");

		//英语书章节
		$arr = $mysql->query("create table english_chapter(
			id int not null auto_increment,
			sid int COMMENT '所属书',
			title varchar(100) DEFAULT '0' COMMENT '标题',
			en_set text COMMENT '词集',
			en_count int COMMENT '数量',
			url varchar(255) COMMENT '链接',
			sym char(1) COMMENT '标志值',
			sym_en char(1) COMMENT '标志值',
			sym_json_file char(1) COMMENT '标志值',
			primary key(id),
			KEY `title` (`title`),
			KEY `sid` (`sid`),
			UNIQUE KEY `url` (`url`)
		) engine = MyISAM default charset = utf8");

		//英语书收藏
		$arr = $mysql->query("create table english_book_collect(
			id int not null auto_increment,
			uid int COMMENT '用户ID',
			bid int COMMENT '书本ID',
			primary key(id),
			KEY `uid` (`uid`),
			KEY `bid` (`bid`)
		) engine = MyISAM default charset = utf8");

		//英语书章节测试
		$arr = $mysql->query("CREATE TABLE `english_chapter_test` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `uid` int(11) NOT NULL,
			  `book_id` int(11) NOT NULL,
			  `book_chapter_id` int(11) NOT NULL,
			  `score` decimal(10,2) NOT NULL,
			  `time` datetime NOT NULL,
			  PRIMARY KEY (`id`),
			  KEY `uid` (`uid`),
			  KEY `book_chapter_id` (`book_chapter_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;");
	}
}