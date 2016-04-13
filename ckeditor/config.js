/*
Copyright (c) 2003-2010, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.editorConfig = function( config )
{
	config.toolbar_Full =
[
    // [ 'Source'] ,
     // [ 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ] ,
    // [ 'Find','Replace','-','SelectAll','-','SpellChecker', 'Scayt' ],
	// [ 'Image','Table','HorizontalRule','Smiley','SpecialChar','PageBreak' ] ,
    '/',
     [ 'Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat' ] ,
     [ 'NumberedList','BulletedList','-', ] ,
     // [ 'Link','Unlink','Anchor' ] ,
	 // [ 'Maximize'],
	 // [ 'Format'],
     
    
];

	config.format_tags = 'p;h1;h2'
	
	config.format_p =
    {
        element: 'p',
        attributes:
        {
            'class': 'caption'
        }
    };

	config.format_h1 =
    {
        element: 'h1',
        attributes:
        {
            'class': 'header_style4'
        }
    };
	
	config.format_h2 =
    {
        element: 'h2',
        attributes:
        {
            'class': 'footer_style'
        }
    };
	
	

};
