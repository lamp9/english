<?php
class IndexAction extends Action {
	public $table = array(
		'book'			=> 'English_book',
		'sort'			=> 'English_sort',
		'chapter'		=> 'English_chapter',
		'test'			=> 'English_chapter_test',
		'table'			=> 'english',
		'table_set'		=> 'english_set',
		'table_log'     => 'english_add_queue_log',
	);
	
	public function _initialize() {

	}
	
	public function index(){
		English_bookAction::index();
	}

	//显示单词
	/*public function word(){
		cookie($this->table['table'], 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
		$table = M($this->table['table']);
		$page = ToolsAction::return_page($table, false, 'Page', '', 20);
		$this->list = ToolsAction::return_result($table, false, false,  array('id'=>'desc'), $page);
		$this->show = $page->show();
		$this->slist = ToolsAction::return_all(M($this->table['sort']), false, false, false, false);
		$this->display();
	}*/
	
	public function search_book(){
		English_bookAction::search_book();
	}

	public function search_word(){
		exit;
		English_bookAction::search_word();
	}

	public function book(){
		English_bookAction::book();
	}

	public function book_chapter(){
		English_bookAction::book_chapter();
	}

	public function book_chapter_test(){
		English_bookAction::book_chapter_test();
	}

	public function book_chapter_test_submit(){
		English_bookAction::book_chapter_test_submit();
	}
}