<?php

/**
 * User Profile Options Section
 *
 * @package Plugins/Users/Profiles/Sections/Options
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

if ( class_exists( 'WP_User_Profile_Section' ) ):
/**
 * User Profiles "Options" class
 *
 * @since 0.2.0
 */
class WP_User_Profile_Social_Section extends WP_User_Profile_Section {

	/**
	 * Add the meta boxes for this section
	 *
	 * @since 0.2.0
	 *
	 * @param  string  $type
	 * @param  WP_User $user
	 */
	public function add_meta_boxes( $type = '', $user = null ) {

		// Allow third party plugins to add metaboxes
		parent::add_meta_boxes( $type, $user );

		// Color schemes
		add_meta_box(
			'social',
			__( 'Social', 'wp-user-profiles' ),
			'wp_user_profiles_social_meta_box',
			$type,
			'normal',
			'core',
			$user
		);
	}

	/**
	 * Save section data
	 *
	 * @since 0.2.0
	 *
	 * @param WP_User $user
	 */
	public function save( $user = null ) {
		$profile_social = wp_user_profiles_social_get();
		foreach( $profile_social as $slug => $label ) {
			$user->{$slug} = isset( $_POST[$slug] )
				? esc_url( sanitize_text_field( $slug ) )
				: '';
		}

		// Allow third party plugins to save data in this section
		parent::save( $user );
	}
}
endif;
