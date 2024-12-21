<?php
/**
 * Google Maps Embed API Parameters Trait
 *
 * @package     ArrayPress\Google\MapsEmbed
 * @copyright   Copyright (c) 2024, ArrayPress Limited
 * @license     GPL2+
 * @version     1.0.0
 * @author      David Sherlock
 */

declare( strict_types=1 );

namespace ArrayPress\Google\MapsEmbed\Traits;

use InvalidArgumentException;

/**
 * Trait Parameters
 *
 * Manages parameters for the Google Maps Embed API.
 *
 * @package ArrayPress\Google\MapsEmbed
 */
trait Parameters {

	/**
	 * API key for Google Maps
	 *
	 * @var string
	 */
	private string $api_key;

	/**
	 * Valid travel modes for directions
	 *
	 * @var array<string>
	 */
	private array $valid_modes = [
		'driving',
		'walking',
		'bicycling',
		'transit'
	];

	/**
	 * Valid map types for view mode
	 *
	 * @var array<string>
	 */
	private array $valid_map_types = [
		'roadmap',
		'satellite'
	];

	/**
	 * Valid units for distance measurements
	 *
	 * @var array<string>
	 */
	private array $valid_units = [
		'metric',
		'imperial'
	];

	/**
	 * Valid avoid options for directions
	 *
	 * @var array<string>
	 */
	private array $valid_avoid = [
		'tolls',
		'ferries',
		'highways'
	];

	/**
	 * Map parameters
	 *
	 * @var array<string, mixed>
	 */
	private array $map_params = [
		'zoom'     => 12,
		'maptype'  => 'roadmap',
		'language' => '',
		'region'   => ''
	];

	/**
	 * View parameters
	 *
	 * @var array<string, mixed>
	 */
	private array $view_params = [
		'heading' => 0,
		'pitch'   => 0,
		'fov'     => 90
	];

	/**
	 * Direction parameters
	 *
	 * @var array<string, mixed>
	 */
	private array $direction_params = [
		'mode'  => 'driving',
		'avoid' => [],
		'units' => 'metric'
	];

	/** API Key ******************************************************************/

	/**
	 * Set API key
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
	 * Get API key
	 *
	 * @return string
	 */
	public function get_api_key(): string {
		return $this->api_key;
	}

	/**
	 * Set travel mode for directions
	 *
	 * @param string $mode Travel mode (driving, walking, bicycling, transit)
	 *
	 * @return self
	 * @throws InvalidArgumentException If invalid mode provided
	 */
	public function set_mode( string $mode ): self {
		if ( ! in_array( $mode, $this->valid_modes ) ) {
			throw new InvalidArgumentException( "Invalid mode. Must be one of: " . implode( ', ', $this->valid_modes ) );
		}
		$this->direction_params['mode'] = $mode;

		return $this;
	}

	/**
	 * Get current travel mode
	 *
	 * @return string Current travel mode
	 */
	public function get_mode(): string {
		return $this->direction_params['mode'];
	}

	/**
	 * Set map type for view mode
	 *
	 * @param string $type Map type (roadmap, satellite)
	 *
	 * @return self
	 * @throws InvalidArgumentException If invalid type provided
	 */
	public function set_map_type( string $type ): self {
		if ( ! in_array( $type, $this->valid_map_types ) ) {
			throw new InvalidArgumentException( "Invalid map type. Must be one of: " . implode( ', ', $this->valid_map_types ) );
		}
		$this->map_params['maptype'] = $type;

		return $this;
	}

	/**
	 * Get current map type
	 *
	 * @return string Current map type
	 */
	public function get_map_type(): string {
		return $this->map_params['maptype'];
	}

	/**
	 * Set units for distance measurements
	 *
	 * @param string $units Units system (metric, imperial)
	 *
	 * @return self
	 * @throws InvalidArgumentException If invalid units provided
	 */
	public function set_units( string $units ): self {
		if ( ! in_array( $units, $this->valid_units ) ) {
			throw new InvalidArgumentException( "Invalid units. Must be one of: " . implode( ', ', $this->valid_units ) );
		}
		$this->direction_params['units'] = $units;

		return $this;
	}

	/**
	 * Get current units setting
	 *
	 * @return string Current units system
	 */
	public function get_units(): string {
		return $this->direction_params['units'];
	}

	/**
	 * Set avoid options for directions
	 *
	 * @param array $avoid Features to avoid (tolls, highways, ferries)
	 *
	 * @return self
	 * @throws InvalidArgumentException If invalid avoid option provided
	 */
	public function set_avoid( array $avoid ): self {
		$invalid = array_diff( $avoid, $this->valid_avoid );
		if ( ! empty( $invalid ) ) {
			throw new InvalidArgumentException( "Invalid avoid options: " . implode( ', ', $invalid ) );
		}
		$this->direction_params['avoid'] = $avoid;

		return $this;
	}

