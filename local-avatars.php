<?php

/**
 * Plugin Name: Local Avatars
 * Description: Use of local avatars
 * Version: 1.0.1
 * Author: Palasthotel <rezeption@palasthotel.de> ([Edward])
 * Author URI: https://palasthotel.de
 */


namespace LocalAvatars;


class Plugin {
	
	/**
	 * Domain for translation
	 */
	const DOMAIN = "local-avatars";
	
	/**
	 * filters
	 */
	const FILTER_UPLOAD_DIR = "local_avatars_get_dir";
	const FILTER_UPLOAD_FILENAME = "local_avatars_get_filename";
	const FILTER_GRAVATAR = "local_avatars_gravatar";

	/**
	 * actions
	 */
	const ACTION_FILE_UPLOADED = "local_avatars_file_uploaded";
	
	/**
	 * js and style handles
	 */
	const HANDLE_JS_USER_PROFILE = "local-avatars-user-profile-js";
	
	/**
	 * Constants for templates in theme
	 */
	const THEME_FOLDER = "plugin-parts";
	const TEMPLATE_NAME_OF_THE_TEMPLATE = "avatar.php";
	
	/**
	 * default path to avatars in uploads folder
	 */
	const UPLOADS_PATH = "local-avatar";
	
	/**
	 * Plugin constructor
	 */
	function __construct() {
		
		/**
		 * Base paths
		 */
		$this->dir = plugin_dir_path( __FILE__ );
		$this->url = plugin_dir_url( __FILE__ );
		
		
		/**
		 * FileHandler class
		 */
		require_once dirname( __FILE__ ) . '/inc/file-handler.php';
		$this->file_handler = new FileHandler( $this );
		
		
		/**
		 * UserProfile class
		 */
		require_once dirname( __FILE__ ) . '/inc/user-profile.php';
		$this->user_profile = new UserProfile( $this );
		
		/**
		 * Avatar class
		 */
		require_once dirname( __FILE__ ) . '/inc/avatar.php';
		$this->avatar = new Avatar( $this );
		
		/**
		 * Render class
		 */
		require_once dirname( __FILE__ ) . '/inc/render.php';
		$this->render = new Render( $this );
		
	}
	
}

global $local_avatars_plugin;
$local_avatars_plugin = new Plugin();

require_once dirname( __FILE__ ) . "/public-functions.php";