﻿<?php
$ROOTDIR='..';
require("$ROOTDIR/base.php");
sendContentType();
openDocument();
?>
<style>
li{
	display:inline;
}
</style> 
<script type="text/javascript">
//<![CDATA[
var keynames = ['ENTER', 'LEFT', 'DOWN', 'PLAY', 'PAUSE', 'STOP', 'FAST_FWD', 'REWIND', 'BACK', '0', '5', '9', 'GREEN', 'YELLOW', 'RED'];
var keycodes = [];
var playpausecode = -1;
var nextidx = 0;
var isVisible = true;

window.onload = function() {
  menuInit();
  registerKeyEventListener();
  initApp();
	for (var i=0; i<keynames.length; i++) {
    keycodes[i] = -1;
    try {
      eval('keycodes['+i+'] = KeyEvent.VK_'+keynames[i]);
      // do not use: eval('keycodes['+i+'] = VK_'+keynames[i]);
    } catch (e) {
      // ignore
    }
  }
  try {
    playpausecode = KeyEvent.VK_PLAY_PAUSE;
  } catch (e) {
    // ignore
  }
  setKeyset(0x1+0x2+0x4+0x8+0x10+0x20+0x100);
	
	playVideo('application/dash+xml', 'http://???', true);
	
	mainVideo.init();
};

function handleKeyCode(kc) {
if(kc==VK_RED) {
		toggleAppVisible();
		return true;
	}
	if(!isVisible){
		return false;
	}
	if (kc==VK_LEFT) {
	menuSelect(selected-1);
    return true;
  }else if (kc==VK_RIGHT) {
    menuSelect(selected+1);
    return true;
  } else if (kc==VK_UP) {
    
    return true;
  }else if (kc==VK_DOWN) {
     
    return true;
  }else if(kc==VK_PAUSE){
    runStep('pause');
	menuSelect(1);
	return true;
  }else if(kc==VK_PLAY){
    runStep('play');
	menuSelect(0);
	return true;
  }else if(kc==VK_STOP){
    runStep('stop');
	return true;
  }else if (kc==VK_BACK || kc==88) {
    document.location.href = './menu.php';
    return true;
  } else if (kc==VK_ENTER) {
    //runStep('fullscreen');
	//menuSelect(2);
	var liid = opts[selected].getAttribute('name');
    runStep(liid);
    return true;
  }
  return false;
}

function runStep(name){
	if(name=='play'){
		mainVideo.play();
	}else if(name=='pause'){
		mainVideo.pause();
	}else if(name=='stop'){
		mainVideo.stop();
	}else if(name=='fullscreen'){
		if(mainVideo.isFullScreen()){
			mainVideo.smallScrreen();
		}else{
			mainVideo.fullScreen();
		}
	}else if(kc==VK_RED) {
		toggleAppVisible();
	};
};

var mainVideo = (function(){
	function getElement(){
		return html_element ? html_element : html_element = document.getElementById('video')
	}
	
	var html_element;

	return {
		init: function(){
			getElement();
		},
		fullScreen : function(){
			html_element.style.height = "720px";
			html_element.style.width = "1280px";
			html_element.style.left = "0px";
			html_element.style.top = "0px";
		},
		smallScrreen : function(){
			html_element.style.height = "360px";
			html_element.style.width = "640px";
			html_element.style.left = "320px";
			html_element.style.top = "180px";
		},
		isFullScreen : function(){
			return (html_element.style.height == "720px");
		},
		play : function(){
			html_element.play(1);
		},
		pause : function(){
			html_element.play(0);
		},
		stop : function(){
			html_element.stop();
		}
	}
})();

function playVideo(mtype, murl, registerlistener) {
  var elem = document.getElementById('vidcontainer');
  var ihtml = '<object id="video" type="'+mtype+'" style="position: absolute; left: 320px; top: 180px; width: 640px; height: 360px;"><'+'/object>';
  elem.innerHTML = ihtml;
  try {
    var videlem = document.getElementById('video');
    if (registerlistener) {
      videlem.onPlayStateChange = function() {
		if(videlem.playState == 5){
			document.location.href = './main.php';
		}
      };
    }
    videlem.data = murl;
    videlem.play(1);
  } catch (e) {
    showStatus(false, 'Setting the video object '+mtype+' failed.');
  }
  
}

function toggleAppVisible(){
	var curAppMgr = document.getElementById('appmgr').getOwnerApplication(document);
	if(isVisible){
		runStep('stop');
		var elem = document.getElementById('vidcontainer');
		elem.innerHTML = '';
		curAppMgr.hide();
		isVisible = false;
	}else{
		curAppMgr.show();
		playVideo('application/dash+xml', 'http://ebu.unified-streaming.com/hbbtv/hbbtv.isml/hbbtv.mpd', true);
		mainVideo.init();
		isVisible = true;
	}
};

//]]>
</script>

</head>

<body>

<div style="left: 0px; top: 0px; width: 1280px; height: 720px; background-image: url('gradient+texture-1.jpg');" />
<div class="txtdiv txtlg" style="left: 120px; top: 60px; width: 1000px; height: 30px;">MPEG DASH (LIVE): Merging Linear with On Demand</div>
<!--<div id="vidcontainer" style="left: 0px; top: 0px; width: 1280px; height: 720px;"></div>-->
<div id="vidcontainer" style="left: 0px; top: 0px; width: 1920px; height: 1080px;"></div>
<?php echo appmgrObject(); ?>

<ul id="menu" class="menu" style="left: 420px; top: 550px;">
	<li name='play' style="display:inline;width:300px;margin:20px"><img src="playbtn.gif" style="width:80px;height:130px;"/></li>
	<li name='pause' style="display:inline;width:300px;margin:20px"><img src="pausebtn.gif" style="width:80px;height:130px;"/></li>
	<!--<li name='stop' style="display:inline;width:300px;">stop</li>-->
	<li name='fullscreen' style="display:inline;width:300px;margin:20px"><img src="full.gif" style="width:80px;height:130px;"/></li>
</ul>
</body>
</html>
