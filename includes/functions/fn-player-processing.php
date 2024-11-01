<?php
/*
 * Spy Analytics
 * (c) 2013. Aleksej Sytnik
 */
?>
<?php
	//check if user can see this page
	if ( !is_user_logged_in() ) {
		die("Login to continue");	
	}
	global $wpdb;
	$option = get_option('spy_analytics_plugin');
	$table = $wpdb->prefix.$option['dbtable_name'];
    $query = "SELECT * FROM $table WHERE id = ".$_GET['session'];
	$session = $wpdb->get_row($query);
	//extract viewed pages
	$page_history = "";
	$arr_data = unserialize($session->session_spydata);
	//build pages list
	foreach ($arr_data['page'] as $key => $value) {
		$str = explode("/", $value);
		if($str[count($str)-1] != "")
			$page_history .= '<option value="'.$key.'">'.$str[count($str)-1].'</option>';
		else
			$page_history .= '<option value="'.$key.'">'.$str[count($str)-2].'</option>';
	}

?>
<!doctype html> 
<html lang="en">
<head>
<title> S: #<?php echo $session->session_id; ?> | Spy View</title>
<?php
wp_head();
?>
<script type="text/javascript">
	var pageurl = [];
	<?php
	//JS array to get URL by id
	foreach ($arr_data['page'] as $key => $value) { ?>
		pageurl[<?php echo $key; ?>] = "<?php echo $value; ?>";
	<?php }
	?>
	//main JS object for recorded data
	var playdata = new Object();
	playdata.mouse_move = [];
	playdata.page_scroll = [];
	playdata.mouse_click = [];
	playdata.window_size = [];
	<?php 
	//initialize JS object
	if(isset($arr_data['mouse_move'])): ?>
		<?php
			$idx = -1;
			$uri = "";
			foreach ($arr_data['mouse_move'] as $key => $value) {
				if($uri != $value[0] && $value[4]==$session->session_id) {
					$idx++;
					$uri = $value[0];
				} ?>
				if(typeof playdata.mouse_move[<?php echo $idx; ?>] == "undefined") playdata.mouse_move[<?php echo $idx; ?>] = [];
				playdata.mouse_move[<?php echo $idx; ?>].push([<?php echo $value[1].",".$value[2].",".$value[3]; ?>]);
			<?php }
		?>
	<?php endif; ?>
	<?php if(isset($arr_data['page_scroll'])): ?>
		<?php
			$idx = -1;
			$uri = "";
			foreach ($arr_data['page_scroll'] as $key => $value) { 
				if($uri != $value[0] && $value[4]==$session->session_id) {
					$idx++;
					$uri = $value[0];
				} ?>
				if(typeof playdata.page_scroll[<?php echo $idx; ?>] == "undefined") playdata.page_scroll[<?php echo $idx; ?>] = [];
				playdata.page_scroll[<?php echo $idx; ?>].push([<?php echo $value[1].",".$value[2].",".$value[3]; ?>]);
			<?php }
		?>
	<?php endif; ?>
	<?php if(isset($arr_data['mouse_click'])): ?>
		<?php
			$idx = -1;
			$uri = "";
			foreach ($arr_data['mouse_click'] as $key => $value) { 
				if($uri != $value[0] && $value[7]==$session->session_id) {
					$idx++;
					$uri = $value[0];
				} ?>
				if(typeof playdata.mouse_click[<?php echo $idx; ?>] == "undefined") playdata.mouse_click[<?php echo $idx; ?>] = [];
				playdata.mouse_click[<?php echo $idx; ?>].push([<?php echo $value[1].",".$value[2].",".$value[3].",".$value[4].",".$value[5].",".$value[6]; ?>]);
			<?php }
		?>
	<?php endif; ?>
	<?php if(isset($arr_data['window_size'])): ?>
		<?php
			$idx = -1;
			$uri = "";
			foreach ($arr_data['window_size'] as $key => $value) {
				if($uri != $value[0] && $value[4]==$session->session_id) {
					$idx++;
					$uri = $value[0];
				} ?>
				if(typeof playdata.window_size[<?php echo $idx; ?>] == "undefined") playdata.window_size[<?php echo $idx; ?>] = [];
				playdata.window_size[<?php echo $idx; ?>].push([<?php echo $value[1].",".$value[2].",".$value[3]; ?>]);
			<?php }
		?>
	<?php endif; ?>
	//convert seconds to HH:MM:SS format
	function secondsToTime(secs)
	{
	    var hours = Math.floor(secs / (60 * 60));
	    var divisor_for_minutes = secs % (60 * 60);
	    var minutes = Math.floor(divisor_for_minutes / 60);
	    var divisor_for_seconds = divisor_for_minutes % 60;
	    var seconds = Math.ceil(divisor_for_seconds);
	    if(hours < 10) hours = "0"+hours;
	    if(minutes < 10) minutes = "0"+minutes;
	    if(seconds < 10) seconds = "0"+seconds;
	    return hours+":"+minutes+":"+seconds;
	}
	//initialize player
	function mainPlay(){
		//initialize undefined arrays
		if(playdata.mouse_click[page] == undefined) {playdata.mouse_click[page] = [0]; playdata.mouse_click[page][0] = [0]}
		if(playdata.page_scroll[page] == undefined) {playdata.page_scroll[page]= [0]; playdata.page_scroll[page][0] = [0]}
		if(playdata.mouse_move[page] == undefined) {playdata.mouse_move[page] = [0]; playdata.mouse_move[page][0] = [0]}
		if(playdata.window_size[page] == undefined) {playdata.window_size[page] = [0]; playdata.window_size[page][0] = [0]}
		//find and set max time
		max_time = Math.max(playdata.window_size[page][playdata.window_size[page].length-1][0],playdata.mouse_click[page][playdata.mouse_click[page].length-1][0],playdata.page_scroll[page][playdata.page_scroll[page].length-1][0],playdata.mouse_move[page][playdata.mouse_move[page].length-1][0]);
		//show max time
		jQuery("#seqtime").text(secondsToTime((max_time*10-time)/10));
		//set iframe defaults
		jQuery(".spy-frame").css("width",playdata.window_size[page][0][2]+"px");
		jQuery(".spy-frame").css("margin-left","-"+(playdata.window_size[page][0][2]/2)+"px");
		jQuery(".spy-frame").css("height",playdata.window_size[page][0][1]+"px");
		jQuery("#winsize").text(playdata.window_size[page][0][2]+" x "+playdata.window_size[page][0][1]);
		//remove white lines from the progressbar if exist
		jQuery(".points").remove();
		//convert recorded data to separate objects
		//and add white lines to the progress bar
		page_scroll = [];
		for (var i=0; i < playdata.page_scroll[page].length; i++) {
		 	page_scroll[playdata.page_scroll[page][i][0]*10] = [playdata.page_scroll[page][i][1],playdata.page_scroll[page][i][2]];
		 	if((playdata.page_scroll[page][i][0]*10) != 0){
		 	var obj = jQuery(".probress div").append('<img class="points" id="pointps_'+i+'" src="<?php echo SPY_PLUGIN_URL; ?>/images/point.gif" style="position: absolute; top: 4px;" />');
			jQuery('#pointps_'+i).css("left",(jQuery('#progress-bar').parent().width()/(max_time*10))*(playdata.page_scroll[page][i][0]*10)+"px");
			}
		 };
		mouse_move = [];
		for (var i=0; i < playdata.mouse_move[page].length; i++) {
		  mouse_move[playdata.mouse_move[page][i][0]*10] = [playdata.mouse_move[page][i][1],playdata.mouse_move[page][i][2]];
		 if((playdata.mouse_move[page][i][0]*10) != 0){
		 	var obj = jQuery(".probress div").append('<img class="points" id="pointmm_'+i+'" src="<?php echo SPY_PLUGIN_URL; ?>/images/point.gif" style="position: absolute; top: 4px;" />');
			jQuery('#pointmm_'+i).css("left",(jQuery('#progress-bar').parent().width()/(max_time*10))*(playdata.mouse_move[page][i][0]*10)+"px");
			}
		};
		mouse_click = [];
		for (var i=0; i < playdata.mouse_click[page].length; i++) {
		  mouse_click[playdata.mouse_click[page][i][0]*10] = [playdata.mouse_click[page][i][1],playdata.mouse_click[page][i][2],playdata.mouse_click[page][i][3],playdata.mouse_click[page][i][4],playdata.mouse_click[page][i][5]];
		 if((playdata.mouse_click[page][i][0]*10) != 0){	
		 	var obj = jQuery(".probress div").append('<img class="points" id="pointmc_'+i+'" src="<?php echo SPY_PLUGIN_URL; ?>/images/point.gif" style="position: absolute; top: 4px;" />');
			jQuery('#pointmc_'+i).css("left",(jQuery('#progress-bar').parent().width()/(max_time*10))*(playdata.mouse_click[page][i][0]*10)+"px");
			}
		};
		window_size = [];
		for (var i=0; i < playdata.window_size[page].length; i++) {
		  window_size[playdata.window_size[page][i][0]*10] = [playdata.window_size[page][i][1],playdata.window_size[page][i][2],playdata.window_size[page][i][3]];
		 if((playdata.window_size[page][i][0]*10) != 0){	
		 	var obj = jQuery(".probress div").append('<img class="points" id="pointws_'+i+'" src="<?php echo SPY_PLUGIN_URL; ?>/images/point.gif" style="position: absolute; top: 4px;" />');
			jQuery('#pointws_'+i).css("left",(jQuery('#progress-bar').parent().width()/(max_time*10))*(playdata.window_size[page][i][0]*10)+"px");
			}
		};
	}
	var interval = 100,
	time = 0,
	max_time = 0,
	page = 0,
	playflag = false,
	page_scroll = [],
	mouse_move = [],
	mouse_click = [],
	window_size = [];
	
	jQuery(document).ready(function() {
		//set pages list event
		jQuery("#play_page").change(function(){
			jQuery(".controll a").css("background-position","50% -2px");
			playflag = false;
			time = 0;
			jQuery('#progress-bar').css("width",(jQuery('#progress-bar').parent().width()/(max_time*10)*time)+"px");
			page = jQuery(this).val();
			jQuery('#spy-iframe').attr("src",pageurl[page]);
			mainPlay();
		});
		//prepare player
		mainPlay();
		
		//player controll
		jQuery(".controll a").click(function(){
			//create mouse cursor if not exist
			if(!jQuery('#spy-iframe').contents().find("#spy-mouse").length > 0)
				jQuery('#spy-iframe').contents().find("body").append("<div id='spy-mouse' style='padding:0 !important; margin: 0 !important; position:absolute !important; width: 56px !important; height: 56px !important; background: url(<?php echo SPY_PLUGIN_URL; ?>/images/spy-cursor.png); z-index: 99998 !important;'></div>");
			//create mouse click label if not exist
			if(!jQuery('#spy-iframe').contents().find("#spy-mouse-click").length > 0)
				jQuery('#spy-iframe').contents().find("body").append("<div id='spy-mouse-click' style='padding:0 !important; margin: 0 !important; position:absolute !important; width: 76px !important; height: 22px !important; background: #000; z-index: 99999 !important; color:#fff !important; border: 1px #fff solid !important; font: 16px sans-serif !important; opacity: 0.6 !important; text-align: center !important'></div>");
			//hide mouse click label
			jQuery('#spy-iframe').contents().find("#spy-mouse-click").hide();
			//when click play button
			if(!playflag) {
				playflag = true;
				jQuery("div.spy-frame").removeClass("spy-cap");
				jQuery(this).css("background-position","50% -34px");
				jQuery(".spy-frame").css("width",playdata.window_size[page][0][2]+"px");
				jQuery(".spy-frame").css("margin-left","-"+(playdata.window_size[page][0][2]/2)+"px");
				jQuery(".spy-frame").css("height",playdata.window_size[page][0][1]+"px");
				jQuery("#winsize").text(playdata.window_size[page][0][2]+" x "+playdata.window_size[page][0][1]);
				jQuery('#spy-iframe').contents().find("html, body").animate({
				    scrollTop: 0,
				    scrollLeft: 0
				 }, 100);
			}
			//when click stop button
			else {
				jQuery("div.spy-frame").addClass("spy-cap");
				jQuery(this).css("background-position","50% -2px");
				playflag = false;
				time = 0;
				jQuery('#progress-bar').css("width",(jQuery('#progress-bar').parent().width()/(max_time*10)*time)+"px");
			}
			return false;
		});
		
		//progress bar click event
		jQuery(".probress div").click(function(e){
		   var parentOffset = jQuery(this).offset(); 
		   var relX = e.pageX - parentOffset.left;
		   if(playflag)
		   		time=Math.round((max_time*10)/jQuery(this).width()*relX);
		});
		
		
		//main timer to play user actions
		setInterval(function(){
			//play scroll
			if(page_scroll[time+1] != undefined && playflag){
				jQuery('#spy-iframe').contents().find("html, body").animate({
				    scrollTop: page_scroll[time+1][0],
				    scrollLeft: page_scroll[time+1][1]
				 }, 100);
			}
			//mouse move
			if(mouse_move[time+1] != undefined && playflag){
				jQuery('#spy-iframe').contents().find("#spy-mouse").animate({
				    left: mouse_move[time+1][0]-17,
				    top: mouse_move[time+1][1]-22
				 }, 100);
			}
			<?php if($option['opt_record_mouseclick']): ?>
			//mouse click
			if(mouse_click[time+1] != undefined && playflag){
				jQuery('#spy-iframe').contents().find("html, body").animate({
				    scrollTop: mouse_click[time+1][3],
				    scrollLeft: mouse_click[time+1][4]
				 }, 100);
				jQuery('#spy-iframe').contents().find("#spy-mouse").animate({
				    left: mouse_click[time+1][1]-17,
				    top: mouse_click[time+1][2]-22
				 }, 100);
				var mouse = (mouse_click[time+1][0] == "1")?"left click":(mouse_click[time+1][0] == "3")?"right click":"unknown";
				jQuery('#spy-iframe').contents().find("#spy-mouse-click").text(mouse);
				jQuery('#spy-iframe').contents().find("#spy-mouse-click").css("left",mouse_click[time+1][1]+"px");
				jQuery('#spy-iframe').contents().find("#spy-mouse-click").css("top",mouse_click[time+1][2]+"px");
				jQuery('#spy-iframe').contents().find("#spy-mouse-click").show(100);
				setTimeout(function(){ jQuery('#spy-iframe').contents().find("#spy-mouse-click").hide(100) },2000);
			}
			<?php endif; ?>
			//window size
			if(window_size[time+1] != undefined && playflag){
				jQuery(".spy-frame").css("width",window_size[time+1][1]+"px");
				jQuery(".spy-frame").css("margin-left","-"+(window_size[time+1][1]/2)+"px");
				jQuery(".spy-frame").css("height",window_size[time+1][0]+"px");
				jQuery("#winsize").text(window_size[time+1][1]+" x "+window_size[time+1][0]);
			}
			//stop when finish
			if(playflag){
				if(max_time*10 == time) {
					jQuery("div.spy-frame").addClass("spy-cap");
					jQuery(".controll a").css("background-position","50% -2px");
					playflag = false;
					time = 0;
				} else 
					time+=1;
				jQuery('#progress-bar').css("width",(jQuery('#progress-bar').parent().width()/(max_time*10)*time)+"px");
				jQuery("#seqtime").text(secondsToTime((max_time*10-time)/10));
			}
		},interval);
	});
