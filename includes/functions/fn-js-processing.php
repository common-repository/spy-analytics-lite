<?php
/*
 * Spy Analytics
 * (c) 2013. Aleksej Sytnik
 */
?>
<?php
	//detect user info
	$broosArr = browser_detection("full");
	$broos =  browser_detection("os")." ".browser_detection("os_number");					

	if(browser_detection("browser") != "ie"){
		$broarr = browser_detection(browser_detection("browser")."_version");
		$broos .= "; ".$broarr[0]." ".$broarr[1];
	} else {
		$broos .= "; ie ".browser_detection("number");
	}
	
	//get user real IP
	$uip = getRealIp();
	
	//wp user detection
	$current_user = wp_get_current_user();
	if ( 0 == $current_user->ID ) {
		$reguser = "guest";
	} else {
		$reguser = $current_user->ID;
	}
	
	//remove wp_debug notices if they will appear
	//we do not need it in loaded JS
	ob_end_clean();
	//secure check $_GET variables
	$_GET = array_map('secure', $_GET);  
	$option = get_option('spy_analytics_plugin');
	header("Content-type: application/javascript");
	//check page we want to record
	if(!$option['opt_record_status']) die('//empty js file');
	if(!$option['opt_record_all'] && !in_array($_GET['i'], $option['opt_record_special'])) die('//empty js file');
	
