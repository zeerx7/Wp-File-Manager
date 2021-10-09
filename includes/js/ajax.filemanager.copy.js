
function filemanager_copy_files($) {
    
    $('.btncopy').on('click', function(event) {
        event.preventDefault();
        $("#errorlog").empty();
        $("#subnav-content-copy").toggleClass("subnav-content-display");

        $('.copy').on('click', function(event) {
            event.preventDefault();

            const path = [];
            var i = 0;
            var inputVal = document.getElementById("lnamecopy").value;
            var link = location.protocol + '//' + location.host + location.pathname;
            var object_id = document.getElementById('sequentialupload').getAttribute('data-object-id');
            const queryString = window.location.search;
            const urlParams = new URLSearchParams(queryString);
            const urlhome = urlParams.get('home');
            const urlworkplace = urlParams.get('workplace');
            const urlpath = urlParams.get('path');
            const urlshare = urlParams.get('share');
            const urlsharepath = urlParams.get('sharepath');
            var url_Params;
           
            document.getElementById('lnamecopy').value = object_id;

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

            jQuery.ajax({
                type: 'post',
                url: copy_filemanager_ajax_url,
                data: {
                    'inputVal': inputVal,
                    'path': path,
                    'action': 'copy_filemanager_files'
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
                            'sharekey': urlshare,
                            'action': 'get_filemanager_files'
                        },
                        dataType: 'json',
                        success: function(data){
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
                    if(data) {
                        data.forEach(function(element, index) {
                            if(data[index][0] != null){
                                console.log(element);
                                $("#errorlog").append(element[0]+' '+element[1]+' ERROR');
                            }
                        });
                    }
                },
                error: function(errorThrown){
                    //error stuff here.text
                }
            });
        });
    });

}

jQuery(document).ready(function($) {
    filemanager_copy_files($);
});