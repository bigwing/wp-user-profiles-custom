<?php
add_action( 'plugins_loaded', function() {
	require_once plugin_dir_path( __FILE__ ) . 'profiles/user-profile-fields.php';
} );
