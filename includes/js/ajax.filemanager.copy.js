
function filemanager_copy_files($) {    
    $('.btncopy').on('click', function(event) {
        event.preventDefault();
        event.stopPropagation();
        event.stopImmediatePropagation();
        $("#errorlog").empty();
        var object_id = document.getElementById('filemanagerpath').innerHTML;
        document.getElementById('lnamecopy').value = object_id;
        $("#subnav-content-copy").toggleClass("subnav-content-display");
    });

    $('.copy').on('click', function(event) {
        event.preventDefault();
        event.stopPropagation();
        event.stopImmediatePropagation();

        const path = [];
        var i = 0;
        var inputVal = document.getElementById("lnamecopy").value;

        $('.checkbox').each(function () {
            if($(this).is(':checked')){
                console.log($(this).attr('name'));
                path[i] = $(this).attr('name');
                i++;
            }
        });
    
        jQuery.ajax({
            type: 'post',
            url: copy_filemanager_ajax_url,
            data: {
                'inputVal': inputVal,
                'path': path,
                'action': 'copy_filemanager_files'
            },
            dataType: 'json',
            success: function(data){
                console.log(data);
                filemanager_get_files($);
                if(data) {
                    data.forEach(function(element, index) {
                        if(data[index][0] != null){
                            console.log(element);
                            $("#errorlog").append(element[0]+' '+element[1]+' ERROR');
                        }
                    });
                }
            },
            error: function(errorThrown){
                //error stuff here.text
            }
        });
    });
}

jQuery(document).ready(function($) {
    filemanager_copy_files($);
});