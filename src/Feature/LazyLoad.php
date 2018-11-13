<?php
/**
 * Lazy load feature.
 *
 * Modify images and embeds for lazy loading with lazysizes.
 *
 * @link https://github.com/aFarkas/lazysizes
 * @package SpeedRunner
 * @since 0.2.0
 */

namespace Sup\SpeedRunner\Feature;

use Sup\SpeedRunner\Provider;

/**
 * CDN jQuery class.
 */
class LazyLoad extends AbstractFeature {
	/**
	 * Feature id.
	 *
	 * @since 0.1.0
	 * @var string
	 */
	protected $id = 'enable-lazy-loading';

	/**
	 * Feature args.
	 *
	 * @since 0.2.0
	 * @var array
	 */
	protected $args;

	/**
	 * Method for loading the feature.
	 *
	 * @since 0.2.0
	 *
	 * @return $this
	 */
	public function load() {
		$defaults = [
			'enqueue' => true,
			'pattern' => 'modern-transparent',
		];

		$args       = get_theme_support( $this->get_id() );
		$args       = ( is_array( $args ) ) ? $args[0] : [];
		$this->args = wp_parse_args( $args, $defaults );

		return $this;
	}

	/**
	 * Register feature hooks.
	 *
	 * @since 0.1.0
	 */
	public function register_hooks() {
		add_filter( 'wp_get_attachment_image_attributes', array( $this, 'modify_image_attributes' ), 10, 3 );
		add_filter( 'the_content', array( $this, 'modify_content_image_attributes' ) );
		add_filter( 'post_thumbnail_html', array( $this, 'wrap_post_thumbnail' ), 10, 5 );
		add_action( 'embed_oembed_html', array( $this, 'lazyload_video_embeds' ), 10, 2 );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 5 );

