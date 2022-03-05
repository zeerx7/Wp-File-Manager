
function filemanager_delete_files($) {
    $('.btndelete').on('click', function(event) {
        event.preventDefault();
        event.stopPropagation();
        event.stopImmediatePropagation();
        $("#errorlog").empty();
        
        const path = [];
        var i = 0;
        var x = 0;

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
                    filemanager_get_files($);
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