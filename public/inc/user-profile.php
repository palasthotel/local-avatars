<?php

namespace LocalAvatars;


class UserProfile {
	const VAR_FILE_INPUT_NAME = "local_avatars_file_input";
	/**
	 * UserProfile constructor.
	 *
	 * @param Plugin $plugin
	 */
	function __construct($plugin) {
		$this->plugin = $plugin;
		
//		add_action('show_user_profile', array($this,'user_profile'));
//		add_action('edit_user_profile',  array($this,'user_profile'));
		
		add_action( 'personal_options_update', array($this,'save_user') );
		add_action( 'edit_user_profile_update', array($this,'save_user') );
		
		/**
		 * js for upload gui
		 */
		add_action('admin_enqueue_scripts', array($this, 'user_profile_js'));
	}
	
	/**
	 * add js on profile screen
	 * @param $hook
	 */
	function user_profile_js($hook){
		if("user-edit.php" == $hook || "profile.php"){
			wp_enqueue_script(Plugin::HANDLE_JS_USER_PROFILE, $this->plugin->url."/js/user-profile.js", array("jquery"));
			wp_localize_script(Plugin::HANDLE_JS_USER_PROFILE, "LocalAvatars", array(
				"file_input_name" => self::VAR_FILE_INPUT_NAME,
			));
		}
	}
	
	/**
	 * user profile update
	 * @param $user_id
	 */
	function save_user($user_id){
		$file = $_FILES[self::VAR_FILE_INPUT_NAME];
		if(!empty($file)){
			$tmp_name = $file["tmp_name"];
			$name = $file["name"];
			$type = $file["type"];
			$this->plugin->file_handler->save_image($user_id, $tmp_name, $name, $type);
			$this->plugin->file_handler->purge($user_id);

		}
	}
	
}