<?php
class IndexAction extends Action {
	public $table = array(
		'book'			=> 'English_book',
		'sort'			=> 'English_sort',
		'chapter'		=> 'English_chapter',
		'table'			=> 'english',
		'table_set'		=> 'english_set',
		'table_log'     => 'english_add_queue_log',
	);
	
	public function _initialize() {

	}
	
	public function index(){
		English_bookAction::index();
	}

	public function word(){
		cookie($this->table['table'], 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
		$table = M($this->table['table']);
		$page = ToolsAction::return_page($table, false, 'Page', '', 20);
		$this->list = ToolsAction::return_result($table, false, false,  array('id'=>'desc'), $page);
		$this->show = $page->show();
		$this->slist = ToolsAction::return_all(M($this->table['sort']), false, false, false, false);
		$this->display();
	}
	
	public function search_book(){
		English_bookAction::search_book();
	}

	public function search_word(){
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
}