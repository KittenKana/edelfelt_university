# Google reCAPTCHA v2 & v3

Provides support for Google reCAPTCHA v2 & v3

![captura de ecra 2015-10-26 as 16 38 26](https://cloud.githubusercontent.com/assets/1154179/10735465/05cfa2f2-7c00-11e5-9070-835837ae2499.png)

It provides three helper functions:
* `appthemes_display_recaptcha` - Display the reCAPTCHA
* `appthemes_recaptcha_verify` - Verifies the user response
* `appthemes_enqueue_recaptcha_scripts` - Adds action to `wp_enqueue_scripts` and enqueues necessary scripts

Accepted theme support parameters:
- `public_key` *already existed*
- `private_key` *already existed*
- `api_version` - v3 - *values:* `v2` (default) | `v3`
- `score` - v3 - *values:* `0.5` (default) | `0.1...1.0`
- `theme` - v2 - *values:* `light` (default) | `dark`
- `size` - v2 - *values:* `normal` (default) | `compact` (default if `wp_is_mobile()` is **true**)
- `type` - v2 - *values:* `image` (default) | `audio`

**Changes from older version**
- Themes no longer need to include the reCAPTCHA library passing it trough the `file` param since the new library is included in the framework. The module still checks for this parameter to fallback to the old reCAPTCHA in case the old library is still being used.
- The custom `appthemes_recaptcha()` helper used by each theme to display the reCAPTCHA is replaced by `appthemes_display_recaptcha()` provided by the module
- The available reCAPTCHA colors are now limited to: `Light` and `Dark`.

**Usage**

Themes must explicitly enqueue the JS reCAPTCHA library using the `g-recaptcha` handle or function `appthemes_enqueue_recaptcha_scripts()`, on every page where the reCAPTCHA will be displayed. This ensures it is not enqueued on every page.

* *Enqueue*

```
if ( current_theme_supports( 'app-recaptcha' ) ) {
	appthemes_enqueue_recaptcha_scripts();
}
```

* *Display*

```
if ( current_theme_supports( 'app-recaptcha' ) ) {
	appthemes_display_recaptcha()
}
```

* *Verify Response*

```
if ( current_theme_supports( 'app-recaptcha' ) ) {

	// Verify the user response.
	$response = appthemes_recaptcha_verify();
	if ( is_wp_error( $response ) ) {
		// Error! - check WP_Error object
		return $response;
	}
	// Success!!
}
```
