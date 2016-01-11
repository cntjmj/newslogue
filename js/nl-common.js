

// Config and Constant Area
var PRODCONFIG = {
	GLOBAL_API_BASE:		"http://api.newslogue.com/",
	GLOBAL_THUMBNAIL_BASE:	"http://www.newslogue.com/uploads/banner/thumbnail/"
};

var TESTCONFIG = {
	/**
	 * MUST CHANGE THESE SETTINGS
	 * ACCORDING TO YOUR TEST ENV,
	 * SO THAT YOUR SYSTEM COULD BE UP AND RUNNING.
	 *                        AFTER THAT, HAVE FUN!
	 */
	GLOBAL_API_BASE:		"http://api.nl.com/",
	GLOBAL_THUMBNAIL_BASE:	"http://www.nl.com/uploads/banner/thumbnail/"
};

var CONFIG = "";
if (location.host.toLowerCase().indexOf("newslogue.com") >= 0)
	CONFIG = PRODCONFIG;
else
	CONFIG = TESTCONFIG;

var CONSTS = {
	DEFAULT_NEWS_NUM_PER_PAGE: 5	
};

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
		"contentType": contentType,
		"xhrFields": {
			"withCredentials": true
		}
	});
}

function getUrlParam(name){
    var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
    if (results != null && results[1] != null)
       return results[1];
    else
       return null;
}
