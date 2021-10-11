
function filemanager_rename_files($) {
    $('.btnrename').on('click', function(event) {
        event.preventDefault();

        const path = [];
        var i = 0;
        var x = 0;

        $('.checkbox').each(function () {
            if($(this).is(':checked')){
                path[i] = $(this).attr('name');
            }
            i++;
        });

        $('.filemanager-table').each(function () {
            if(path[x] != null){
                console.log(path[x]);
                var filename = path[x].replace(/^.*[\\\/]/, '');
                $(this).empty();
                $(this).append("<input type='text' id='filename_"+x+"' name='filename' value='"+filename+"'><button class='rename_btn' data-object-id='"+x+"' data-path='"+path[x]+"'>save</button></input>");
            }
            x++;
        });

        $('.rename_btn').on('click', function(event) {
            event.preventDefault();
            $("#errorlog").empty();	
        
            var y = 0;
            var object_id = $(this).attr('data-object-id');
            var path_id = $(this).attr('data-path');
            var filename_value = $("#filename_"+object_id).val();
            var cell = document.getElementById('file-table').rows[object_id].cells; 
            var link = location.protocol + '//' + location.host + location.pathname;

            if(y === 0) {
                jQuery.ajax({
                    type: 'post',
                    url: rename_filemanager_ajax_url,
                    data: {
                        'filename': filename_value,
                        'path': path_id,
                        'link': link,
                        'action': 'rename_filemanager_files'
                    },
                    dataType: 'json',
                    success: function(data){
                        console.log(data);
                        console.log(object_id);
                        console.log(cell[1].innerHTML = data);
                        filemanager_rename_files($);
                    },
                    error: function(errorThrown){
                        //error stuff here.text
                    }
                });
            }
        });

    });

}

jQuery(document).ready(function($) {
	filemanager_rename_files($);
});