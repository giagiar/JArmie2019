<?php
/**
	Plugin Name: Embed Google Photos album
	Description: Embed your Google Photos album direct from Google Photos
	Version: 2.1.0
	Plugin URI: https://www.publicalbum.org/blog/embedding-google-photos-albums
	Author: pavex@ines.cz
	Author URI: https://www.publicalbum.org/blog/about-pavex
	License: GPLv2
	Text Domain: pavex-embed-google-photos-album
*/


class Pavex_embed_google_photos_album {

// Embed player component
	private $player_js = "https://cdn.jsdelivr.net/npm/publicalbum@latest/embed-ui.min.js";
	private $min_expiration = 86400;

	static public $name = "pavex-embed-player";
	static private $index = 1;


	public function __construct()
	{
		add_shortcode('embed-google-photos-album', array($this, 'shortcode'));
	}





	private function get_dimmension_attr($attrs, $name, $default)
	{
		if (isset($attrs[$name])) {
			if (strtolower($attrs[$name]) == 'auto') {
				return 0;
			}
			elseif (intval($attrs[$name]) > 0) {
				return intval($attrs[$name]);
			}
		}
		return $default;
	}





	public function create_default_props()
	{
		$props = new \StdClass();
		$props -> mode = 'gallery-player';
		$props -> link = NULL;
		$props -> width = 0;
		$props -> height = 480;
		$props -> imageWidth = 1920;
		$props -> imageHeight = 1080;
		$props -> includeThumbnails = NULL;
		$props -> attachMetadata = NULL;
		$props -> slideshowAutoplay = NULL;
		$props -> slideshowDelay = NULL;
		$props -> slideshowRepeat = NULL;
		$props -> mediaItemsAspectRatio = NULL;
		$props -> mediaItemsEnlarge = NULL;
		$props -> mediaItemsStretch = NULL;
		$props -> mediaItemsCover = NULL;
		$props -> expiration = 0;
		return $props;
	}





	public function shortcode($attrs)
	{
		if (count($attrs) == 0) {
			return NULL;
		}
		$props = $this -> create_default_props();
		$props -> link = isset($attrs['link']) ? $attrs['link'] : $attrs[0];
//
		if (isset($attrs['mode']) && in_array($attrs['mode'], ['carousel', 'gallery-player'])) {
			$props -> mode = $attrs['mode'];
		}
		$props -> width = $this -> get_dimmension_attr($attrs, 'width', $props -> width);
		$props -> height = $this -> get_dimmension_attr($attrs, 'height', $props -> height);

		if (isset($attrs['image-width'])) {
			$props -> imageWidth = intval($attrs['image-width']);
		}
		if (isset($attrs['image-height'])) {
			$props -> imageHeight = intval($attrs['image-height']);
		}
		if (isset($attrs['include-thumbnails'])) {
			$props -> includeThumbnails = strtolower($attrs['include-thumbnails']) == 'true';
		}
		if (isset($attrs['attach-metadata'])) {
			$props -> attachMetadata = strtolower($attrs['attach-metadata']) == 'true';
		}
		if (isset($attrs['autoplay'])) {
			$props -> slideshowAutoplay = strtolower($attrs['autoplay']) == 'true';
		}
		elseif (isset($attrs['slideshow-autoplay'])) {
			$props -> slideshowAutoplay = strtolower($attrs['slideshow-autoplay']) == 'true';			
		}
		if (isset($attrs['delay'])) {
			$props -> slideshowDelay = intval($attrs['delay']);
		}
		elseif (isset($attrs['slideshow-delay'])) {
			$props -> slideshowDelay = intval($attrs['slideshow-delay']);
		}
		if (isset($attrs['repeat'])) {
			$props -> slideshowRepeat = strtolower($attrs['repeat']) == 'true';
		}
		elseif (isset($attrs['slideshow-repeat'])) {
			$props -> slideshowRepeat = strtolower($attrs['slideshow-repeat']) == 'true';
		}
		if (isset($attrs['mediaitems-aspectratio'])) {
			$props -> mediaItemsAspectRatio = strtolower($attrs['mediaitems-aspectratio']) == 'true';
		}
		if (isset($attrs['mediaitems-enlarge'])) {
			$props -> mediaItemsEnlarge = strtolower($attrs['mediaitems-enlarge']) == 'true';
		}
		if (isset($attrs['mediaitems-stretch'])) {
			$props -> mediaItemsStretch = strtolower($attrs['mediaitems-stretch']) == 'true';
		}
		if (isset($attrs['mediaitems-cover'])) {
			$props -> mediaItemsCover = strtolower($attrs['mediaitems-cover']) == 'true';
		}
		if (isset($attrs['expiration'])) {
			$props -> expiration = intval($attrs['expiration']) == 'true';
		}
//
		return $this -> get_html($props, $expiration);
	}





// since 2.1.0 not additional parameters, link as props support.
	public function getcode($link, $width = 0, $height = 480, $imageWidth = 1920, $imageHeight = 1080, $expiration = 0)
	{
		if (is_object($link)) {
			return $this -> get_html($link, 0);
		}
		$props = $this -> create_default_props();
		$props -> link = $link;
		$props -> width = $width;
		$props -> height = $height;
		$props -> imageWidth = $imageWidth;
		$props -> imageHeight = $imageHeight;
		return $this -> get_html($props, $expiration);
	}





