( function( $ ) {

	function addBlacklistClass() { 
		$( "a" ).each( function() {
			if ( this.href.indexOf("/wp-admin/") !== -1 || 
				 this.href.indexOf("/wp-login.php") !== -1 ) {
				$( this ).addClass( "no-smoothState" );
			}
        });
	}

	$( function() {

		addBlacklistClass();

		var settings = { 
			anchors: "a",
			blacklist: ".no-smoothState",
			cache: true,
			prefetchOn: 'aim',
			scroll: true,
			locationHeader: "X-SmoothState-Location",
			onAfter: function( $container , $newcontainer ) {

				const queryString = window.location.search;
				const urlParams = new URLSearchParams(queryString);
				const urlhome = urlParams.get('home');
				const urlworkplace = urlParams.get('workplace');
				const urlpath = urlParams.get('path');
				var url_Params;
		
				if(urlhome != null){
					url_Params = urlhome;
				}
				if(urlworkplace != null){
					url_Params = urlworkplace;
				}
				if(urlpath != null){
					url_Params = urlpath;
				}

				document.getElementById('sequentialupload').setAttribute('data-object-id', url_Params);

				filemanager_select_files($);

				filemanager_uploads_files($, url_Params);

				filemanager_delete_files($, url_Params);

				filemanager_createfile_files($, url_Params);

				filemanager_createdir_files($, url_Params);

				filemanager_moveto_files($, url_Params);

				filemanager_rename_files($);

				filemanager_info_files($);
				
				$("#errorlog").empty();	

			}
		};

		if (!$("body").hasClass("elementor-editor-active")) {
			console.log(	$( "#filemanager-wrapper" ).smoothState( settings ).data("smoothState") );
		}

	});

})(jQuery);	