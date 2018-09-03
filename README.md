# SpeedRunner

Mach 3+ speed optimisations for WordPress inspired by the [SR-71 Blackbird](https://en.wikipedia.org/wiki/Lockheed_SR-71_SpeedRunner).

## Requirements
* [Git](https://git-scm.com)
* Composer - [Install](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-osx)
* PHP >= 5.6+

## Installation

Install dependencies with Composer:

```bash
$ composer install
```

## Theme Features

- Lazy loading
- CDN version of jQuery
- Cache busting

## Lazy loading

The lazy loading theme feature modifies default WordPress image and embed markup to faciliate lazy loading via the the modern transparent srcset pattern. The Lazysizes JavaScript library is enqueued automatically when using this feature.

### Usage

Enable lazy loading within your theme by using `add_theme_support` in your theme's `after_setup_theme` function.

```php
add_action( 'after_setup_theme', 'theme_setup' );

function theme_setup() {
	add_theme_support( 'speedrunner-enable-lazy-loading' );
}
```

#### Styles

Lazysizes adds the class lazyloading while the images are loading and the class lazyloaded as soon as the image is loaded. The following styles should be added to your theme as a minimum starting point:

```css
/* fade image in after load */
.lazyload,
.lazyloading {
	opacity: 0;
}
.lazyloaded {
	opacity: 1;
	transition: opacity 300ms;
}
```

#### Template functions

Lazy loading can be enabled on a per image basis by adding the `lazyload` class. Images can also be wrapped in a container with an optional intrinsic ratio. Containers can currently only be applied to post thumbnails when using the standard WordPress template functions. A custom `get_wrapped_attachment_image` function can be used to wrap other attachment images.

Lazy load the post thumbnail and wrap it in a container with an intrinsic ratio:
```php
the_post_thumbnail( 'medium', [
	'class'   => 'lazyload',
	'wrapper' => 'u-ratio customClass',
	'ratio'   => true,
] );
```
The ratio is applied via an inline style with padding-bottom. The default ratio container class is `u-ratio`. The class can be customised by setting `wrapper` to a string.

Lazy load a WordPress image attachment:
```php
echo wp_get_attachment_image(
	get_post_thumbnail_id(),
	'medium',
	false,
	[
		'class' => 'lazyload',
	]
);
```

Lazy load a WordPress image attachment but wrapped in a container with an intrinsic ratio:
```php
use function Sup\SpeedRunner\Template\get_wrapped_attachment_image;

echo get_wrapped_attachment_image(
	get_post_thumbnail_id(),
	'medium',
	[
		'class'   => 'lazyload',
		'wrapper' => 'u-ratio customClass',
		'ratio'   => true,
	]
);
```

#### Disable JavaScript
The Lazysizes JavaScript enqueued by SpeedRunner can be removed by passing the `'enqueue' => false` option when adding lazy loading theme support.

```php
add_theme_support( 'speedrunner-enable-lazy-loading', [
	'enqueue' => false,
] );
```
