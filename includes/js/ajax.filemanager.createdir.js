
function filemanager_createdir_files($, object_id) {
    
    $('.btnnewdir').on('click', function(event) {
        event.preventDefault();
        $("#subnav-content").toggleClass("subnav-content-display");

        $('.newdir').on('click', function(event) {
            event.preventDefault();
            var inputVal = document.getElementById("lname").value;
            console.log(inputVal);
            jQuery.ajax({
                type: 'post',
                url: createdir_filemanager_ajax_url,
                data: {
                    'inputVal': object_id+'/'+inputVal,
                    'action': 'createdir_filemanager_files'
                },
                dataType: 'json',
                success: function(data){
                    console.log(data);
                    jQuery.ajax({
                        type: 'post',
                        url: get_filemanager_ajax_url,
                        data: {
                            'object_id': object_id,
                            'action': 'get_filemanager_files'
                        },
                        dataType: 'json',
                        success: function(data){
                            console.log(data);
                            $( '.filemanager-wrapper' ).empty();		
                            $('.filemanager-wrapper').append(data);
                            filemanager_delete_files($, object_id);
                            filemanager_createdir_files($, object_id);
                        },
                        error: function(errorThrown){
                            //error stuff here.text
                        }
                    });
                },
                error: function(errorThrown){
                    //error stuff here.text
                }
            });
            document.getElementById("lname").value = '';
        });
    });

}

jQuery(document).ready(function($) {
	filemanager_createdir_files($, $("#sequentialupload").data('object-id'));
});