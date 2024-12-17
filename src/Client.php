<?php
/**
 * Google Maps Embed API Client Class
 *
 * @package     ArrayPress\Google\MapsEmbed
 * @copyright   Copyright (c) 2024, ArrayPress Limited
 * @license     GPL2+
 * @version     1.0.0
 * @author      David Sherlock
 */

declare( strict_types=1 );

namespace ArrayPress\Google\MapsEmbed;

use WP_Error;

/**
 * Class Client
 *
 * A comprehensive utility class for interacting with the Google Maps Embed API.
 */
class Client {

	/**
	 * API key for Google Maps
	 *
	 * @var string
	 */
	private string $api_key;

	/**
	 * Base URL for the Maps Embed API
	 *
	 * @var string
	 */
	private const API_ENDPOINT = 'https://www.google.com/maps/embed/v1/';

	/**
	 * Initialize the Maps Embed client
	 *
	 * @param string $api_key API key for Google Maps
	 */
	public function __construct( string $api_key ) {
		$this->api_key = $api_key;
	}

	/**
	 * Generate embed URL for a place
	 *
	 * @param string $place_id Google Place ID
	 * @param array  $options  Additional options for the embed
	 *
	 * @return string|WP_Error  URL for the embed or WP_Error on failure
	 */
	public function place( string $place_id, array $options = [] ) {
		return $this->generate_url( 'place', array_merge(
			[ 'q' => 'place_id:' . $place_id ],
			$options
		) );
	}

	/**
	 * Generate embed URL for a search query
	 *
	 * @param string $query   Search query
	 * @param array  $options Additional options for the embed
	 *
	 * @return string|WP_Error URL for the embed or WP_Error on failure
	 */
	public function search( string $query, array $options = [] ) {
		return $this->generate_url( 'search', array_merge(
			[ 'q' => $query ],
			$options
		) );
	}

	/**
	 * Generate embed URL for a view
	 *
	 * @param float $latitude  Latitude
	 * @param float $longitude Longitude
	 * @param array $options   Additional options for the embed
	 *
	 * @return string|WP_Error   URL for the embed or WP_Error on failure
	 */
	public function view( float $latitude, float $longitude, array $options = [] ) {
		return $this->generate_url( 'view', array_merge(
			[ 'center' => "{$latitude},{$longitude}" ],
			$options
		) );
	}

	/**
	 * Generate embed URL for directions
	 *
	 * @param string $origin      Starting location
	 * @param string $destination Ending location
	 * @param array  $options     Additional options for the embed
	 *
	 * @return string|WP_Error    URL for the embed or WP_Error on failure
	 */
	public function directions( string $origin, string $destination, array $options = [] ) {
		return $this->generate_url( 'directions', array_merge(
			[
				'origin'      => $origin,
				'destination' => $destination
			],
			$options
		) );
	}

	/**
	 * Generate embed URL for a street view
	 *
	 * @param float $latitude  Latitude
	 * @param float $longitude Longitude
	 * @param array $options   Additional options for the embed
	 *
	 * @return string|WP_Error   URL for the embed or WP_Error on failure
	 */
	public function streetview( float $latitude, float $longitude, array $options = [] ) {
		return $this->generate_url( 'streetview', array_merge(
			[ 'location' => "{$latitude},{$longitude}" ],
			$options
		) );
	}

	/**
	 * Generate the complete iframe HTML
	 *
	 * @param string $url   The embed URL
	 * @param array  $attrs Additional iframe attributes
	 *
	 * @return string        Complete iframe HTML
	 */
	public function generate_iframe( string $url, array $attrs = [] ): string {
		$default_attrs = [
			'width'           => '600',
			'height'          => '450',
			'frameborder'     => '0',
			'style'           => 'border:0',
			'allowfullscreen' => true,
			'loading'         => 'lazy',
			'referrerpolicy'  => 'no-referrer-when-downgrade'
		];

		$merged_attrs = array_merge( $default_attrs, $attrs );
		$attr_string  = '';

		foreach ( $merged_attrs as $key => $value ) {
			if ( is_bool( $value ) ) {
				if ( $value ) {
					$attr_string .= " $key";
				}
			} else {
				$attr_string .= " $key=\"" . esc_attr( $value ) . "\"";
			}
		}

		return sprintf(
			'<iframe src="%s"%s></iframe>',
			esc_url( $url ),
			$attr_string
		);
	}

	/**
	 * Generate the API URL
	 *
	 * @param string $mode   The embed mode
	 * @param array  $params URL parameters
	 *
	 * @return string|WP_Error
	 */
	private function generate_url( string $mode, array $params = [] ) {
		if ( empty( $this->api_key ) ) {
			return new WP_Error(
				'missing_api_key',
				__( 'Google Maps API key is required', 'arraypress' )
			);
		}

		$params['key'] = $this->api_key;
		$url           = self::API_ENDPOINT . $mode;

		return add_query_arg( $params, $url );
	}

	/**
	 * Validate required parameters
	 *
	 * @param array $params   Parameters to validate
	 * @param array $required Required parameter keys
	 *
	 * @return bool|WP_Error  True if valid, WP_Error if missing required params
	 */
	private function validate_params( array $params, array $required ) {
		$missing = array_diff( $required, array_keys( $params ) );

		if ( ! empty( $missing ) ) {
			return new WP_Error(
				'missing_params',
				sprintf(
					__( 'Missing required parameters: %s', 'arraypress' ),
					implode( ', ', $missing )
				)
			);
		}

		return true;
	}

}