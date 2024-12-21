<?php
/**
 * Google Maps Embed API Client Class
 *
 * A comprehensive PHP library for generating embeddable Google Maps URLs and iframes.
 * Supports place, search, view, directions, and street view modes.
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
 * Main client class for generating embeddable Google Maps.
 *
 * @package ArrayPress\Google\MapsEmbed
 */
class Client {
	use Parameters;

	/**
	 * Base URL for the Maps Embed API
	 *
	 * @var string
	 */
	private const API_ENDPOINT = 'https://www.google.com/maps/embed/v1/';

	/**
	 * Initialize the Maps Embed client
	 *
	 * @param string $api_key Google Maps API key
	 */
	public function __construct( string $api_key ) {
		$this->set_api_key( $api_key );
	}

	/**
	 * Generate embed URL for a place
	 *
	 * @param string $place_id Google Place ID
	 * @param array  $options  Additional options for the embed
	 *
	 * @return string|WP_Error URL for the embed or WP_Error on failure
	 */
	public function place( string $place_id, array $options = [] ) {
		$params = array_merge(
			[ 'q' => 'place_id:' . $place_id ],
			$this->get_common_options(),
			$options
		);

		return $this->generate_url( 'place', $params );
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
		$params = array_merge(
			[ 'q' => $query ],
			$this->get_common_options(),
			$options
		);

		return $this->generate_url( 'search', $params );
	}

	/**
	 * Generate embed URL for a specific view
	 *
	 * @param float $latitude  Latitude coordinate
	 * @param float $longitude Longitude coordinate
	 * @param array $options   Additional options for the embed
	 *
	 * @return string|WP_Error URL for the embed or WP_Error on failure
	 */
	public function view( float $latitude, float $longitude, array $options = [] ) {
		$params = array_merge(
			[
				'center'  => "{$latitude},{$longitude}",
				'zoom'    => $this->get_zoom(),
				'maptype' => $this->get_map_type()
			],
			$this->get_common_options(),
			$options
		);

		return $this->generate_url( 'view', $params );
	}

	/**
	 * Generate embed URL for directions
	 *
	 * @param string $origin      Starting location
	 * @param string $destination Ending location
	 * @param array  $options     Additional options for the embed
	 *
	 * @return string|WP_Error URL for the embed or WP_Error on failure
	 */
	public function directions( string $origin, string $destination, array $options = [] ) {
		$direction_params = $this->get_all_params()['direction'];

		$params = [
			'origin'      => $origin,
			'destination' => $destination,
			'mode'        => $direction_params['mode']
		];

		if ( ! empty( $direction_params['avoid'] ) ) {
			$params['avoid'] = implode( '|', $direction_params['avoid'] );
		}

		if ( ! empty( $direction_params['units'] ) ) {
			$params['units'] = $direction_params['units'];
		}

		$params = array_merge(
			$params,
			$this->get_common_options(),
			$options
		);

		return $this->generate_url( 'directions', $params );
	}

	/**
	 * Generate embed URL for street view
	 *
	 * @param float $latitude  Latitude coordinate
	 * @param float $longitude Longitude coordinate
	 * @param array $options   Additional options for the embed
	 *
	 * @return string|WP_Error URL for the embed or WP_Error on failure
	 */
	public function streetview( float $latitude, float $longitude, array $options = [] ) {
		$view_params = $this->get_all_params()['view'];

		$params = [
			'location' => "{$latitude},{$longitude}"
		];

		if ( $view_params['heading'] !== 0 ) {
			$params['heading'] = $view_params['heading'];
		}
		if ( $view_params['pitch'] !== 0 ) {
			$params['pitch'] = $view_params['pitch'];
		}
		if ( $view_params['fov'] !== 90 ) {
			$params['fov'] = $view_params['fov'];
		}

		$params = array_merge(
			$params,
			$this->get_common_options(),
			$options
		);

		return $this->generate_url( 'streetview', $params );
	}

	/**
	 * Generate the complete iframe HTML
	 *
	 * @param string $url   The embed URL
	 * @param array  $attrs Additional iframe attributes
	 *
	 * @return string Complete iframe HTML
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
	 * Get current API key
	 *
	 * @return string Current API key
	 */
	public function get_api_key(): string {
		return $this->api_key;
	}

	/**
	 * Set new API key
	 *
	 * @param string $api_key The API key to use
	 *
	 * @return self
	 */
	public function set_api_key( string $api_key ): self {
		$this->api_key = $api_key;

		return $this;
	}

	/**
	 * Get common options that apply to all modes
	 *
	 * @return array<string, string> Common options
	 */
	private function get_common_options(): array {
		$map_params = $this->get_all_params()['map'];
		$common     = [];

		if ( ! empty( $map_params['language'] ) ) {
			$common['language'] = $map_params['language'];
		}
		if ( ! empty( $map_params['region'] ) ) {
			$common['region'] = $map_params['region'];
		}

		return $common;
	}

	/**
	 * Generate the API URL
	 *
	 * @param string $mode   The embed mode
	 * @param array  $params URL parameters
	 *
	 * @return string|WP_Error URL for the embed or WP_Error on failure
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

}