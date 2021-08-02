
function filemanager_back_files($) {

    $.fn.ready();
	'use strict';
    
    $('.filemanager-click-back').on('click', function(event) {

        var $this = $(this),
        object_id = $this.data('object-id');

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
                setTimeout(function(){ filemanager_back_files($); }, 1000);
                setTimeout(function(){ filemanager_read_files($); }, 1000);
            },
            error: function(errorThrown){
                //error stuff here.text
            }
        });

    });

}


function filemanager_read_files($) {

    $.fn.ready();
	'use strict';
    
    $('.filemanager-click-file').on('click', function(event) {

        var $this = $(this),
        object_id = $this.data('object-id');

        jQuery.ajax({    
            type: 'post',
            url: read_filemanager_ajax_url,
            data: {
                'object_id': object_id,
                'action': 'read_filemanager_files'
            },
            dataType: 'json',
            success: function(data){
                console.log(data);
                $( '.filemanager-wrapper' ).empty();	
                $('.filemanager-wrapper').append(data[1]);	
                $('.filemanager-wrapper').append(data[2]);	
                if((data[0] = 'mvk') || (data[0] = 'mp4')){
                    window.dp1 = new DPlayer({
                        container: document.getElementById('dplayer'),
                        preload: 'none',
                        screenshot: true,
                        video: {
                            url: data[3],
                            pic:  null,
                            thumbnails: null
                        },
                        subtitle: {
                            url: null
                        }
                    });
                }
                setTimeout(function(){ filemanager_back_files($); }, 1000);
                setTimeout(function(){ filemanager_read_files($); }, 1000);
                setTimeout(function(){ filemanager_uploads_files($, object_id); }, 1000);
            },
            error: function(errorThrown){
                //error stuff here.text
            }
        });

    });

}

jQuery(document).ready(function($) {
	filemanager_read_files($);
});