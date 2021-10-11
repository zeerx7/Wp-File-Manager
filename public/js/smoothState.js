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
				const urlsharepath = urlParams.get('sharepath');
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
				if(urlsharepath != null){
					url_Params = urlsharepath;
				}

				document.getElementById('sequentialupload').setAttribute('data-object-id', url_Params);

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
				filemanager_search_files($);	
				$("#errorlog").empty();	

			}
		};

		if (!$("body").hasClass("elementor-editor-active")) {
			console.log(	$( "#filemanager-wrapper" ).smoothState( settings ).data("smoothState") );
		}

	});

})(jQuery);	