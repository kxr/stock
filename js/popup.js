var popupStatus = 0;
//loads popup only if it is disabled
function loadPopup(){
	if(popupStatus==0){
		$("#backgroundPopup").css({
			"opacity": "0.7"
			});

		$("#backgroundPopup").fadeIn("slow");
		$("#popupForm").fadeIn("slow");
		popupStatus = 1;
	}
}
//disables popup only if it is enabled
function disablePopup(){
	if(popupStatus==1){
		$("#backgroundPopup").fadeOut("slow");
		$("#popupForm").fadeOut("slow");
		popupStatus = 0;
	}
}
//centering popup
function centerPopup(){
	//request data for centering
	var windowWidth = document.documentElement.clientWidth;
	var windowHeight = document.documentElement.clientHeight;
	var popupHeight = $("#popupForm").height();
	var popupWidth = $("#popupForm").width();
	//centering
	$("#popupForm").css({
		"position": "absolute",
		"top": windowHeight/2-popupHeight/2,
		"left": windowWidth/2-popupWidth/2
	});
	//only need force for IE6
	$("#backgroundPopup").css({
		"height": windowHeight
	});
}
$(document).ready(function(){
	//LOADING POPUP  
	//Click the button event!  
	$("#button").click(function(){
		//centering with css
		centerPopup();
		//load popup
		loadPopup();
	});

	//CLOSING POPUP
	//Click the x event!
	$("#popupFormClose").click(function(){
	disablePopup();
	});
	//Click out event!
	$("#backgroundPopup").click(function(){
	disablePopup();
	});
	//Press Escape event!
	$(document).keypress(function(e){
	if(e.keyCode==27 && popupStatus==1){
	disablePopup();
	}
	});

});
