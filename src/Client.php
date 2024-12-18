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
	 * Default options for map configuration
	 *
	 * @var array
	 */
	private array $options = [
		'zoom'     => 12,      // Default city-level zoom
		'maptype'  => 'roadmap', // Default map type
		'language' => '',      // Default to no specific language
		'region'   => '',      // Default to no specific region
		'heading'  => 0,       // Default street view heading (north)
		'pitch'    => 0,       // Default street view pitch (horizontal)
		'fov'      => 90,      // Default street view field of view
		'mode'     => 'driving', // Default travel mode for directions
		'avoid'    => [],      // Default to no route avoidance
		'units'    => 'metric' // Default measurement units
	];

	/**
	 * Initialize the Maps Embed client
	 *
	 * @param string $api_key API key for Google Maps
	 */
	public function __construct( string $api_key ) {
		$this->api_key = $api_key;
	}

	/**
	 * Set the zoom level for the map
	 *
	 * @param int $level Zoom level (0-21)
	 *                   0: World view
	 *                   5: Continent/Region
	 *                   10: City
	 *                   15: Streets
	 *                   20: Buildings
	 *
	 * @return self
	 */
	public function set_zoom( int $level ): self {
		$this->options['zoom'] = max( 0, min( 21, $level ) );

		return $this;
	}

	/**
	 * Set the map type
	 *
	 * @param string $type Map type (roadmap, satellite)
	 *
	 * @return self
	 */
	public function set_map_type( string $type ): self {
		if ( in_array( $type, [ 'roadmap', 'satellite' ] ) ) {
			$this->options['maptype'] = $type;
		}

		return $this;
	}

	/**
	 * Set the language for map labels and controls
	 *
	 * @param string $language Language code (e.g., 'en', 'es', 'fr')
	 *                         See: https://developers.google.com/maps/faq#languagesupport
	 *
	 * @return self
	 */
	public function set_language( string $language ): self {
		$this->options['language'] = $language;

		return $this;
	}

	/**
	 * Set the region bias for the map
	 *
	 * @param string $region Region code (e.g., 'US', 'GB')
	 *                       See: https://developers.google.com/maps/coverage
	 *
	 * @return self
	 */
	public function set_region( string $region ): self {
		$this->options['region'] = $region;

		return $this;
	}

	/**
	 * Set the street view camera heading
	 *
	 * @param float $degrees Heading in degrees (0-360)
	 *                       0: North
	 *                       90: East
	 *                       180: South
	 *                       270: West
	 *
	 * @return self
	 */
	public function set_heading( float $degrees ): self {
		$this->options['heading'] = max( 0, min( 360, $degrees ) );

		return $this;
	}

	/**
	 * Set the street view camera pitch
	 *
	 * @param float $degrees Pitch in degrees (-90 to 90)
	 *                       -90: Straight down
	 *                       0: Horizontal
	 *                       90: Straight up
	 *
	 * @return self
	 */
	public function set_pitch( float $degrees ): self {
		$this->options['pitch'] = max( - 90, min( 90, $degrees ) );

		return $this;
	}

	/**
	 * Set the street view field of view
	 *
	 * @param float $degrees Field of view in degrees (10-100)
	 *                       Lower values = more zoom
	 *                       Higher values = wider angle
	 *
	 * @return self
	 */
	public function set_fov( float $degrees ): self {
		$this->options['fov'] = max( 10, min( 100, $degrees ) );

		return $this;
	}

	/**
	 * Set the travel mode for directions
	 *
	 * @param string $mode Mode (driving, walking, bicycling, transit)
	 *
	 * @return self
	 */
	public function set_travel_mode( string $mode ): self {
		if ( in_array( $mode, [ 'driving', 'walking', 'bicycling', 'transit' ] ) ) {
			$this->options['mode'] = $mode;
		}

		return $this;
	}

	/**
	 * Set routes to avoid in directions
	 *
	 * @param array $avoid Features to avoid (tolls, ferries, highways)
	 *
	 * @return self
	 */
	public function set_avoid_routes( array $avoid ): self {
		$valid_avoid            = array_intersect( $avoid, [ 'tolls', 'ferries', 'highways' ] );
		$this->options['avoid'] = $valid_avoid;

		return $this;
	}

	/**
	 * Set the measurement units for directions
	 *
	 * @param string $units Units system (metric, imperial)
	 *
	 * @return self
	 */
	public function set_units( string $units ): self {
		if ( in_array( $units, [ 'metric', 'imperial' ] ) ) {
			$this->options['units'] = $units;
		}

		return $this;
	}

	/**
	 * Reset all options to their default values
	 *
	 * @return self
	 */
	public function reset_options(): self {
		$this->options = [
			'zoom'     => 12,
			'maptype'  => 'roadmap',
			'language' => '',
			'region'   => '',
			'heading'  => 0,
			'pitch'    => 0,
			'fov'      => 90,
			'mode'     => 'driving',
			'avoid'    => [],
			'units'    => 'metric'
		];

		return $this;
	}

	/**
	 * Generate embed URL for a place
	 *
	 * @param string $place_id Google Place ID
	 * @param array  $options  Additional options for the embed
	 *                         - language (string): Language code
	 *                         - region (string): Region code
	 *
	 * @return string|WP_Error URL for the embed or WP_Error on failure
	 */
	public function place( string $place_id, array $options = [] ) {
		return $this->generate_url( 'place', array_merge(
			[ 'q' => 'place_id:' . $place_id ],
			$this->options,
			$options
		) );
	}

	/**
	 * Generate embed URL for a search query
	 *
	 * @param string $query   Search query
	 * @param array  $options Additional options for the embed
	 *                        - language (string): Language code
	 *                        - region (string): Region code
	 *
	 * @return string|WP_Error URL for the embed or WP_Error on failure
	 */
	public function search( string $query, array $options = [] ) {
		return $this->generate_url( 'search', array_merge(
			[ 'q' => $query ],
			$this->options,
			$options
		) );
	}

	/**
	 * Generate embed URL for a view
	 *
	 * @param float $latitude  Latitude
	 * @param float $longitude Longitude
	 * @param array $options   Additional options for the embed
	 *                         - zoom (int): Zoom level from 0 (world) to 21 (street)
	 *                         - maptype (string): roadmap, satellite
	 *                         - language (string): Language code
	 *                         - region (string): Region code
	 *
	 * @return string|WP_Error URL for the embed or WP_Error on failure
	 */
	public function view( float $latitude, float $longitude, array $options = [] ) {
		return $this->generate_url( 'view', array_merge(
			[
				'center'  => "{$latitude},{$longitude}",
				'zoom'    => $this->options['zoom'],
				'maptype' => $this->options['maptype']
			],
			$this->options,
			$options
		) );
	}

	/**
	 * Generate embed URL for directions
	 *
	 * @param string $origin      Starting location
	 * @param string $destination Ending location
	 * @param array  $options     Additional options for the embed
	 *                            - mode (string): driving, walking, bicycling, transit
	 *                            - avoid (array): tolls, ferries, highways
	 *                            - units (string): metric, imperial
	 *                            - language (string): Language code
	 *                            - region (string): Region code
	 *
	 * @return string|WP_Error URL for the embed or WP_Error on failure
	 */
	public function directions( string $origin, string $destination, array $options = [] ) {
		$params = [
			'origin'      => $origin,
			'destination' => $destination,
			'mode'        => $this->options['mode'],
			'units'       => $this->options['units']
		];

		if ( ! empty( $this->options['avoid'] ) ) {
			$params['avoid'] = implode( '|', $this->options['avoid'] );
		}

		return $this->generate_url( 'directions', array_merge(
			$params,
			$this->options,
			$options
		) );
	}

	/**
	 * Generate embed URL for a street view
	 *
	 * @param float $latitude  Latitude
	 * @param float $longitude Longitude
	 * @param array $options   Additional options for the embed
	 *                         - heading (float): Camera heading in degrees (0-360)
	 *                         - pitch (float): Camera pitch in degrees (-90 to 90)
	 *                         - fov (float): Field of view in degrees (10-100)
	 *                         - language (string): Language code
	 *                         - region (string): Region code
	 *
	 * @return string|WP_Error URL for the embed or WP_Error on failure
	 */
	public function streetview( float $latitude, float $longitude, array $options = [] ) {
		return $this->generate_url( 'streetview', array_merge(
			[
				'location' => "{$latitude},{$longitude}",
				'heading'  => $this->options['heading'],
				'pitch'    => $this->options['pitch'],
				'fov'      => $this->options['fov']
			],
			$this->options,
			$options
		) );
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
	 * @return bool|WP_Error True if valid, WP_Error if missing required params
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