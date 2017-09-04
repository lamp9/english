<?php
class LoginAction extends Action {
	
	public function logout(){
		session('[destroy]');
		cookie(null);
		$this->redirect('index');
	}
	
	public function verify(){
		import('ORG.Util.Image');
		Image::buildImageVerify(4, 1, "png", 100, 25, "code");
	}
	
	public function index(){
		session('[destroy]');
		cookie(null);
		$this->display();
	}
	
	public function check(){
		if($_SESSION['code'] != md5($this->_post('code'))) $this->error('验证码错误，请重新登录！', U('Login/index'));
		$_POST['pwd'] = md5($this->_post("pwd"));
		$admin = M('admin');
		$arr = ToolsAction::return_all($admin, $_POST, false, false, false);
		$arr = $arr[0];
		if(is_array($arr) && count($arr) > 0){
			if(1 != $arr['boot']) $this->redirect('index');

			$this->login($arr);

			ToolsAction::check_level();
		} else $this->redirect('index');
	}

	public function login($arr){
		session('id', $arr['id']);
		session('name', $arr['name']);
		session('level', $arr['level']);
		if($this->_post('online') == 1){
			$time = 3600 * 24 * 10;
			cookie('id', $arr['id'], $time);
			cookie('name', $arr['name'], $time);
		} else {
			cookie('id', $arr['id'], 43200);
			cookie('name', $arr['name'], 43200);
		}
	}

	public function register(){
		if($this->isPost()){
			if($_SESSION['code'] != md5($this->_post('code'))) {
				echo json_encode(array('code' => '验证码错误！'));
				exit;
			}

			if($this->_post('pwd') == $this->_post('pwd2')){
				$admin = M('admin');
				$_POST['pwd'] = md5($this->_post('pwd'));
				$_POST['level'] = 'guest';
				$_POST['boot'] = 1;
				$admin->create();
				if($id = $admin->add()){
					$this->login($admin->find($id));
					echo json_encode(array('code' => 'T'));
				}
				else echo json_encode(array('code' => 'F'));
			} else {
				echo json_encode(array('code' => '密码两次输入不一致！'));
				exit;
			}
		} else {
			$this->display();
		}
	}
	
	public function rand_word_set(){
		$table = M('english');
		$table_set = M('english_set');
		$find = $table_set->find($this->_get('id'));

		$arr = explode(',', $find['en_set']);
		$en_arr = array();
		foreach($arr as $item){
			$en_list = explode(',', $item);

			foreach($en_list as $en_item){
				$en_item_i = ToolsAction::return_all($table, array('en' => $en_item), false, false, false);
				$en_arr[] = $en_item_i[0];
			}
		}

		$this->find = $find;
		$this->data = json_encode($en_arr);
		$this->display('Index/rand_word');
	}

	public function rand_word(){
		$this->book = $this->_post('book');
		$ids = $_POST['id'];
		$str = '';
		foreach($ids as $id){
			$str .= $id.',';
		}
		$str = trim($str, ',');
		$this->data_position = $str;

		$this->display('Index/rand_word');
	}

	//生成合并JSON
	/*public function rand_word_json(){
		$data_position = explode(',', $this->_post('data_position'));
		$json = array();
		foreach($data_position as $item){
			if($item == '') continue;
			$config = get_config();
			$path = $config['english_chapter_json'];
			$json_tmp = file_get_contents($path.$item.".json");
			$json = json_arr_merge($json, $json_tmp);
		}
		echo json_encode($json);
	}*/

	public function rand_word_select(){
		$this->display();
	}
}