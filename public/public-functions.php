<?php

/**
 * @return \LocalAvatars\Plugin
 */
function local_avatars_get_plugin(){
	global $local_avatars_plugin;
	return $local_avatars_plugin;
}

/**
 * save image as new avatar
 * @param $tmp_image_path
 * @param $user_id
 */
function local_avatars_save_image($user_id, $tmp_image_path, $original_filename = false, $type = false){
	local_avatars_get_plugin()->file_handler->save_image($user_id, $tmp_image_path, $original_filename, $type);
}

/**
 * delete user avatar image
 * @param $user_id
 */
function local_avatars_delete_image($user_id){
	local_avatars_get_plugin()->file_handler->delete_image($user_id);
}

/**
 * purge avatar
 * @param $user_id
 */
function local_avatars_purge($user_id){
	local_avatars_get_plugin()->file_handler->purge($user_id);
}