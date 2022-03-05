
function filemanager_zip_files($) {
    $('.btnzip').on('click', function(event) {
        event.preventDefault();
        event.stopPropagation();
        event.stopImmediatePropagation();
        $("#errorlog").empty();	
        $("#subnav-content-zip").toggleClass("subnav-content-display");
    });
    $('.zipbtn').on('click', function() {filemanager_zip_ajax_files($)});
}

function filemanager_zip_ajax_files($) {
    const path = [];
    var i = 0;
    var x = 0
    var inputVal = document.getElementById("lnamezip").value;

    $('.checkbox').each(function () {
        if($(this).is(':checked')){
            path[i] = $(this).attr('name');
        }
        i++;
    });

    if(x === 0) {
        jQuery.ajax({
            type: 'post',
            url: zip_filemanager_ajax_url,
            data: {
                'path': path,
                'inputVal': inputVal,
                'action': 'zip_filemanager_files'
            },
            dataType: 'json',
            beforeSend: function() {
                $("#loadzip").css('display', 'block');
            },
            success: function(data){
                console.log(data);
                filemanager_get_files($);
            },
            error: function(errorThrown){
                //error stuff here.text
            }
        });
    }
}

jQuery(document).ready(function($) {
	filemanager_zip_files($);
});