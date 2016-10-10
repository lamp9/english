<?php
class AdminFunAction extends Action {
	public $table = array(
		'table'			=> 'english',
		'table_set'		=> 'english_set',
		'table_log'     => 'english_add_queue_log',
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

	public function get_en_set(){
		$table = M($this->table['table']);
		$page = ToolsAction::return_page($table, false, 'Page', '条记录', $this->_get('count'));
		$list = ToolsAction::return_result($table, false, false,  array('id'=>'asc'), $page);
		$en = '<meta http-equiv="Content-Type" content="text/html; charset=utf-8">';
		$i = 0;
		foreach($list as $item){
			$en .= $item['en'].',';
			$i++;
		}
		echo $en.'<br><br>'.$i.'<br>';
		$show = $page->show();
		var_dump($show);
	}
	
	public function search_word(){

		$word = $this->_post('search_word');
		if($word != '') cookie('search_word', $word);
		else $word = cookie('search_word');
		$table = M($this->table['table']);

		$data['en'] = array('like','%'.$word.'%');
		$data['cn'] = array('like','%'.$word.'%');
		$data['_logic'] = 'or';

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
			$table->add();
		}
		
		if($mode == 'M'){
			$table->create();
			$table->save();
		}
		ToolsAction::return_url($this->table['table']);
	}
	
	public function add_queue(){
		if ($this->isPost()){
			$table = M($this->table['table']);
			$en = $this->_post('en');
			$en = str_replace(",\r\n", ',', $en);
			$en = str_replace(",\n", ',', $en);
			$en = str_replace("\r\n", ',', $en);
			$en = str_replace("\n", ',', $en);
			$en = explode(',', $en);

			$i = 0;
			foreach($en as $item){
				$str = '';
				$str .= $item.':';
				if($data = IndexAction::get_word($item)){
					$id = $table->add($data);
					$str .= $id.';';
					$str = '<font color="#00ff00">'.$str.'</font>';
				} else {
					$str = '<font color="#ff0000">'.$str.';'.'</font>';
				}

				$tmp = (++$i % 5  == 0 ? '<br>': '');
				$log['queue_str'] .= $item.','.$tmp;
				$log['queue_str_id'] .= $str.$tmp;
			}
			$log['time'] = date('Y-m-d H:i:s', time());
			IndexAction::add_queue_log_put($log);
			$this->success();
		}else{
			$this->display();
		}
	}
	public function add_queue_log(){
		$table = M($this->table['table_log']);
		$page = ToolsAction::return_page($table, false, 'Page', '条记录', 20);
		$this->list = ToolsAction::return_result($table, false, false,  array('id'=>'desc'), $page);
		$this->show = $page->show();
		$this->display();
	}
	public function add_queue_log_put($data){
		$table = M($this->table['table_log']);
		$table->add($data);
	}

	public function sync_data(){
		$table = M($this->table['table']);

		if ($this->isPost()){
			$en = $this->_post('en');
			$en = explode(',', $en);
			$condition = array(
				'en'	=> array('in', $en)
				);
			$data = ToolsAction::return_all($table, $condition, false, false, false);
			foreach($data as $item){
				$data_new = IndexAction::get_word($item['en']);
				if($data_new){
					$data_new['id'] = $item['id'];
					$table->save($data_new);
				}
			}
			$this->success();
		}else{
			$start = $this->_get('start');
			$stop = $this->_get('stop');

			if($start != '' && $stop != ''){
				echo $start.'-'.$stop;
				$condition = array(
					'id'	=> array('between',array($start, $stop))
					);
				$data = ToolsAction::return_all($table, $condition, false, false, false);
				foreach($data as $item){
					$data_new = IndexAction::get_word($item['en']);
					if($data_new){
						$data_new['id'] = $item['id'];
						$table->save($data_new);
					}
				}
			} else $this->display('add_queue');
		}

	}
	
	public function get_word($word){
		if(!$word) return false;
		$config = get_config();
		$url = sprintf($config['search_word_from_url'], $word);

		$con = get_content_curl($url, false, false, false);
		
		preg_match_all("/<h2 class=\"wordbook-js\">\s*?<span class=\"keyword\">(.*)<\/span>/", $con, $matches);
		$data['en'] = $matches[1][0];
		if(!$data['en']) return false;
		
		preg_match_all("/<span class=\"phonetic\">(.*)<\/span>/", $con, $matches);
		$data['symbol'] = '英'.$matches[1][0].'$$美'.$matches[1][1];
		
		preg_match_all("/<div class=\"trans-container\">\s*?<ul>\s*?(<li>(.*)<\/li>\s*?)*<\/ul>/", $con, $matches);
		$cn = $matches[0][0];
		$cn = preg_replace("/<div class=\"trans-container\">\s*?<ul>/", '', $cn);
		$cn = preg_replace("/<\/li>\s*?<\/ul>/", '', $cn);
		$cn = preg_replace("/\s*?<li>/", '', $cn);
		$cn = preg_replace("/<\/li>/", '<br/>', $cn);
		$data['cn'] = $cn;

		return $data;
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
		$this->display('AdminFun/admin_set');
	}
}