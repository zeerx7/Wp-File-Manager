(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

})( jQuery );

function filemanager_select_files($) {

    $.fn.ready();
	'use strict';

	var link = location.protocol + '//' + location.host + location.pathname;
	$('.ajax-file-upload-statusbar').each(function(i, obj) {
		var data_path_attr = $(obj).data('object-id');
		var data_path_name = $(obj).data('name');
		var data_path_rand = $(obj).data('rand');
		var data_path_size = $(obj).data('size');
		var data_path_percent = document.getElementById("percent"+data_path_rand);	
		
		var upload_curent_path_element = document.getElementById("sequentialupload");
		var upload_curent_path = upload_curent_path_element.getAttribute('data-object-id');
		if( data_path_attr === upload_curent_path) {
			$('#file-table').append('<tr><td><input class="checkbox" type="checkbox" name="'+data_path_attr+'/'+data_path_name+'"/></td><td class="filemanager-table"><a id="file-id" class="filemanager-click" href="'+link+'?path='+data_path_attr+'/'+data_path_name+'">'+data_path_name+'</a><div class="percent" id="_percent'+data_path_rand+'" style="float: right;">'+data_path_percent.innerHTML+'</div></td><td>'+ data_path_size+'</td></tr>');
		}

		console.log(data_path_percent.innerHTML);

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