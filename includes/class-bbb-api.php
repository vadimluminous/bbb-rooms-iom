<?php
class Bigbluebutton_Api {

	// Check if Meeting already Running
	public static function is_meeting_running( $room_id ) {

		$rid = intval( $room_id );

		if ( get_post( $rid ) === false || 'iomrooms' != get_post_type( $rid ) ) {
			return null;
		}

		// $meeting_id = get_post_meta( $rid, 'bbb-room-meeting-id', true );
		$meeting_id = $rid;
		$arr_params = array(
			'meetingID' => rawurlencode( $meeting_id ),
		);

		$url           = self::build_url( 'isMeetingRunning', $arr_params );
		$full_response = self::get_response( $url );
		if ( is_wp_error( $full_response ) ) {
			return null;
		}

		$response = self::response_to_xml( $full_response );

		if ( property_exists( $response, 'running' ) && 'true' == $response->running ) {
			return true;
		}
		return false;
	}

	// Create BigBlueButton Meeting
	public static function create_meeting( $room_id, $logout_url ) {
		$rid = intval( $room_id );

		if ( get_post( $rid ) === false || 'iomrooms' != get_post_type( $rid ) ) {
			return 404;
		}

		$name           = html_entity_decode( get_the_title( $rid ) );
		$moderator_code = get_post_meta( $rid, 'bbb-room-moderator-code', true );
		$viewer_code    = get_post_meta( $rid, 'bbb-room-viewer-code', true );
		$recordable     = get_post_meta( $rid, 'bbb-room-recordable', true );
		if ($recordable != 'true') {
			$recordable = 'false';
		}

		$meeting_id     = $rid;
		$arr_params     = array(
			'attendeePW'  => rawurlencode( $viewer_code ),
			'autoStartRecording' => 'false',
			'logoutURL'   => esc_url( $logout_url ),
			'meetingID'   => rawurlencode( $meeting_id ),
			'moderatorPW' => rawurlencode( $moderator_code ),
			'name'        => esc_attr( $name ),
			'record'      => $recordable,
			'welcome' 	  => WELCOME_MESSAGE,
		);

		$url = self::build_url( 'create', $arr_params );
		$full_response = self::get_response( $url );
		if ( is_wp_error( $full_response ) ) {
			return 404;
		}
		echo $url;

		$response = self::response_to_xml( $full_response );

		if ( property_exists( $response, 'returncode' ) && 'SUCCESS' == $response->returncode ) {
			return 200;
		} elseif ( property_exists( $response, 'returncode' ) && 'FAILURE' == $response->returncode ) {
			return 403;
		}

		return 500;

	}

	// Get Join Meeting URL
	public static function get_join_meeting_url( $room_id, $username, $password, $logout_url = null) {

		$rid   = intval( $room_id );
		$uname = sanitize_text_field( $username );
		$pword = sanitize_text_field( $password );
        $attendeePW = get_post_meta($rid, 'bbb-room-viewer-code', true);
        $moderatorPW = get_post_meta($rid, 'bbb-room-moderator-code', true);
		$lo_url = ( $logout_url ? esc_url( $logout_url ) : get_permalink( $rid ) );

		if ( get_post( $rid ) === false || 'iomrooms' != get_post_type( $rid ) ) {
			return null;
		}

		if ( ! self::is_meeting_running( $rid ) ) {
			$code = self::create_meeting( $rid, $lo_url );
			echo $code;
			if ( 200 !== $code ) {
				wp_die( esc_html__( 'It is currently not possible to create rooms on the server. Please contact support for help.', 'bigbluebutton' ) );
			}
		}

		$meeting_id = $rid;;
		$arr_params = array(
			'meetingID' => rawurlencode( $meeting_id ),
			'fullName'  => $uname,
			'password'  => rawurlencode( $pword ),
		);

		$url = self::build_url( 'join', $arr_params );

		return $url;
	}


