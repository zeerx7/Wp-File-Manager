
function filemanager_delete_files($) {
    $('.btndelete').on('click', function(event) {
        event.preventDefault();
        $("#errorlog").empty();
        
        const path = [];
        var i = 0;
        var x = 0;
        var link = location.protocol + '//' + location.host + location.pathname;
        var object_id = document.getElementById('sequentialupload').getAttribute('data-object-id');
        const queryString = window.location.search;
        const urlParams = new URLSearchParams(queryString);
        const urlhome = urlParams.get('home');
        const urlworkplace = urlParams.get('workplace');
        const urlpath = urlParams.get('path');
        const urlshare = urlParams.get('share');
        const urlsharepath = urlParams.get('sharepath');
        const treepath = urlParams.get('treepath');
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
        if(urlsharepath != null){
            url_Params = 'sharepath';
        }

        $('.checkbox').each(function () {
            if($(this).is(':checked')){
                console.log($(this).attr('name'));
                path[i] = $(this).attr('name');
                i++;
            }
        });
        
        if(x === 0) {
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
                            'urlParams': url_Params,
                            'sharekey': urlshare,
                            'treepath': treepath,
                            'action': 'get_filemanager_files'
                        },
                        dataType: 'json',
                        success: function(data){
                            console.log(data);
                            $( '.filemanager-wrapper' ).empty();		
                            $('.filemanager-wrapper').append(data);
                            filemanager_select_files($);
                            filemanager_copy_files($);
                            filemanager_createdir_files($);
                            filemanager_createfile_files($);
                            filemanager_delete_files($);
                            filemanager_info_files($);
                            filemanager_moveto_files($);
                            filemanager_rename_files($);
                            filemanager_search_files($);
                            filemanager_share_files($);
                            filemanager_uploads_files($);
                            filemanager_zip_files($);
                            filemanager_tree_get_files($);
                            x++;
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
        }
    });
}

jQuery(document).ready(function($) {
	filemanager_delete_files($);
});