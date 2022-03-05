function filemanager_select_files($) {

	$('.ajax-file-upload-statusbar').each(function(i, obj) {
		var data_path_attr = $(obj).data('object-id');
		var data_path_name = $(obj).data('name');
		var data_path_rand = $(obj).data('rand');
		var data_path_size = $(obj).data('size');
		var data_path_percent = document.getElementById("percent"+data_path_rand);	
		var object_id = document.getElementById('sequentialupload').getAttribute('data-object-id');
		if(data_path_attr === object_id) {
			$('#file-table tr:first').after('<tr><td><input class="checkbox" type="checkbox" name="'+data_path_attr+'/'+data_path_name+'"/></td><td class="filemanager-table">'+data_path_name+'<div class="percent" id="_percent'+data_path_rand+'" style="float: right;">'+data_path_percent.innerHTML+'</div></td><td>'+ data_path_size+'</td></tr>');
		}
	});

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

	var object_id = document.getElementById('sequentialupload').getAttribute('data-object-id');
    $('.btnback').on('click', function(event) {
        event.preventDefault();
        var link = location.protocol + '//' + location.host + location.pathname;
        location.href = link+'?path='+object_id+'/..'
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