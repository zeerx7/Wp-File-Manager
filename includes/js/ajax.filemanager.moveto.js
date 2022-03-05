    
function filemanager_moveto_files($) {
    $('.btnmoveto').on('click', function(event) {
        event.preventDefault();
        event.stopPropagation();
        event.stopImmediatePropagation();
        $("#errorlog").empty();
        var object_id = document.getElementById('filemanagerpath').innerHTML;
        document.getElementById('lnamemoveto').value = object_id;
        $("#subnav-content-moveto").toggleClass("subnav-content-display");
    });
    $('.moveto').on('click', function(event) {
        event.preventDefault();
        event.stopPropagation();
        event.stopImmediatePropagation();
        const path = [];
        var i = 0;
        var inputVal = document.getElementById("lnamemoveto").value;

        $('.checkbox').each(function () {
            if($(this).is(':checked')){
                console.log($(this).attr('name'));
                path[i] = $(this).attr('name');
                i++;
            }
        });

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
                filemanager_get_files($);
                if(data[0][0] != ''){
                    data.forEach(function(element, index) {
                        console.log(element);
                        filemanager_get_files($);
                        $("#errorlog").append(element[0]+' ERROR');
                    });
                }
            },
            error: function(errorThrown){
                //error stuff here.text
            }
        });
    });
}

function filemanager_moveto_ajax_files($) {
    
}

jQuery(document).ready(function($) {
    filemanager_moveto_files($);
});