		$this->plugin->register_hooks( new Provider\EmbedCustomisations( $this ) );
	}

	/**
	 * Modify image attributes to facilitate lazy loading.
	 *
	 * Use the modern transparent srcset pattern.
	 *
	 * @since 0.2.0
	 *
	 * @link https://github.com/aFarkas/lazysizes#modern-transparent-srcset-pattern
	 * @param array        $attr       Attributes for the image markup.
	 * @param WP_Post      $attachment Image attachment post.
	 * @param string|array $size       Requested size. Image size or array of width and height values
	 *                                 (in that order). Default 'thumbnail'.
	 * @return string Updated image attributes if the image has the lazyload class.
	 */
	public function modify_image_attributes( $attr, $attachment, $size ) {
		if ( false === strpos( $attr['class'], 'lazyload' ) ) {
			return $attr;
		}

		$placeholder_src = $this->get_placeholder_src( $attachment->ID );

		if ( isset( $attr['srcset'] ) ) {
			$attr['data-srcset'] = $attr['srcset'];
			$attr['srcset']      = $placeholder_src;
		} else {
			$attr['data-src'] = $attr['src'];
			$attr['src']      = $placeholder_src;
		}

		return $attr;
	}

	/**
	 * Modify content images for lazysizes.
	 *
	 * Use the modern transparent srcset pattern.
	 *
	 * @since 0.2.0
	 *
	 * @link https://github.com/aFarkas/lazysizes#modern-transparent-srcset-pattern
	 * @param string $content The post content.
	 * @return string Modified post content markup.
	 */
	public function modify_content_image_attributes( $content ) {
		$classes = 'lazyload';

		if ( 'modern-blur' === $this->get_pattern() ) {
			$classes .= ' blur-up';
		}

		$content = str_replace( 'srcset=', 'srcset="' . $this->get_placeholder_src() . '" data-srcset=', $content );
		$content = str_replace( 'wp-image', $classes . ' wp-image', $content );
		return $content;
	}

	/**
	 * Wrap post thumbnail with an optional instrinsic ratio.
	 *
	 * @since 0.2.0
	 *
	 * @param string       $html              The post thumbnail HTML.
	 * @param int          $post_id           The post ID.
	 * @param string       $post_thumbnail_id The post thumbnail ID.
	 * @param string|array $size              The post thumbnail size. Image size or array of width and height
	 *                                        values (in that order). Default 'post-thumbnail'.
	 * @param string|array $attr              Optional. Query string or array of attributes. Default empty.
	 * @return string Post thumbnail markup wrapped in a ratio container.
	 */
	public function wrap_post_thumbnail( $html, $post_id, $post_thumbnail_id, $size, $attr ) {
		if ( $post_thumbnail_id && isset( $attr['wrapper'] ) || isset( $attr['ratio'] ) ) {
			$replacements = [];
			$attr_keys    = [
				'wrapper',
				'ratio',
			];

			foreach ( $attr_keys as $key ) {
				if ( isset( $attr[ $key ] ) ) {
					$replacements[] = $key . '=' . $attr[ $key ] . '"';
				}
				$$key = ( isset( $attr[ $key ] ) ) ? $attr[ $key ] : false;
			}

			// Remove the attributes used to flag that thumbnail should be wrapped.
			$html = str_replace( $replacements, '', $html );

			return $this->get_image_wrapper( $html, $post_thumbnail_id, $size, $wrapper, $ratio );
		}

		return $html;
	}

	/**
	 * Lazy load youtube/vimeo.
	 *
	 * @since 0.2.0
	 *
	 * Can't use a simple string replace with the_content filter as iframe markup is
	 * different depending on the oembed provider.
	 *
	 * @param string $cache The cached oEmbed HTML.
	 * @param string $url   URL of the content to be embedded.
	 * @return string Embed html.
	 */
	public function lazyload_video_embeds( $cache, $url ) {
		if ( is_admin() ) {
			return $cache;
		}

		if ( false !== strpos( $url, 'youtube.com' ) || false !== strpos( $url, 'vimeo.com' ) || false !== strpos( $url, 'youtu.be' ) ) {
			return str_replace( 'src=', 'class="lazyload" data-src=', $cache );
		}

		return $cache;
	}

	/**
	 * Enqueue scripts.
	 *
	 * @since 0.2.0
	 */
	public function enqueue_scripts() {
		if ( false !== $this->get_args()['enqueue'] ) {
			wp_enqueue_script(
				'lazysizes',
				$this->plugin->get_url( 'assets/dist/scripts/lazysizes.min.js' ),
				[],
				SPDRNR_PLUGIN_VERSION,
				true
			);
		}
	}

	/**
	 * Wrap an image with an optional instrinsic ratio.
	 *
	 * @since 0.2.0
	 *
	 * @param string $html          The image html.
	 * @param int    $attachment_id The attachment ID.
	 * @param string $size          The post thumbnail size.
	 * @param string $class         The class for the container. Default 'ratio'.
	 * @param bool   $has_ratio     Should an intrinsic ratio be added to the wrapper? Default false.
	 * @return string Image markup wrapped in a ratio container.
	 */
	public function get_image_wrapper( $html, $attachment_id, $size, $class, $has_ratio = false ) {
		if ( $has_ratio ) {
			$image = wp_get_attachment_image_src( $attachment_id, $size );
			$ratio = ( $image[2] / $image[1] ) * 100;
		}

		$class = ( is_string( $class ) ) ? $class : 'u-ratio';
		$style = ( isset( $ratio ) ) ? ' style="padding-bottom:' . esc_attr( $ratio ) . '%"' : '';
		return '<div class="' . esc_attr( $class ) . '"' . $style . '>' . $html . '</div>';
	}

	/**
	 * Image placeholder.
	 *
	 * @since 0.2.0
	 *
	 * @param int $attachment_id The attachment ID.
	 * @return WordPress thumbnail src. Defaults to base64 encoded transparent gif.
	 */
	public function get_placeholder_src( $attachment_id = false ) {
		$placeholder = 'data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==';

		if ( $attachment_id && 'modern-blur' === $this->get_pattern() ) {
			$placeholder = wp_get_attachment_image_url( $attachment_id, $this->get_modern_blur_thumbnail_size() );
		}

		return $placeholder;
	}

	/**
	 * Get the feature args.
	 *
	 * @since 0.1.0
	 *
	 * @return string
	 */
	public function get_args() {
		return $this->args;
	}

	/**
	 * Get the loading pattern.
	 *
	 * @since 0.1.0
	 *
	 * @return string
	 */
	public function get_pattern() {
		return ( is_array( $this->args['pattern'] ) ) ? $this->array_key_first( $this->args['pattern'] ) : $this->args['pattern'];
	}

	/**
	 * Get modern-blur pattern thumbnail size.
	 *
	 * @since 0.6.0
	 *
	 * @return string
	 */
	public function get_modern_blur_thumbnail_size() {
		$pattern = $this->args['pattern'];
		return ( isset( $pattern['modern-blur']['thumbnail'] ) ) ? $pattern['modern-blur']['thumbnail'] : 'lowres';
	}

	/**
	 * Get first array key.
	 *
	 * @since 0.6.0
	 *
	 * @param array $array An array to return the first key from.
	 * @return string
	 */
	private function array_key_first( $array ) {
		reset( $array );
		return key( $array );
	}
}
