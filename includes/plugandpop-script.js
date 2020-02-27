
var mask = document.getElementById('mask');
var dialog = document.getElementById('dialog');

function centerDialog() {
	var tWidth = document.getElementById('pop').offsetWidth;
	var border = pp_options.border;
	var margin = tWidth / 2 + Number(border);
	dialog.style.marginLeft = '-' + margin;
}

window.onload = function() {	
	mask.style.opacity = 1;	
	dialog.style.display = "block"; 	
	centerDialog();	

	setTimeout(function(){  
			dialog.style.opacity = 1; 	
	}, 500);
};

function hide_popup() {
	mask.style.opacity = 0;	
	mask.style.display = "none";

	dialog.style.opacity = 0;	
	dialog.style.display = "none";
}
