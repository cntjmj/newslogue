

// Constant Area
var MIN_PAGE_CONTENT_PULL_INTVAL = 5 * 60 * 1000; // 5 min




// Common functions
function jqAjaxGet(url, data, success, error, dataType, contentType) {
	jqAjax("GET", url, data, success, error, dataType, contentType);
}

function jqAjaxPost(url, data, success, error, dataType, contentType) {
	jqAjax("POST", url, data, success, error, dataType, contentType);
}

function jqAjaxPut(url, data, success, error, dataType, contentType) {
	jqAjax("PUT", url, data, success, error, dataType, contentType);
}

function jqAjaxDel(url, success, error, dataType, contentType) {
	jqAjax("DELETE", url, "", success, error, dataType, contentType);
}

function jqAjax(type, url, data, success, error, dataType, contentType) {
	if (dataType == null || dataType == "")
		dataType = "text";
	if (contentType == null || contentType == "")
		contentType = "application/x-www-form-urlencoded; charset=UTF-8";
	$.ajax({
		"type": type,
		"url": url,
		"data": data,
		"success": success,
		"error": error,
		"dataType": dataType,
		"contentType": contentType
	});
}

function getUrlParam(name){
    var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
    if (results != null && results[1] != null)
       return results[1];
    else
       return null;
}

// Document ready
$(document).ready(function() {

	// For headers elements
	var ntfmenu = $("#notification-dropdown-menu");
	if (ntfmenu.length > 0) {
		loadNtfMenu(5000);
	}

});

// Functions for header elements
function loadNtfMenu(intval) {

	// load notification menu content
	jqAjaxGet(
		"ajax/ajax_ntfmenu.php",
		"",
		function(data) {
			$("#notification-dropdown-menu").html(data);
			var ntfcount = $("#total-new-notification-count").attr("ntfcount");
			if (ntfcount > 0) {
				$(".icomo-notification").html('<i class="notification-superscript">'+(ntfcount>10?"10+":ntfcount)+'</i>');
			}
		}
	);

	// refresh menu content if intval provided
	if (intval > 0) {
		if (intval < MIN_PAGE_CONTENT_PULL_INTVAL)
			intval = MIN_PAGE_CONTENT_PULL_INTVAL;
		setInterval("loadNtfMenu(0)", intval);
	}
}

// Functions for debate page
function debatePageTasks() {
	highlightReply();
	// others tasks if any ...
}

function highlightReply() {
	var prid = getUrlParam("prid");
	var rid = getUrlParam("rid");
	var lid = getUrlParam("lid");
	
	if (rid > 0 || lid > 0) {
		var replyID = rid>0?rid:lid;
		jqAjaxGet(
				"ajax/ajax_ntfmenu.php",
				{"replyID": replyID}
			);
	}
	
	if (prid > 0 && (rid == null || rid == 0)) {
		$(".reply"+prid).show();
		document.getElementById("debate-box-"+prid).scrollIntoView(true);
	} else if (prid > 0 && rid > 0) {
		$(".reply"+prid).show();
		$("#r2reply"+rid).css({"background":"#fff45f"});
		document.getElementById("r2reply"+rid).scrollIntoView(true);
	}
}
