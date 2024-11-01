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
<link rel="stylesheet" media="all" href="<?php echo SPY_PLUGIN_URL ?>/css/player.css" />
<link rel="stylesheet" media="all" href="<?php echo SPY_PLUGIN_URL ?>/css/flags.css" />
<script type="text/javascript" src="//code.jquery.com/jquery-latest.min.js"></script>
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
	playdata.resonsetive = [];
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
	<?php if(isset($arr_data['resonsetive'])): ?>
		<?php
			$idx = -1;
			$uri = "";
			foreach ($arr_data['resonsetive'] as $key => $value) {
				if($uri != $value[0] && $value[6]==$session->session_id) {
					$idx++;
					$uri = $value[0];
				} ?>
				if(typeof playdata.resonsetive[<?php echo $idx; ?>] == "undefined") playdata.resonsetive[<?php echo $idx; ?>] = [];
				
				<?php 
				$v4 = "{";
				foreach ($value[4] as $k => $v) {
					$v4 .= "'".$k."':'".$v."',";	
				} $v4 .= "}";
				
				$v5 = "{";
				foreach ($value[5] as $k2 => $v2) {
					$v5 .= "'".$k2."':'".$v2."',";	
				} $v5 .= "}";
				
				?>
				playdata.resonsetive[<?php echo $idx; ?>].push(['<?php echo $value[1]."',".$value[2].",".$value[3].",".$v4.",".$v5;?>]);
				
			<?php 
			 } 
		?>
	<?php endif; ?>
var cs_sh = [];
	cs_sh[0]="backgroundAttachment";cs_sh[1]="backgroundClip";cs_sh[2]="backgroundColor";cs_sh[3]="backgroundImage";cs_sh[4]="backgroundOrigin";cs_sh[5]="backgroundPosition";cs_sh[6]="backgroundRepeat";cs_sh[7]="backgroundSize";cs_sh[8]="borderBottomColor";cs_sh[9]="borderBottomLeftRadius";cs_sh[10]="borderBottomRightRadius";cs_sh[11]="borderBottomStyle";cs_sh[12]="borderBottomWidth";cs_sh[13]="borderCollapse";cs_sh[14]="borderImageOutset";cs_sh[15]="borderImageRepeat";cs_sh[16]="borderImageSlice";cs_sh[17]="borderImageSource";cs_sh[18]="borderImageWidth";cs_sh[19]="borderLeftColor";cs_sh[20]="borderLeftStyle";cs_sh[21]="borderLeftWidth";cs_sh[22]="borderRightColor";cs_sh[23]="borderRightStyle";cs_sh[24]="borderRightWidth";cs_sh[25]="borderTopColor";cs_sh[26]="borderTopLeftRadius";cs_sh[27]="borderTopRightRadius";cs_sh[28]="borderTopStyle";cs_sh[29]="borderTopWidth";cs_sh[30]="bottom";cs_sh[31]="boxShadow";cs_sh[32]="boxSizing";cs_sh[33]="captionSide";cs_sh[34]="clear";cs_sh[35]="clip";cs_sh[36]="color";cs_sh[37]="cursor";cs_sh[38]="direction";cs_sh[39]="display";cs_sh[40]="emptyCells";cs_sh[41]="float";cs_sh[42]="fontFamily";cs_sh[43]="fontSize";cs_sh[44]="fontStyle";cs_sh[45]="fontVariant";cs_sh[46]="fontWeight";cs_sh[47]="height";cs_sh[48]="imageRendering";cs_sh[49]="left";cs_sh[50]="letterSpacing";cs_sh[51]="lineHeight";cs_sh[52]="listStyleImage";cs_sh[53]="listStylePosition";cs_sh[54]="listStyleType";cs_sh[55]="marginBottom";cs_sh[56]="marginLeft";cs_sh[57]="marginRight";cs_sh[58]="marginTop";cs_sh[59]="maxHeight";cs_sh[60]="maxWidth";cs_sh[61]="minHeight";cs_sh[62]="minWidth";cs_sh[63]="opacity";cs_sh[64]="orphans";cs_sh[65]="outlineColor";cs_sh[66]="outlineStyle";cs_sh[67]="outlineWidth";cs_sh[68]="overflowWrap";cs_sh[69]="overflowX";cs_sh[70]="overflowY";cs_sh[71]="paddingBottom";cs_sh[72]="paddingLeft";cs_sh[73]="paddingRight";cs_sh[74]="paddingTop";cs_sh[75]="pageBreakAfter";cs_sh[76]="pageBreakBefore";cs_sh[77]="pageBreakInside";cs_sh[78]="pointerEvents";cs_sh[79]="position";cs_sh[80]="resize";cs_sh[81]="right";cs_sh[82]="speak";cs_sh[83]="tableLayout";cs_sh[84]="tabSize";cs_sh[85]="textAlign";cs_sh[86]="textDecoration";cs_sh[87]="textIndent";cs_sh[88]="textRendering";cs_sh[89]="textShadow";cs_sh[90]="textOverflow";cs_sh[91]="textTransform";cs_sh[92]="top";cs_sh[93]="unicodeBidi";cs_sh[94]="verticalAlign";cs_sh[95]="visibility";cs_sh[96]="whiteSpace";cs_sh[97]="widows";cs_sh[98]="width";cs_sh[99]="wordBreak";cs_sh[100]="wordSpacing";cs_sh[101]="wordWrap";cs_sh[102]="zIndex";cs_sh[103]="zoom";cs_sh[104]="WebkitAnimationDelay";cs_sh[105]="WebkitAnimationDirection";cs_sh[106]="WebkitAnimationDuration";cs_sh[107]="WebkitAnimationFillMode";cs_sh[108]="WebkitAnimationIterationCount";cs_sh[109]="WebkitAnimationName";cs_sh[110]="WebkitAnimationPlayState";cs_sh[111]="WebkitAnimationTimingFunction";cs_sh[112]="WebkitAppearance";cs_sh[113]="WebkitBackfaceVisibility";cs_sh[114]="WebkitBackgroundClip";cs_sh[115]="WebkitBackgroundComposite";cs_sh[116]="WebkitBackgroundOrigin";cs_sh[117]="WebkitBackgroundSize";cs_sh[118]="WebkitBorderFit";cs_sh[119]="WebkitBorderHorizontalSpacing";cs_sh[120]="WebkitBorderImage";cs_sh[121]="WebkitBorderVerticalSpacing";cs_sh[122]="WebkitBoxAlign";cs_sh[123]="WebkitBoxDecorationBreak";cs_sh[124]="WebkitBoxDirection";cs_sh[125]="WebkitBoxFlex";cs_sh[126]="WebkitBoxFlexGroup";cs_sh[127]="WebkitBoxLines";cs_sh[128]="WebkitBoxOrdinalGroup";cs_sh[129]="WebkitBoxOrient";cs_sh[130]="WebkitBoxPack";cs_sh[131]="WebkitBoxReflect";cs_sh[132]="WebkitBoxShadow";cs_sh[133]="WebkitClipPath";cs_sh[134]="WebkitColorCorrection";cs_sh[135]="WebkitColumnBreakAfter";cs_sh[136]="WebkitColumnBreakBefore";cs_sh[137]="WebkitColumnBreakInside";cs_sh[138]="WebkitColumnAxis";cs_sh[139]="WebkitColumnCount";cs_sh[140]="WebkitColumnGap";cs_sh[141]="WebkitColumnProgression";cs_sh[142]="WebkitColumnRuleColor";cs_sh[143]="WebkitColumnRuleStyle";cs_sh[144]="WebkitColumnRuleWidth";cs_sh[145]="WebkitColumnSpan";cs_sh[146]="WebkitColumnWidth";cs_sh[147]="WebkitFilter";cs_sh[148]="WebkitAlignContent";cs_sh[149]="WebkitAlignItems";cs_sh[150]="WebkitAlignSelf";cs_sh[151]="WebkitFlexBasis";cs_sh[152]="WebkitFlexGrow";cs_sh[153]="WebkitFlexShrink";cs_sh[154]="WebkitFlexDirection";cs_sh[155]="WebkitFlexWrap";cs_sh[156]="WebkitJustifyContent";cs_sh[157]="WebkitFontKerning";cs_sh[158]="WebkitFontSmoothing";cs_sh[159]="WebkitFontVariantLigatures";cs_sh[160]="WebkitGridColumns";cs_sh[161]="WebkitGridRows";cs_sh[162]="WebkitGridColumn";cs_sh[163]="WebkitGridRow";cs_sh[164]="WebkitHighlight";cs_sh[165]="WebkitHyphenateCharacter";cs_sh[166]="WebkitHyphenateLimitAfter";cs_sh[167]="WebkitHyphenateLimitBefore";cs_sh[168]="WebkitHyphenateLimitLines";cs_sh[169]="WebkitHyphens";cs_sh[170]="WebkitLineAlign";cs_sh[171]="WebkitLineBoxContain";cs_sh[172]="WebkitLineBreak";cs_sh[173]="WebkitLineClamp";cs_sh[174]="WebkitLineGrid";cs_sh[175]="WebkitLineSnap";cs_sh[176]="WebkitLocale";cs_sh[177]="WebkitMarginBeforeCollapse";cs_sh[178]="WebkitMarginAfterCollapse";cs_sh[179]="WebkitMarqueeDirection";cs_sh[180]="WebkitMarqueeIncrement";cs_sh[181]="WebkitMarqueeRepetition";cs_sh[182]="WebkitMarqueeStyle";cs_sh[183]="WebkitMaskAttachment";cs_sh[184]="WebkitMaskBoxImage";cs_sh[185]="WebkitMaskBoxImageOutset";cs_sh[186]="WebkitMaskBoxImageRepeat";cs_sh[187]="WebkitMaskBoxImageSlice";cs_sh[188]="WebkitMaskBoxImageSource";cs_sh[189]="WebkitMaskBoxImageWidth";cs_sh[190]="WebkitMaskClip";cs_sh[191]="WebkitMaskComposite";cs_sh[192]="WebkitMaskImage";cs_sh[193]="WebkitMaskOrigin";cs_sh[194]="WebkitMaskPosition";cs_sh[195]="WebkitMaskRepeat";cs_sh[196]="WebkitMaskSize";cs_sh[197]="WebkitNbspMode";cs_sh[198]="WebkitOrder";cs_sh[199]="WebkitPerspective";cs_sh[200]="WebkitPerspectiveOrigin";cs_sh[201]="WebkitPrintColorAdjust";cs_sh[202]="WebkitRtlOrdering";cs_sh[203]="WebkitShapeInside";cs_sh[204]="WebkitShapeOutside";cs_sh[205]="WebkitTapHighlightColor";cs_sh[206]="WebkitTextCombine";cs_sh[207]="WebkitTextDecorationsInEffect";cs_sh[208]="WebkitTextEmphasisColor";cs_sh[209]="WebkitTextEmphasisPosition";cs_sh[210]="WebkitTextEmphasisStyle";cs_sh[211]="WebkitTextFillColor";cs_sh[212]="WebkitTextOrientation";cs_sh[213]="WebkitTextSecurity";cs_sh[214]="WebkitTextStrokeColor";cs_sh[215]="WebkitTextStrokeWidth";cs_sh[216]="WebkitTransform";cs_sh[217]="WebkitTransformOrigin";cs_sh[218]="WebkitTransformStyle";cs_sh[219]="WebkitTransitionDelay";cs_sh[220]="WebkitTransitionDuration";cs_sh[221]="WebkitTransitionProperty";cs_sh[222]="WebkitTransitionTimingFunction";cs_sh[223]="WebkitUserDrag";cs_sh[224]="WebkitUserModify";cs_sh[225]="WebkitUserSelect";cs_sh[226]="WebkitWritingMode";cs_sh[227]="WebkitFlowInto";cs_sh[228]="WebkitFlowFrom";cs_sh[229]="WebkitRegionOverflow";cs_sh[230]="WebkitRegionBreakAfter";cs_sh[231]="WebkitRegionBreakBefore";cs_sh[232]="WebkitRegionBreakInside";cs_sh[233]="WebkitAppRegion";cs_sh[234]="WebkitWrapFlow";cs_sh[235]="WebkitWrapMargin";cs_sh[236]="WebkitWrapPadding";cs_sh[237]="WebkitWrapThrough";cs_sh[238]="clipPath";cs_sh[239]="clipRule";cs_sh[240]="mask";cs_sh[241]="filter";cs_sh[242]="floodColor";cs_sh[243]="floodOpacity";cs_sh[244]="lightingColor";cs_sh[245]="stopColor";cs_sh[246]="stopOpacity";cs_sh[247]="colorInterpolation";cs_sh[248]="colorInterpolationFilters";cs_sh[249]="colorRendering";cs_sh[250]="fill";cs_sh[251]="fillOpacity";cs_sh[252]="fillRule";cs_sh[253]="markerEnd";cs_sh[254]="markerMid";cs_sh[255]="markerStart";cs_sh[256]="maskType";cs_sh[257]="shapeRendering";cs_sh[258]="stroke";cs_sh[259]="strokeDasharray";cs_sh[260]="strokeDashoffset";cs_sh[261]="strokeLinecap";cs_sh[262]="strokeLinejoin";cs_sh[263]="strokeMiterlimit";cs_sh[264]="strokeOpacity";cs_sh[265]="strokeWidth";cs_sh[266]="alignmentBaseline";cs_sh[267]="baselineShift";cs_sh[268]="dominantBaseline";cs_sh[269]="kerning";cs_sh[270]="textAnchor";cs_sh[271]="writingMode";cs_sh[272]="glyphOrientationHorizontal";cs_sh[273]="glyphOrientationVertical";cs_sh[274]="WebkitSvgShadow";cs_sh[275]="vectorEffect";
	
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
	
	function initIframe(){
		//check Init frame	
		jQuery('#spy-iframe').load(function(){
			//create mouse cursor if not exist
			if(!jQuery('#spy-iframe').contents().find("#spy-mouse").length > 0)
				jQuery('#spy-iframe').contents().find("body").append("<div id='spy-mouse' style='padding:0 !important; margin: 0 !important; position:absolute !important; width: 56px !important; height: 56px !important; background: url(<?php echo SPY_PLUGIN_URL; ?>/images/spy-cursor.png); z-index: 99998 !important;'></div>");
			//create mouse click label if not exist
			if(!jQuery('#spy-iframe').contents().find("#spy-mouse-click").length > 0)
				jQuery('#spy-iframe').contents().find("body").append("<div id='spy-mouse-click' style='padding:0 !important; margin: 0 !important; position:absolute !important; width: 76px !important; height: 22px !important; background: #000; z-index: 99999 !important; color:#fff !important; border: 1px #fff solid !important; font: 16px sans-serif !important; opacity: 0.6 !important; text-align: center !important'></div>");
			
			jQuery('#spy-iframe').contents().find("#spy-mouse-click").hide();
			jQuery('li.controll').show();
		});
	}
	
	//initialize player
	function mainPlay(){
		interval_init = false;
		initIframe();
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
		if(playdata.window_size[page][0][2] != undefined)
			jQuery(".spy-frame").css("margin-left","-"+(playdata.window_size[page][0][2]/2)+"px");
		else 
			jQuery(".spy-frame").css("margin-left","-"+(jQuery(".spy-frame").width()/2)+"px");
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
		 var color = (playdata.mouse_click[page][i][1] == 1)?"red":(playdata.mouse_click[page][i][1] == 3)?"blue":"";
		 if((playdata.mouse_click[page][i][0]*10) != 0){
		 	var obj = jQuery(".probress div").append('<img class="points" id="pointmc_'+i+'" src="<?php echo SPY_PLUGIN_URL; ?>/images/point'+color+'.gif" style="position: absolute; top: 4px;" />');
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
		
		resonsetive = [];
		if(playdata.resonsetive[page] != undefined)
		for (var i=0; i < playdata.resonsetive[page].length; i++) {
		  resonsetive[playdata.resonsetive[page][i][1]*10] = [playdata.resonsetive[page][i][0],playdata.resonsetive[page][i][4],true];
		  resonsetive[playdata.resonsetive[page][i][2]*10] = [playdata.resonsetive[page][i][0],playdata.resonsetive[page][i][3], false]
		};
	}
	var interval = 100,
	time = 0,
	max_time = 0,
	page = 0,
	speed = 1,
	totalpages = <?php echo count($arr_data['page'])-1 ?>,
	playflag = false,
	page_scroll = [],
	mouse_move = [],
	mouse_click = [],
	resonsetive = [],
	window_size = [],
	interval_id = null,
	interval_init = false;
	
	jQuery(document).ready(function() {	
	
		//set pages list event
		jQuery("#play_page").change(function(){
			jQuery(".controll a").css("background-position","50% -2px");
			jQuery("div.spy-frame").animate({ opacity: 1 });
			playflag = false;
			time = 0;
			jQuery('#progress-bar').css("width",(jQuery('#progress-bar').parent().width()/(max_time*10)*time)+"px");
			page = jQuery(this).val();
			jQuery('li.controll').hide();
			jQuery('#spy-iframe').attr("src",pageurl[page]);	
			interval_id = setInterval(function(){
				if(jQuery('#spy-iframe').contents().find("body").width() > 0){
					mainPlay();
					clearInterval(interval_id);
				}
			},300)
		});
		//set speed change event
		jQuery("#play_speed").change(function(){
			speed = jQuery(this).val();
		});
		jQuery(".spy-frame").css("margin-left","-"+(jQuery(".spy-frame").width()/2)+"px");
		var start = 0;
		//prepare player
		interval_id = setInterval(function(){
			if(jQuery('#spy-iframe').contents().find("body").width() > 0){
				mainPlay();
				clearInterval(interval_id);
			}
		},300)
		
		jQuery('li.controll').hide()
		//player controll
		jQuery(".controll a").click(function(){
			
			//when click play button
			if(!playflag) {
				playflag = true;
				jQuery("div.spy-frame").animate({ opacity: 0 }); 
				jQuery(this).css("background-position","50% -34px");
				jQuery(".spy-frame").css("width",playdata.window_size[page][0][2]+"px");
				jQuery(".spy-frame").css("margin-left","-"+(playdata.window_size[page][0][2]/2)+"px");
				jQuery(".spy-frame").css("height",playdata.window_size[page][0][1]+"px");
				jQuery("#winsize").text(playdata.window_size[page][0][2]+" x "+playdata.window_size[page][0][1]);
				jQuery('spy-iframe').contents().find("html, body").animate({
				    scrollTop: 0,
				    scrollLeft: 0
				 }, 100);
			}
			//when click stop button
			else {
				jQuery("div.spy-frame").animate({ opacity: 1 });
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
		var speedCounter = 1;
		setInterval(function(){
			//play scroll
			if(page_scroll[time+1] != undefined && playflag){
				jQuery('#spy-iframe').contents().find("html, body").animate({
				    scrollTop: page_scroll[time+1][0],
				    scrollLeft: page_scroll[time+1][1]
				 }, 100*parseInt(speed));
			}
			//mouse move
			if(mouse_move[time+1] != undefined && playflag){
				jQuery('#spy-iframe').contents().find("#spy-mouse").animate({
				    left: mouse_move[time+1][0]-17,
				    top: mouse_move[time+1][1]-22
				 }, 100);
			}
			//mouse click
			if(mouse_click[time+1] != undefined && playflag){
				jQuery('#spy-iframe').contents().find("html, body").animate({
				    scrollTop: mouse_click[time+1][3],
				    scrollLeft: mouse_click[time+1][4]
				 }, 100*parseInt(speed));
				jQuery('#spy-iframe').contents().find("#spy-mouse").animate({
				    left: mouse_click[time+1][1]-17,
				    top: mouse_click[time+1][2]-22
				 }, 100*parseInt(speed));
				 
				var mouse = (mouse_click[time+1][0] == "1")?"left click":(mouse_click[time+1][0] == "3")?"right click":"unknown";
				
				jQuery('#spy-iframe').contents().find("#spy-mouse-click").text(mouse);
				jQuery('#spy-iframe').contents().find("#spy-mouse-click").css("left",mouse_click[time+1][1]+"px");
				jQuery('#spy-iframe').contents().find("#spy-mouse-click").css("top",mouse_click[time+1][2]+"px");
				setTimeout(function(){jQuery('#spy-iframe').contents().find("#spy-mouse-click").show(100); },100*parseInt(speed))
				setTimeout(function(){ jQuery('#spy-iframe').contents().find("#spy-mouse-click").hide(100) },2000+(100*parseInt(speed)));
			}
			//window size
			if(window_size[time+1] != undefined && playflag){
				jQuery(".spy-frame").css("width",window_size[time+1][1]+"px");
				jQuery(".spy-frame").css("margin-left","-"+(window_size[time+1][1]/2)+"px");
				jQuery(".spy-frame").css("height",window_size[time+1][0]+"px");
				jQuery("#winsize").text(window_size[time+1][1]+" x "+window_size[time+1][0]);
			}
			
			//super resonsetive
			if(resonsetive[time+1] != undefined && playflag){
				var addr=resonsetive[time+1][0].split(",");
				var el = jQuery('#spy-iframe').contents().find("body").get(0);
				
				var cs = {};
				jQuery.each( resonsetive[time+1][1], function( key, value ) {
					cs[cs_sh[key]] = value;
				});
				
				for (var i = addr.length - 1; i >= 0; i--){
				  if(addr[i] != ""){
				  	el = el.children[addr[i]];
				  }
				}
							
				if(resonsetive[time+1][2])
					setTimeout(function(){
						jQuery(el).css(cs); jQuery(el).parent().trigger("mouseover").trigger("mouseenter").trigger("mousemove");
					},100);
				else
					setTimeout(function(){jQuery(el).css(cs);},100);
			}
			//stop when finish
			if(playflag){
				if(max_time*10 == time) {
					if(page < totalpages){
						time = 0;
						playflag = false;
						setTimeout(function(){jQuery("div.spy-frame").animate({ opacity: 1 });},100*parseInt(speed));
						setTimeout(function(){
							page++;
							jQuery('#spy-iframe').attr("src",pageurl[page]);
							interval_id = setInterval(function(){
								if(!interval_init)
								jQuery('#spy-iframe').one("load",function(){
									jQuery("#play_page").val(page);
									jQuery("div.spy-frame").animate({ opacity: 0 }); 
									mainPlay();
									playflag = true;
									clearInterval(interval_id);
								});
								interval_init = true;								
							},300)
						},1000+100*parseInt(speed))
					} else {
						playflag = false;
						time = 0;
						setTimeout(function(){
							page = 0;
							jQuery("#play_page").val(page);
							jQuery('#spy-iframe').attr("src",pageurl[page]);	
							if(!interval_init)
							interval_id = setInterval(function(){
								jQuery('#spy-iframe').one("load",function(){
									mainPlay();
									clearInterval(interval_id);
								});
								interval_init = true;
							},300)
							
							jQuery("div.spy-frame").animate({ opacity: 1 }); 
							jQuery(".controll a").css("background-position","50% -2px");
							
							
						},1000+100*parseInt(speed));
					}
				} else 
					if(speed > speedCounter) {
						speedCounter++;
					} else {
						time+=1;
						speedCounter = 1;
					}
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
			<a href="#click" id="logo-btn"></a>
		</li>
		<li class="info">
			<span><?php
					//split user id
					$usrData = explode("~", $session->user_id);
					//geoid
					$objGeoIP = new GeoIP();
					$objGeoIP->search_ip($usrData[0]);
					$country = "not found";
    				if ($objGeoIP->found()) 
    				{
						$fclass = "flag-".$objGeoIP->getCountryCode(); 
    				}

			?><i class="<?php echo $fclass ?>"></i> <?php echo $usrData[1]; ?></span>
		</li>
		<li class="info">
			<span><select id="play_page">
				<?php echo $page_history; ?>
			</select></span>
		</li>
		<li class="info">
			<span><select style="width: 60px" id="play_speed">
				<option value="1">X 1</option>
				<option value="2">X0.5</option>
				<option value="10">X0.1</option>
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
	</ul>
</div>
<div class="spy-frame spy-cap" style="z-index: 2"></div>
<iframe id="spy-iframe" class="spy-frame" src="<?php echo $arr_data['page'][0]; ?>" name="spy-frame" frameborder="0" noresize="noresize"></iframe>
</body>
</html>