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
    var object_id = document.getElementById('filemanagerpath').innerHTML+'/';
    for(i=0; i<files_obj.length; i++) {
        var rand = makeid(6);
        if(files_obj[i].webkitRelativePath === ''){
            $('#ajax-file-upload-container').append('<div id="progress_div'+rand+'" class="ajax-file-upload-statusbar" data-object-id="'+object_id+'" data-name="'+files_obj[i].name+'" data-rand="'+rand+'" data-size="'+formatBytes(files_obj[i].size)+'" style="display:flex;width: 100%;margin: 15px 0;"><div class="bar" style="width: 100%;">'+files_obj[i].name+'</div><div class="percent" id="percent'+rand+'" style="float: right;">0%</div><div class="ajax-file-upload-green done'+rand+'" id="done'+rand+'" style="display: none;margin: 0 15px;">Done</div></div>');
            $('#file-table tr:first').after('<tr><td><input class="checkbox" type="checkbox" name="'+object_id+files_obj[i].name+'"/></td><td class="filemanager-table">'+files_obj[i].name+'<div class="percent" id="_percent'+rand+'" style="float: right;">0%</div></td><td>'+ formatBytes(files_obj[i].size)+'</td></tr>');
        } else {
            var relativepath = files_obj[i].webkitRelativePath;
            var str = relativepath.split("/").pop();
            var str_ = relativepath.replace(str, "");
            var editedText = str_.slice(0, -1);
            $('#ajax-file-upload-container').append('<div id="progress_div'+rand+'" class="ajax-file-upload-statusbar" data-object-id="'+object_id+editedText+'" data-name="'+str+'" data-rand="'+rand+'" data-size="'+formatBytes(files_obj[i].size)+'" style="display:flex;width: 100%;margin: 15px 0;"><div class="bar" style="width: 100%;">'+str+'</div><div class="percent" id="percent'+rand+'" style="float: right;">0%</div><div class="ajax-file-upload-green done'+rand+'" id="done'+rand+'" style="display: none;margin: 0 15px;">Done</div></div>');
            $('#file-table tr:first').after('<tr><td><input class="checkbox" type="checkbox" name="'+object_id+'/'+files_obj[i].webkitRelativePath+'"/></td><td class="filemanager-table">'+files_obj[i].webkitRelativePath+'<div class="percent" id="_percent'+rand+'" style="float: right;">0%</div></td><td>'+ formatBytes(files_obj[i].size)+'</td></tr>');
            var headerHeight = jQuery('header').height();
            var contentHeight = jQuery("#loop-container").height();
            jQuery("#main").height(contentHeight+headerHeight);
        }
        form_data.append('myfile', files_obj[i]);
        uploadFormData($, form_data, files_obj[i], rand, object_id);
    }
}

function uploadFormData($, formData, files_obj, rand, object_id) {
    $.ajax({
        xhr: function() {
            var xhr = new window.XMLHttpRequest();
            xhr.upload.addEventListener("progress", function(evt) {
                var percent = document.getElementById('percent'+rand);
                var _percent = document.getElementById('_percent'+rand);
                if (evt.lengthComputable) {
                    var percentComplete = evt.loaded / evt.total;
                    percentComplete = parseInt(percentComplete * 100);
                    percent.innerHTML = percentComplete;
                    _percent.innerHTML = percentComplete;
                }
            }, false);
            return xhr;
        },
        url: upload_filemanager_ajax_url+"?upload_dir="+object_id+"&relativepath="+files_obj.webkitRelativePath,
        type: "POST",
        data: formData,
        contentType:false,
        cache: false,
        processData: false,
        sequential:false,
        success: function(obj) {
            console.log(obj);
            document.getElementById('progress_div'+rand).remove();
            filemanager_get_files($);
        }
    });
}

function filemanager_uploads_files($) {   
    $('#sequentialupload').uploadFile({
        url:"#",
        fileName:"myfile",
        sequential:false,
        showDone:true
    }); 

    var table = document.getElementById("file-table");
    if(table) { 
        table.addEventListener("drop", e => {
            e.preventDefault();
            e.stopPropagation();
            e.stopImmediatePropagation();
            createFormData($, e.dataTransfer.files);
        });
    }

    let pickerfiles = document.getElementById('pickerfiles');
    if(pickerfiles) { 
        pickerfiles.addEventListener('change', e => {
            e.preventDefault();
            e.stopPropagation();
            e.stopImmediatePropagation();
            console.log(e.target.files);
            createFormData($, e.target.files);
        });
    }

    let pickerdir = document.getElementById('pickerdir');
    if(pickerdir) { 
        pickerdir.addEventListener('change', e => {
            e.preventDefault();
            e.stopPropagation();
            e.stopImmediatePropagation();
            console.log(e.target.files);
            createFormData($, e.target.files);
        });
    }
}

function myFile() {
	document.getElementById('pickerfiles').click();
}

function mydir() {
	document.getElementById('pickerdir').click();
}

jQuery(document).ready(function($) {
    filemanager_uploads_files($);
});