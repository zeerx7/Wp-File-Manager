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
				const path = urlParams.get('path')

				filemanager_uploads_files($, path);

				filemanager_delete_files($, path);

				filemanager_createdir_files($, path);

			}
		};

		if (!$("body").hasClass("elementor-editor-active")) {
			console.log(	$( "#filemanager-wrapper" ).smoothState( settings ).data("smoothState") );
		}

	});

})(jQuery);	