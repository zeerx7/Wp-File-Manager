function filemanager_uploads_files($, object_id) {

    $.fn.ready();
	'use strict';
    
    $('#sequentialupload').uploadFile({
        url:"/wp-content/plugins/file-manager/includes/upload.php?upload_dir="+object_id+"/",
        fileName:"myfile",
        sequential:true,
        sequentialCount:1,
        showDone:true
    }); 

    $('.file-table').on('drop', function(event) {
        event.preventDefault();
        files = event.originalEvent.dataTransfer.files;
        createFormData(files);
    });
    
    function createFormData(files_obj) {
        var form_data = new FormData();
        for(i=0; i<files_obj.length; i++) {
            form_data.append('myfile', files_obj[i]);
            uploadFormData(form_data, files_obj[i]);
        }
    }

    function makeid(length) {
        var result           = '';
        var characters       = '0123456789';
        var charactersLength = characters.length;
        for ( var i = 0; i < length; i++ ) {
            result += characters.charAt(Math.floor(Math.random() * charactersLength));
        }
        return result;
    }

    function formatBytes(bytes, decimals = 2) {
        if (bytes === 0) return '0 Bytes';
    
        const k = 1024;
        const dm = decimals < 0 ? 0 : decimals;
        const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
    
        const i = Math.floor(Math.log(bytes) / Math.log(k));
    
        return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
    }

    function uploadFormData(formData, files_obj) {
        var rand = makeid(6);
        var link = location.protocol + '//' + location.host + location.pathname;
        $('#ajax-file-upload-container').append('<div id="progress_div'+rand+'" class="ajax-file-upload-statusbar" data-object-id="'+object_id+'" data-name="'+files_obj.name+'" data-rand="'+rand+'" data-size="'+formatBytes(files_obj.size)+'" style="display:flex;width: 100%;margin: 15px 0;"><div class="bar" style="width: 100%;">'+files_obj.name+'</div><div class="percent" id="percent'+rand+'" style="float: right;">0%</div><div class="ajax-file-upload-green done'+rand+'" id="done'+rand+'" style="display: none;margin: 0 15px;">Done</div></div>');
        $('#file-table').append('<tr><td><input class="checkbox" type="checkbox" name="'+object_id+'/'+files_obj.name+'"/></td><td class="filemanager-table"><a id="file-id" class="filemanager-click" href="'+link+'?path='+object_id+'/'+files_obj.name+'">'+files_obj.name+'</a><div class="percent" id="_percent'+rand+'" style="float: right;">0%</div></td><td>'+ formatBytes(files_obj.size)+'</td></tr>');

        var table, rows, switching, i, x, y, shouldSwitch;
        table = document.getElementById("file-table");
        switching = true;
        while (switching) {
          switching = false;
          rows = table.rows;
          for (i = 1; i < (rows.length - 1); i++) {
            shouldSwitch = false;
            x = rows[i].getElementsByTagName("TD")[1];
            y = rows[i + 1].getElementsByTagName("TD")[1];
            if (x.innerText.toLowerCase() > y.innerText.toLowerCase()) {
              shouldSwitch = true;
              break;
            }
          }
          if (shouldSwitch) {
            rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
            switching = true;
          }
        }

        $.ajax({
            xhr: function() {
                var xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener("progress", function(evt) {
                    var percent = document.getElementById('percent'+rand);
                    var _percent = document.getElementById('_percent'+rand);
                    if (evt.lengthComputable) {
                        var percentComplete = evt.loaded / evt.total;
                        percentComplete = parseInt(percentComplete * 100);
                        var percentVal = percentComplete + '%';
                        percent.innerHTML = percentVal;
                        if(_percent != null){
                            _percent.innerHTML = percentVal;
                        }
                        if (percentComplete === 100) {
                            var parent = $('#done'+rand).parent().attr('id');
                            document.getElementById(parent).remove();
                            percent.innerHTML = '';
                        }
                    }
                }, false);
                return xhr;
            },
            url: "/wp-content/plugins/file-manager/includes/upload.php?upload_dir="+object_id+"/",
            type: "POST",
            data: formData,
            contentType:false,
            cache: false,
            processData: false,
            success: function(obj) {

            }});
    }

}

jQuery(document).ready(function($) {
    filemanager_uploads_files($, $('#sequentialupload').data('object-id'));
});