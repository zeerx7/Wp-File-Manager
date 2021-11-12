
function filemanager_info_files($) {
    $('.btninfo').on('click', function(event) {
        event.preventDefault();
        event.stopPropagation();
        event.stopImmediatePropagation();
        $("#errorlog").empty();	

        const path = [];
        var i = 0;
        var x = 0;

        $('.checkbox').each(function () {
            if($(this).is(':checked')){
                path[i] = $(this).attr('name');
            }
            i++;
        });

        if(x === 0) {
            jQuery.ajax({
                type: 'post',
                url: info_filemanager_ajax_url,
                data: {
                    'path': path,
                    'action': 'info_filemanager_files'
                },
                dataType: 'json',
                success: function(data){
                    console.log(data);
                    $("#filemanager-wrapper").append(data);
                    $( ".info-window" ).each(function () {
                        $(this).draggable();
                    });
                    $('.info-window-close').on('click', function(event) {
                        event.preventDefault();
                        $(this).parent().remove();
                    });
                    x++;
                },
                error: function(errorThrown){
                    //error stuff here.text
                }
            });
        }
    });
}

jQuery(document).ready(function($) {
	filemanager_info_files($);
});