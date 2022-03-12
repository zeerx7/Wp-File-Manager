function RemoveLastDirectoryPartOf(the_url) {
    var the_arr = the_url.split('/');
    the_arr.pop();
    return( the_arr.join('/') );
}

function GetLastDirectoryPartOf(the_url) {
    var the_arr = the_url.split('/');
    let lastElement = the_arr[the_arr.length - 1];
    return( lastElement );
}

function ArrayAvg(myArray) {
    var i = 0, summ = 0, ArrayLen = myArray.length;
    while (i < ArrayLen) {
        summ = summ + myArray[i++];
	}
    return summ / ArrayLen;
}

function filemanager_select_files($) {

	var object_id = document.getElementById('sequentialupload').getAttribute('data-object-id');
	$('.ajax-file-upload-statusbar').each(function(i, obj) {
		var data_path_attr = $(obj).data('object-id');
		var data_path_name = $(obj).data('name');
		var data_path_rand = $(obj).data('rand');
		var data_path_size = $(obj).data('size');
		var data_path_percent = document.getElementById("percent"+data_path_rand);	
		if(data_path_attr === object_id) {
			$('#file-table tr:first').after('<tr><td><input class="checkbox" type="checkbox" name="'+data_path_attr+'/'+data_path_name+'"/></td><td class="filemanager-table">'+data_path_name+'<div class="percent" id="_percent'+data_path_rand+'" style="float: right;">'+data_path_percent.innerHTML+'</div></td><td>'+ data_path_size+'</td></tr>');
		}
	});

	const object_path_attr = [];
	const object_path_name = [];
	const object_path_rand = [];
	const object_path_size = [];
	const object_path_percent = [];
	$('.ajax-file-upload-statusbar').each(function(i, obj) {
		object_path_attr[i] = $(obj).data('object-id');
		object_path_name[i] = $(obj).data('name');
		object_path_rand[i] = $(obj).data('rand');
		object_path_size[i] = $(obj).data('size');
		object_path_percent[i] = document.getElementById("percent"+object_path_rand[i]).innerHTML;	
	});
	let uniqueChars = object_path_attr.filter((element, index) => {
		return object_path_attr.indexOf(element) === index;
	});
	for (let i = 0; i < uniqueChars.length; i++) {
		console.log(RemoveLastDirectoryPartOf(uniqueChars[i]));
		console.log(object_id);
		if(RemoveLastDirectoryPartOf(uniqueChars[i]) === object_id){
			$('#file-table tr:first').after('<tr><td><input class="checkbox" type="checkbox"></td><td class="filemanager-table">'+GetLastDirectoryPartOf(uniqueChars[i])+'<div class="percent" id="_percent'+object_path_rand[i]+'" style="float: right;">'+ArrayAvg(object_path_percent)+'</div></td><td></td></tr>');
		}
		if(uniqueChars[i] === object_id){
			$('#file-table tr:first').after('<tr><td><input class="checkbox" type="checkbox"></td><td class="filemanager-table">'+object_path_name[i]+'<div class="percent" id="_percent'+object_path_rand[i]+'" style="float: right;">'+object_path_percent[i].innerHTML+'</div></td>'+object_path_size[i]+'<td></td></tr>');
		}
	}

	const displayhome = document.getElementsByClassName("filemanager-home-click");
	if(displayhome.length > 1){
		for(let i = 0; i < displayhome.length; i++) {
			displayhome_ = document.getElementsByClassName("filemanager-home");
			displayhome_[i].style.display = "block";
		}
	}
	var filemanagerhomehrefcount = $('.filemanager-home-click').length;
	if(filemanagerhomehrefcount === 1){
		var filemanagerhomehref = $('.filemanager-home-click').attr('href');
		window.location = filemanagerhomehref;
	}

	const filemanagervideo = document.getElementById("filemanagervideo");
	if(filemanagervideo){
		filemanagervideo.onended = function() {
			var filemanagernext = $('.filenamenext').attr('href');
			window.location = filemanagernext;
		};
	}

	$('.folder').on( 'click', function () {
		console.log($(this).next("ul"));
		$(this).next("ul").toggleClass("treeviewitemshow");
	});

	jQuery("#treeview").height(jQuery("#file-table").height());

    $('#file-table tbody').on( 'click', 'tr', function () {
        $(this).toggleClass('selected');
		if ($(this).find("td :input").is(":checked")) {
			$(this).find("td :input").attr("checked", false);
		} else {
			$(this).find("td :input").attr("checked", true);
		}
    });

	$('.grid-item').on( 'click', function () {
        $(this).toggleClass('selected');
		if ($(this).find("input").is(":checked")) {
			$(this).find("input").attr("checked", false);
		} else {
			$(this).find("input").attr("checked", true);
		}
    });

	$('.checkboxall').on( 'click', function () {
		if ($('.checkboxall').find("input").is(":checked") == false) {
			$('.checkbox').each(function () {
				$(this).find("input").attr("checked", true);
				var parent = $(this).parent();
				var parentparent = parent.parent();
				if(parentparent.hasClass('selected') == false){
					parentparent.addClass('selected');
					parentparent.find("td :input").attr("checked", true);
				}
			});
		} else {
			$('.checkbox').each(function () {
				$(this).find("input").attr("checked", false);
				var parent = $(this).parent();
				var parentparent = parent.parent();
				if(parentparent.hasClass('selected')){
					parentparent.removeClass('selected');
					parentparent.find("td :input").attr("checked", false);
				}
			});
		}
	});

    $('.btnback').on('click', function(event) {
        event.preventDefault();
		var object_id = document.getElementById('sequentialupload').getAttribute('data-object-id');
        var link = location.protocol + '//' + location.host + location.pathname;
		if(object_id){
			location.href = link+'?path='+object_id+'/..'
		}
    });

	$('#viewtype').on( 'click', function (event) {
        event.stopImmediatePropagation();
		val = $(this).val();
		$(this).val(val === "list" ? "grid" : "list");
		$('#viewtypetext').html($(this).val());
		filemanager_view_type($, $(this).val())
	});
}

jQuery(document).ready(function($) {
	filemanager_select_files($);
} );