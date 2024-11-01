<?php
/*
 * Spy Analytics
 * (c) 2013. Aleksej Sytnik
 */
?>
<?php
//secure check $_POST variables
$_POST['session'] = secure($_POST['session']);
$_POST['user'] = secure($_POST['user']);

//save spy data to db
if(isset($_POST['session'])){
    $option = get_option('spy_analytics_plugin');
	global $wpdb;
	$table = $wpdb->prefix.$option['dbtable_name'];
	$session = $wpdb->get_row("SELECT * FROM $table WHERE session_id = '$_POST[session]'");
	$session_array = array();
	// if session number exist:
	if($session != null){
		if(isset($session->session_spydata)){
			$session_array = unserialize($session->session_spydata);
		}
		
		//add new records if exist in post
		if(isset($_POST['mouse_move']) && $_POST['mouse_move'] != ""){
			if(isset($session_array['mouse_move']))				
				$session_array['mouse_move'] = array_merge($session_array['mouse_move'], $_POST['mouse_move']);
			else 
				$session_array['mouse_move'] = $_POST['mouse_move'];
		}	
		if(isset($_POST['mouse_click']) && $_POST['mouse_click'] != ""){ 
			if(isset($session_array['mouse_click']))
				$session_array['mouse_click'] = array_merge($session_array['mouse_click'], $_POST['mouse_click']);
			else
				$session_array['mouse_click'] = $_POST['mouse_click'];
		}		
		if(isset($_POST['page_scroll'] ) && $_POST['page_scroll'] != ""){ 
			if(isset($session_array['page_scroll']))
				$session_array['page_scroll'] = array_merge($session_array['page_scroll'], $_POST['page_scroll']);
			else
				$session_array['page_scroll'] = $_POST['page_scroll'];
		}
				
		if(isset($_POST['window_size']) && $_POST['window_size'] != ""){
			if(isset($session_array['window_size']))
				$session_array['window_size'] = array_merge($session_array['window_size'], $_POST['window_size']);
			else
				$session_array['window_size'] = $_POST['window_size'];
		}
		if(isset($_POST['resonsetive']) && $_POST['resonsetive'] != ""){
			if(isset($session_array['resonsetive']))
				$session_array['resonsetive'] = array_merge($session_array['resonsetive'], $_POST['resonsetive']);
			else
				$session_array['resonsetive'] = $_POST['resonsetive'];
		}
		if(isset($_POST['page']))
			if(isset($session_array['page']) && $_POST['page'] != $session_array['page'][count($session_array['page'])-1])				
				$session_array['page'][] = $_POST['page'];
		//update db record
		$wpdb->update( 
			$table, 
			array(
				'session_end' => time(),
				'session_spydata' => serialize($session_array)
			), 
			array( 'session_id' => $_POST['session'] )); 
	}
	// if we have new session just insert new record
	else {
		if(isset($_POST['mouse_move'])) $session_array['mouse_move'] = $_POST['mouse_move'];
		if(isset($_POST['mouse_click'])) $session_array['mouse_click'] = $_POST['mouse_click'];
		if(isset($_POST['page_scroll'])) $session_array['page_scroll'] = $_POST['page_scroll'];
		if(isset($_POST['window_size'])) $session_array['window_size'] = $_POST['window_size'];
		if(isset($_POST['page'])) $session_array['page'] = array($_POST['page']);
		
		$q = "INSERT INTO `".$table."` (`user_id`,`session_id`,`session_start`,`session_end`,`session_time`,`session_spydata`) VALUES ('".$_POST['user']."','".$_POST['session']."','".time()."','".time()."','".time()."','".serialize($session_array)."')";
		$wpdb->query($q);
		/*$wpdb->insert(
			$table,
			array(
				'user_id' => "dafvnad",
				'session_id' => $_POST['session'],
				'session_start' => time(),
				'session_end' => time(),
				'session_time' => time(),
				'session_spydata' => serialize($session_array) 
			)
		);*/
	}
}
?>