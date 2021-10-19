
function filemanager_tree_get_files($) {    
    $('.isfolder').click(function(event) {
        event.preventDefault();
        event.stopPropagation();
        event.stopImmediatePropagation();

        var path = $(this).attr('path');
        var next = $(this).next();

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