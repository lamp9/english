<?php
class UserAction extends Action {
	public $table = array(
		'table'			=> 'Admin',
	);
	
	public function _initialize() {
		ToolsAction::check_login();
		ToolsAction::check_level('admin');
	}
	
	public function index(){
		cookie($this->table['table'], 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
		$table = M($this->table['table']);
		$page = ToolsAction::return_page($table, false, 'Page', '条记录', 20);
		$this->list = ToolsAction::return_result($table, false, false,  array('id'=>'desc'), $page);
		$this->show = $page->show();
		$this->display();
	}
	
	public function search_word(){
		$word = $this->_post('search_word');
		if($word != '') cookie('search_word', $word);
		else $word = cookie('search_word');

		$table = M($this->table['table']);
		
		$data['name'] = array('like','%'.$word.'%');
		$page = ToolsAction::return_page($table, $data, 'Page', '条记录', 20);
		$this->list = ToolsAction::return_result($table, $data, false,  array('id'=>'desc'), $page);
		$this->show = $page->show();
		$this->display('index');
	}
	
	public function add_modify(){
		$id = $this->_get('id');
		if($id != '' && $id != null){
			$this->action = 'M';
			$this->v = M($this->table['table'])->find($id);
		} else $this->action = 'A';
		$this->display();
	}
	
	public function add_modify_down(){
		$table = M($this->table['table']);
		$mode = $this->_post('mode');

		$pwd = $this->_post('pwd');
		if('' == $pwd){
			unset($_POST['pwd']);
		} else $_POST['pwd'] = md5($pwd);
		$table->create();
		if($mode == 'A'){
			$id = $table->add();
		}
		
		if($mode == 'M'){
			$table->save();
		}
		ToolsAction::return_url($this->table['table']);
	}
	
	public function del(){
		$table = M($this->table['table']);
		$id = $this->_get('id');
		ToolsAction::data_delete($table, $id);
		$this->success();
	}
	
	public function del_batch(){
		$table = M($this->table['table']);
		foreach($_POST['id'] as $id){
			ToolsAction::data_delete($table, $id);
		}
		$this->success();
	}
}