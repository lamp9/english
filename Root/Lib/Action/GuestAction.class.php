<?php
class GuestAction extends Action {
	public $table = array(
		'table'			=> 'english',
		'table_set'		=> 'english_set',
		'table_log'     => 'english_add_queue_log',
	);
	
	public function _initialize() {
		ToolsAction::check_login();
		ToolsAction::check_level('guest');
	}
	
	public function admin_set(){
		$id = session('id');
		$table = M('admin');
		if(isset($_POST)){
			$pwd = $this->_post('pwd');
			$pwd2 = $this->_post('pwd2');
			if($pwd != ''){
				if($pwd == $pwd2){
					$data['pwd'] = md5($pwd);
					$data['id'] = $id;
					$table->save($data);
				}
			}
		}
		$this->v = $table->find($id);
		$this->display();
	}
}