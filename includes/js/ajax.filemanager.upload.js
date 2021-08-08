function filemanager_uploads_files($, object_id) {

    $.fn.ready();
	'use strict';
    
    $("#sequentialupload").uploadFile({
        url:"/wp-content/plugins/file-manager/includes/upload.php?upload_dir="+object_id+"/",
        fileName:"myfile",
        sequential:true,
        sequentialCount:1,
        afterUploadAll: function(obj) {
            jQuery.ajax({    
                type: 'post',
                url: get_filemanager_ajax_url,
                data: {
                    'object_id': object_id,
                    'link': location.protocol + '//' + location.host + location.pathname,
                    'action': 'get_filemanager_files'
                },
                dataType: 'json',
                success: function(data){
                    console.log(data);
                    $( '.filemanager-wrapper' ).empty();		
                    $('.filemanager-wrapper').append(data);
                    filemanager_delete_files($, object_id);
                    filemanager_createdir_files($, object_id);
                },
                error: function(errorThrown){
                    //error stuff here.text
                }
            });
        }
    }); 
    console.log(object_id);
        
}

jQuery(document).ready(function($) {
    filemanager_uploads_files($, $("#sequentialupload").data('object-id'));
});