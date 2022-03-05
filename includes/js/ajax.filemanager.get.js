function getCookie(cname) {
    let name = cname + "=";
    let ca = document.cookie.split(';');
    for(let i = 0; i < ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) == ' ') {
        c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
        return c.substring(name.length, c.length);
        }
    }
    return "";
}

function filemanager_get_files($) {         
    var Cookie = getCookie('uniqid');
    var link = location.protocol + '//' + location.host + location.pathname;
    const queryString = window.location.search;
    var object_id = document.getElementById('filemanagerpath').innerHTML;
    const urlParams = new URLSearchParams(queryString);
    const urlhome = urlParams.get('home');
    const urlworkplace = urlParams.get('workplace');
    const urlpath = urlParams.get('path');
    const urlshare = urlParams.get('share');
    const urlsharepath = urlParams.get('sharepath');
    const treepath = urlParams.get('treepath');
    const pages = urlParams.get('pages');
    var url_Params, url_key;
    var viewtype = $('#viewtype').val();
    var i = 0;

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
            'uniqid': Cookie,
            'viewtype': viewtype,
            'pages': pages,
            'action': 'get_filemanager_files'
        },
        dataType: 'json',
        success: function(data){
            console.log(object_id);
            $( '.file-table' ).empty();		
            $('.file-table').append(data);
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

}