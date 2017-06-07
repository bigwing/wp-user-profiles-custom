<?php

// Remove Social Profiles from Profile Tab
add_action( 'add_meta_boxes', function() {
	remove_meta_box( 'contact', 'users_page_profile', 'normal' );
}, 100, 1 );

// Get Social Profiles
function wp_user_profiles_social_get() {
	return array(
		'bw-linkedin'    => 'LinkedIn',
		//'bw-twitter'     => 'Twitter',
		//'bw-google-plus' => 'Google Plus',
		//'bw-instagram'   => 'Instagram',
		//'bw-pinterest'   => 'Pinterest',
		'bw-wordpress'   => 'WordPress Profile',
		'bw-github'      => 'GitHub Profile',
	);
}

// Social Meta Box
function wp_user_profiles_social_meta_box() {
	$profile_social = wp_user_profiles_social_get();
	?>
	<table class="form-table">
		<?php
		foreach( $profile_social as $slug => $label ) {
			$profile_value = get_user_meta( get_current_user_id(), $slug, true );
			if ( ! $profile_value || ! is_string( $profile_value ) || empty( $profile_value ) ) {
				$profile_value = '';
			}
			?>
			<tr>
				<th scope="row"><?php echo esc_html( $label ); ?></th>
				<td>
					<label for="<?php echo esc_attr( $slug ); ?>"><input type="text" name="<?php echo esc_attr( $slug ); ?>" id="<?php echo esc_attr( $slug ); ?>" value="<?php echo esc_attr( $profile_value ); ?>" class="regular-text" />
				</td>
			</tr>
			<?php
		}

	?></table>
	<?php
}

function wp_user_profiles_register_bw_sections() {
	require_once 'class-profile-social.php';
	
	new WP_User_Profile_Social_Section( array(
		'id'    => 'social',
		'slug'  => 'social',
		'name'  => esc_html__( 'Social', 'wp-user-profiles' ),
		'cap'   => 'edit_profile',
		'icon'  => 'dashicons-share',
		'order' => 120
	) );
}
add_action( 'init', function() {
	wp_user_profiles_register_bw_sections();
} );

// Add Job Title Meta Box
add_action( 'add_meta_boxes', function() {
	$user = wp_get_current_user();
	add_meta_box(
			'job-title',
			__( 'Job Title', 'wp-user-profiles' ),
			'wp_user_profiles_title_meta_box',
			'users_page_profile',
			'side',
			'high',
			$user
		);
} );

// Job Title Meta Box Output
function wp_user_profiles_title_meta_box() {
	$job_title = get_user_meta( get_current_user_id(), 'bw-job-title', true );
	if ( ! $job_title || ! is_string( $job_title ) || empty( $job_title ) ) {
		$job_title = '';
	}
	?>
	<p><label for="bw-job-title">Title: <input type="text" value="<?php echo esc_attr( $job_title ); ?>" name="bw-job-title" id="bw-job-title" /></label></p>
	<?php
}

// Save Job Title
add_action( 'wp_user_profiles_save', 'wp_user_profiles_title_meta_box_save' );
function wp_user_profiles_title_meta_box_save() {
	
	if ( ! is_user_logged_in() || ! current_user_can( 'edit_profile') || ! isset( $_POST['bw-job-title'] ) ) {
		return;
	}
	
	// Get User
	$user_id = absint( $_POST['user_id'] );
	
	// Nonce check
	check_admin_referer( 'update-user_' . $user_id );
	
	// Save Job Title
	$job_title = sanitize_text_field( $_POST[ 'bw-job-title' ] );
	update_user_meta( $user_id, 'bw-job-title', $job_title );
}