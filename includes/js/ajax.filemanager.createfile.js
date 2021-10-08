
function filemanager_createfile_files($) {
    $('.btnnewfile').on('click', function(event) {
        event.preventDefault();
        $("#errorlog").empty();	
        $("#subnav-content-file").toggleClass("subnav-content-display");

        $('.newfile').on('click', function(event) {
            event.preventDefault();

            var inputVal = document.getElementById("lnamefile").value;
            var link = location.protocol + '//' + location.host + location.pathname;
            var object_id = document.getElementById('sequentialupload').getAttribute('data-object-id');
            const queryString = window.location.search;
            const urlParams = new URLSearchParams(queryString);
            const urlhome = urlParams.get('home');
            const urlworkplace = urlParams.get('workplace');
            const urlpath = urlParams.get('path');
            var url_Params;
    
            if(urlhome != null){
                url_Params = 'home';
            }
            if(urlworkplace != null){
                url_Params = 'workplace';
            }
            if(urlpath != null){
                url_Params = 'path';
            }
            jQuery.ajax({
                type: 'post',
                url: createfile_filemanager_ajax_url,
                data: {
                    'inputVal': object_id+'/'+inputVal,
                    'action': 'createfile_filemanager_files'
                },
                dataType: 'json',
                success: function(data){
                    console.log(data);
                    jQuery.ajax({
                        type: 'post',
                        url: get_filemanager_ajax_url,
                        data: {
                            'object_id': object_id,
                            'link': link,
                            'urlParams': url_Params,
                            'action': 'get_filemanager_files'
                        },
                        dataType: 'json',
                        success: function(data){
                            console.log(data);
                            $( '.filemanager-wrapper' ).empty();		
                            $('.filemanager-wrapper').append(data);
                            filemanager_select_files($);
                            filemanager_uploads_files($);
                            filemanager_delete_files($);
                            filemanager_createfile_files($);
                            filemanager_createdir_files($);
                            filemanager_copy_files($);
                            filemanager_moveto_files($);
                            filemanager_zip_files($);
                            filemanager_rename_files($);
                            filemanager_info_files($);
                            filemanager_share_files($);	
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
            document.getElementById("lnamefile").value = '';
            $("#subnav-content-file").toggleClass("subnav-content-display");
        });
    });

}

jQuery(document).ready(function($) {
    filemanager_createfile_files($);
});