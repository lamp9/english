<?php
class English_bookAction extends Action {
	public $table = array(
		'book'			=> 'English_book',
		'sort'			=> 'English_sort',
		'chapter'		=> 'English_chapter',
		'test'			=> 'English_chapter_test',
	);

	/*public function book_en_count(){
		$book = M($this->table['book']);
		$chapter = M($this->table['chapter']);
		$page = ToolsAction::return_page($book, false, 'Page', '条记录', 2000);
		$list = ToolsAction::return_result($book, false, false,  array('id'=>'desc'), $page);

		foreach($list as $item){
			$all = ToolsAction::return_all($chapter, array('sid' => $item['id']), array('sum(en_count) sac, count(*) ac'), false, false);
			$item['en_count'] = $all[0]['sac'];

			$item['en_chapter_count'] = $all[0]['ac'];

			echo $item['id'].':'.$item['en_chapter_count'].'/'.$item['en_count'].'<br>';
			$book->save($item);
		}
	}*/
	
	public function _initialize() {
		ToolsAction::check_login();
		ToolsAction::check_level('admin');
	}
	
	public function index(){
		cookie($this->table['book'], 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
		$table = D($this->table['book']);
		$page = ToolsAction::return_page($table, false, 'Page', '条记录', 20);
		$this->list = ToolsAction::return_result($table, false, true,  array('id'=>'desc'), $page);
		$this->slist = ToolsAction::return_all(M($this->table['sort']), false, false, false, false);
		$this->show = $page->show();
		$this->display();
	}
	
	public function search_book(){
		if(IS_POST){
			$sid = $this->_post('sid');
			$word = $this->_post('word');
			if($sid != "") cookie($this->table['book'].'_sid', $sid);
			else cookie($this->table['book'].'_sid', null);
			if($word != "") cookie($this->table['book'].'_word', $word);
			else cookie($this->table['book'].'_word', null);
		}
		$table = D($this->table['book']);

		$sid = cookie($this->table['book'].'_sid');
		if($sid != '') $data['sid'] = $sid;
		$data['title'] = array('like','%'.cookie($this->table['book'].'_word').'%');
		$page = ToolsAction::return_page($table, $data, 'Page', '条记录', 20);
		$this->list = ToolsAction::return_result($table, $data, true,  array('id'=>'desc'), $page);
		$this->slist = ToolsAction::return_all(M($this->table['sort']), false, false, false, false);
		$this->show = $page->show();
		$this->display('index');
	}

	public function search_word(){
		$word = $this->_post('search_word');
		if($word != '') cookie('search_word', $word);
		else $word = cookie('search_word');
		$table = M('english');


		$data['en'] = $word;
		$data['cn'] = $word;
		$data['_logic'] = 'or';
		$list1 = ToolsAction::return_all($table, $data, false, false, false);

		$data['en'] = array('like','%'.$word.'%');
		$data['cn'] = array('like','%'.$word.'%');
		$data['_logic'] = 'or';
		$list2 = ToolsAction::return_all($table, $data, false, false, false);

		$this->english = array_merge($list1, $list2);
		$this->english_json = json_encode($this->english);

		$this->slist = ToolsAction::return_all(M($this->table['sort']), false, false, false, false);
		$this->display('book_chapter');
	}
	
	public function add_modify(){
		$id = $this->_get('id');
		if($id != '' && $id != null){
			$this->action = 'M';
			$this->v = M($this->table['book'])->find($id);
		} else $this->action = 'A';
		$this->slist = ToolsAction::return_all(M($this->table['sort']), false, false, false, false);
		$this->display();
	}
	
	public function add_modify_down(){
		$table = M($this->table['book']);
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
		ToolsAction::return_url($this->table['book']);
	}
	
	public function del(){
		$table = M($this->table['book']);
		$id = $this->_get('id');
		ToolsAction::data_delete($table, $id);
		$this->success();
	}
	
	public function del_batch(){
		$table = M($this->table['book']);
		foreach($_POST['id'] as $id){
			ToolsAction::data_delete($table, $id);
		}
		$this->success();
	}

	public function book(){
		cookie($this->table['chapter'], 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);

		$table = M($this->table['chapter']);

		$sid = $this->_get('sid');
		$this->sid = $sid;
		$this->book = M($this->table['book'])->find($sid);

		$this->sort = M($this->table['sort'])->find($this->book['sid']);

		$data['sid'] = $sid;
		$this->list = ToolsAction::return_all($table, $data, false, false, false);

		$this->slist = ToolsAction::return_all(M($this->table['sort']), false, false, false, false);
		$this->display();
	}

	public function book_chapter(){
		$id = $this->_get('id');
		$sid = $this->_get('sid');
		$this->slist = ToolsAction::return_all(M($this->table['sort']), false, false, false, false);
		$this->book = M($this->table['book'])->find($sid);
		$this->sort = M($this->table['sort'])->find($this->book['sid']);
		$this->chapter = M($this->table['chapter'])->find($id);

		$config = get_config();
		$path = $config['english_chapter_json'];
		$this->english_json = file_get_contents($path.$id.".json");
		$this->english = json_to_array(json_decode($this->english_json));
		$this->display();
	}

	public function book_chapter_test(){
		ToolsAction::check_login();
		$this->id = $id = $this->_get('id');
		$this->sid = $sid = $this->_get('sid');
		$this->slist = ToolsAction::return_all(M($this->table['sort']), false, false, false, false);
		$this->book = M($this->table['book'])->find($sid);
		$this->sort = M($this->table['sort'])->find($this->book['sid']);
		$this->chapter = M($this->table['chapter'])->find($id);

		$config = get_config();
		$path = $config['english_chapter_json'];
		$this->english_json = file_get_contents($path.$id.".json");
		$this->english = json_to_array(json_decode($this->english_json));
		$this->display();
	}

	public function book_chapter_test_submit(){
		$post = $this->_post();
		$count = 0;
		foreach($post['answer'] as $answer){
			if('T' == $answer['score']) ++$count;
		}

		$data['uid'] = session('id');
		$data['book_id'] = $post['sid'];
		$data['book_chapter_id'] = $post['id'];
		$data['score'] = ($count) ? sprintf("%.2f", ($count / $post['count']) * 100) : 0;
		$data['time'] = date('Y-m-d H:i:s', time());
		if(M($this->table['test'])->add($data)){
			$result = ['code' => 'T', 'result' => $data];
		} else $result = ['code' => 'F'];
		echo json_encode($result);
	}

	public function book_add_modify(){
		$id = $this->_get('id');
		if($id != '' && $id != null){
			$this->action = 'M';
			$this->v = M($this->table['chapter'])->find($id);
		} else $this->action = 'A';
		$this->sid = $this->_get('sid');
		$this->display();
	}

	public function book_add_modify_down(){
		$table = M($this->table['chapter']);
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
		ToolsAction::return_url($this->table['chapter']);
	}

	public function book_del(){
		$table = M($this->table['chapter']);
		$id = $this->_get('id');
		ToolsAction::data_delete($table, $id);
		$this->success();
	}

	public function book_del_batch(){
		$table = M($this->table['chapter']);
		foreach($_POST['id'] as $id){
			ToolsAction::data_delete($table, $id);
		}
		$this->success();
	}





	//获取大分类和所有书籍
	public function test(){
		exit;
		$table = M($this->table['sort']);
		$table_book = M($this->table['book']);

		echo '<meta http-equiv="content-type" content="text/html; charset=UTF-8">';

		//添加大分类
		/*$sort = '<ul class="nav nav-tabs nav-stacked" id="wordbook-category-list">

                        <li data="10" class="active"><a href="/wordbook/category/10/">考研</a></li>

                        <li data="20"><a href="/wordbook/category/20/">托福</a></li>

                        <li data="30"><a href="/wordbook/category/30/">雅思</a></li>

                        <li data="40"><a href="/wordbook/category/40/">GRE</a></li>

                        <li data="51"><a href="/wordbook/category/51/">四级</a></li>

                        <li data="52"><a href="/wordbook/category/52/">六级</a></li>

                        <li data="55"><a href="/wordbook/category/55/">BEC</a></li>

                        <li data="60"><a href="/wordbook/category/60/">英专</a></li>

                        <li data="70"><a href="/wordbook/category/70/">托业</a></li>

                        <li data="80"><a href="/wordbook/category/80/">GMAT</a></li>

                        <li data="90"><a href="/wordbook/category/90/">SAT</a></li>

                        <li data="91"><a href="/wordbook/category/91/">ACT</a></li>

                        <li data="101"><a href="/wordbook/category/101/">高中</a></li>

                        <li data="102"><a href="/wordbook/category/102/">初中</a></li>

                        <li data="103"><a href="/wordbook/category/103/">小学</a></li>

                        <li data="104"><a href="/wordbook/category/104/">医学</a></li>

                        <li data="109"><a href="/wordbook/category/109/">文学作品</a></li>

                        <li data="110"><a href="/wordbook/category/110/">英语辅导</a></li>

                        <li data="120"><a href="/wordbook/category/120/">公共英语</a></li>

                        <li data="130"><a href="/wordbook/category/130/">公开课</a></li>

                        <li data="140"><a href="/wordbook/category/140/">影视剧</a></li>

                        <li data="150"><a href="/wordbook/category/150/">其他</a></li>

                </ul>';

		preg_match_all("/<li.*?><a href=\"(.*?)\">(.*?)<\/a><\/li>/", $sort, $matches);
		$urls = $matches[1];
		$titles = $matches[2];


		for($i = 0; $i < count($urls); $i++){
			echo $titles[$i].'----'.$urls[$i].'----';

			$data['title'] = $titles[$i];
			$data['url'] = 'https://www.shanbay.com'.$urls[$i];
			echo $table->add($data).'<br>';
			$data = null;
		}*/

		//添加书籍
		$data = ToolsAction::return_all($table, false, false, false, false);
		foreach($data as $item){
			echo $item['title'].'<br>';
			$url = $item['url'];
			$html = get_content_curl($url, $url, false, false);

			$html = str_replace("\r\n", '', $html);
			$html = str_replace("\n", '', $html);

			$pattern = "/>\s*</";
			$html = preg_replace($pattern, '><', $html);

			preg_match_all(
				"/<div title=\".*?\" class=\"wordbook-title\"><a href=\"(.*?)\">(.*?)<\/a><\/div>/"
				, $html, $matches);

			$book_urls = $matches[1];
			$book_title = $matches[2];
			$count = 0;
			$count_all = count($book_urls);
			for($i = 0; $i < $count_all; $i++){
				echo '----'.$book_title[$i].'----https://www.shanbay.com'.$book_urls[$i];
				$book['sid'] = $item['id'];
				$book['sym'] = 'F';
				$book['title'] = $book_title[$i];
				$book['url'] = 'https://www.shanbay.com'.$book_urls[$i];
				if($table_book->add($book)){
					echo 'TTT';
					$count++;
				} else echo 'FFF';
				echo '<br>';
			}

			if($count_all != $count) echo 'FAIL!!';
			echo 'OK!'.$count_all.':'.$count.'<br><br><hr>';
		}
	}

	//获取书籍章节URL
	public function book_get_list(){
		set_time_limit(0);
		//echo '<meta http-equiv="content-type" content="text/html; charset=UTF-8">';

		$book = M($this->table['book']);
		$chapter = M($this->table['chapter']);

		$condition['sym'] = 'F';
		$page = ToolsAction::return_page($book, $condition, 'Page', '条记录', 5);
		$list = ToolsAction::return_result($book, $condition, false,  array('id'=>'asc'), $page);

		foreach($list as $item){
			//var_dump($item);
			$sid = $item['id'];
			$url = $item['url'];

			$html = get_content_curl($url, $url, false, false);

			$html = str_replace("\r\n", '', $html);
			$html = str_replace("\n", '', $html);
			$html = preg_replace("/>\s*</", '><', $html);

			//获取书本章节
			preg_match_all("/<td class=\"wordbook-wordlist-name\"><a href=\"(.*?)\">(.*?)<\/a><\/td><td align=\"right\" class=\"wordbook-wordlist-count\"><span>单词数：<\/span>(.*?)<\/td>/", $html, $matches);

			//遍历章节，获取单词
			$list_url = $matches[1];
			$list_name = $matches[2];
			$list_count = $matches[3];
			$count = count($list_url);
			$count_tmp = 0;
			for($i = 0; $i < $count; $i++){
				if($list_url[$i] == '/wordlist/${ wordbook_id }/${ wordlist.id }/') continue;

				$data['sid'] = $sid;
				$data['title'] = $list_name[$i];
				$data['en_count'] = $list_count[$i];
				$data['url'] = 'https://www.shanbay.com'.$list_url[$i];
				$data['sym'] = 'F';
				//var_dump($data);

				if($chapter->add($data)) $count_tmp++;
				$data = null;
			}
			$item['sym'] = ($count == $count_tmp) ? 'T' : 'K';
			$book->save($item);
		}

		$return = array();

		$static['sym'] = 'F';
		$all = ToolsAction::return_all($book, $static, array('count(*) ac'), false, false);
		$return['f'] = $all[0]['ac'];
		$static['sym'] = 'K';
		$all = ToolsAction::return_all($book, $static, array('count(*) ac'), false, false);
		$return['k'] = $all[0]['ac'];
		$static['sym'] = 'T';
		$all = ToolsAction::return_all($book, $static, array('count(*) ac'), false, false);
		$return['t'] = $all[0]['ac'];
		echo json_encode($return);
	}

	//获取书籍章节单词
	public function book_get_list_en(){
		//exit;
		set_time_limit(0);
		//echo '<meta http-equiv="content-type" content="text/html; charset=UTF-8">';
		$table = M($this->table['chapter']);

		$condition['sym'] = 'F';
		$page = ToolsAction::return_page($table, $condition, 'Page', '条记录', 1);
		$list = ToolsAction::return_result($table, $condition, false,  array('id'=>'asc'), $page);

		$ids = '';
		$url_count = 0;
		foreach($list as $item){
			$ids .= $item['id'].',';
			$url = $item['url'];
			$count = $item['en_count'];

			$page = intval($count / 20);
			if($count % 20 > 0) $page += 1;

			$en = '';
			$sym = false;
			for($j = 1; $j <= $page; $j++){
				$url_count++;
				$url_j = $url.'?page='.$j;
				//echo $url_j.'<br>';
				$html = get_content_curl($url_j, $url_j, false, false);
				//echo $html.'<br>';

				if($html === false){
					$sym = true;
					break;
				}

				$html = str_replace("\r\n", ' ', $html);
				$html = str_replace("\n", ' ', $html);
				preg_match_all("/<td class=\"span2\"><strong>(.*?)<\/strong><\/td>.*?<td class=\"span10\">.*?<\/td>/", $html, $matches);

				$list_en = $matches[1];
				//if(empty($list_en)) break;
				if(count($list_en) == 0) {
					$sym = true;
					break;
				}
				foreach($list_en as $item_en){
					$en .= $item_en.',';
				}
			}
			$en = trim($en, ',');
			$data['id'] = $item['id'];
			$data['en_set'] = $en;
			$data['sym'] = $sym ? 'K' : 'T';
			//var_dump($data);

			$table->save($data);
			$data = null;
		}

		$return = array();

		$static['sym'] = 'F';
		$all = ToolsAction::return_all($table, $static, array('count(*) ac'), false, false);
		$return['f'] = $all[0]['ac'];
		$static['sym'] = 'K';
		$all = ToolsAction::return_all($table, $static, array('count(*) ac'), false, false);
		$return['k'] = $all[0]['ac'];
		$static['sym'] = 'T';
		$all = ToolsAction::return_all($table, $static, array('count(*) ac'), false, false);
		$return['t'] = $all[0]['ac'];

		$return['ids'] = $ids;
		$return['url_count'] = $url_count;
		echo json_encode($return);
	}

	//单词入库
	public function book_word_in(){
		//echo '<meta http-equiv="content-type" content="text/html; charset=UTF-8">';
		$table_en_set = M($this->table['chapter']);
		$english = M('english');
		$condition['sym_en'] = 'F';
		$page = ToolsAction::return_page($table_en_set, $condition, 'Page', '条记录', 50);
		$list = ToolsAction::return_result($table_en_set, $condition, false,  array('id'=>'asc'), $page);

		$state = array();
		foreach($list as $item){
			$su = 0;
			$fa = 0;
			$en_list = explode(',', $item['en_set']);
			foreach($en_list as $en_item){
				$data['en'] = $en_item;
				$data['cn'] = 'F';
				$data['symbol'] = 'F';
				if($english->add($data)) $su++;
				else $fa++;
			}

			$return['id'] = $item['id'];
			$return['count'] = $item['en_count'];
			$return['su'] = $su;
			$return['fa'] = $fa;

			$state[] = $return;

			$item['sym_en'] = 'T';
			$table_en_set->save($item);
		}

		$return = array();

		$static['sym_en'] = 'F';
		$all = ToolsAction::return_all($table_en_set, $static, array('count(*) ac'), false, false);
		$return['f'] = $all[0]['ac'];
		$static['sym_en'] = 'T';
		$all = ToolsAction::return_all($table_en_set, $static, array('count(*) ac'), false, false);
		$return['t'] = $all[0]['ac'];

		$return = array('all' => $return, 'state' => $state);

		echo json_encode($return);
	}

	//批量采集单词解释
	public function book_word_save_cn_sym(){
		$english = M('english');

		$condition = array(
			'cn'	=> 'F'
		);

		$page = ToolsAction::return_page($english, $condition, 'Page', '条记录', 50);
		$list = ToolsAction::return_result($english, $condition, false,  array('id'=>'desc'), $page);


		if(count($list) == 0){
			$arr = array('state' => 'none');
			echo json_encode($arr);
			exit;
		}

		$su = 0;
		$fa = 0;
		$save_su = 0;
		$fail_str = '';
		foreach($list as $item){
			$data_new = IndexAction::get_word($item['en']);
			if($data_new){
				$su++;
				$item['symbol'] = $data_new['symbol'];
				$item['cn'] = $data_new['cn'];
			} else {
				$item['symbol'] = 'K';
				$item['cn'] = 'K';
				$fa++;
			}

			if($english->save($item)) $save_su++;
			else {
				$fail_str .= $item['id'].'/'.$data_new['cn'].'/'.$data_new['symbol'];
				$item['symbol'] = 'K';
				$item['cn'] = 'K';
				$english->save($item);
			}
		}

		$english = M('english');
		$return = array();

		$static['cn'] = 'F';
		$all = ToolsAction::return_all($english, $static, array('count(*) ac'), false, false);
		$return['f'] = $all[0]['ac'];

		$static['cn'] = 'K';
		$all = ToolsAction::return_all($english, $static, array('count(*) ac'), false, false);
		$return['k'] = $all[0]['ac'];

		$return['all'] = $return['f'] + $return['k'];

		$return['state'] = $su.'/'.$fa.'/'.$save_su;

		$return['fail_str'] = $fail_str;

		echo json_encode($return);
	}

	//书籍章节单词解析-修补
	public function book_word_repair_cn_sym(){
		//echo '<meta http-equiv="content-type" content="text/html; charset=UTF-8">';
		$english = M('english');
		$english_chapter = M($this->table['chapter']);
		//$all = ToolsAction::return_all($english, array('cn' => 'K'), false, false, false);


		$page = ToolsAction::return_page($english, array('cn' => 'K'), 'Page', '条记录', 100);
		$all = ToolsAction::return_result($english, array('cn' => 'K'), false,  array('id'=>'desc'), $page);

		if(count($all) == 0){
			$arr = array('state' => 'none');
			echo json_encode($arr);
			exit;
		}

		foreach($all as $item){
			$en = $item['en'];
			$en_list = ToolsAction::return_all($english_chapter, array('en_set' => array('like','%,'.$en.',%')), false, false, false);
			$en_data = $en_list[0];

			$en_arr = explode(',', $en_data['en_set']);

			//单词索引
			$j = 0;
			foreach($en_arr as $en_j){
				$j++;
				if($en_j == $en) break;
			}
			//页索引
			$page_index = (int) ($j / 20);
			if($j % 20 != 0) $page_index++;
			//链接
			$url = $en_data['url'].'?page='.$page_index;

			//echo $en .':'. $url .'<br>';continue;

			$html = get_content_curl($url, $url, false, false);

			if($html === false){
				$item['cn'] = 'P';
				$english->save($item);
				continue;
			}

			$html = str_replace("\r\n", '', $html);
			$html = str_replace("\n", '', $html);
			preg_match_all("/<td class=\"span2\"><strong>{$en}<\/strong><\/td>.*?<td class=\"span10\">(.*?)<\/td>/", $html, $matches);

			//var_dump($matches);

			if(empty($matches[1][0])){
				$item['cn'] = 'P';
				$english->save($item);
				//echo 'true<br>';
				continue;
			}

			$item['cn'] = $matches[1][0];
			$english->save($item);

		}

		$static['cn'] = 'K';
		$all = ToolsAction::return_all($english, $static, array('count(*) ac'), false, false);
		$return['k'] = $all[0]['ac'];

		$static['cn'] = 'P';
		$all = ToolsAction::return_all($english, $static, array('count(*) ac'), false, false);
		$return['p'] = $all[0]['ac'];

		$return['all'] = $return['k'] + $return['p'];

		echo json_encode($return);
	}

	//书籍章节单词生成JSON文件
	public function book_chapter_create_json(){
		$english = M('english');
		$english_chapter = M($this->table['chapter']);

		$page = ToolsAction::return_page($english_chapter, array('sym_json_file' => 'F'), 'Page', '条记录', 50);
		$all = ToolsAction::return_result($english_chapter, array('sym_json_file' => 'F'), false,  array('id'=>'desc'), $page);

		if(count($all) == 0){
			$arr = array('state' => 'none');
			echo json_encode($arr);
			exit;
		}

		foreach($all as $item){
			$en_list = explode(',', $item['en_set']);
			$en_arr = array();
			foreach($en_list as $en_item){
				$en_item_i = ToolsAction::return_all($english, array('en' => $en_item), false, false, false);
				$en_arr[] = $en_item_i[0];
			}
			$en_json = json_encode($en_arr);

			$fp = fopen('./Public/en_chapter/'.$item['id'].'.json', "wb");
			fwrite($fp, $en_json);
			fclose($fp);

			$item['sym_json_file'] = 'T';
			$english_chapter->save($item);
		}

		$all = ToolsAction::return_all($english_chapter, false, array('count(*) ac'), false, false);
		$return['a'] = $all[0]['ac'];

		$static['sym_json_file'] = 'T';
		$all = ToolsAction::return_all($english_chapter, $static, array('count(*) ac'), false, false);
		$return['t'] = $all[0]['ac'];

		echo json_encode($return);
	}
}