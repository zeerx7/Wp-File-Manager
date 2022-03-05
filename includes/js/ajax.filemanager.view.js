
function filemanager_view_type($, viewtype) {
    $("#errorlog").empty();

    var userid = $("#userid").text();
    
    jQuery.ajax({
        type: 'post',
        url: viewtype_filemanager_ajax_url,
        data: {
            'viewtype': viewtype,
            'userid': userid,
            'action': 'viewtype_filemanager_files'
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