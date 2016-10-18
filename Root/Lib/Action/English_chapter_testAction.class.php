<?php
class English_chapter_testAction extends Action {
	public $table = array(
		'book'			=> 'English_book',
		'sort'			=> 'English_sort',
		'chapter'		=> 'English_chapter',
		'test'			=> 'English_chapter_test',
		'test_log'			=> 'English_chapter_test',
	);
	
	public function _initialize() {
		ToolsAction::check_login();
	}
	
	public function index(){
		$this->id = $id = $this->_get('id');
		$this->sid = $sid = $this->_get('sid');
		$this->book = M($this->table['book'])->find($sid);
		$this->sort = M($this->table['sort'])->find($this->book['sid']);
		$this->chapter = M($this->table['chapter'])->find($id);

		$table = M($this->table['test_log']);
		$data = ['uid' => session('id'), 'book_chapter_id' => $id];
		$page = ToolsAction::return_page($table, $data, 'Page', '条记录', 20);
		$this->list = ToolsAction::return_result($table, $data, false,  array('id'=>'desc'), $page);
		$this->show = $page->show();
		$this->display();
	}
}