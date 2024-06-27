<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Api_model extends CI_Model {

	protected $prefix;
    function __construct(){
        parent::__construct();
        //$this->load->library('parser');
        //$this->load->helper('html');
        //$this->load->helper('string');
		
		$this->load->database();

		$this->prefix = $this->db->dbprefix;
    }    
    
    public function user_menu($type){
    		
		$lang = $this->lang->language['lang'];
		//debug( $lang);
		//$admin_id = $this->session->userdata('admin_id');
		//$admin_type = $this->session->userdata('admin_type');
		$loadfile = "admintype".$type.".json";
		$list_data = $title = '';
		//if($type == 1) $loadfile = "superadmin.json";
		$admin_menu = LoadJSON($loadfile);
		$subadmin_menu = $admin_menu;			
		//Debug($subadmin_menu);
		if($admin_menu){
			foreach($admin_menu as $arr => $list_mainmenu){
					
				$sub_mainparent = '';
				$havesub = 0;						
					
				if($list_mainmenu){
					foreach($list_mainmenu as $field => $title){
						//echo "$field => $title<br>";
						if($field == "admin_type_id") $admin_type_id = $title;
						if($field == "admin_menu_id") $admin_menu_id2 = $title;
						if($field == "title_en") $title_en = $title;
						if($field == "title_th") $title_th = $title;
						if($field == "url") $url = $title;
						if($field == "icon") $icon = $title;
						if($field == "sub") $sub = $title;
						if($field == "parent") $mainparent = $title;									
						if($field == "option") $havesub = $title;									
					}
					$icon = ($admin_menu_id2 == 28) ? $icon : 'fa '.$icon ;
						
					if($mainparent == 0){

							//$icon_menu = ($row->_icon != '') ? $row->_icon : 'fa-file-text';
							$title = ($lang == 'th') ? $title_th : $title_en;
							/*************Sub menu Active****************/
							if(($admin_menu_id2 == 2) && ((strtolower($this->uri->segment(1)) == 'team')
									|| (strtolower($this->uri->segment(1)) == 'week') || (strtolower($this->uri->segment(1)) == 'partner')
									|| (strtolower($this->uri->segment(1)) == 'channel') || (strtolower($this->uri->segment(1)) == 'match')
									|| (strtolower($this->uri->segment(1)) == 'tournament') || (strtolower($this->uri->segment(1)) == 'season') )){
									//ข้อมูลพื้นฐาน
									$curactive = 'class="hsub open"';
									$submenu = 'style="display:block;"';
							}else if(($admin_menu_id2 == 27) && ((strtolower($this->uri->segment(1)) == 'admin_menu') 
								|| (strtolower($this->uri->segment(1)) == 'admin_delete')	 || (strtolower($this->uri->segment(1)) == 'activity_logs')
								|| (strtolower($this->uri->segment(1)) == 'admin') || (strtolower($this->uri->segment(1)) == 'memberlist')
								|| (strtolower($this->uri->segment(1)) == 'accessmenu') )){
								//ตั้งค่า
								$curactive = 'class="hsub open"';
								$submenu = 'style="display:block;"';

							/*}else if(($admin_menu_id2 == 28) && ((strtolower($this->uri->segment(1)) == 'json') || (strtolower($this->uri->segment(1)) == 'gen')	)){
								//สร้างแคช
								$curactive = 'class="hsub open"';
								$submenu = 'style="display:block;"';*/

							}else if(($admin_menu_id2 == 41) && ((strtolower($this->uri->segment(1)) == 'homepage_menu') || (strtolower($this->uri->segment(1)) == 'block') || (strtolower($this->uri->segment(1)) == 'programtv') || (strtolower($this->uri->segment(1)) == 'highlight') || (strtolower($this->uri->segment(1)) == 'order') )){
								//หน้าเวปแสดงผล
								$curactive = 'class="hsub open"';
								$submenu = 'style="display:block;"';

							}else if(($admin_menu_id2 == 87) && (strtolower($this->uri->segment(1)) == 'dev')){
								//Dev tool
								$curactive = 'class="hsub open"';
								$submenu = 'style="display:block;"';
							}else if(($admin_menu_id2 == 101) && (strtolower($this->uri->segment(1)) == 'popular_vote')){
								$curactive = 'class="hsub open"';
								$submenu = 'style="display:block;"';
							}else{
								$curactive = '';
								$submenu = '';
							}

							/*************Sub menu Active****************/
							$chkurl = ltrim($url,"/");
							$classactive = '';

							if(strtolower($this->uri->segment(1)) == strtolower($chkurl)){
								$classactive = 'class="active"';
							}else
								$classactive = '';

							if($sub == 1)
							
								$list_data .= '<li '.$curactive.'>
									<a href="#" class="dropdown-toggle">
										<i class="menu-icon '.$icon.'"></i><span class="menu-text">'.$title.'</span>
											<b class="arrow fa fa-angle-down"></b>
									</a>
									<b class="arrow"></b>
									<ul class="submenu" '.$submenu.'>';
							else
								$list_data .= '<li '.$classactive.'>
									<a href="'.base_url($url).'">
										<i class="menu-icon '.$icon.'"></i><span class="menu-text">'.$title.'</span></a>
										<b class="arrow"></b>
										';

								//$list_data .= '<b class="arrow"></b>';
								//Debug($list_mainmenu);
								
							if($subadmin_menu){
								foreach($subadmin_menu as $subarr => $list_mainmenu2){
									//Debug($list_mainmenu2);
									foreach($list_mainmenu2 as $subfield => $subtitle){
										//echo "$subfield => $subtitle<br>";
														
										if($subfield == "admin_type_id") $sub_admin_type_id = $subtitle;
										if($subfield == "admin_menu_id") $sub_admin_menu_id = $subtitle;
														
										if($subfield == "title_en") $sub_title_en = $subtitle;
										if($subfield == "title_th") $sub_title_th = $subtitle;
										if($subfield == "url") $sub_url = $subtitle;
										if($subfield == "icon") $sub_icon = $subtitle;
										if($subfield == "parent") $sub_mainparent = $subtitle;														
										//echo "($sub_mainparent == $admin_type_id)<br>";																												
									}
												
									if($sub_mainparent == $admin_menu_id2){
									//if($sub_url == strtolower($this->uri->segment(1))){
													
										$havesub++;
										$active = '';
										$subtitle = ($lang == 'th') ? $sub_title_th : $sub_title_en;
										$chksub_url = str_replace("/", "", $sub_url);

										//Check Current
										//$classactive = (strtolower($this->uri->segment(1)) == strtolower($sub_title_en)) ? 'class="active"' : '';

										if(sizeof($this->uri->segments) > 1){
											$segment = strtolower($this->uri->segment(1)).'/'.strtolower($this->uri->segment(2));
											//$classactive = ($segment == $chksub_url) ? 'class="active"' : '';
											if(($segment == $chksub_url) || (strtolower($this->uri->segment(1)) == $chksub_url))
												$classactive = 'class="active"';
											else
												$classactive = '';
										}else
											$classactive = (strtolower($this->uri->segment(1)) == $chksub_url) ? 'class="active"' : '';

											//('.$segment.' == '.$chksub_url.')														
											$list_data .= '<li '.$classactive.'>
													<a href="'.base_url($sub_url).'">
														<i class="menu-icon fa fa-caret-right"></i>'.$subtitle.' </a>
														<b class="arrow"></b>
													</li>';
										}
									}
										
								}//if($subadmin_menu)
										
								/*************Sub menu Active****************/
								if($admin_menu_id2 == 2 || $admin_menu_id2 == 27 || $admin_menu_id2 == 41 || $admin_menu_id2 == 87 || $admin_menu_id2 == 101) $list_data .= '</ul>';
								//$admin_menu_id2 == 28 ||
								/*************Sub menu Active****************/
								$list_data .= '</li>';
						}	
						//echo "<hr>";						
					}											
			}
			//echo $list;
		}			
		//Debug($admin_menu);
		return $list_data;
    }

    public function notification_birthday(){

			//$language = $this->lang->language['lang'];

			$datenow = date('-m-d');
			//$thismonth = '-'.date('m').'-';
			$this->db->select('dp.*');
			$this->db->from('_dara_profile as dp');

			if(isset($thismonth)) $this->db->like('birth_date', $thismonth, 'both');
			if(isset($datenow)) $this->db->like('birth_date', $datenow, 'before');

			$this->db->where('status', 1);
			$this->db->where('approve', 1);
			//$this->db->where('birth_date >=', $datenow);

	    	$query = $this->db->get();
	    	//Debug($this->db->last_query());
	    	return $query->result_object();    	
    }

    public function notification_msg($mod = 'news', $count = 1){

			//$language = $this->lang->language['lang'];
			$language = 'th';

			if($count == 1) 
				$this->db->select('count(*) as count_approve');
			else
				$this->db->select('*');

			if($mod == 'news'){
					$this->db->from('_news');
			}else if($mod == 'column'){
					$this->db->from('_column');
			}else if($mod == 'gallery'){
					$this->db->from('_gallery');
			}else if($mod == 'vdo'){
					$this->db->from('_video');
			}else if($mod == 'dara'){
					$this->db->from('_dara_profile');
			}
			$this->db->where('status', 1);
			$this->db->where('approve', 0);

			if($mod != 'dara'){
				$this->db->where('lang', $language);
				$this->db->where('create_date >', '2015-01-01');
			}

	    	$query = $this->db->get();
	    	//if($mod == 'vdo') Debug($this->db->last_query());
	    	return $query->result_object();    	
    }
	
    public function notification_tags(){

			//$language = $this->lang->language['lang'];
			$language = 'th';
			$count = 0;

			if($count == 1) 
				$this->db->select('count(*) as count_approve');
			else
				$this->db->select('*');

			$this->db->from('_tag');
			$this->db->where('status', 0);
	    	$query = $this->db->get();
	    	return $query->result_object();    	
    }

    public function get_picture($ref_id, $picid = 0, $ref_type = 1 ){

	    	$language = $this->lang->language['lang'];
			
			if($ref_type == 1){
				$this->db->select('p.*, n.title, n.create_date');
				//$this->db->select('no.picture, no.folder_img');
			}else
				$this->db->select('p.*');

	    	$this->db->from('_picture p');

			if($ref_type == 1){
				$this->db->join('_news n', 'n.news_id2 = p.ref_id and n.lang = "'.$language.'"', 'left');
				
				/*$this->db->select('*');
				$this->db->from('_news_old');
				$this->db->where('idzone =', $idzone);
				$this->db->order_by('news_id', 'ASC');*/
			}

	    	$this->db->where('ref_id', $ref_id);
	    	$this->db->where('ref_type', $ref_type);
	    	//$this->db->where('status', $pic_status);
	    	
	    	if($picid != 0) $this->db->where('picture_id', $picid);

			//if($lang != '') $this->db->where('_news.lang', $language);

	    	$this->db->order_by('p.order', 'ASC');
	    	$this->db->order_by('p.create_date', 'DESC');
	    	
	    	$query = $this->db->get();
	    	//Debug($this->db->last_query());
	    	return $query->result_array();    	
    }

    public function Highlight($language, $list_number = 5, $displayfolder = 'headnews'){

        $response = array();
        $header = array();
        $result = array();
        $item = array();	

		$allhighlight_list = $this->get_news_highlight();

		//Debug($allhighlight_list);
		//echo "<br><br><br>";
		$new_highlight = array();

		if(isset($allhighlight_list)){
				$maxorder = count($allhighlight_list);
				for($i=0;$i<$maxorder;$i++){
						$number = $i+1;
						$get_highlight = $this->get_highlight($allhighlight_list[$i]->ref_type, $allhighlight_list[$i]->news_id);
						if(isset($get_highlight[0])) $new_highlight[$i] = $get_highlight[0];
				}
		}
		//echo "<hr>";
		//Debug($new_highlight);
		//die();

		$total_all = count($new_highlight);
		//Debug($new_highlight);        
        if($total_all > 0){
			    $i = $j = 0;

				for($i=0;$i<$total_all;$i++){
				//foreach($highlight_list as $i => $obj){

						if($j < $list_number){ 

								$number = $i + 1;
								$type = "";

								$highlight_id = $new_highlight[$i]->news_highlight_id;
								$news_id = $new_highlight[$i]->news_id;
								$ref_type = $new_highlight[$i]->ref_type;
								$order = $new_highlight[$i]->order;
								$title = StripTxt($new_highlight[$i]->title);

								$external_link =  (isset($new_highlight[$i]->other_link) && (trim($new_highlight[$i]->other_link) != '')) ? $new_highlight[$i]->other_link : '';

								$category_id = (isset($new_highlight[$i]->category_id)) ? $new_highlight[$i]->category_id : 0;
								$category_name = (isset($new_highlight[$i]->category_name)) ? $new_highlight[$i]->category_name : 0;
								$subcategory_id = (isset($new_highlight[$i]->subcategory_id)) ? $new_highlight[$i]->subcategory_id : 0;
								$subcategory_name = (isset($new_highlight[$i]->subcategory_name)) ? $new_highlight[$i]->subcategory_name : 0;

								$lastupdate_date = $new_highlight[$i]->lastupdate_date;
								$create_date = $new_highlight[$i]->create_date;
								$countview = $new_highlight[$i]->countview;
								$file_name = $new_highlight[$i]->file_name;
								$folder = $new_highlight[$i]->folder;

								$type = (isset($new_highlight[$i]->type)) ? $new_highlight[$i]->type : "Gallery";
								//if($type == "") $new_highlight[$i]->type = "Gallery";

								if(!isset($category_id)) $category_id  = 0;
								if(!isset($category_name)) $category_name  = "";

								/*switch($new_highlight[$i]->type){
											case "News" : $pathpic = base_url("uploads/news/".$folder."/".$file_name); break;
											case "Column" : $pathpic = base_url("uploads/column/".$folder."/".$file_name); break;
											case "Gallery" : $pathpic = base_url("uploads/gallery/".$folder."/".$file_name); break;
											case "Clip" : $pathpic = base_url("uploads/vdo/".$folder."/".$file_name); break;
								}*/
								
								//$pathpic = base_url("uploads/highlight/".$folder."/".$file_name); 
								$pathpic = $this->config->config['static'].'/'.$displayfolder.'/'.$folder.'/'.$file_name;

								$new_highlight[$i]->picture = $pathpic;

								if($category_id == 0){
									if($ref_type == 3) $category_id = 19;
									if($ref_type == 4) $category_id = 5;
								}
								if($category_name == ""){
									if($ref_type == 3) $category_name = 'แกเลอรี่';
									if($ref_type == 4) $category_name = 'คลิปวิดีโอ';
								}

								$item[$j]['highlight_id'] = intval($highlight_id);

								$files3 = 'http:';
								if((preg_match("/".$files3."/i", $external_link))){
									$item[$j]['external_link'] = trim($external_link);
								}else
									$item[$j]['external_link'] = '';

								$item[$j]['id'] = intval($news_id);
								$item[$j]['ref_type'] = intval($ref_type);
								$item[$j]['category_id'] = intval($category_id);
								$item[$j]['category_name'] = $category_name;
								$item[$j]['subcategory_id'] = intval($subcategory_id);
								$item[$j]['subcategory_name'] = $subcategory_name;
								$item[$j]['title'] = $title;
								//$item[$j]['description'] = $row->description;
								$item[$j]['lastupdate_date'] = $lastupdate_date;
								$item[$j]['file_name'] = $file_name;
								$item[$j]['folder'] = $folder;
								$item[$j]['order'] = intval($order);
								$item[$j]['type'] = strtolower($type);
								$item[$j]['picture'] = $pathpic;
								$j++;

								//echo "<hr>($list_number == $j)<hr>";
								if($list_number == $j){ 
									unset($obj); 
									unset($new_highlight); 
								}
						}

			}
        
            $header['resultcode'] = 200;
            $header['message'] = "success";
            $header['total_rows'] = $j;

            
        } else {
            $header['resultcode'] = 204;
            $header['message'] = "success";
        }
		//Debug($response);
        $result['item'] = $item;        
        $response['header'] = $header;
        $response['body'] = $result;

        return json_encode($response);
	}

    public function navigation($language){

        $response = array();
        $header = array();
        $result = array();
        $item = array();	

        $sql = "web_menu_id2 as nav_id, title, url, parent, order_by";
        
        $this->db->select($sql);
        $this->db->from('_homepage_menu');
        $this->db->where('status',1);
        $this->db->where('lang',$language);
        $this->db->order_by('parent',"asc");
        $this->db->order_by('order_by',"asc");
        $rs = $this->db->get()->result();
		//Debug($this->db->last_query());

		$total_all = $this->db->count_all_results();
        
        if($total_all > 0){
            $i=0;
            foreach($rs as $key => $row){
                $item[$i]['nav_id'] = $row->nav_id;
                $item[$i]['title'] = $row->title;
                $item[$i]['url'] = $row->url;
                $item[$i]['parent'] = $row->parent;
                $item[$i]['sort'] = $row->order_by;
                $i++;
            }
        
            $header['resultcode'] = "200";
            $header['message'] = "success";
            $header['total_rows'] = $i;
            
        } else {
            $header['resultcode'] = "204";
            $header['message'] = "success";
        }
        
        $result['item'] = $item;        
        $response['header'] = $header;
        $response['body'] = $result;
        
        return json_encode($response);
	}

    public function category($language){

        $response = array();
        $header = array();
        $result = array();
        $item = array();	

        $sql = "category_id_map as category_id, category_name, order_by, status";
        
        $this->db->select($sql);
        $this->db->from('_category');
        $this->db->where('status',1);
        $this->db->where('lang',$language);
        $this->db->order_by('order_by',"asc");
        $rs = $this->db->get()->result();
		//Debug($this->db->last_query());

		$total_all = $this->db->count_all_results();
        
        if($total_all > 0){
            /*$i=0;
            foreach($rs as $key => $row){
                $item[$i]['category_id'] = $row->category_id;
                $item[$i]['category_id'] = $row->category_id;
                $item[$i]['title'] = $row->title;
                $item[$i]['url'] = $row->url;
                $item[$i]['parent'] = $row->parent;
                $item[$i]['sort'] = $row->order_by;
                $i++;
            }*/

			for($i=0;$i<count($rs);$i++){
					$item[$i] = $rs[$i];
			}
		
            $header['resultcode'] = "200";
            $header['message'] = "success";
            $header['total_rows'] = $i;
            
        } else {
            $header['resultcode'] = "204";
            $header['message'] = "success";
        }
        
        $result['item'] = $item;        
        $response['header'] = $header;
        $response['body'] = $result;
		//Debug($response);
		//die();
        
        return json_encode($response);
	}

    public function subcategory($language){

        $response = array();
        $header = array();
        $result = array();
        $item = array();	

        $sql = "subcategory_id_map as subcategory_id, subcategory_name, category_id, order_by, status";
        
        $this->db->select($sql);
        $this->db->from('_subcategory');
        $this->db->where('status',1);
        $this->db->where('lang',$language);
        $this->db->order_by('order_by',"asc");
        $rs = $this->db->get()->result();
		//Debug($this->db->last_query());

		$total_all = $this->db->count_all_results();
        
        if($total_all > 0){
            /*$i=0;
            foreach($rs as $key => $row){
                $item[$i]['category_id'] = $row->category_id;
                $item[$i]['category_id'] = $row->category_id;
                $item[$i]['title'] = $row->title;
                $item[$i]['url'] = $row->url;
                $item[$i]['parent'] = $row->parent;
                $item[$i]['sort'] = $row->order_by;
                $i++;
            }*/

			for($i=0;$i<count($rs);$i++){
					$item[$i] = $rs[$i];
			}
		
            $header['resultcode'] = "200";
            $header['message'] = "success";
            $header['total_rows'] = $i;
            
        } else {
            $header['resultcode'] = "204";
            $header['message'] = "success";
        }
        
        $result['item'] = $item;        
        $response['header'] = $header;
        $response['body'] = $result;
		//Debug($response);
		//die();
        
        return json_encode($response);
	}

    public function news($language, $id = null){

		$this->load->model('news_model');

        $response = array();
        $header = array();
        $result = array();
        $item = array();
        $tags = $tags_arr = $pic_arr = array();

		$prefix = 'sd';

		if($id > 0){
		        $sql = "_news.news_id2 as news_id, _news.title, _news.description, _news.start_date, _news.expire_date, _category.category_name, _subcategory.subcategory_name";
		}else{
		        $sql = "_news.news_id2 as news_id, _news.title, _news.description, _news.start_date, _news.expire_date, _category.category_name, _subcategory.subcategory_name, _dara_profile.first_name, _dara_profile.last_name, _dara_profile.nick_name, _dara_profile.avatar";
		}
        
        $this->db->select($sql);
        $this->db->from('_news');

		$this->db->join('_category', '_news.category_id = _category.category_id_map AND `'.$prefix.'_category`.lang = "'.$language.'" ', 'left');
		$this->db->join('_subcategory', '_news.subcategory_id = _subcategory.subcategory_id_map AND `'.$prefix.'_subcategory`.lang = "'.$language.'" ', 'left');

		if(!$id) $this->db->join('_dara_profile', '_news.dara_id = _dara_profile.dara_profile_id', 'left');

        $this->db->where('_news.status',1);
        $this->db->where('_news.lang',$language);

		if($id){
			$this->db->where('_news.news_id2', $id);		
		}

        $this->db->order_by('_news.create_date',"DESC");
        $rs = $this->db->get()->result();

		//Debug($this->db->last_query());
		//die();
		$total_all = $this->db->count_all_results();
        
        if($total_all > 0){
			for($i=0;$i<count($rs);$i++){

					$item[$i] = $rs[$i];

					//--------------- Picture--------------------
					unset($this->db);
					$this->db->select('*');
					$this->db->from('_picture');
					$this->db->where('ref_id', $rs[$i]->news_id);
					$this->db->where('ref_type', 1);
					$this->db->where('status', 1);
					$rs_pic = $this->db->get()->result();
					$item[$i]->picture = $rs_pic;

					//--------------- Tags--------------------
					$this->db->select('_tag.tag_id, tag_text');
					$this->db->from('_tag');
					$this->db->join('_tag_pair', '_tag.tag_id = _tag_pair.tag_id AND `'.$prefix.'_tag`.status = 1 ', 'left');
					$this->db->where('_tag_pair.ref_id', $rs[$i]->news_id);
					$this->db->where('_tag_pair.ref_type', 1);
					$rs_tag = $this->db->get()->result();
					$item[$i]->tags = $rs_tag;

					//--------------- Relate--------------------
					$rs_relate = $this->news_model->get_relate($rs[$i]->news_id);				
					$item[$i]->relates = $rs_relate;
					//--------------- End--------------------
			}
			//Debug($item);
			//die();

            $header['resultcode'] = "200";
            $header['message'] = "success";
            $header['total_rows'] = $i;
            
        } else {
            $header['resultcode'] = "204";
            $header['message'] = "success";
        }
        
        $result['item'] = $item;        
        $response['header'] = $header;
        $response['body'] = $result;
        
        return json_encode($response);
	}

    public function column($language){

        $response = array();
        $header = array();
        $result = array();
        $item = array();
        $tags = $tags_arr = $pic_arr = array();

		$prefix = 'sd';

        $sql = "_column.*, _category.category_name, _subcategory.subcategory_name, _dara_profile.first_name, _dara_profile.last_name, _dara_profile.nick_name, _dara_profile.avatar";
        
        $this->db->select($sql);
        $this->db->from('_column');

		$this->db->join('_category', '_column.category_id = _category.category_id_map AND `'.$prefix.'_category`.lang = "'.$language.'" ', 'left');
		$this->db->join('_subcategory', '_column.subcategory_id = _subcategory.subcategory_id_map AND `'.$prefix.'_subcategory`.lang = "'.$language.'" ', 'left');
		$this->db->join('_dara_profile', '_column.dara_id = _dara_profile.dara_profile_id', 'left');

        $this->db->where('_column.status',1);
        $this->db->where('_column.lang',$language);

        $this->db->order_by('_column.create_date',"DESC");
        $rs = $this->db->get()->result();

		//echo "<br>".$this->db->last_query();
		$total_all = $this->db->count_all_results();
        
        if($total_all > 0){
			for($i=0;$i<count($rs);$i++){

					$item[$i] = $rs[$i];

					//--------------- Picture--------------------
					unset($this->db);
					$this->db->select('*');
					$this->db->from('_picture');
					$this->db->where('ref_id', $rs[$i]->column_id2);
					$this->db->where('ref_type', 2);
					$this->db->where('status', 1);

					$rs_pic = $this->db->get()->result();

					$item[$i]->picture = $rs_pic;

					//--------------- Tags--------------------
					$this->db->select('_tag.tag_id, tag_text');
					$this->db->from('_tag');
					$this->db->join('_tag_pair', '_tag.tag_id = _tag_pair.tag_id AND `'.$prefix.'_tag`.status = 1 ', 'left');

					$this->db->where('_tag_pair.ref_id', $rs[$i]->column_id2);
					$this->db->where('_tag_pair.ref_type', 2);
					$rs_tag = $this->db->get()->result();

					$item[$i]->tags = $rs_tag;

					//--------------- End--------------------
			}
        
            $header['resultcode'] = "200";
            $header['message'] = "success";
            $header['total_rows'] = $i;
            
        } else {
            $header['resultcode'] = "204";
            $header['message'] = "success";
        }
        
        $result['item'] = $item;        
        $response['header'] = $header;
        $response['body'] = $result;
        
        return json_encode($response);

	}

    public function gallery($language){

        $response = array();
        $header = array();
        $result = array();
        $item = array();
        $tags = $tags_arr = $pic_arr = array();

		$prefix = 'sd';

        //$sql = "_gallery.*, _category.category_name, _subcategory.subcategory_name, _dara_profile.first_name, _dara_profile.last_name, _dara_profile.nick_name, _dara_profile.avatar";
        $sql = "_gallery.*, _gallery_type.gallery_type_name , _dara_profile.first_name, _dara_profile.last_name, _dara_profile.nick_name, _dara_profile.avatar";
        
        $this->db->select($sql);
        $this->db->from('_gallery');

		$this->db->join('_gallery_type', '_gallery.gallery_type_id = _gallery_type.gallery_type_id2 AND `'.$prefix.'_gallery_type`.lang = "'.$language.'" ', 'left');
		$this->db->join('_dara_profile', '_gallery.dara_id = _dara_profile.dara_profile_id', 'left');

        $this->db->where('_gallery.status',1);
        $this->db->where('_gallery.lang',$language);

        $this->db->order_by('_gallery.create_date',"DESC");
        $rs = $this->db->get()->result();

		//echo "<br>".$this->db->last_query();
		//die();
		$total_all = $this->db->count_all_results();
        
        if($total_all > 0){
			for($i=0;$i<count($rs);$i++){

					$item[$i] = $rs[$i];

					//--------------- Picture--------------------
					unset($this->db);
					$this->db->select('*');
					$this->db->from('_picture');
					$this->db->where('ref_id', $rs[$i]->gallery_id2);
					$this->db->where('ref_type', 3);
					$this->db->where('status', 1);

					$rs_pic = $this->db->get()->result();

					$item[$i]->picture = $rs_pic;

					//--------------- Tags--------------------
					$this->db->select('_tag.tag_id, tag_text');
					$this->db->from('_tag');
					$this->db->join('_tag_pair', '_tag.tag_id = _tag_pair.tag_id AND `'.$prefix.'_tag`.status = 1 ', 'left');

					$this->db->where('_tag_pair.ref_id', $rs[$i]->gallery_id2);
					$this->db->where('_tag_pair.ref_type', 3);
					$rs_tag = $this->db->get()->result();

					$item[$i]->tags = $rs_tag;

					//--------------- End--------------------

			}
        
            $header['resultcode'] = "200";
            $header['message'] = "success";
            $header['total_rows'] = $i;
            
        } else {
            $header['resultcode'] = "204";
            $header['message'] = "success";
        }
        
        $result['item'] = $item;        
        $response['header'] = $header;
        $response['body'] = $result;
        //debug($response);
		//die();
        return json_encode($response);
	}

    public function api_external($url,$proxy=NULL){
        $arr = array();
        
        $arr[0] = $url;
        $output = $this->multiple_request($arr,$proxy);
        return $output;
    }
    
    /********************************************************
     * proxy ip or hostname with port ext. 127.0.0.1:80 
    ********************************************************/
    public function get_curl($url,$proxy=NULL){
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        if($proxy !== NULL){
            curl_setopt($ch, CURLOPT_PROXY, $proxy);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 10);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
        $output = curl_exec($ch);
        curl_close($ch);

        return $output;
    }
    
    /********************************************************/    
    public function multi_request($nodes,$proxy=NULL){

        $mh = curl_multi_init(); 
        $curl_array = array(); 
        foreach($nodes as $i => $url) { 
            $curl_array[$i] = curl_init($url); 

            if($proxy === NULL){
                //$proxy = "xxx.xxx.xxx.xxx:80";
                curl_setopt($curl_array[$i] , CURLOPT_PROXY, $proxy);
            }
            curl_setopt($curl_array[$i], CURLOPT_RETURNTRANSFER, true); 
            curl_multi_add_handle($mh, $curl_array[$i]); 
        }

        $running = NULL; 
        do{ 
            usleep(10000); // 10000
            curl_multi_exec($mh,$running); 
        } while($running > 0); 
        
        $res = array(); 
        foreach($nodes as $i => $url) { 
            $res[$url] = curl_multi_getcontent($curl_array[$i]); 
        }        
        foreach($nodes as $i => $url){ 
            curl_multi_remove_handle($mh, $curl_array[$i]); 
        } 
        curl_multi_close($mh);    
        return $res[$url]; 
    } 

}