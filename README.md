# Google Maps Embed API for WordPress

A PHP library for integrating with the Google Maps Embed API in WordPress, providing easy-to-use methods for embedding Google Maps with various modes including places, search, directions, street view, and standard views. Features WordPress integration, method chaining, and `WP_Error` support.

## Features

- 🗺️ **Multiple Embed Modes**: Support for places, search, directions, view, and street view
- 📍 **Place Integration**: Embed maps using Google Place IDs
- 🔍 **Search Support**: Embed maps with search queries
- 🚗 **Directions**: Show routes between locations
- 🏠 **Street View**: Embed street-level imagery
- ⚡ **WordPress Integration**: Native WP_Error support and escaping
- 🛡️ **Type Safety**: Full type hinting and strict types
- 🎨 **Customizable**: Flexible iframe attributes and styling options
- 🌍 **Global Support**: Works with locations worldwide
- 🔐 **Secure**: Built-in security features including referrer policy
- 📱 **Responsive**: Support for responsive iframe attributes
- ✨ **Easy Implementation**: Simple, intuitive API methods
- 🔄 **Method Chaining**: Fluent interface for setting options
- ⚙️ **Validation**: Built-in parameter validation for all setters

## Requirements

- PHP 7.4 or later
- WordPress 5.0 or later
- Google Maps Embed API key

## Installation

Install via Composer:

```bash
composer require arraypress/google-maps-embed
```

## Basic Usage

```php
use ArrayPress\Google\MapsEmbed\Client;

// Initialize client with your API key
$client = new Client( 'your-google-api-key' );

// Configure options using method chaining
$client
    ->set_mode( 'driving' )
    ->set_units( 'imperial' )
    ->set_zoom( 15 )
    ->set_language( 'en' );

// Embed a place using Place ID
$place_url = $client->place( 'ChIJN1t_tDeuEmsRUsoyG83frY4' );
$iframe = $client->generate_iframe( $place_url );

// Embed a search result
$search_url = $client->search( 'Coffee shops in Seattle' );
$iframe = $client->generate_iframe( $search_url );

// Embed directions
$directions_url = $client->directions( 'Seattle, WA', 'Portland, OR' );
$iframe = $client->generate_iframe( $directions_url );
```

## Configuration Methods

### Setting Options

```php
// Travel mode
$client->set_mode( 'driving|walking|bicycling|transit' );

// Map type
$client->set_map_type( 'roadmap|satellite' );

// Units
$client->set_units( 'metric|imperial' );

// Route avoidance
$client->set_avoid( ['tolls', 'highways', 'ferries'] );

// Zoom level (0-21)
$client->set_zoom( 15 );

// Street view settings
$client->set_heading( 90 );    // 0-360 degrees
$client->set_pitch( -30 );     // -90 to 90 degrees
$client->set_fov( 60 );        // 10-100 degrees

// Localization
$client->set_language( 'en' );
$client->set_region( 'US' );

// API key management
$client->set_api_key( 'new-api-key' );
```

### Getting Options

```php
// Get current settings
$mode = $client->get_mode();
$map_type = $client->get_map_type();
$units = $client->get_units();
$avoid = $client->get_avoid();
$zoom = $client->get_zoom();
$heading = $client->get_heading();
$pitch = $client->get_pitch();
$fov = $client->get_fov();
$language = $client->get_language();
$region = $client->get_region();
$api_key = $client->get_api_key();

// Get all options
$all_options = $client->get_options();

// Reset options to defaults
$client->reset_options();
```

## Extended Examples

### Embedding a Place

```php
$client = new Client( 'your-api-key' );

// Basic place embed
$place_url = $client
	->set_zoom( 15 )
	->set_language( 'en' )
	->place( 'ChIJN1t_tDeuEmsRUsoyG83frY4' );

// Generate iframe with custom attributes
$iframe = $client->generate_iframe( $place_url, [
	'width'  => '800',
	'height' => '600',
	'class'  => 'my-custom-map'
] );
```

### Working with Different View Types

```php
// Standard view using coordinates
$view_url = $client
	->set_zoom( 12 )
	->set_map_type( 'satellite' )
	->view( 47.6062, - 122.3321 );

// Street view with camera settings
$street_url = $client
	->set_heading( 90 )
	->set_pitch( 10 )
	->set_fov( 75 )
	->streetview( 47.6062, - 122.3321 );

// Search with specific parameters
$search_url = $client
	->set_zoom( 13 )
	->set_language( 'en' )
	->search( 'Parks in Seattle' );
```

### Customizing the Iframe

```php
$url    = $client->place( 'ChIJN1t_tDeuEmsRUsoyG83frY4' );
$iframe = $client->generate_iframe( $url, [
	'width'           => '100%',
	'height'          => '450',
	'class'           => 'google-map',
	'id'              => 'location-map',
	'style'           => 'border: 2px solid #ccc;',
	'loading'         => 'lazy',
	'allowfullscreen' => true
] );
```

### Handling Directions

```php
// Configure options for directions
$client
	->set_mode( 'driving' )
	->set_avoid( [ 'tolls', 'highways' ] )
	->set_units( 'imperial' )
	->set_language( 'en' );

// Generate directions URL
$directions_url = $client->directions( 'Seattle, WA', 'Portland, OR' );
```

## API Methods

### Client Methods

* `place( $place_id, $options = [] )`: Generate URL for place embed
* `search( $query, $options = [] )`: Generate URL for search results
* `view( $latitude, $longitude, $options = [] )`: Generate URL for map view
* `directions( $origin, $destination, $options = [] )`: Generate URL for directions
* `streetview( $latitude, $longitude, $options = [] )`: Generate URL for street view
* `generate_iframe( $url, $attrs = [] )`: Generate complete iframe HTML

### Setters and Getters

* Travel Mode: `set_mode()`, `get_mode()`
* Map Type: `set_map_type()`, `get_map_type()`
* Units: `set_units()`, `get_units()`
* Avoid Routes: `set_avoid()`, `get_avoid()`
* Zoom Level: `set_zoom()`, `get_zoom()`
* Street View Camera:
  - `set_heading()`, `get_heading()`
  - `set_pitch()`, `get_pitch()`
  - `set_fov()`, `get_fov()`
* Localization:
  - `set_language()`, `get_language()`
  - `set_region()`, `get_region()`
* API Management:
  - `set_api_key()`, `get_api_key()`
  - `get_options()`, `reset_options()`

### Option Values

#### Travel Modes
* `driving`: Default driving directions
* `walking`: Walking directions
* `bicycling`: Bicycling directions
* `transit`: Public transit directions

#### Map Types
* `roadmap`: Default road map view
* `satellite`: Satellite imagery

#### Units
* `metric`: Kilometers and meters
* `imperial`: Miles and feet

#### Route Avoidance
* `tolls`: Avoid toll roads
* `highways`: Avoid highways
* `ferries`: Avoid ferries

## Use Cases

* **Business Locations**: Display store or office locations
* **Event Maps**: Show event venues and directions
* **Property Listings**: Display real estate locations
* **Travel Planning**: Show routes and destinations
* **Location Discovery**: Embed searchable maps
* **Virtual Tours**: Street view integration
* **Contact Pages**: Display business locations
* **Directory Listings**: Show multiple locations
* **Travel Guides**: Display tourist destinations
* **Store Locators**: Help customers find locations

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## License

This project is licensed under the GPL-2.0-or-later License.

## Support

- [Documentation](https://github.com/arraypress/google-maps-embed)
- [Issue Tracker](https://github.com/arraypress/google-maps-embed/issues)