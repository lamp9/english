<?php
class ToolsAction extends Action {
	
	public function check_login(){
		if(session('id') == '') $this->redirect('Login/index');
		if(cookie('id') == null || cookie('id') == '') $this->redirect('Login/index');
		if(cookie('name') == null || cookie('name') == '') $this->redirect('Login/index');
	}

	public function check_level($level){
		$level_session = session('level');
		if($level != $level_session){
			switch($level_session){
				case 'admin' : $this->redirect('AdminFun/index'); break;
				case 'guest' : $this->redirect('Index/index'); break;
				default : $this->redirect('index');
			}
		}
	}
	
	//返回分页信息
	public function return_page($table, $data, $page_type, $header, $num){
		import('ORG.Util.'.$page_type);
		if($data) $table = $table->where($data);
		$arr = $table->select();
		$count=count($arr);
		$page = new Page($count,$num);
		$page->setConfig('header',$header);
		return $page;
	}
	//根据分页信息返回数据记录
	public function return_result($table, $data, $relation, $order, $page){
		if($data) $table = $table->where($data);
		if($order) $table = $table->order($order);
		if($relation) $table = $table->relation(true);
		return $table->limit($page->firstRow.','.$page->listRows)->select();
	}
	//根据特定的分页参数返回特定的数据记录
	public function return_result_page($table, $data, $field, $relation, $order, $page_start, $num){
		if($data) $table = $table->where($data);
		if($field) $table = $table->field($field);
		if($order) $table = $table->order($order);
		if($relation) $table = $table->relation(true);
		return $table->limit($page_start.','.$num)->select();
	}
	//返回所有符合条件的记录
	public function return_all($table, $data, $field, $relation, $order){
		if($data) $table = $table->where($data);
		if($field) $table = $table->field($field);
		if($order) $table = $table->order($order);
		if($relation) $table = $table->relation(true);
		return $table->select();
	}
	
	public function data_delete($table, $id){
		if($table->delete($id)) return true;
		else return false;
	}
	
	public function data_delete_condition($table, $where){
		if($table->where($where)->delete()) return true;
		else return false;
	}
	
	//批量删除数据
	public function data_queue_delete($table){
		for($i=0;$i<count($_POST['id']);$i++){
			$table->delete($_POST['id'][$i]);
		}
	}
	//删除数据及其相应关联文件
	public function data_delete_with($do){
		$admin = M($do);
		$arr = $admin->find($this->_get("id"));
		unlink("./uploads/$do/".$arr['pic']);
		ToolsAction::data_delete($admin, $this->_get('id'));
	}
	
	public function parent_page_reload(){
		echo '<script>var index = parent.layer.getFrameIndex(window.name);
			parent.location.reload();
			parent.layer.close(index);</script>';
	}

	public function return_url($jump){
		header('Location:'.cookie($jump));
	}
}