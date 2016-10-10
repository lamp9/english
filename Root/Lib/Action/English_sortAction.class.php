<?php
class English_sortAction extends Action {
	public $table = array(
		'table'			=> 'English_sort',
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
		$mode = $this->_post('search_mode');
		$word = $this->_post('search_word');
		cookie('search_mode', $mode);
		cookie('search_word', $word);
		$table = M($this->table['table']);
		
		$data['title'] = array('like','%'.$word.'%');
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
		if($mode == 'A'){
			$table->create();
			$id = $table->add();
		}
		
		if($mode == 'M'){
			$id = $this->_post('id');
			$table->create();
			$table->save();
		}
		$content = file_get_contents('http://'.$_SERVER['HTTP_HOST'].__APP__.'/Login-rand_word_set-id-'.$id);
		$file = fopen('./Public/en_set/'.$id.'.html', 'w');
		fwrite($file, $content);
		fclose($file);
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