
function filemanager_createdir_files($) {
    $('.btnnewdir').on('click', function(event) {
        event.preventDefault();
        event.stopPropagation();
        event.stopImmediatePropagation();
        $("#errorlog").empty();	
        $("#subnav-content-dir").toggleClass("subnav-content-display");
    });
    $('.newdir').on('click', function() {filemanager_createdir_ajax_files($)});
}

function filemanager_createdir_ajax_files($) {
    var i = 0;
    var inputVal = document.getElementById("lname").value;
    var object_id = document.getElementById('filemanagerpath').innerHTML;

    if(i === 0) {
        jQuery.ajax({
            type: 'post',
            url: createdir_filemanager_ajax_url,
            data: {
                'inputVal': object_id+'/'+inputVal,
                'action': 'createdir_filemanager_files'
            },
            dataType: 'json',
            success: function(data){
                filemanager_get_files($);
            },
            error: function(errorThrown){
                //error stuff here.text
            }
        });
        document.getElementById("lname").value = '';
        $("#subnav-content-dir").toggleClass("subnav-content-display");
    }
}

jQuery(document).ready(function($) {
	filemanager_createdir_files($);
});