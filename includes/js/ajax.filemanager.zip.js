
function filemanager_zip_files($) {
    $('.btnzip').on('click', function(event) {
        event.preventDefault();
        event.stopPropagation();
        event.stopImmediatePropagation();
        $("#errorlog").empty();	
        $("#subnav-content-zip").toggleClass("subnav-content-display");
    });
    $('.zipbtn').on('click', function() {filemanager_zip_ajax_files($)});
}

function filemanager_zip_ajax_files($) {
    const path = [];
    var i = 0;
    var x = 0
    var inputVal = document.getElementById("lnamezip").value;
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
    var url_Params, url_key;

    document.getElementById('lnamecopy').value = object_id;

    if(urlhome != null){
        url_Params = 'home';
        url_key = urlhome;
    }
    if(urlworkplace != null){
        url_Params = 'workplace';
        url_key = urlworkplace;
    }
    if(urlpath != null){
        url_Params = 'path';
        url_key = urlpath;
    }
    if(urlsharepath != null){
        url_Params = 'sharepath';
        url_key = urlsharepath;
    }

    $('.checkbox').each(function () {
        if($(this).is(':checked')){
            path[i] = $(this).attr('name');
        }
        i++;
    });

    if(x === 0) {
        jQuery.ajax({
            type: 'post',
            url: zip_filemanager_ajax_url,
            data: {
                'path': path,
                'inputVal': inputVal,
                'action': 'zip_filemanager_files'
            },
            dataType: 'json',
            beforeSend: function() {
                $("#loadzip").css('display', 'block');
            },
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
                        'treepath': treepath,
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
            },
            error: function(errorThrown){
                //error stuff here.text
            }
        });
    }
}

jQuery(document).ready(function($) {
	filemanager_zip_files($);
});