	private function get_html($props, $expiration = 0)
	{
		if (self::$index == 1) {
			wp_enqueue_script(self::$name, $this -> player_js, array(), FALSE, TRUE);  // Paul Ryan (@figureone), thanks for the improvements
		}
//
		global $post;
		$transient = sprintf('%s-%d-%d', self::$name, $post -> ID, self::$index++);
//
		if ($html = get_transient($transient)) {
//			return $html;
		}
		if ($html = $this -> get_embed_player_html_code($props)) {			
			$expiration = $expiration > 0 ? max($expiration, $this -> min_expiration) : 0;
			set_transient($transient, $html, $expiration);
			return $html;
		}
		return NULL;
	}





	private function get_remote_contents($url)
	{
		$response = wp_remote_get($url);
		if (!is_wp_error($response)) {
			return wp_remote_retrieve_body($response);
		}
		return NULL;
	}





	private function parse_ogtags($contents)
	{
		$m = NULL;
		preg_match_all('~<\s*meta\s+property="(og:[^"]+)"\s+content="([^"]*)~i', $contents, $m);
		$ogtags = array();
		for($i = 0; $i < count($m[1]); $i++) {
			$ogtags[$m[1][$i]] = $m[2][$i];
		}
		return $ogtags;
	}





	private function parse_photos($contents)
	{
		$m = NULL;
		preg_match_all('~\"(http[^"]+)"\,[0-9^,]+\,[0-9^,]+~i', $contents, $m);
		return array_unique($m[1]);
	}





	private function get_embed_player_html_code($props)
	{
		if ($contents = $this -> get_remote_contents($props -> link)) {
			$og = $this -> parse_ogtags($contents);
			$title = isset($og['og:title']) ? $og['og:title'] : NULL;
			$photos = $this -> parse_photos($contents);
//
			$style = 'display:none;'
				. 'width:' . ($props -> width === 0 ? '100%' : ($props -> width . 'px')) . ';'
				. 'height:' . ($props -> height === 0 ? '100%' : ($props -> height . 'px')) . ';';
//
			$items_code = '';
			foreach ($photos as $photo) {
				$src = sprintf('%s=w%d-h%d', $photo, $props -> imageWidth, $props -> imageHeight);
				$items_code .= '<img data-src="' . $src . '" src="" alt="" />';
			}
			return "<!-- publicalbum.org -->\n"
				. '<div class="pa-' . $props -> mode . '-widget" style="' . $style . '"'
				. ($title ? ' data-title="' . $title . '"' : '')
				. ($props -> slideshowAutoplay !== NULL ? ' data-autoplay="' . ($props -> slideshowAutoplay ? 'true' : 'false') . '"' : '')
				. ($props -> slideshowDelay > 0 ? ' data-delay="' . $props -> slideshowDelay . '"' : '')
				. ($props -> slideshowRepeat !== NULL ? ' data-repeat="' . ($props -> slideshowRepeat ? 'true' : 'false') . '"' : '')
				. ($props -> mediaItemsAspectRatio !== NULL ? ' data-mediaitems-aspectratio="' . ($props -> mediaItemsAspectRatio ? 'true' : 'false') . '"' : '')
				. ($props -> mediaItemsEnlarge !== NULL ? ' data-mediaitems-enlarge="' . ($props -> mediaItemsEnlarge ? 'true' : 'false') . '"' : '')
				. ($props -> mediaItemsStretch !== NULL ? ' data-mediaitems-stretch="' . ($props -> mediaItemsStretch ? 'true' : 'false') . '"' : '')
				. ($props -> mediaItemsCover !== NULL ? ' data-mediaitems-cover="' . ($props -> mediaItemsCover ? 'true' : 'false') . '"' : '')
				. '>' . $items_code . '</div>' . "\n";
		}
		return NULL;
	}
}





add_action('init', function() {
	new Pavex_embed_google_photos_album();
});





add_action('save_post', function($post_id) {
	$index = 1;
	while (get_transient($transient = sprintf('%s-%d-%d', Pavex_embed_google_photos_album::$name, $post_id, $index++))) {
		delete_transient($transient);
	}
});