	/**
	 * Join meeting if possible.
	 *
	 * @since   3.0.0
	 *
	 * @param   String  $return_url     URL of the page the request was made from.
	 * @param   Integer $room_id        ID of the room to join.
	 * @param   String  $username       The name of the user who wants to enter the meeting.
	 * @param   String  $entry_code     The entry code the user is attempting to join with.
	 * @param   String  $viewer_code    The entry code for viewers.
	 * @param   Boolean $wait_for_mod   Boolean value for if the room requires a moderator to join before any viewers.
	 */
	private function join_meeting( $return_url, $room_id, $username, $entry_code, $viewer_code, $wait_for_mod ) {
		$join_url = Bigbluebutton_Api::get_join_meeting_url( $room_id, $username, $entry_code, $return_url );

		if ( $entry_code == $viewer_code && 'true' == $wait_for_mod ) {
			if ( Bigbluebutton_Api::is_meeting_running( $room_id ) ) {
				wp_redirect( $join_url );
			} else {
				$query = array(
					'bigbluebutton_wait_for_mod' => true,
					'room_id'                    => $room_id,
				);

				$access_as_viewer = BigBlueButton_Permissions_Helper::user_has_bbb_cap( 'join_as_viewer_bbb_room' );
				if ( ! is_user_logged_in() ) {
					$query['username'] = $username;
				}
				// Make user wait for moderator to join room.
				if ( ! $access_as_viewer ) {
					$query['temp_entry_pass'] = wp_create_nonce( 'bigbluebutton_entry_code_' . $entry_code );
				}
				wp_redirect( add_query_arg( $query, $return_url ) );
			}
		} else {
			wp_redirect( $join_url );
		}
	}

	/**
	 * Get all recordings for selected room.
	 *
	 * @since   3.0.0
	 *
	 * @param   Array  $room_ids               List of custom post ids for rooms.
	 * @param   String $recording_state        State of recordings to get.
	 * @return  Array  $recordings             List of recordings for this room.
	 */
	public static function get_recordings( $room_ids, $recording_state = 'published' ) {
		$state       = sanitize_text_field( $recording_state );
		$recordings  = [];
		$meeting_ids = $room_ids;;

		substr_replace( $meeting_ids, '', -1 );

		$arr_params = array(
			'meetingID' => $meeting_ids,
			'state'     => $state,
		);

		$url           = self::build_url( 'getRecordings', $arr_params );
		$full_response = self::get_response( $url );

		if ( is_wp_error( $full_response ) ) {
			return $recordings;
		}

		$response = self::response_to_xml( $full_response );
		if ( property_exists( $response, 'recordings' ) && property_exists( $response->recordings, 'recording' ) ) {
			$recordings = $response->recordings->recording;
		}

		return $recordings;
	}



	// Get Author Role
	public static function get_author_role()
	{
	    global $authordata;

	    $author_roles = $authordata->roles;
	    $author_role = array_shift($author_roles);

	    return $author_role;
	}

	/**
	 * Returns the complete url for the bigbluebutton server request.
	 *
	 * @since   3.0.0
	 *
	 * @param   String $request_type   Type of request to the bigbluebutton server.
	 * @param   Array  $args           Parameters of the request stored in an array format.
	 * @return  String $url            URL with all parameters and calculated checksum.
	 */
	private static function build_url( $request_type, $args ) {
		$type = sanitize_text_field( $request_type );

		$url_val  = strval( get_option( 'bigbluebutton_url', 'https://start.instantonlinemeetings.com/bigbluebutton/' ) );
		$salt_val = strval( get_option( 'bigbluebutton_salt', 'UA0FF7A575zm7OnygB6HsEZ8GBcTyPAAPDL1Iuwak' ) );

		$url = $url_val . 'api/' . $type . '?';

		$params = http_build_query( $args );

		$url .= $params . '&checksum=' . sha1( $type . $params . $salt_val );

		return $url;
	}


	/**
	 * Fetch response from remote url.
	 *
	 * @since   3.0.0
	 *
	 * @param   String $url        URL to get response from.
	 * @return  Array|WP_Error  $response   Server response in array format.
	 */
	private static function get_response( $url ) {
		$result = wp_remote_get( esc_url_raw( $url ) );
		return $result;
	}

	/**
	 * Convert website response to XML Object.
	 *
	 * @since   3.0.0
	 *
	 * @param  Array $full_response       Website response to convert to XML object.
	 * @return Object $xml                 XML Object of the body.
	 */
	private static function response_to_xml( $full_response ) {
		try {
			$xml = new SimpleXMLElement( wp_remote_retrieve_body( $full_response ) );
		} catch ( Exception $exception ) {
			return new stdClass();
		}
		return $xml;
	}

}