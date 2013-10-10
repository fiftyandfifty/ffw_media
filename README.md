## Fifty & Fifty Frame Work Staff Plugin
==========

Create simple events for your site.

Needs
- [ ] Shortcodes
- [ ] Archive Template
- [ ] Meta Data (possibly using not using ACF)


### Features

Quickly change the slug with

```php
if( !defined('FFW_STAFF_SLUG') ){
	define( 'FFW_STAFF_SLUG', 'team' );
}
```

or quickly change the labels with

```php
function ffw_staff_labels( $labels ) {
	$labels = array(
	   'singular' => __('Team Member', 'your-domain'),
	   'plural' => __('Team Members', 'your-domain')
	);
	return $labels;
}
add_filter('ffw_staff_default_name', 'ffw_staff_labels');
```


### Changelog

Coming soon.