</script>
</head>
<body style="background:#555">
<div id="spy-frame" class="spy-panel">
	<ul>
		<li class="logo">
			<a href="#"></a>
		</li>
		<li class="info">
			<span>S: #<?php echo $session->session_id; ?></span>
		</li>
		<li class="info">
			<span>U: #<?php echo $session->user_id; ?></span>
		</li>
		<li class="info">
			<span><select id="play_page">
				<?php echo $page_history; ?>
			</select></span>
		</li>
		<li class="controll">
			<a href="#"></a>
		</li>
		<li class="probress">
			<div>
				<img id="progress-bar" src="<?php echo SPY_PLUGIN_URL; ?>/images/progress.png" height="20" width="0" />
			</div>
		</li>
		<li class="info">
			<span id="seqtime"></span>
		</li>
		<li class="info">
			<span id="winsize"></span>
		</li>
		<li class="info rate">
			<a href="http://wordpress.org/support/view/plugin-reviews/spy-analytics-lite" target="_blank">Rate Spy Analytics</a>
		</li>
	</ul>
</div>
<div class="spy-frame spy-cap" style="z-index: 2"></div>
<iframe id="spy-iframe" class="spy-frame" src="<?php echo $arr_data['page'][0]; ?>" name="spy-frame" frameborder="0" noresize="noresize"></iframe>
</body>
</html>