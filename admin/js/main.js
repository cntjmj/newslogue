function trim(str, chars) {
    return ltrim(rtrim(str, chars), chars);
}

function ltrim(str, chars) {
    chars = chars || "\\s";
    return str.replace(new RegExp("^[" + chars + "]+", "g"), "");
}

function rtrim(str, chars) {
    chars = chars || "\\s";
    return str.replace(new RegExp("[" + chars + "]+$", "g"), "");
}

function MakeLinkSafe(){
    var e = document.getElementById('newsBannerSource');
    var f = document.getElementById('newsSource');
    str = trim(e.value);
    if(str.substr(0, 7) == 'http://'){
        e.value = str.substr(7);
    } else if (str.substr(0, 8) == 'https://'){
    	e.value = str.substr(8);
    }
    str = trim(f.value);
    if(str.substr(0, 7) == 'http://'){
        f.value = str.substr(7);
    } else if (str.substr(0, 8) == 'https://'){
    	f.value = str.substr(8);
    }

    return true;
}