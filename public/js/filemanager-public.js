(function( $ ) {
	'use strict';

	$(function() {
		console.log('test');
		var filemanagerhomehrefcount = $('.filemanager-home-click').length;
		if(filemanagerhomehrefcount === 1){
			var filemanagerhomehref = $('.filemanager-home-click').attr('href');
			window.location = filemanagerhomehref;
		}
	});

})( jQuery );

function filemanager_select_files($) {

    $.fn.ready();
	'use strict';

	$('.ajax-file-upload-statusbar').each(function(i, obj) {
		var data_path_attr = $(obj).data('object-id');
		var data_path_name = $(obj).data('name');
		var data_path_rand = $(obj).data('rand');
		var data_path_size = $(obj).data('size');
		var data_path_percent = document.getElementById("percent"+data_path_rand);	
		
		var upload_curent_path_element = document.getElementById("sequentialupload");
		var upload_curent_path = upload_curent_path_element.getAttribute('data-object-id');
		if(data_path_attr.includes(upload_curent_path)) {
			$('#file-table').append('<tr><td><input class="checkbox" type="checkbox" name="'+data_path_attr+'/'+data_path_name+'"/></td><td class="filemanager-table">'+data_path_name+'<div class="percent" id="_percent'+data_path_rand+'" style="float: right;">'+data_path_percent.innerHTML+'</div></td><td>'+ data_path_size+'</td></tr>');
		}
	});

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

    $('#file-table tbody').on( 'click', 'tr', function () {
        $(this).toggleClass('selected');
		console.log($(this).find("td :input").is(":checked"));
		if ($(this).find("td :input").is(":checked")) {
			$(this).find("td :input").attr("checked", false);
		} else {
			$(this).find("td :input").attr("checked", true);
		}

    } );

}

function myFile() {
	document.getElementById('pickerfiles').click();
}

function mydir() {
	document.getElementById('pickerdir').click();
}

jQuery(document).ready(function($) {
	filemanager_select_files($);
} );