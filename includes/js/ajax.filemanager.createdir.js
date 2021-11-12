
function filemanager_createdir_files($) {
    $('.btnnewdir').on('click', function(event) {
        event.preventDefault();
        event.stopPropagation();
        event.stopImmediatePropagation();
        $("#errorlog").empty();	
        $("#subnav-content-dir").toggleClass("subnav-content-display");
    });
    $('.newdir').on('click', function() {filemanager_createdir_ajax_files($)});
}

function filemanager_createdir_ajax_files($) {
    var i = 0;
    var inputVal = document.getElementById("lname").value;
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

    if(i === 0) {
        jQuery.ajax({
            type: 'post',
            url: createdir_filemanager_ajax_url,
            data: {
                'inputVal': object_id+'/'+inputVal,
                'action': 'createdir_filemanager_files'
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
                        'urlkey': url_key,
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
                        i++;
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
        document.getElementById("lname").value = '';
        $("#subnav-content-dir").toggleClass("subnav-content-display");
    }
}

jQuery(document).ready(function($) {
	filemanager_createdir_files($);
});