/**
 * @license Copyright (c) 2003-2021, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see https://ckeditor.com/legal/ckeditor-oss-license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
	config.removePlugins = 'smiley,blockquote,wsc,scayt,save,flash,iframe,pagebreak,templates,about,showblocks,newpage,language,print,div';
    config.removeButtons = 'Print,Form,Maximize,BackgroundColor,Scayt,TextField,Textarea,Button,CreateDiv,PasteText,PasteFromWord,Select,HiddenField,Radio,Checkbox,ImageButton,Anchor,BidiLtr,BidiRtl,Font,Styles,Preview,Indent,Outdent';
};
