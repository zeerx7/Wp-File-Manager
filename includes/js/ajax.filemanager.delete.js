
function filemanager_delete_files($, object_id) {

    $.fn.ready();
	'use strict';
    
    $('.btndelete').on('click', function(event) {
        event.preventDefault();
        $("#errorlog").empty();
        
        const path = [];
        var i = 0;
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
            url: delete_filemanager_ajax_url,
            data: {
                'path': path,
                'action': 'delete_filemanager_files'
            },
            dataType: 'json',
            success: function(data){
                console.log(data);
                console.log(object_id);
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
                        console.log(data);
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
            },
            error: function(errorThrown){
                //error stuff here.text
            }
        });

    });

}

jQuery(document).ready(function($) {
	filemanager_delete_files($, $("#sequentialupload").data('object-id'));
});