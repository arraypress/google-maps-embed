# Google Maps Embed API for WordPress

A PHP library for integrating with the Google Maps Embed API in WordPress, providing easy-to-use methods for embedding Google Maps with various modes including places, search, directions, street view, and standard views. Features WordPress integration and `WP_Error` support.

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
$client = new Client( 'your-google-api-key'  );

// Embed a place using Place ID
$place_url = $client->place( 'ChIJN1t_tDeuEmsRUsoyG83frY4'  );
$iframe = $client->generate_iframe( $place_url  );

// Embed a search result
$search_url = $client->search( 'Coffee shops in Seattle' );
$iframe = $client->generate_iframe( $search_url );

// Embed directions
$directions_url = $client->directions( 'Seattle, WA', 'Portland, OR' );
$iframe = $client->generate_iframe( $directions_url );
```

## Extended Examples

### Embedding a Place

```php
$client = new Client( 'your-api-key' );

// Basic place embed
$place_url = $client->place( 'ChIJN1t_tDeuEmsRUsoyG83frY4' );

// Place with additional options
$place_url = $client->place( 'ChIJN1t_tDeuEmsRUsoyG83frY4', [
    'zoom' => 15,
    'language' => 'en'
] );

// Generate iframe with custom attributes
$iframe = $client->generate_iframe( $place_url, [
    'width' => '800',
    'height' => '600',
    'class' => 'my-custom-map'
] );
```

### Working with Different View Types

```php
// Standard view using coordinates
$view_url = $client->view( 47.6062, -122.3321, [
    'zoom' => 12
] );

// Street view
$street_url = $client->streetview( 47.6062, -122.3321, [
    'heading' => 90,
    'pitch' => 10
] );

// Search with specific parameters
$search_url = $client->search( 'Parks in Seattle', [
    'zoom' => 13,
    'language' => 'en'
] );
```

### Customizing the Iframe

```php
$url = $client->place( 'ChIJN1t_tDeuEmsRUsoyG83frY4' );
$iframe = $client->generate_iframe( $url, [
    'width' => '100%',
    'height' => '450',
    'class' => 'google-map',
    'id' => 'location-map',
    'style' => 'border: 2px solid #ccc;',
    'loading' => 'lazy',
    'allowfullscreen' => true
] );
```

### Handling Directions

```php
// Basic directions
$directions_url = $client->directions( 'Seattle, WA', 'Portland, OR' );

// Directions with options
$directions_url = $client->directions(
    'Seattle, WA',
    'Portland, OR',
    [
        'mode' => 'driving',
        'avoid' => 'tolls|highways',
        'units' => 'imperial'
    ]
 );
```

## API Methods

### Client Methods

* `place( $place_id, $options = [])`: Generate URL for place embed
* `search( $query, $options = [])`: Generate URL for search results
* `view( $latitude, $longitude, $options = [])`: Generate URL for map view
* `directions( $origin, $destination, $options = [])`: Generate URL for directions
* `streetview( $latitude, $longitude, $options = [])`: Generate URL for street view
* `generate_iframe( $url, $attrs = [])`: Generate complete iframe HTML

### Options Parameters

#### Common Options
* `key`: API key (automatically added)
* `language`: Map language
* `region`: Region bias

#### Mode-Specific Options
* Place/Search:
    * `zoom`: Zoom level
* Directions:
    * `mode`: Travel mode (driving, walking, bicycling, transit)
    * `avoid`: Route restrictions (tolls, highways, ferries)
    * `units`: Unit system (metric, imperial)
* Street View:
    * `heading`: Camera heading in degrees
    * `pitch`: Camera pitch in degrees
    * `fov`: Field of view

#### Iframe Attributes
* `width`: Iframe width
* `height`: Iframe height
* `style`: CSS styles
* `class`: CSS classes
* `id`: Element ID
* `loading`: Loading behavior
* `allowfullscreen`: Fullscreen permission
* `referrerpolicy`: Referrer policy

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