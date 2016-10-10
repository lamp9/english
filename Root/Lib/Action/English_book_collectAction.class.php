<?php
class English_book_collectAction extends Action {
	public $table = array(
		'book'			=> 'English_book',
		'sort'			=> 'English_sort',
		'chapter'		=> 'English_chapter',
		'collect'		=> 'English_book_collect',
	);
	
	public function _initialize() {
		ToolsAction::check_login();
	}
	
	public function index(){
		cookie($this->table['book'], 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
		$table = D($this->table['collect']);
		$condition['uid'] = session('id');
		$page = ToolsAction::return_page($table, $condition, 'Page', '条记录', 20);
		$this->list = ToolsAction::return_result($table, $condition, true,  array('id'=>'desc'), $page);
		$this->show = $page->show();
		$this->display();
	}

	public function add(){
		$table = M($this->table['collect']);

		$data['uid'] = session('id');
		$data['bid'] = $this->_post('bid');

		$arr = ToolsAction::return_all($table, $data, false, false, false);
		if(count($arr) > 0){echo json_encode(['code' => 'F', 'info' => '此书已收藏！']);}
		else {
			if($table->add($data)){echo json_encode(['code' => 'T', 'info' => '收藏成功！']);}
			else {echo json_encode(['code' => 'F', 'info' => '收藏失败！']);}
		}
	}
	
	public function del(){
		$table = M($this->table['collect']);
		$uid = session('id');
		foreach($_POST['id'] as $id){
			$arr = $table->find($id);
			if($arr['uid'] == $uid) ToolsAction::data_delete($table, $id);
		}
		$this->success();
	}
}