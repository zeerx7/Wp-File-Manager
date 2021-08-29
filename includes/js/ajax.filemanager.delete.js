
function filemanager_delete_files($, object_id) {

    $.fn.ready();
	'use strict';
    
    $('.btndelete').on('click', function(event) {
        event.preventDefault();

        const path = [];
        var i = 0;
        var link = location.protocol + '//' + location.host + location.pathname;

        $('.checkbox').each(function () {
            if($(this).is(':checked')){
                console.log($(this).attr('name'));
                path[i] = $(this).attr('name');
                i++;
            }
        });
        

        jQuery.ajax({
            type: 'post',
            url: delete_filemanager_ajax_url,
            data: {
                'path': path,
                'action': 'delete_filemanager_files'
            },
            dataType: 'json',
            success: function(data){
                console.log(data);
                console.log(object_id);
                jQuery.ajax({
                    type: 'post',
                    url: get_filemanager_ajax_url,
                    data: {
                        'object_id': object_id,
                        'link': link,
                        'action': 'get_filemanager_files'
                    },
                    dataType: 'json',
                    success: function(data){
                        console.log(data);
                        $( '.filemanager-wrapper' ).empty();		
                        $('.filemanager-wrapper').append(data);
                        filemanager_delete_files($, object_id);
                        filemanager_createdir_files($, object_id);
                        filemanager_uploads_files($, object_id);
                        filemanager_select_files($);
                    },
                    error: function(errorThrown){
                        //error stuff here.text
                    }
                });
            },
            error: function(errorThrown){
                //error stuff here.text
            }
        });

    });

}

jQuery(document).ready(function($) {
	filemanager_delete_files($, $("#sequentialupload").data('object-id'));
});