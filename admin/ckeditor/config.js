/*
Copyright (c) 2003-2011, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/
var link = "kcfinder/";
CKEDITOR.editorConfig = function( config )
{
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
    

   config.filebrowserBrowseUrl = link+'browse.php?type=files';
   config.filebrowserImageBrowseUrl = link+'browse.php?type=images';
   config.filebrowserFlashBrowseUrl = link+'browse.php?type=flash';
   config.filebrowserUploadUrl = link+'upload.php?type=files';
   config.filebrowserImageUploadUrl = link+'upload.php?type=images';
   config.filebrowserFlashUploadUrl = link+'upload.php?type=flash';
   
        
};
