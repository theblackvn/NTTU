/**
 * @license Copyright (c) 2003-2014, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';

	config.allowedContent = true;
	config.protectedSource.push(/<i[^>]*><\/i>/g);
	config.toolbar = 'MyToolbar';
	config.filebrowserBrowseUrl = amz_root_link + '/web/backend/plugins/bower_components/cke-editor/kcfinder/browse.php?opener=ckeditor&type=files';
	config.filebrowserImageBrowseUrl = amz_root_link + '/web/backend/plugins/bower_components/cke-editor/kcfinder/browse.php?opener=ckeditor&type=images';
	config.filebrowserFlashBrowseUrl = amz_root_link + '/web/backend/plugins/bower_components/cke-editor/kcfinder/browse.php?opener=ckeditor&type=flash';
	config.filebrowserUploadUrl = amz_root_link + '/web/backend/plugins/bower_components/cke-editor/kcfinder/upload.php?opener=ckeditor&type=files';
	config.filebrowserImageUploadUrl = amz_root_link + '/web/backend/plugins/bower_components/cke-editor/kcfinder/upload.php?opener=ckeditor&type=images';
	config.filebrowserFlashUploadUrl = amz_root_link + '/web/backend/plugins/bower_componentscke-editor/kcfinder/upload.php?opener=ckeditor&type=flash';
};
