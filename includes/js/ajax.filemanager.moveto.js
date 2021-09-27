
function filemanager_moveto_files($, object_id) {
    
    $('.btnmoveto').on('click', function(event) {
        event.preventDefault();
        $("#errorlog").empty();
        $("#subnav-content-moveto").toggleClass("subnav-content-display");

        $('.moveto').on('click', function(event) {
            event.preventDefault();

            const path = [];
            var i = 0;
            var inputVal = document.getElementById("lnamemoveto").value;
            var link = location.protocol + '//' + location.host + location.pathname;
            const queryString = window.location.search;
            const urlParams = new URLSearchParams(queryString);
            const urlhome = urlParams.get('home');
            const urlworkplace = urlParams.get('workplace');
            const urlpath = urlParams.get('path');
            var url_Params;
    
            if(urlhome != null){
                url_Params = 'home';
            }
            if(urlworkplace != null){
                url_Params = 'workplace';
            }
            if(urlpath != null){
                url_Params = 'path';
            }

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
                    jQuery.ajax({
                        type: 'post',
                        url: get_filemanager_ajax_url,
                        data: {
                            'object_id': object_id,
                            'link': link,
                            'urlParams': url_Params,
                            'action': 'get_filemanager_files'
                        },
                        dataType: 'json',
                        success: function(data){
                            $( '.filemanager-wrapper' ).empty();		
                            $('.filemanager-wrapper').append(data);
                            filemanager_select_files($);
                            filemanager_uploads_files($, object_id);                       
                            filemanager_createfile_files($, object_id);            
                            filemanager_createdir_files($, object_id);            
                            filemanager_moveto_files($, object_id);            
                            filemanager_rename_files($);           
                            filemanager_delete_files($, object_id);  
                            filemanager_info_files($);
                        },
                        error: function(errorThrown){
                            //error stuff here.text
                        }
                    });
                    if(data[0][0] != ''){
                        data.forEach(function(element, index) {
                            console.log(element);
                            $("#errorlog").append(element[0]+' ERROR');
                        });
                    }
                },
                error: function(errorThrown){
                    //error stuff here.text
                }
            });
        });
    });

}

jQuery(document).ready(function($) {
    filemanager_moveto_files($, $("#sequentialupload").data('object-id'));
});