	/**
	 * Get current avoid options
	 *
	 * @return array Current avoid settings
	 */
	public function get_avoid(): array {
		return $this->direction_params['avoid'];
	}

	/**
	 * Set zoom level for map
	 *
	 * @param int $level Zoom level (0-21)
	 *
	 * @return self
	 * @throws InvalidArgumentException If invalid zoom level provided
	 */
	public function set_zoom( int $level ): self {
		if ( $level < 0 || $level > 21 ) {
			throw new InvalidArgumentException( "Invalid zoom level. Must be between 0 and 21." );
		}
		$this->map_params['zoom'] = $level;

		return $this;
	}

	/**
	 * Get current zoom level
	 *
	 * @return int Current zoom level
	 */
	public function get_zoom(): int {
		return $this->map_params['zoom'];
	}

	/**
	 * Set heading for street view
	 *
	 * @param float $degrees Heading in degrees (0-360)
	 *
	 * @return self
	 * @throws InvalidArgumentException If invalid heading provided
	 */
	public function set_heading( float $degrees ): self {
		if ( $degrees < 0 || $degrees > 360 ) {
			throw new InvalidArgumentException( "Invalid heading. Must be between 0 and 360 degrees." );
		}
		$this->view_params['heading'] = $degrees;

		return $this;
	}

	/**
	 * Get current heading
	 *
	 * @return float Current heading in degrees
	 */
	public function get_heading(): float {
		return $this->view_params['heading'];
	}

	/**
	 * Set pitch for street view
	 *
	 * @param float $degrees Pitch in degrees (-90 to 90)
	 *
	 * @return self
	 * @throws InvalidArgumentException If invalid pitch provided
	 */
	public function set_pitch( float $degrees ): self {
		if ( $degrees < - 90 || $degrees > 90 ) {
			throw new InvalidArgumentException( "Invalid pitch. Must be between -90 and 90 degrees." );
		}
		$this->view_params['pitch'] = $degrees;

		return $this;
	}

	/**
	 * Get current pitch
	 *
	 * @return float Current pitch in degrees
	 */
	public function get_pitch(): float {
		return $this->view_params['pitch'];
	}

	/**
	 * Set field of view for street view
	 *
	 * @param float $degrees Field of view in degrees (10-100)
	 *
	 * @return self
	 * @throws InvalidArgumentException If invalid FOV provided
	 */
	public function set_fov( float $degrees ): self {
		if ( $degrees < 10 || $degrees > 100 ) {
			throw new InvalidArgumentException( "Invalid field of view. Must be between 10 and 100 degrees." );
		}
		$this->view_params['fov'] = $degrees;

		return $this;
	}

	/**
	 * Get current field of view
	 *
	 * @return float Current FOV in degrees
	 */
	public function get_fov(): float {
		return $this->view_params['fov'];
	}

	/**
	 * Set language for map labels and controls
	 *
	 * @param string $language Language code (e.g., 'en', 'es', 'fr')
	 *
	 * @return self
	 */
	public function set_language( string $language ): self {
		$this->map_params['language'] = $language;

		return $this;
	}

	/**
	 * Get current language setting
	 *
	 * @return string Current language code
	 */
	public function get_language(): string {
		return $this->map_params['language'];
	}

	/**
	 * Set region bias for the map
	 *
	 * @param string $region Region code (e.g., 'US', 'GB')
	 *
	 * @return self
	 */
	public function set_region( string $region ): self {
		$this->map_params['region'] = $region;

		return $this;
	}

	/**
	 * Get current region setting
	 *
	 * @return string Current region code
	 */
	public function get_region(): string {
		return $this->map_params['region'];
	}

	/**
	 * Get all parameters
	 *
	 * @return array All current parameters
	 */
	public function get_all_params(): array {
		return [
			'map'       => $this->map_params,
			'view'      => $this->view_params,
			'direction' => $this->direction_params
		];
	}

	/**
	 * Reset map parameters
	 *
	 * @return self
	 */
	public function reset_map_params(): self {
		$this->map_params = [
			'zoom'     => 12,
			'maptype'  => 'roadmap',
			'language' => '',
			'region'   => ''
		];

		return $this;
	}

	/**
	 * Reset view parameters
	 *
	 * @return self
	 */
	public function reset_view_params(): self {
		$this->view_params = [
			'heading' => 0,
			'pitch'   => 0,
			'fov'     => 90
		];

		return $this;
	}

	/**
	 * Reset direction parameters
	 *
	 * @return self
	 */
	public function reset_direction_params(): self {
		$this->direction_params = [
			'mode'  => 'driving',
			'avoid' => [],
			'units' => 'metric'
		];

		return $this;
	}

	/**
	 * Reset all parameters
	 *
	 * @return self
	 */
	public function reset_all_params(): self {
		$this->reset_map_params();
		$this->reset_view_params();
		$this->reset_direction_params();

		return $this;
	}

}