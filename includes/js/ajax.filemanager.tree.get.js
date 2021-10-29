
function filemanager_tree_get_files($) {    
    $('.isfolder').click(function(event) {
        event.preventDefault();
        event.stopPropagation();
        event.stopImmediatePropagation();

        var path = $(this).attr('path');
        var next = $(this).next();

        var link = location.protocol + '//' + location.host + location.pathname;
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

        if(next.hasClass('open')) {
            if(next.hasClass('close')) {
                next.removeClass('close');
            } else {
                next.addClass('close');
            }
        } else {
            jQuery.ajax({
                type: 'post',
                url: tree_get_filemanager_ajax_url,
                data: {
                    'path': path,
                    'link': link,
                    'treepath': treepath,
                    'url_Params': url_Params,
                    'sharekey': urlshare,
                    'action': 'tree_get_filemanager_files'
                },
                dataType: 'json',
                success: function(data){
                    console.log(data);
                    next.append(data);
                    next.addClass('open');
                    filemanager_tree_get_files($);
                },
                error: function(errorThrown){
                    //error stuff here.text
                }
            });
        }
    });
}

jQuery(document).ready(function($) {
    filemanager_tree_get_files($);
    var filetableheight = $('file-table').height();
    document.getElementById("treeview").style.height = filetableheight;
});