<?php

namespace LocalAvatars;


class FileHandler {
	
	/**
	 * FileHandler constructor.
	 *
	 * @param Plugin $plugin
	 */
	function __construct( $plugin ) {
		$this->plugin = $plugin;
		add_filter(Plugin::FILTER_UPLOAD_FILENAME, array( $this, "filter_filename"));
	}
	
	/**
	 * @param $abs_path_to_img
	 * @param int $user_id
	 *
	 * @return boolean
	 */
	function save_image( $user_id, $abs_path_to_img, $original_filename = false, $type = false ) {
		$path = $this->get_path();
		
		if ( ! is_dir( $path ) ) {
			error_log( "Folder does not exist. Try to crate folder in path: {$path}", 4 );
			if ( ! mkdir( $path, 0777, TRUE ) ) {
				error_log( "Could not create local avatars folder for path: {$path}", 4 );
			} else {
				error_log( "Created folder in: {$path}", 4 );
			}
		}

		$existing = $this->get_file_path($user_id);

		if( is_file($existing) ){
			unlink($existing);
		}

		$destination = rtrim( $path, "/" ) . "/" . $this->get_filename( $user_id );

		if($original_filename !== false){
			$extension = pathinfo($original_filename, PATHINFO_EXTENSION);
			$destination.=".$extension";
		}

		$moved = move_uploaded_file( $abs_path_to_img, $destination );

		if($moved) do_action(Plugin::ACTION_FILE_UPLOADED, $destination);

		return $moved;
	}

	/**
	 * get filename of avatar image
	 *
	 * @param $user_id
	 *
	 * @param string $extension with extension or not
	 *
	 * @return mixed
	 */
	function get_filename( $user_id ) {
		return apply_filters( Plugin::FILTER_UPLOAD_FILENAME, $user_id );
	}

	/**
	 * wild guess search for filename extension
	 * @param string $user_id
	 *
	 * @return string
	 */
	function filter_filename($user_id){

		$file_path = rtrim($this->get_path(),"/")."/".$user_id;

		$endings = array(
			".png",
			".jpg",
			".jpeg",
			".gif",
		);

		foreach ($endings as $ending){
			if(is_file($file_path.$ending)){
				return $user_id.$ending;
			}
		}


		return $user_id;
	}

	
	/**
	 * path to file
	 *
	 * @param $user_id
	 *
	 * @return string
	 */
	function get_file_path( $user_id ) {
		return rtrim( $this->get_path(), "/" ) . "/" . $this->get_filename( $user_id );
	}
	
	/**
	 * get abs path to avatars, no trailing slash
	 *
	 * @param $user_id
	 *
	 * @return mixed
	 */
	function get_path() {
		return apply_filters( Plugin::FILTER_UPLOAD_DIR, rtrim( \wp_upload_dir()["basedir"], "/" ) . "/" . Plugin::UPLOADS_PATH );
	}
	
	/**
	 * get url to avatars no trailing slash
	 *
	 * @param $user_id
	 *
	 * @return mixed
	 */
	function get_url() {
		return str_replace( rtrim( ABSPATH, "/" ) . "/", "/", $this->get_path() );
	}
	
	/**
	 * get full url to avatar image
	 *
	 * @param $user_id
	 *
	 * @return string
	 */
	function get_avatar_url( $user_id ) {
		return rtrim( $this->get_url(), "/" ) . "/" . $this->get_filename( $user_id );
	}
	
	/**
	 * @param $user_id
	 */
	function delete_image( $user_id ) {
		unlink( $this->get_file_path( $user_id ) );
	}

	/**
	 * purge user avatar
	 * @param $user_id
	 */
	function purge($user_id){
		$url  = get_site_url() . $this->get_avatar_url( $user_id );
		$curl = curl_init( $url );
		curl_setopt( $curl, CURLOPT_CUSTOMREQUEST, "PURGE" );
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
		curl_exec( $curl );
	}
	
	
}