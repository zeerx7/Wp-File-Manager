var object_id = document.getElementById('sequentialupload').getAttribute('data-object-id');

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

function createFormData($, files_obj) {
    $("#errorlog").empty();	
    var form_data = new FormData();
    var link = location.protocol + '//' + location.host + location.pathname;
    var object_id = document.getElementById('sequentialupload').getAttribute('data-object-id');
    for(i=0; i<files_obj.length; i++) {
        var rand = makeid(6);
        if(files_obj[i].webkitRelativePath === ''){
            $('#ajax-file-upload-container').append('<div id="progress_div'+rand+'" class="ajax-file-upload-statusbar" data-object-id="'+object_id+'" data-name="'+files_obj[i].name+'" data-rand="'+rand+'" data-size="'+formatBytes(files_obj[i].size)+'" style="display:flex;width: 100%;margin: 15px 0;"><div class="bar" style="width: 100%;">'+files_obj[i].name+'</div><div class="percent" id="percent'+rand+'" style="float: right;">0%</div><div class="ajax-file-upload-green done'+rand+'" id="done'+rand+'" style="display: none;margin: 0 15px;">Done</div></div>');
            $('#file-table').append('<tr><td><input class="checkbox" type="checkbox" name="'+object_id+'/'+files_obj[i].name+'"/></td><td class="filemanager-table">'+files_obj[i].name+'<div class="percent" id="_percent'+rand+'" style="float: right;">0%</div></td><td>'+ formatBytes(files_obj[i].size)+'</td></tr>');
        } else {
            $('#ajax-file-upload-container').append('<div id="progress_div'+rand+'" class="ajax-file-upload-statusbar" data-object-id="'+object_id+'" data-name="'+files_obj[i].webkitRelativePath+'" data-rand="'+rand+'" data-size="'+formatBytes(files_obj[i].size)+'" style="display:flex;width: 100%;margin: 15px 0;"><div class="bar" style="width: 100%;">'+files_obj[i].webkitRelativePath+'</div><div class="percent" id="percent'+rand+'" style="float: right;">0%</div><div class="ajax-file-upload-green done'+rand+'" id="done'+rand+'" style="display: none;margin: 0 15px;">Done</div></div>');
            $('#file-table').append('<tr><td><input class="checkbox" type="checkbox" name="'+object_id+'/'+files_obj[i].webkitRelativePath+'"/></td><td class="filemanager-table">'+files_obj[i].webkitRelativePath+'<div class="percent" id="_percent'+rand+'" style="float: right;">0%</div></td><td>'+ formatBytes(files_obj[i].size)+'</td></tr>');
        }
        form_data.append('myfile', files_obj[i]);
        uploadFormData($, form_data, files_obj[i], rand, link, files_obj.length);
    }
}

function uploadFormData($, formData, files_obj, rand, link, length) {
    const queryString = window.location.search;
    var object_id = document.getElementById('sequentialupload').getAttribute('data-object-id');
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
        url: "/wp-content/plugins/file-manager/includes/upload.php?upload_dir="+object_id+"/&relativepath="+files_obj.webkitRelativePath,
        type: "POST",
        data: formData,
        contentType:false,
        cache: false,
        processData: false,
        sequential:true,
        sequentialCount:1,
        success: function(obj) {
            console.log(obj);
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
                    console.log(object_id);
                    $( '.filemanager-wrapper' ).empty();		
                    $('.filemanager-wrapper').append(data);
                    filemanager_select_files($);
                    filemanager_uploads_files($);
                    filemanager_delete_files($);
                    filemanager_createfile_files($);
                    filemanager_createdir_files($);
                    filemanager_copy_files($);
                    filemanager_moveto_files($);
                    filemanager_zip_files($);
                    filemanager_rename_files($);
                    filemanager_info_files($);
                    filemanager_share_files($);
                },
                error: function(errorThrown){
                    //error stuff here.text
                }
            });
        }
        
    });
}

function filemanager_uploads_files($) {   
    $('#sequentialupload').uploadFile({
        url:"/wp-content/plugins/file-manager/includes/upload.php?upload_dir="+object_id+"/",
        fileName:"myfile",
        sequential:true,
        sequentialCount:1,
        showDone:true
    }); 

    var table = document.getElementById("file-table");
    if(table) { 
        table.addEventListener("drop", e => {
            e.preventDefault();
            createFormData($, e.dataTransfer.files);
        }, { once: true });
    }

    let pickerfiles = document.getElementById('pickerfiles');
    if(pickerfiles) { 
        pickerfiles.addEventListener('change', e => {
            e.preventDefault();
            console.log(e.target.files);
            createFormData($, e.target.files);
        }, { once: true });
    }

    let pickerdir = document.getElementById('pickerdir');
    if(pickerdir) { 
        pickerdir.addEventListener('change', e => {
            e.preventDefault();
            createFormData($, e.target.files);
        }, { once: true });
    }
}

jQuery(document).ready(function($) {
    filemanager_uploads_files($);
});