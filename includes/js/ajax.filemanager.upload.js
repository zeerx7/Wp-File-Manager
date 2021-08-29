function filemanager_uploads_files($, object_id) {

    $.fn.ready();
	'use strict';
    
    $('#sequentialupload').uploadFile({
        url:"/wp-content/plugins/file-manager/includes/upload.php?upload_dir="+object_id+"/",
        fileName:"myfile",
        sequential:true,
        sequentialCount:1,
        showDone:true,
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
                    filemanager_uploads_files($, object_id);
                },
                error: function(errorThrown){
                    //error stuff here.text
                }
            });
        }
    }); 

    $('.file-table').on('drop', function(event) {
        event.preventDefault();  
        $(this).css('background', '#D8F9D3');
        files = event.originalEvent.dataTransfer.files;
        createFormData(files);
    });
    
    function createFormData(files_obj) {
        var form_data = new FormData();
        for(i=0; i<files_obj.length; i++) {
            form_data.append('myfile', files_obj[i]);
            uploadFormData(form_data, files_obj[i]);
            console.log(form_data);
        }
    }

    function makeid(length) {
        var result           = '';
        var characters       = '0123456789';
        var charactersLength = characters.length;
        for ( var i = 0; i < length; i++ ) {
            result += characters.charAt(Math.floor(Math.random() * charactersLength));
        }
        return result;
    }

    function uploadFormData(formData, files_obj) {
        var rand = makeid(6);
        $('#ajax-file-upload-container').append('<div id="progress_div'+rand+'" class="ajax-file-upload-statusbar" style="display:flex;width: 100%;margin: 15px 0;"><div class="bar" style="width: 100%;">'+files_obj.name+'</div><div class="percent" id="percent'+rand+'">0%</div><div class="ajax-file-upload-green done'+rand+'" id="done'+rand+'" style="display: none;margin: 0 15px;">Done</div></div>');
        var percent = document.getElementById('percent'+rand);
        $('#done'+rand).on('click', function(event) {
            event.preventDefault();
            var parent = $(this).parent().attr('id');
            document.getElementById(parent).remove();
        });
        $.ajax({
            xhr: function() {
                var xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener("progress", function(evt) {
                    if (evt.lengthComputable) {
                        var percentComplete = evt.loaded / evt.total;
                        percentComplete = parseInt(percentComplete * 100);
                        var percentVal = percentComplete + '%';
                        percent.innerHTML = percentVal;
                        if (percentComplete === 100) {
                           $('.done'+rand).css('display','block');
                        }
                    }
                }, false);
                return xhr;
            },
            url: "/wp-content/plugins/file-manager/includes/upload.php?upload_dir="+object_id+"/",
            type: "POST",
            data: formData,
            contentType:false,
            cache: false,
            processData: false,
            success: function(obj) {
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
                        const queryString = window.location.search;
                        const urlParams = new URLSearchParams(queryString);
                        const urlpath = urlParams.get('path');
                        if(object_id === urlpath || urlpath === null) {
                            $( '.filemanager-wrapper' ).empty();		
                            $('.filemanager-wrapper').append(data);
                        }
                        filemanager_delete_files($, object_id);
                        filemanager_createdir_files($, object_id);
                        filemanager_uploads_files($, object_id);
                    },
                    error: function(errorThrown){
                        //error stuff here.text
                    }
                });
            }});
    }

}

jQuery(document).ready(function($) {
    filemanager_uploads_files($, $('#sequentialupload').data('object-id'));
});