?>
	var sh_cs = {};
	sh_cs["backgroundAttachment"]="0";sh_cs["backgroundClip"]="1";sh_cs["backgroundColor"]="2";sh_cs["backgroundImage"]="3";sh_cs["backgroundOrigin"]="4";sh_cs["backgroundPosition"]="5";sh_cs["backgroundRepeat"]="6";sh_cs["backgroundSize"]="7";sh_cs["borderBottomColor"]="8";sh_cs["borderBottomLeftRadius"]="9";sh_cs["borderBottomRightRadius"]="10";sh_cs["borderBottomStyle"]="11";sh_cs["borderBottomWidth"]="12";sh_cs["borderCollapse"]="13";sh_cs["borderImageOutset"]="14";sh_cs["borderImageRepeat"]="15";sh_cs["borderImageSlice"]="16";sh_cs["borderImageSource"]="17";sh_cs["borderImageWidth"]="18";sh_cs["borderLeftColor"]="19";sh_cs["borderLeftStyle"]="20";sh_cs["borderLeftWidth"]="21";sh_cs["borderRightColor"]="22";sh_cs["borderRightStyle"]="23";sh_cs["borderRightWidth"]="24";sh_cs["borderTopColor"]="25";sh_cs["borderTopLeftRadius"]="26";sh_cs["borderTopRightRadius"]="27";sh_cs["borderTopStyle"]="28";sh_cs["borderTopWidth"]="29";sh_cs["bottom"]="30";sh_cs["boxShadow"]="31";sh_cs["boxSizing"]="32";sh_cs["captionSide"]="33";sh_cs["clear"]="34";sh_cs["clip"]="35";sh_cs["color"]="36";sh_cs["cursor"]="37";sh_cs["direction"]="38";sh_cs["display"]="39";sh_cs["emptyCells"]="40";sh_cs["float"]="41";sh_cs["fontFamily"]="42";sh_cs["fontSize"]="43";sh_cs["fontStyle"]="44";sh_cs["fontVariant"]="45";sh_cs["fontWeight"]="46";sh_cs["height"]="47";sh_cs["imageRendering"]="48";sh_cs["left"]="49";sh_cs["letterSpacing"]="50";sh_cs["lineHeight"]="51";sh_cs["listStyleImage"]="52";sh_cs["listStylePosition"]="53";sh_cs["listStyleType"]="54";sh_cs["marginBottom"]="55";sh_cs["marginLeft"]="56";sh_cs["marginRight"]="57";sh_cs["marginTop"]="58";sh_cs["maxHeight"]="59";sh_cs["maxWidth"]="60";sh_cs["minHeight"]="61";sh_cs["minWidth"]="62";sh_cs["opacity"]="63";sh_cs["orphans"]="64";sh_cs["outlineColor"]="65";sh_cs["outlineStyle"]="66";sh_cs["outlineWidth"]="67";sh_cs["overflowWrap"]="68";sh_cs["overflowX"]="69";sh_cs["overflowY"]="70";sh_cs["paddingBottom"]="71";sh_cs["paddingLeft"]="72";sh_cs["paddingRight"]="73";sh_cs["paddingTop"]="74";sh_cs["pageBreakAfter"]="75";sh_cs["pageBreakBefore"]="76";sh_cs["pageBreakInside"]="77";sh_cs["pointerEvents"]="78";sh_cs["position"]="79";sh_cs["resize"]="80";sh_cs["right"]="81";sh_cs["speak"]="82";sh_cs["tableLayout"]="83";sh_cs["tabSize"]="84";sh_cs["textAlign"]="85";sh_cs["textDecoration"]="86";sh_cs["textIndent"]="87";sh_cs["textRendering"]="88";sh_cs["textShadow"]="89";sh_cs["textOverflow"]="90";sh_cs["textTransform"]="91";sh_cs["top"]="92";sh_cs["unicodeBidi"]="93";sh_cs["verticalAlign"]="94";sh_cs["visibility"]="95";sh_cs["whiteSpace"]="96";sh_cs["widows"]="97";sh_cs["width"]="98";sh_cs["wordBreak"]="99";sh_cs["wordSpacing"]="100";sh_cs["wordWrap"]="101";sh_cs["zIndex"]="102";sh_cs["zoom"]="103";sh_cs["WebkitAnimationDelay"]="104";sh_cs["WebkitAnimationDirection"]="105";sh_cs["WebkitAnimationDuration"]="106";sh_cs["WebkitAnimationFillMode"]="107";sh_cs["WebkitAnimationIterationCount"]="108";sh_cs["WebkitAnimationName"]="109";sh_cs["WebkitAnimationPlayState"]="110";sh_cs["WebkitAnimationTimingFunction"]="111";sh_cs["WebkitAppearance"]="112";sh_cs["WebkitBackfaceVisibility"]="113";sh_cs["WebkitBackgroundClip"]="114";sh_cs["WebkitBackgroundComposite"]="115";sh_cs["WebkitBackgroundOrigin"]="116";sh_cs["WebkitBackgroundSize"]="117";sh_cs["WebkitBorderFit"]="118";sh_cs["WebkitBorderHorizontalSpacing"]="119";sh_cs["WebkitBorderImage"]="120";sh_cs["WebkitBorderVerticalSpacing"]="121";sh_cs["WebkitBoxAlign"]="122";sh_cs["WebkitBoxDecorationBreak"]="123";sh_cs["WebkitBoxDirection"]="124";sh_cs["WebkitBoxFlex"]="125";sh_cs["WebkitBoxFlexGroup"]="126";sh_cs["WebkitBoxLines"]="127";sh_cs["WebkitBoxOrdinalGroup"]="128";sh_cs["WebkitBoxOrient"]="129";sh_cs["WebkitBoxPack"]="130";sh_cs["WebkitBoxReflect"]="131";sh_cs["WebkitBoxShadow"]="132";sh_cs["WebkitClipPath"]="133";sh_cs["WebkitColorCorrection"]="134";sh_cs["WebkitColumnBreakAfter"]="135";sh_cs["WebkitColumnBreakBefore"]="136";sh_cs["WebkitColumnBreakInside"]="137";sh_cs["WebkitColumnAxis"]="138";sh_cs["WebkitColumnCount"]="139";sh_cs["WebkitColumnGap"]="140";sh_cs["WebkitColumnProgression"]="141";sh_cs["WebkitColumnRuleColor"]="142";sh_cs["WebkitColumnRuleStyle"]="143";sh_cs["WebkitColumnRuleWidth"]="144";sh_cs["WebkitColumnSpan"]="145";sh_cs["WebkitColumnWidth"]="146";sh_cs["WebkitFilter"]="147";sh_cs["WebkitAlignContent"]="148";sh_cs["WebkitAlignItems"]="149";sh_cs["WebkitAlignSelf"]="150";sh_cs["WebkitFlexBasis"]="151";sh_cs["WebkitFlexGrow"]="152";sh_cs["WebkitFlexShrink"]="153";sh_cs["WebkitFlexDirection"]="154";sh_cs["WebkitFlexWrap"]="155";sh_cs["WebkitJustifyContent"]="156";sh_cs["WebkitFontKerning"]="157";sh_cs["WebkitFontSmoothing"]="158";sh_cs["WebkitFontVariantLigatures"]="159";sh_cs["WebkitGridColumns"]="160";sh_cs["WebkitGridRows"]="161";sh_cs["WebkitGridColumn"]="162";sh_cs["WebkitGridRow"]="163";sh_cs["WebkitHighlight"]="164";sh_cs["WebkitHyphenateCharacter"]="165";sh_cs["WebkitHyphenateLimitAfter"]="166";sh_cs["WebkitHyphenateLimitBefore"]="167";sh_cs["WebkitHyphenateLimitLines"]="168";sh_cs["WebkitHyphens"]="169";sh_cs["WebkitLineAlign"]="170";sh_cs["WebkitLineBoxContain"]="171";sh_cs["WebkitLineBreak"]="172";sh_cs["WebkitLineClamp"]="173";sh_cs["WebkitLineGrid"]="174";sh_cs["WebkitLineSnap"]="175";sh_cs["WebkitLocale"]="176";sh_cs["WebkitMarginBeforeCollapse"]="177";sh_cs["WebkitMarginAfterCollapse"]="178";sh_cs["WebkitMarqueeDirection"]="179";sh_cs["WebkitMarqueeIncrement"]="180";sh_cs["WebkitMarqueeRepetition"]="181";sh_cs["WebkitMarqueeStyle"]="182";sh_cs["WebkitMaskAttachment"]="183";sh_cs["WebkitMaskBoxImage"]="184";sh_cs["WebkitMaskBoxImageOutset"]="185";sh_cs["WebkitMaskBoxImageRepeat"]="186";sh_cs["WebkitMaskBoxImageSlice"]="187";sh_cs["WebkitMaskBoxImageSource"]="188";sh_cs["WebkitMaskBoxImageWidth"]="189";sh_cs["WebkitMaskClip"]="190";sh_cs["WebkitMaskComposite"]="191";sh_cs["WebkitMaskImage"]="192";sh_cs["WebkitMaskOrigin"]="193";sh_cs["WebkitMaskPosition"]="194";sh_cs["WebkitMaskRepeat"]="195";sh_cs["WebkitMaskSize"]="196";sh_cs["WebkitNbspMode"]="197";sh_cs["WebkitOrder"]="198";sh_cs["WebkitPerspective"]="199";sh_cs["WebkitPerspectiveOrigin"]="200";sh_cs["WebkitPrintColorAdjust"]="201";sh_cs["WebkitRtlOrdering"]="202";sh_cs["WebkitShapeInside"]="203";sh_cs["WebkitShapeOutside"]="204";sh_cs["WebkitTapHighlightColor"]="205";sh_cs["WebkitTextCombine"]="206";sh_cs["WebkitTextDecorationsInEffect"]="207";sh_cs["WebkitTextEmphasisColor"]="208";sh_cs["WebkitTextEmphasisPosition"]="209";sh_cs["WebkitTextEmphasisStyle"]="210";sh_cs["WebkitTextFillColor"]="211";sh_cs["WebkitTextOrientation"]="212";sh_cs["WebkitTextSecurity"]="213";sh_cs["WebkitTextStrokeColor"]="214";sh_cs["WebkitTextStrokeWidth"]="215";sh_cs["WebkitTransform"]="216";sh_cs["WebkitTransformOrigin"]="217";sh_cs["WebkitTransformStyle"]="218";sh_cs["WebkitTransitionDelay"]="219";sh_cs["WebkitTransitionDuration"]="220";sh_cs["WebkitTransitionProperty"]="221";sh_cs["WebkitTransitionTimingFunction"]="222";sh_cs["WebkitUserDrag"]="223";sh_cs["WebkitUserModify"]="224";sh_cs["WebkitUserSelect"]="225";sh_cs["WebkitWritingMode"]="226";sh_cs["WebkitFlowInto"]="227";sh_cs["WebkitFlowFrom"]="228";sh_cs["WebkitRegionOverflow"]="229";sh_cs["WebkitRegionBreakAfter"]="230";sh_cs["WebkitRegionBreakBefore"]="231";sh_cs["WebkitRegionBreakInside"]="232";sh_cs["WebkitAppRegion"]="233";sh_cs["WebkitWrapFlow"]="234";sh_cs["WebkitWrapMargin"]="235";sh_cs["WebkitWrapPadding"]="236";sh_cs["WebkitWrapThrough"]="237";sh_cs["clipPath"]="238";sh_cs["clipRule"]="239";sh_cs["mask"]="240";sh_cs["filter"]="241";sh_cs["floodColor"]="242";sh_cs["floodOpacity"]="243";sh_cs["lightingColor"]="244";sh_cs["stopColor"]="245";sh_cs["stopOpacity"]="246";sh_cs["colorInterpolation"]="247";sh_cs["colorInterpolationFilters"]="248";sh_cs["colorRendering"]="249";sh_cs["fill"]="250";sh_cs["fillOpacity"]="251";sh_cs["fillRule"]="252";sh_cs["markerEnd"]="253";sh_cs["markerMid"]="254";sh_cs["markerStart"]="255";sh_cs["maskType"]="256";sh_cs["shapeRendering"]="257";sh_cs["stroke"]="258";sh_cs["strokeDasharray"]="259";sh_cs["strokeDashoffset"]="260";sh_cs["strokeLinecap"]="261";sh_cs["strokeLinejoin"]="262";sh_cs["strokeMiterlimit"]="263";sh_cs["strokeOpacity"]="264";sh_cs["strokeWidth"]="265";sh_cs["alignmentBaseline"]="266";sh_cs["baselineShift"]="267";sh_cs["dominantBaseline"]="268";sh_cs["kerning"]="269";sh_cs["textAnchor"]="270";sh_cs["writingMode"]="271";sh_cs["glyphOrientationHorizontal"]="272";sh_cs["glyphOrientationVertical"]="273";sh_cs["WebkitSvgShadow"]="274";sh_cs["vectorEffect"]="275";
	
	//functions to work with cookies
	function setCookie(e,t,n){var r=new Date;r.setDate(r.getDate()+n);var i=escape(t)+(n==null?"":"; expires="+r.toUTCString());document.cookie=e+"="+i}
	function getCookie(e){var t,n,r,i=document.cookie.split(";");for(t=0;t<i.length;t++){n=i[t].substr(0,i[t].indexOf("="));r=i[t].substr(i[t].indexOf("=")+1);n=n.replace(/^\s+|\s+$/g,"");if(n==e){return unescape(r)}}return null}

	//serialize/unserialize array function
	function serialize(arr){
		var parts = [];
		var is_list = (Object.prototype.toString.apply(arr) === '[object Array]');
		for(var key in arr) {
			var value = arr[key];
		    if(typeof value == "object") { 
			    if(is_list) parts.push(serialize(value)); 
			    else parts[key] = serialize(value); 
			} else {
			    var str = "";
				if(!is_list) str = '"' + key + '":';
				if(typeof value == "number") str += value; 
				else if(value === false) str += 'false'; 
				else if(value === true) str += 'true';
				else str += '"' + value + '"'; 
			    parts.push(str);
			}
		}
		var json = parts.join(",");		
		if(is_list) return '[' + json + ']';
		return '{' + json + '}';
	}
	function unserialize(e){
		return jQuery.parseJSON(e);
	}

	function isiOS(){
	    return (
	        //Detect iPhone
	        (navigator.platform.indexOf("iPhone") != -1) ||
	        //Detect iPod
	        (navigator.platform.indexOf("iPod") != -1) ||
	        //Detect iPad
	        (navigator.platform.indexOf("iPad") != -1)
	    );
	}

	function addr(el, str){
		var address = str;
		if(jQuery(el)[0].nodeName!='BODY')
			return addr(jQuery(el).parent(),address+jQuery(el).index()+",");
		else 
			return address;
	}
	
	isActive = true;
	
	function initLogic(){
	jQuery(document).ready(function() {
		
		
		 jQuery.fn.getStyleObject = function(){
        var dom = this.get(0);
        var style;
        var returns = {};
        if(window.getComputedStyle){
            var camelize = function(a,b){
                return b.toUpperCase();
            }
            style = window.getComputedStyle(dom, null);
            for(var i=0;i < style.length;i++){
                var prop = style[i];
                var camel = prop.replace(/\-([a-z])/g, camelize);
                var val = style.getPropertyValue(prop);
                returns[camel] = val;
            }
            return returns;
        }
        if(dom.currentStyle){
            style = dom.currentStyle;
            for(var prop in style){
                returns[prop] = style[prop];
            }
            return returns;
        }
        return this.css();
    }
		
		//here you can change coockies name
		var cookie_name = "spy_analytics";
		//check if window/tab is active
   		jQuery(window).focus(function() { isActive = true;});
    	jQuery(window).blur(function() { isActive = false;});
		//dont record in iframe
		if(top !== self) return false;
		//get user id from the cookies
		//create new if not exist 
		var myVar = "<?php echo $uip."~".$broos."~".$reguser; ?>";
		//get session id from the cookies
		//create new if not exist 
		var session_data = getCookie(cookie_name+"_session");
		if (session_data == null) {
			session = [Math.floor((Math.random() * 1000000000) + 1), Math.floor(new Date().getTime()/1000)];
			setCookie(cookie_name+"_session", serialize(session), 365);
		} else {
			var now = Math.floor(new Date().getTime()/1000);
			session = unserialize(session_data);
			if((now-session[1]) > <?php print $option['opt_record_kill_session']; ?>){
				session = [Math.floor((Math.random() * 1000000000) + 1), Math.floor(new Date().getTime()/1000)];
				setCookie(cookie_name+"_session", serialize(session), 365);
			}
		}
		var lastmousex = 0, lastmousey = 0, lastscrollv = 0, lastscrollh = 0, lastwinh = 0, lastwinw = 0;
		var prevmousex = 0, prevmousey = 0, prevscrollv = 0, prevscrollh = 0, prevwinh = 0, prevwinw = 0;
		var mouse_move, mouse_click, page_scroll;
		var sendwhen = <?php print $option['opt_record_interval']; ?>*1000;
		var interval = 100;
		var time = 0;
		var sending = false;
		//timer for sending recorded actions to db
		//<script>
		setInterval(function() {
			//break when inactive current window/tab
			sending = true;
			var cur_sess_data = getCookie(cookie_name+"_session");
			var cur_sess = unserialize(cur_sess_data);
			//get recorded data from the cookies
			var send_mousemove =  unserialize(getCookie(cookie_name+"_buff_mouse_move")) || [];
			var send_pagescroll =  unserialize(getCookie(cookie_name+"_buff_page_scroll")) || [];
			var send_mouse_click =  unserialize(getCookie(cookie_name+"_buff_mouse_click")) || [];	
			var send_window_size =  unserialize(getCookie(cookie_name+"_buff_window_size")) || [];
			var send_resonsetive =  unserialize(getCookie(cookie_name+"_buff_super_resonsetive")) || [];
			
			//if we have new actions from the users, send it	
			if(!(send_mousemove.length == 0 && send_pagescroll.length == 0 && send_mouse_click.length == 0 && send_window_size.length == 0 && send_resonsetive.length == 0) && isActive){
				jQuery.post("<?php echo get_bloginfo( 'url' ); ?>/?spydata", {
					"user" : myVar,
					"page" : document.location.href,
					"session" : cur_sess[0],
					<?php if ($option['opt_record_mousemove']): ?>"mouse_move" : send_mousemove ,<?php endif; ?>
					<?php if ($option['opt_record_pagescroll']): ?>"page_scroll" : send_pagescroll ,<?php endif; ?>
					<?php if ($option['opt_record_mouseclick']): ?>"mouse_click" : send_mouse_click ,<?php endif; ?>
					"resonsetive" : send_resonsetive,
					"window_size" : send_window_size
				}, function(data) {
				}, "json");
				//set empty arrays to the cookies
				mouse_move = new Array(); setCookie(cookie_name+"_buff_mouse_move",serialize(mouse_move),365);
				page_scroll = new Array(); setCookie(cookie_name+"_buff_page_scroll",serialize(page_scroll),365);
				mouse_click = new Array(); setCookie(cookie_name+"_buff_mouse_click",serialize(mouse_click),365);
				window_size = new Array(); setCookie(cookie_name+"_buff_window_size",serialize(window_size),365);
				resonsetive = new Array(); setCookie(cookie_name+"_buff_super_resonsetive",serialize(resonsetive),365);
			} else {
			//if we do not have new actions from the user,
			//check session live time to create new if need
				var session_data = getCookie(cookie_name+"_session");
				if (session_data == null) {
					session = [Math.floor((Math.random() * 1000000000) + 1), Math.floor(new Date().getTime()/1000)];
					setCookie(cookie_name+"_session", serialize(session), 365);
				} else {
					var now = Math.floor(new Date().getTime()/1000);
					session = unserialize(session_data);
					if((now-session[1]) > <?php print $option['opt_record_kill_session']; ?>){
						session = [Math.floor((Math.random() * 1000000000) + 1), Math.floor(new Date().getTime()/1000)];
						setCookie(cookie_name+"_session", serialize(session), 365);
					}
				}
			}
			sending = false;
		}, sendwhen)
		//record window size
		prevwinw = prevwinh = 0;
		lastwinh = jQuery(window).height();
		lastwinw = jQuery(window).width();
		//timer for record user actions
		setInterval(function() {
			//get session id
			var cur_sess_data = getCookie(cookie_name+"_session");
			var cur_sess = unserialize(cur_sess_data);
			//if data was changed add it to array,
			//in this way we have optimized sending
			if((prevmousex != lastmousex || prevmousey != lastmousey) && !sending && <?php print ($option['opt_record_mousemove'])?'true':'false'; ?>){
				var mouse_move_buff = getCookie(cookie_name+"_buff_mouse_move");
				if(mouse_move_buff != null) mouse_move = unserialize(mouse_move_buff);
				else mouse_move = new Array();
				mouse_move.push([document.location.href,time.toFixed(1),lastmousex,lastmousey,cur_sess[0]]);
				setCookie(cookie_name+"_buff_mouse_move",serialize(mouse_move),365);
				prevmousex = lastmousex;
				prevmousey = lastmousey;
			}
			if((prevscrollv != lastscrollv || prevscrollh != lastscrollh) && !sending && <?php print ($option['opt_record_pagescroll'])?'true':'false'; ?>){
				var page_scroll_buff = getCookie(cookie_name+"_buff_page_scroll");
				if(page_scroll_buff != null) page_scroll = unserialize(page_scroll_buff);
				else page_scroll = new Array();
				page_scroll.push([document.location.href,time.toFixed(1),lastscrollv,lastscrollh,cur_sess[0]]);
				setCookie(cookie_name+"_buff_page_scroll",serialize(page_scroll),365)
				prevscrollv = lastscrollv;
				prevscrollh = lastscrollh;
			}
			if((prevwinw != lastwinw || prevwinh != lastwinh) && !sending){
				var window_size_buff = getCookie(cookie_name+"_buff_window_size");
				if(window_size_buff != null) window_size = unserialize(window_size_buff);
				else window_size = new Array();
				window_size.push([document.location.href,time.toFixed(1),lastwinh,lastwinw,cur_sess[0]]);
				setCookie(cookie_name+"_buff_window_size",serialize(window_size),365)
				prevwinw = lastwinw;
				prevwinh = lastwinh;
			}
			time += (interval/1000);
		}, interval)
		
		//mouse position
		jQuery("body").mousemove(function(e) {
			lastmousex = e.pageX;
			lastmousey = e.pageY;
		});
		
		//scroll
		jQuery(window).scroll(function(e) {
			lastscrollv = jQuery(document).scrollTop();
			lastscrollh = jQuery(document).scrollLeft();
		})
		
		//window resize
		jQuery(window).resize(function() {
			lastwinh = jQuery(window).height();
			lastwinw = jQuery(window).width();
		});
				
		//superResonsetive
		var cssObj = {};
		jQuery('a, li').each(function() {
			cssObj[addr(jQuery(this),"")] = jQuery(this).getStyleObject();
			jQuery(this).children().each(function(index, domEle) {
				cssObj[addr(jQuery(domEle),"")] = jQuery(domEle).getStyleObject();
			});
		})
		var lastHovered = {};
		jQuery('a, li').mouseenter(function() {
			
			//save hover
			var path = addr(jQuery(this),"");
			
			if(lastHovered[path] == undefined)
				lastHovered[path] = {}
			
			if(!lastHovered[path].hover){
				lastHovered[path].timeStart = time.toFixed(1);
				lastHovered[path].hover = true;
				lastHovered[path].ischanged = false;
				lastHovered[path].childs = {};
				lastHovered[path].el = jQuery(this);
				lastHovered[path].fn = function (pth, isTimer){
										
		  		lastHovered[pth].ischanged = false;
					
					//ELEMENT
					if(cssObj[pth] != undefined){
						var was = cssObj[pth];
						var now = lastHovered[pth].el.getStyleObject();
						jQuery.each( was, function( key, value ) {
				  			if(was[key] != now[key]){
				  				if(lastHovered[pth].changes == undefined)
				  				lastHovered[pth].changes = {}
				  				if(lastHovered[pth].default == undefined)
				  					lastHovered[pth].default = {}
				  				lastHovered[pth].changes[sh_cs[key]] = now[key];
				  				lastHovered[pth].default[sh_cs[key]] = was[key];
				  				lastHovered[pth].ischanged = true;
				  			}
						});
					}
					
					//CHILDREN
					lastHovered[pth].el.children().each(function( index, domEle) {
						var subpath = addr(jQuery(domEle),"");
						if(cssObj[subpath] != undefined){
							if(domEle.nodeName == "UL" || domEle.nodeName == "DIV"){
							var was2 = cssObj[subpath];
							var now2 = jQuery(domEle).getStyleObject();
							jQuery.each( was2, function( key, value ) {
				  				if(was2[key] != now2[key]){
									if(lastHovered[pth].childs[subpath] == undefined)
										lastHovered[pth].childs[subpath] = {}
				
				  					if(lastHovered[pth].childs[subpath].changes == undefined)
				  						lastHovered[pth].childs[subpath].changes = {}
				  		
				  					if(lastHovered[pth].childs[subpath].default == undefined)
				  						lastHovered[pth].childs[subpath].default = {}
				  	
				  					lastHovered[pth].childs[subpath].changes[sh_cs[key]] = now2[key];
				  					lastHovered[pth].childs[subpath].default[sh_cs[key]] = was2[key];
				  	
				  					lastHovered[pth].ischanged = true;
				  				}
							});
							}
						}
					});
					
					
					if(isTimer){
						if(!lastHovered[pth].ischanged){
							
							window.clearTimeout(lastHovered[pth].tmr);
							
							lastHovered[pth].timeEnd = time.toFixed(1);
							lastHovered[pth].hover = false;
							
							var cur_sess_data = getCookie(cookie_name+"_session");
							var cur_sess = unserialize(cur_sess_data);
				
							var responsetive_buff = getCookie(cookie_name+"_buff_super_resonsetive");
							if(responsetive_buff != null) responsetive = unserialize(responsetive_buff);
							else responsetive = new Array();
							
							if(lastHovered[pth].default != undefined)
								responsetive.push([document.location.href, pth, lastHovered[pth].timeStart, lastHovered[pth].timeEnd, lastHovered[pth].default, lastHovered[pth].changes, cur_sess[0] ]);
														
							jQuery.each( lastHovered[pth].childs, function( key, value ) {
								responsetive.push([document.location.href, key, lastHovered[pth].timeStart, lastHovered[pth].timeEnd, value.default, value.changes, cur_sess[0] ]);	
							});
							
							setCookie(cookie_name+"_buff_super_resonsetive",serialize(responsetive),365)
						}
					}
					
					
				console.log(pth+" - "+lastHovered[pth].ischanged);
					
					var self = this;
					if(lastHovered[pth].ischanged)
						lastHovered[pth].tmr = setTimeout(function(){self.fn(pth, true)},500);
				};
				lastHovered[path].fn(path);
				
			}								
			
		});
		
		//mouse clicks
		if(!isiOS())
		jQuery("body").click(function(event) {
			if(!sending){
				//get session id
				var cur_sess_data = getCookie(cookie_name+"_session");
				var cur_sess = unserialize(cur_sess_data);
				var mouse_click_buff = getCookie(cookie_name+"_buff_mouse_click");
				if(mouse_click_buff != null) mouse_click = unserialize(mouse_click_buff);
				else mouse_click = new Array();
				mouse_click.push([document.location.href,time.toFixed(1), event.which, lastmousex, lastmousey, lastscrollv, lastscrollh,cur_sess[0], jQuery(window).width()]);
				setCookie(cookie_name+"_buff_mouse_click",serialize(mouse_click),365);
			}
		});
		//touch in devices
		if(isiOS())
		$('body').bind( "touchstart", function(e){
			if(!sending){
				//get session id
				var cur_sess_data = getCookie(cookie_name+"_session");
				var cur_sess = unserialize(cur_sess_data);
				var mouse_click_buff = getCookie(cookie_name+"_buff_mouse_click");
				if(mouse_click_buff != null) mouse_click = unserialize(mouse_click_buff);
				else mouse_click = new Array();
				mouse_click.push([document.location.href, time.toFixed(1), event.which,  e.touches[0].pageX,  e.touches[0].pageY, lastscrollv, lastscrollh, cur_sess[0], jQuery(window).width()]);
				setCookie(cookie_name+"_buff_mouse_click",serialize(mouse_click),365);
			}
		});
	});
	}//initLogic()
function waitForJQuery() {
	if (typeof jQuery != 'undefined') { // JQuery is loaded!
		initLogic();
		return;
	}
	setTimeout(waitForJQuery, 100); // Check 0,1 a second
	return;
}
function checkjQuery() {
	if (typeof jQuery == 'undefined') { 
		var script = document.createElement('script');
		script.type = "text/javascript";
		script.src = "//code.jquery.com/jquery-latest.min.js";
		document.getElementsByTagName('head')[0].appendChild(script);
		waitForJQuery();
	} else {
		initLogic();
	}
}
if (window.addEventListener)
window.addEventListener("load", checkjQuery, false);
else if (window.attachEvent)
window.attachEvent("onload", checkjQuery);
