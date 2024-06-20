<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Genarate {
    private $_ci;
    function __construct(){
        //When the class is constructed get an instance of codeigniter so we can access it locally
        $this->_ci =& get_instance();
        //$this->_ci->load->helper('table');
    }

    public function user_menu($type = 7){
            
        // $lang = $this->_ci->lang->language['lang'];
        $lang = 'th';
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
                        if(($admin_menu_id2 == 2) && ((strtolower($this->_ci->uri->segment(1)) == 'team')
                            || (strtolower($this->_ci->uri->segment(1)) == 'category') || (strtolower($this->_ci->uri->segment(1)) == 'guru')
                            || (strtolower($this->_ci->uri->segment(1)) == 'league') || (strtolower($this->_ci->uri->segment(1)) == 'import_match') || (strtolower($this->_ci->uri->segment(1)) == 'tournament')
                            || (strtolower($this->_ci->uri->segment(1)) == 'tags') || (strtolower($this->_ci->uri->segment(1)) == 'price') )){
                            //ข้อมูลพื้นฐาน
                                $curactive = 'class="active"';
                                //$submenu = 'style="display:block;"';
                        }else if(($admin_menu_id2 == 27) && ((strtolower($this->_ci->uri->segment(1)) == 'admin_menu') || (strtolower($this->_ci->uri->segment(1)) == 'admin_delete')  || (strtolower($this->_ci->uri->segment(1)) == 'activity_logs')   || (strtolower($this->_ci->uri->segment(1)) == 'admin') || (strtolower($this->_ci->uri->segment(1)) == 'memberlist'))){
                            //ตั้งค่า
                            $curactive = 'class="active"';
                            //$submenu = 'style="display:block;"';
                        /*}else if(($admin_menu_id2 == 28) && ((strtolower($this->_ci->uri->segment(1)) == 'json') || (strtolower($this->_ci->uri->segment(1)) == 'gen')  )){
                            //สร้างแคช
                            $curactive = 'class="hsub open"';
                            $submenu = 'style="display:block;"';*/
                        }else if(($admin_menu_id2 == 41) && ((strtolower($this->_ci->uri->segment(1)) == 'homepage_menu') || (strtolower($this->_ci->uri->segment(1)) == 'block') || (strtolower($this->_ci->uri->segment(1)) == 'programtv') || (strtolower($this->_ci->uri->segment(1)) == 'highlight') || (strtolower($this->_ci->uri->segment(1)) == 'order') )){
                            //หน้าเวปแสดงผล
                            $curactive = 'class="active"';
                            //$submenu = 'style="display:block;"';
                        }else if(($admin_menu_id2 == 87) && (strtolower($this->_ci->uri->segment(1)) == 'dev')){
                            //Dev tool
                            $curactive = 'class="active"';
                            //$submenu = 'style="display:block;"';
                        }else if(($admin_menu_id2 == 101) && (strtolower($this->_ci->uri->segment(1)) == 'popular_vote')){
                            $curactive = 'class="active"';
                            //$submenu = 'style="display:block;"';
                        }else{
                            $curactive = '';
                            $submenu = '';
                        }
                        /*************Sub menu Active****************/

                        $chkurl = ltrim($url,"/");
                        $classactive = '';

                        if(strtolower($this->_ci->uri->segment(1)) == strtolower($chkurl)){
                            $classactive = 'class="active"';
                        }else 
                            $classactive = '';

                        if($sub == 1)
                            $list_data .= '<li '.$curactive.'>
                                <a href="#">
                                    <i class="'.$icon.'"></i> '.$title.'
                                    <span class="fa arrow"></span>
                                </a>
                                <ul class="nav nav-second-level" '.$submenu.'>';
                        else
                            $list_data .= '<li '.$classactive.'>
                                <a href="'.base_url($url).'">
                                    <i class="'.$icon.'"></i> '.$title.'</a>';
                        
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
                                //if($sub_url == strtolower($this->_ci->uri->segment(1))){
                                    
                                    $havesub++;
                                    $active = '';
                                    $subtitle = ($lang == 'th') ? $sub_title_th : $sub_title_en;
                                    $chksub_url = str_replace("/", "", $sub_url);

                                    //Check Current
                                    //$classactive = (strtolower($this->_ci->uri->segment(1)) == strtolower($sub_title_en)) ? 'class="active"' : '';

                                    if(sizeof($this->_ci->uri->segments) > 1){
                                        $segment = strtolower($this->_ci->uri->segment(1)).'/'.strtolower($this->_ci->uri->segment(2));
                                        //$classactive = ($segment == $chksub_url) ? 'class="active"' : '';
                                        if(($segment == $chksub_url) || (strtolower($this->_ci->uri->segment(1)) == $chksub_url))
                                            $classactive = 'class="active"';
                                        else
                                            $classactive = '';
                                    }else
                                        $classactive = (strtolower($this->_ci->uri->segment(1)) == $chksub_url) ? 'class="active"' : '';

                                    //('.$segment.' == '.$chksub_url.')                                                     
                                    $list_data .= '<li '.$classactive.'>
                                                    <a href="'.base_url($sub_url).'">
                                                        <i class="fa fa-caret-right"></i> '.$subtitle.'</a>
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

    public function DateTimeDiff($strDateTime){

        $strDateTime2 = date('Y-m-d H:i:j');

        $days = (strtotime($strDateTime2) - strtotime($strDateTime))/  ( 60 * 60 * 24 );  // 1 day = 60*60*24
        $days = round($days);

        if($days > 0){
            $val = $days.' day ';
        } else{
            $days = '';
            list($date1, $time1) = explode(" ", $strDateTime);
            list($date2, $time2) = explode(" ", $strDateTime2);

            $val = (strtotime($time2) - strtotime($time1)) / ( 60 ); // 1 min =  60
            if($val >= 60){
                $val = (strtotime($time2) - strtotime($time1))/  ( 60 * 60 ); // 1 Hour =  60*60

                if($val > 24){
                    $val = ($val/24);
                }
                $val =  round($val, 2);
                if($val > 1)
                    @list($hr, $min) = explode(".", $val);
                //$val = $hr.' Hour '.round($min, 2).' min';
                $val = $hr.' Hour ';
            }else{

                if($val < 10)
                    $val =  'A few minutes';
                else
                    $val =  round($val, 0).' min';
            }

        }
        return $val;
    }

    public function GenfileJS($data_asset){
        $datafile = "Morris.Area({
                        element: 'morris-chart-area',
                        data: [
                        ".$data_asset."
                        ],
                        xkey: 'd',
                        ykeys: ['register', 'play'],
                        labels: ['Register', 'Play Predictor games'],
                        smooth: false,
                        pointSize: 2,
                        hideHover: 'auto',
                        resize: true
                    });";
        return $datafile;
	}

    public function GenDataWidget($data_asset){

        $language = $this->_ci->lang->language;

        $datafile = '
        <div class="row">
                <div class="col-sm-5">
                    <div class="widget-box transparent">
                        <div class="widget-header widget-header-flat">
                            <h4 class="widget-title lighter">
                                <i class="ace-icon fa fa-futbol-o orange"></i>
                                '.$language['number_of_member'].'
                            </h4>

                            <div class="widget-toolbar">
                                <a href="#" data-action="collapse">
                                    <i class="ace-icon fa fa-chevron-up"></i>
                                </a>
                            </div>
                        </div>

                        <div class="widget-body">
                            <div class="widget-main no-padding">
                                <table class="table table-bordered table-striped">
                                    <thead class="thin-border-bottom">
                                    <tr>
                                        <th>
                                            <i class="ace-icon fa fa-caret-right blue"></i>Date
                                        </th>
                                        <th>
                                            <i class="ace-icon fa fa-caret-right blue"></i>Week
                                        </th>
                                        <th>
                                            <i class="ace-icon fa fa-caret-right blue"></i>People
                                        </th>

                                    </tr>
                                    </thead>
                                    <tbody>';

        //$attributes = array('target' => '_blank');

        $allcount = count($data_asset);

        for($i=0;$i<$allcount;$i++){

            $count_profile = $data_asset[$i]->count_profile;
            $cdate = $data_asset[$i]->cdate;
            $week_name = $data_asset[$i]->week_name;
                
            //$pagename = anchor($this->config->config['www'].$pagename, $pagename, $attributes);
            $datafile .= '<tr>
                        <td>'.DateTH($cdate).'</td>
                        <td>'.$week_name.'</td>
                        <td>
                            <b class="green">'.number_format($count_profile).'</b>
                        </td>
                    </tr>';
        }

        $datafile .= '</tbody>
                                </table>
                            </div><!-- /.widget-main -->
                        </div><!-- /.widget-body -->
                    </div><!-- /.widget-box -->
                </div><!-- /.col -->

            </div>';


        return $datafile;
    }

    function GenStatusBar(&$arr_data = array()){

        krsort($arr_data);
        $html = '<div class="space-6"></div>
		<div class="col-sm-7 infobox-container">';

        $allitem = count($arr_data);
        for($i=0;$i<$allitem;$i++){
            //Debug($arr_data[$i]);
            $score = $arr_data[$i]['score'];
            $num = $arr_data[$i]['num'];

            if(($num > 100) && ($num < 200)) {
                $class = 'infobox-orange';
                $gritter = 'gritter-warning';
            }else if($num > 200){
                $class = 'infobox-red';
                $gritter = 'gritter-error';
            }else {
                $class = 'infobox-blue';
                $gritter = 'gritter-info';
            }

            $html .= '<div class="infobox '.$class.' sticky-notice" data-name="ทำนาย '.$score.' จำนวน '.$num.' คน" data-class="'.$gritter.'">
				<div class="infobox-icon">
					<i class="ace-icon fa fa-futbol-o"></i>
				</div>

				<div class="infobox-data" data-rel="tooltip" title="">
					<span class="infobox-data-number">'.$score.'</span>
					<div class="infobox-content">'.$num.' คน</div>
				</div>
			</div>';

        }
        $html .= '</div></div>';

        return $html;
    }

    function SelFavorite($default = 0, $name = "favorite"){
        
        // $language = $this->_ci->lang->language;
        // $first = "--- ".$language['please_select'].$language['favorite']." ---";
        $first = "--- กรุณาเลือก ---";

        //Debug($rows);
        $opt = array();
        $opt[]  = makeOption(0, $first);
        $opt[]  = makeOption(1, 'Home');
        $opt[]  = makeOption(2, 'Away');
        /*for($i=0;$i<count($rows);$i++){
            $row = @$rows[$i];
            $opt[] = makeOption($row->program_id, $row->section_name);
        }*/
        return selectList($opt, $name, 'class="form-control"', 'value', 'text', $default);

    }

    public function table_list($property, $heading, $body, $link = 1, $caption = ''){

        $html = '';
        $obj_data = array();
        // $language = $this->_ci->lang->language;

        if($caption != '') $this->table->set_caption($caption);
        // Set a table template to specify the design of table layout
        //$table_property = array('table_open' => '<table id="dataTables-news" class="table-responsive table table-striped table-bordered table-hover ">');

        $table_property = array('table_open' => $property);
        $this->_ci->table->set_template($table_property);

        // Create heading
        $number_field = count($heading);
        /*for($i=0;$i<$number_field;$i++){
            $header_col1
        }*/

        switch(count($heading)){
            case 5 :
                $this->_ci->table->set_heading($heading[0], $heading[1], $heading[2], $heading[3], $heading[4]);
            break;
            case 6 :
                $this->_ci->table->set_heading($heading[0], $heading[1], $heading[2], $heading[3], $heading[4], $heading[5]);
            break;
            case 7 :
                $this->_ci->table->set_heading($heading[0], $heading[1], $heading[2], $heading[3], $heading[4], $heading[5], $heading[6]);
            break;
            case 8 :
                $this->_ci->table->set_heading($heading[0], $heading[1], $heading[2], $heading[3], $heading[4], $heading[5], $heading[6], $heading[7]);
            break;
            case 9 :
                $this->_ci->table->set_heading($heading[0], $heading[1], $heading[2], $heading[3], $heading[4], $heading[5], $heading[6], $heading[7], $heading[8]);
            break;
        }
        $number_rows = count($body);
        if($body)
            for($i=0;$i<$number_rows;$i++){
                $j=0;
                foreach($body[$i] as $key => $val){
                    //echo "$key => $val<br>";
                    $obj_data[$j] = $val;
                    $j++;
                }
                //Debug($obj_data);
                if(isset($obj_data[3]) && isset($obj_data[4]))
                    $name= $obj_data[3].' vs '.$obj_data[4];
                else
                    $name = '';

                //Check Column 7
                if(isset($obj_data[6])){
                    if($this->_ci->uri->segment(2) == "topweek" || $this->_ci->uri->segment(1) == "ranking" || $this->_ci->uri->segment(2) == "preview"){
                        $button = $obj_data[6];
                    }else{

                        /*if($this->_ci->uri->segment(1) == "match_predictor"){
                            //$button = $obj_data[6];
                            switch ($obj_data[6]) {
                                case 0 :
                                    $button = '<span class="label label-danger arrowed-in">Inactive</span>';
                                    break;
                                case 1 :
                                    $button = '<span class="label label-success arrowed">Active</span>';
                                    break;
                                case 2 :
                                    $button = '<span class="label label-warning">' . $language['waiting'] . '</span>';
                                    break;
                                case 3 :
                                    $button = '<span class="label label-info arrowed-in-right arrowed">' . $language['finish'] . '</span>';
                                    break;
                                default :
                                    $button = $obj_data[6];
                                    break;
                            }

                        }else{*/
                            if ($obj_data[6] == 1) {
                                $button = '<label><input name="switch-field-1" class="ace ace-switch ace-switch-4 btn-flat predictor_status" type="checkbox" value="1" data-id="' . $obj_data[0] . '" data-name="' . $name . '" checked>
                                        <span class="lbl green" id="status' . $obj_data[0] . '">Active</span>
                                    </label>';
                            } else if ($obj_data[6] == 0){
                                $button = '<label><input name="switch-field-1" class="ace ace-switch ace-switch-4 btn-flat predictor_status" type="checkbox" value="1" data-id="' . $obj_data[0] . '" data-name="' . $name . '">
                                        <span class="lbl red" id="status' . $obj_data[0] . '">Inactive</span>
                                    </label>';
                            }else{
                                                            
                                if ($obj_data[6] == 2)
                                    $button = '<span class="lbl center blue">FT</span>';
                                else if ($obj_data[6] == 3)
                                    $button = '<span class="lbl center yellow">Postp</span>';
                                else if ($obj_data[6] == 4)
                                    $button = '<span class="lbl center red">Calc</span>';
                            }
                        //}

                    }
                }

                if(($this->_ci->uri->segment(1) == "predictor_games") || ($this->_ci->uri->segment(1) == "match_predictor")){
                    switch ($obj_data[6]) {
                        case 0 :
                            $button = '<span class="label label-danger arrowed-in">Inactive</span>';
                            /*$button = '<label><input name="switch-field-1" class="ace ace-switch ace-switch-4 btn-flat predictor_status" type="checkbox" value="1" data-id="' . $obj_data[0] . '" data-name="' . $name . '">
                                        <span class="lbl"></span>
                                    </label>';*/
                        break;
                        case 1 :
                            $button = '<span class="label label-success arrowed">Active</span>';
                            /*$button = '<label><input name="switch-field-1" class="ace ace-switch ace-switch-4 btn-flat predictor_status" type="checkbox" value="1" data-id="' . $obj_data[0] . '" data-name="' . $name . '" checked>
                                        <span class="lbl"></span>
                                    </label>';*/
                        break;
                        case 2 :
                            $button = '<span class="label label-warning">' . $language['waiting'] . '</span>';
                         break;
                        case 3 :
                            $button = '<span class="label label-info arrowed-in-right arrowed">' . $language['finish'] . '</span>';
                         break;
                        default :
                            $button = $obj_data[6];
                        break;
                    }
                }

                if($link == 1)
                    $edit_btn = base_url($this->_ci->uri->segment(1).'/edit/'.$obj_data[0]);
                else
                    $edit_btn = '#';

                $button_action = '<div class="hidden-sm hidden-xs action-buttons">
								<a class="green" href="'.$edit_btn.'" data-rel="tooltip" title="แก้ไข">
                                    <i class="ace-icon fa fa-pencil bigger-130" ></i>
                                </a>
								<a class="red del-confirm" href="javascript:void(0);" id="bootbox-confirm'.$obj_data[0].'" data-value="'.$obj_data[0].'" data-name="'.$name.'" data-rel="tooltip" title="Delete">
								    <i class="ace-icon fa fa-trash-o bigger-130" ></i>
								</a>
							</div>
							<div class="hidden-md hidden-lg">
								<div class="inline position-relative">
									<button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown" data-position="auto">
		            					<i class="ace-icon fa fa-caret-down icon-only bigger-120"></i>
									</button>
									<ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">
									<li>
										<a href="'.$edit_btn.'" class="tooltip-success" data-rel="tooltip" title="แก้ไข">
								    	<span class="green">
											<i class="ace-icon fa fa-pencil-square-o bigger-120"></i>
										</span>
										</a>
									</li>
									<li>
										<a href="javascript:void(0);" id="bx-confirm'.$obj_data[0].'" class="tooltip-error del-confirm" data-value="'.$obj_data[0].'" data-name="'.$name.'"  data-rel="tooltip" title="ลบ">
											<span class="red">
												<i class="ace-icon fa fa-trash-o bigger-120"></i>
									    	</span>
										</a>
									</li>
									</ul>
								</div>
							</div>';
                $attributes = array('data-rel' => 'tooltip', 'title' => 'แก้ไข');

                $col1 = array('data' => $obj_data[0], 'class' => 'col-xs-1 center' );
                $col2 = array('data' => $obj_data[1], 'class' => 'col-xs-1 center');
                $col3 = array('data' => $obj_data[2], 'class' => 'col-xs-1 center');

                if($this->_ci->uri->segment(2) == "topweek" || $this->_ci->uri->segment(1) == "ranking"){
                    $col4 = array('data' => $obj_data[3], 'class' => 'col-xs-1 center');
                    $col5 = array('data' => $obj_data[5], 'class' => 'col-xs-1 center');
                    $col6 = array('data' => $obj_data[4], 'class' => 'col-xs-2 center');
                }else{
                    if($link == 1) {
                        $col4 = array('data' => anchor($edit_btn, $obj_data[3], $attributes), 'class' => 'col-xs-1 right', 'align' => 'right'); //Home
                        $col5 = array('data' => anchor($edit_btn, $obj_data[5], $attributes), 'class' => 'col-xs-1 center');    //Result
                        $col6 = array('data' => anchor($edit_btn, $obj_data[4], $attributes), 'class' => 'col-xs-1');           //Away
                    }else{

                        $data3 = (isset($obj_data[3])) ? $obj_data[3] : '';
                        $data4 = (isset($obj_data[4])) ? $obj_data[4] : '';
                        $data5 = (isset($obj_data[5])) ? $obj_data[5] : '';

                        $col4 = array('data' => $data3, 'class' => 'col-xs-1 center', 'align' => 'center'); //Home
                        $col5 = array('data' => $data4, 'class' => 'col-xs-1 center');    //Result
                        $col6 = array('data' => $data5, 'class' => 'col-xs-1');           //Away
                    }
                }

                if(isset($obj_data[6])) $col7 = array('data' => $button, 'class' => 'col-xs-1 center');
                //if(isset($obj_data[7])) $col8 = array('data' => $obj_data[7], 'class' => 'col-xs-1');

                if(isset($obj_data[7]))
                    $col8 = array('data' => $obj_data[7], 'class' => 'col-xs-1 center');
                else if(isset($heading[7])){
                    $col8 = array('data' => $button_action, 'class' => 'col-xs-2');
                    $j++;
                }

                if(isset($obj_data[8]))
                    $col9 = array('data' => $obj_data[8], 'class' => 'col-xs-2 center');
                else if(isset($heading[8])){
                    $col9 = array('data' => $button_action, 'class' => 'col-xs-2');
                    $j++;
                }
                /*if(isset($obj_data[9]))
                    $col9 = array('data' => $obj_data[9], 'class' => 'col-xs-1');
                else if(isset($heading[9])){
                    $col9 = array('data' => $button_action, 'class' => 'col-xs-1');
                    $j++;
                }*/
                switch($j){
                    case 9 : $this->_ci->table->add_row($col1, $col2, $col3, $col4, $col5, $col6, $col7, $col8, $col9); break;
                    case 8 : $this->_ci->table->add_row($col1, $col2, $col3, $col4, $col5, $col6, $col7, $col8); break;
                    case 7 : $this->_ci->table->add_row($col1, $col2, $col3, $col4, $col5, $col6, $col7); break;
                    case 6 : $this->_ci->table->add_row($col1, $col2, $col3, $col4, $col5, $col6); break;
                    case 5 : $this->_ci->table->add_row($col1, $col2, $col3, $col4, $col5); break;
                    default: $this->_ci->table->add_row($col1, $col2, $col3, $col4); break;
                }
            }
        //Debug($body);
        //die();

        // Generate table
        $html = $this->_ci->table->generate();
        return $html;
    }

    public function table_list_normal($property, $heading, $body, $caption = ''){
        $html = '';
        $obj_data = array();
        // $language = $this->_ci->lang->language;

        if($caption != '') $this->table->set_caption($caption);
        // Set a table template to specify the design of table layout
        //$table_property = array('table_open' => '<table id="dataTables-news" class="table-responsive table table-striped table-bordered table-hover ">');
        $table_property = array('table_open' => $property);

        $this->_ci->table->set_template($table_property);

        switch(count($heading)) {
            case 6 :
                $this->_ci->table->set_heading($heading[0], $heading[1], $heading[2], $heading[3], $heading[4], $heading[5]);
                break;
            case 7 :
                $this->_ci->table->set_heading($heading[0], $heading[1], $heading[2], $heading[3], $heading[4], $heading[5], $heading[6]);
                break;
            case 8 :
                $this->_ci->table->set_heading($heading[0], $heading[1], $heading[2], $heading[3], $heading[4], $heading[5], $heading[6], $heading[7]);
                break;
            case 9 :
                $this->_ci->table->set_heading($heading[0], $heading[1], $heading[2], $heading[3], $heading[4], $heading[5], $heading[6], $heading[7], $heading[8]);
                break;
        }


        $number_rows = count($body);
        if($body)
            for($i=0;$i<$number_rows;$i++){
                $j=0;
                foreach($body[$i] as $key => $val){
                    //echo "$key => $val<br>";
                    $obj_data[$j] = $val;
                    $j++;
                }
                //Debug($obj_data);

                $name= $obj_data[2].' vs '.$obj_data[3];

                if($obj_data[5] == 1){
                    //$button = 'Active';
                    $button = '<label><input name="switch-field-1" class="ace ace-switch ace-switch-4 btn-flat predictor_status" type="checkbox" value="1" data-id="'.$obj_data[0].'" data-name="'.$name.'" checked>
									<span class="lbl"></span>
								</label>';
                }else{
                    //$button = 'Inactive';
                    $button = '<label><input name="switch-field-1" class="ace ace-switch ace-switch-4 btn-flat predictor_status" type="checkbox" value="1" data-id="'.$obj_data[0].'" data-name="'.$name.'">
									<span class="lbl"></span>
								</label>';
                }

                $edit_btn = base_url($this->_ci->uri->segment(1).'/edit/'.$obj_data[0]);

                $button_action = '<div class="hidden-sm hidden-xs action-buttons">
								<a class="green" href="'.$edit_btn.'" data-rel="tooltip" title="'.$language["edit"].'">
				    				<i class="ace-icon fa fa-pencil bigger-130" ></i></a>

								<a class="red del-confirm" href="javascript:void(0);" id="bootbox-confirm'.$obj_data[0].'" data-value="'.$obj_data[0].'" data-name="'.$name.'" data-rel="tooltip" title="Delete">
								    <i class="ace-icon fa fa-trash-o bigger-130" ></i>
								</a>
							</div>';


                $col1 = array('data' => $obj_data[0], 'class' => 'col-xs-1 center' );
                $col2 = array('data' => $obj_data[1], 'class' => 'col-xs-1 center');
                $col3 = array('data' => $obj_data[2], 'class' => 'col-xs-1 center');
                $col4 = array('data' => $obj_data[3], 'class' => 'col-xs-2 center');
                $col5 = array('data' => $obj_data[4], 'class' => 'col-xs-1 center');
                if(isset($obj_data[5])) $col6 = array('data' => $button, 'class' => 'col-xs-1 center');

                //if(isset($obj_data[7])) $col8 = array('data' => $obj_data[7], 'class' => 'col-xs-1');

                if(isset($obj_data[6]))
                    $col7 = array('data' => $obj_data[6], 'class' => 'col-xs-2');
                else if(isset($heading[6])){
                    $col7 = array('data' => $button_action, 'class' => 'col-xs-2');
                    $j++;
                }

                if(isset($obj_data[7]))
                    $col8 = array('data' => $obj_data[7], 'class' => 'col-xs-2');
                else if(isset($heading[7])){
                    $col8 = array('data' => $button_action, 'class' => 'col-xs-2');
                    $j++;
                }
                /*if(isset($obj_data[9]))
                    $col9 = array('data' => $obj_data[9], 'class' => 'col-xs-1');
                else if(isset($heading[9])){
                    $col9 = array('data' => $button_action, 'class' => 'col-xs-1');
                    $j++;
                }*/

                switch($j){
                    case 9 : $this->_ci->table->add_row($col1, $col2, $col3, $col4, $col5, $col6, $col7, $col8, $col9); break;
                    case 8 : $this->_ci->table->add_row($col1, $col2, $col3, $col4, $col5, $col6, $col7, $col8); break;
                    case 7 : $this->_ci->table->add_row($col1, $col2, $col3, $col4, $col5, $col6, $col7); break;
                    case 6 : $this->_ci->table->add_row($col1, $col2, $col3, $col4, $col5, $col6); break;
                    default: $this->_ci->table->add_row($col1, $col2, $col3, $col4, $col5); break;
                }
            }
        //Debug($body);
        //die();

        // Generate table
        $html = $this->_ci->table->generate();
        return $html;
    }

    public function table_import($property, $heading, $body, $caption = ''){

        $html = '';
        
        if($caption != '') $this->table->set_caption($caption);
        // Set a table template to specify the design of table layout
        //$table_property = array('table_open' => '<table id="dataTables-news" class="table-responsive table table-striped table-bordered table-hover ">');
        $table_property = array('table_open' => $property);

        $this->_ci->table->set_template($table_property);

        $number_field = count($heading);

        if($heading){
            $this->_ci->table->set_heading($heading[0], $heading[1], $heading[2], $heading[3], $heading[4], $heading[5]);

            $number_rows = count($body);
            if($body)
                for($i=0;$i<$number_rows;$i++){

                    $id = $body[$i]['id'];
                    $season = $body[$i]['season'];
                    $name_team_home = $body[$i]['name_team_home'];
                    $result = $body[$i]['result'];
                    $name_team_away = $body[$i]['name_team_away'];
                    $kickoff = $body[$i]['kickoff'];

                    $col1 = array('data' => $id, 'class' => 'col-xs-1 center' );
                    $col2 = array('data' => $season, 'class' => 'col-xs-1 center');
                    $col3 = array('data' => $name_team_home, 'class' => 'col-xs-3 right', 'align' => 'right');
                    $col4 = array('data' => $result, 'class' => 'col-xs-1 center');
                    $col5 = array('data' => $name_team_away, 'class' => 'col-xs-3');
                    $col6 = array('data' => $kickoff, 'class' => 'col-xs-1');
                    $this->_ci->table->add_row($col1, $col2, $col3, $col4, $col5, $col6);

                }
            // Generate table
            $html = $this->_ci->table->generate();
        }
        return $html;
    }

    public function table_list_data($property, $heading, $body, $caption = ''){

        $html = '';
        $obj_data = array();
        $language = $this->_ci->lang->language;

        if($caption != '') $this->table->set_caption($caption);
        // Set a table template to specify the design of table layout
        //$table_property = array('table_open' => '<table id="dataTables-news" class="table-responsive table table-striped table-bordered table-hover ">');
        $table_property = array('table_open' => $property);

        $this->_ci->table->set_template($table_property);

        switch(count($heading)) {
            case 6 :
                $this->_ci->table->set_heading($heading[0], $heading[1], $heading[2], $heading[3], $heading[4], $heading[5]);
            break;
            case 7 :
                $this->_ci->table->set_heading($heading[0], $heading[1], $heading[2], $heading[3], $heading[4], $heading[5], $heading[6]);
            break;
            case 8 :
                $this->_ci->table->set_heading($heading[0], $heading[1], $heading[2], $heading[3], $heading[4], $heading[5], $heading[6], $heading[7]);
            break;
            case 9 :
                $this->_ci->table->set_heading($heading[0], $heading[1], $heading[2], $heading[3], $heading[4], $heading[5], $heading[6], $heading[7], $heading[8]);
            break;
        }


        $number_rows = count($body);
        if($body)
            for($i=0;$i<$number_rows;$i++){
                $j=0;
                foreach($body[$i] as $key => $val){
                    //echo "$key => $val<br>";
                    $obj_data[$j] = $val;
                    $j++;
                }
                //Debug($obj_data);

                $name= $obj_data[2].' vs '.$obj_data[3];

                $col1 = array('data' => $obj_data[0], 'class' => 'col-xs-1 center' );
                $col2 = array('data' => $obj_data[1], 'class' => 'col-xs-1 center');
                $col3 = array('data' => $obj_data[2], 'class' => 'col-xs-1 center');
                $col4 = array('data' => $obj_data[3], 'class' => 'col-xs-2 center');
                $col5 = array('data' => $obj_data[4], 'class' => 'col-xs-1 center');
                $col6 = array('data' => $obj_data[5], 'class' => 'col-xs-1 center');
                $col7 = array('data' => $obj_data[6], 'class' => 'col-xs-2');

                if(isset($obj_data[7])) {
                    $col8 = array('data' => $obj_data[7], 'class' => 'col-xs-2');
                }else if(isset($heading[7])){
                    $col8 = array('data' => $obj_data[7], 'class' => 'col-xs-2');
                    $j++;
                }
                if(isset($obj_data[8])) $col9 = array('data' => $obj_data[8], 'class' => 'col-xs-2');

                switch($j){
                    case 9 : $this->_ci->table->add_row($col1, $col2, $col3, $col4, $col5, $col6, $col7, $col8, $col9); break;
                    case 8 : $this->_ci->table->add_row($col1, $col2, $col3, $col4, $col5, $col6, $col7, $col8); break;
                    case 7 : $this->_ci->table->add_row($col1, $col2, $col3, $col4, $col5, $col6, $col7); break;
                    case 6 : $this->_ci->table->add_row($col1, $col2, $col3, $col4, $col5, $col6); break;
                    default: $this->_ci->table->add_row($col1, $col2, $col3, $col4, $col5); break;
                }
            }
        // Generate table
        $html = $this->_ci->table->generate();
        return $html;
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

/* End of file Genarate.php */