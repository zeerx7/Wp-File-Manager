
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
        if(urlshare != null){
            url_Params = 'share';
        }
        if(urlsharepath != null){
            url_Params = 'sharepath';
            var urlsharekey = urlshare;
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
                    'url_Params': url_Params,
                    'sharekey': urlsharekey,
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
    treeview = document.getElementById("treeview");
    if(treeview){
        treeview.style.height = filetableheight;
    }
});