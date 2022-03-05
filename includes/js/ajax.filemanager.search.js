
function filemanager_search_files($) {
    $('.btnsearch').on('click', function(event) {
        event.preventDefault();
        event.stopPropagation();
        event.stopImmediatePropagation();
        $("#errorlog").empty();
        $("#subnav-content-search").toggleClass("subnav-content-display");
    });
    $('.newsearch').on('click', function() {filemanager_search_ajax_files($)});
}

function filemanager_search_ajax_files($) {
    var i = 0;
    var inputVal = document.getElementById("lnamesearch").value;
    var path = document.getElementById('filemanagerpath').innerHTML;

    if(i === 0) {
        jQuery.ajax({
            type: 'post',
            url: search_filemanager_ajax_url,
            data: {
                'path': path,
                'inputVal': inputVal,
                'action': 'search_filemanager_files'
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
                i++;
            },
            error: function(errorThrown){
                //error stuff here.text
            }
        });
    }
}

jQuery(document).ready(function($) {
	filemanager_search_files($);
});