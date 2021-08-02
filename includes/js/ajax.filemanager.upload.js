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
                    'action': 'get_filemanager_files'
                },
                dataType: 'json',
                success: function(data){
                    console.log(data);
                    $( '.filemanager-wrapper' ).empty();		
                    $('.filemanager-wrapper').append(data);
                    setTimeout(function(){ filemanager_get_files($); }, 1000);
                    setTimeout(function(){ filemanager_back_files($); }, 1000);
                    setTimeout(function(){ filemanager_read_files($); }, 1000);
                    setTimeout(function(){ filemanager_uploads_files($, object_id); }, 1000);
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