    
function filemanager_moveto_files($) {
    $('.btnmoveto').on('click', function(event) {
        event.preventDefault();
        $("#errorlog").empty();
        $("#subnav-content-moveto").toggleClass("subnav-content-display");
    });
    $('.moveto').on('click', function() {filemanager_moveto_ajax_files($)});
}

function filemanager_moveto_ajax_files($) {
    const path = [];
    var i = 0;
    var x = 0;
    var inputVal = document.getElementById("lnamemoveto").value;
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

    document.getElementById('lnamemoveto').value = object_id;

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
            url: moveto_filemanager_ajax_url,
            data: {
                'inputVal': inputVal,
                'path': path,
                'action': 'moveto_filemanager_files'
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
                if(data[0][0] != ''){
                    data.forEach(function(element, index) {
                        console.log(element);
                        $("#errorlog").append(element[0]+' ERROR');
                    });
                }
            },
            error: function(errorThrown){
                //error stuff here.text
            }
        });
    }
}

jQuery(document).ready(function($) {
    filemanager_moveto_files($);
});