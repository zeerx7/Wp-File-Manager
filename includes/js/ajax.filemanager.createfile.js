
function filemanager_createfile_files($) {
    $('.btnnewfile').on('click', function(event) {
        event.preventDefault();
        event.stopPropagation();
        event.stopImmediatePropagation();
        $("#errorlog").empty();	
        $("#subnav-content-file").toggleClass("subnav-content-display");
    });
    $('.newfile').on('click', function(event) {
        event.preventDefault();
        event.stopPropagation();
        event.stopImmediatePropagation();
        var i = 0;
        var inputVal = document.getElementById("lnamefile").value;
        var object_id = document.getElementById('filemanagerpath').innerHTML;
    
        if(i === 0) {
            jQuery.ajax({
                type: 'post',
                url: createfile_filemanager_ajax_url,
                data: {
                    'inputVal': object_id+'/'+inputVal,
                    'action': 'createfile_filemanager_files'
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
            document.getElementById("lnamefile").value = '';
            $("#subnav-content-file").toggleClass("subnav-content-display");
        }
    });
}

jQuery(document).ready(function($) {
    filemanager_createfile_files($);
});