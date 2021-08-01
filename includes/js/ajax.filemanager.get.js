
function filemanager_get_files($) {

    $.fn.ready();
	'use strict';
    
    $('.filemanager-click').on('click', function(event) {

        var $this = $(this),
        object_id = $this.data('object-id');

        jQuery.ajax({    
            type: 'post',
            url: get_filemanager_ajax_url,
            data: {
                'object_id': object_id,
                'action': 'get_filemanager_files'
            },
            dataType: 'json',
            success: function(data){
                console.log(data);
                $( '.filemanager-wrapper' ).empty();		
				$('.filemanager-wrapper').append(data);
                setTimeout(function(){ filemanager_get_files($); }, 1000);
                setTimeout(function(){ filemanager_read_files($); }, 1000);
            },
            error: function(errorThrown){
                //error stuff here.text
            }
        });

    });

}

jQuery(document).ready(function($) {
	filemanager_get_files($);
});