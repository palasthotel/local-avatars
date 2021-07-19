<?php

namespace LocalAvatars;


class Avatar {
	/**
	 * Avatar constructor.
	 *
	 * @param Plugin $plugin
	 */
	function __construct( $plugin ) {
		$this->plugin = $plugin;
		
		add_filter( "pre_get_avatar_data", array( $this, "pre_get_avatar_data" ), 10, 2 );
		add_filter( "get_avatar_data", array( $this, "pre_get_avatar_data" ), 10, 2 );
		
		add_filter( 'get_avatar', array( $this, 'get_avatar' ), 99, 3 );
	}
	
	
	function pre_get_avatar_data( $args, $id_or_email ) {
		
		$id_or_email = $this->get_avatar_user_id( $id_or_email );
		
		/**
		 * get local avatar by user id
		 */
		if ( is_numeric( $id_or_email ) && $id_or_email > 0 ) {
			
			// TODO: make sizes
			
			$user_id = absint( $id_or_email );

//			error_log( $this->plugin->file_handler->get_file_path( $user_id ), 4 );

			$file = $this->plugin->file_handler->get_file_path( $user_id );

			if ( file_exists( $file ) ) {
				$args["url"] = $this->plugin->file_handler->get_avatar_url( $user_id );
				
				return $args;
			}
			
		}
		
		return $args;
		
		
	}
	
	/**
	 * want to overwrite avatar html?
	 *
	 * @param $avatar
	 * @param $id_or_email
	 *
	 * @return string
	 */
	function get_avatar( $avatar, $id_or_email ) {
		$user_id = $this->get_avatar_user_id( $id_or_email );
		/**
		 * overwrite default gravatar url?
		 */
		if ( preg_match( '/https?:\/\/([^\.]+\.)?gravatar\.com\/avatar\//', $avatar ) ) {
			return apply_filters( Plugin::FILTER_GRAVATAR, $avatar, $user_id, $id_or_email );
		}
		
		return $avatar;
	}
	
	/**
	 * @param $id_or_email
	 *
	 * @return int
	 */
	function get_avatar_user_id( $id_or_email ) {
		
		/**
		 * check if comment avatar call
		 */
		if ( is_object( $id_or_email ) && isset( $id_or_email->comment_ID ) ) {
			$id_or_email = get_comment( $id_or_email )->user_id;
		}
		
		/**
		 * handle for user id
		 */
		if ( is_a( $id_or_email, "WP_User" ) ) {
			$id_or_email = $id_or_email->ID;
		}
		
		return $id_or_email;
	}
}