<?php
/**
 * Lazy load feature.
 *
 * Modify images and embeds for lazy loading with lazysizes.
 *
 * @link https://github.com/aFarkas/lazysizes
 * @package Blackbird
 * @since 0.2.0
 */

namespace Sup\Blackbird\Feature;

use Sup\Blackbird\Provider;

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
	 * Register feature hooks.
	 *
	 * @since 0.1.0
	 */
	public function register_hooks() {
		add_filter( 'wp_get_attachment_image_attributes', array( $this, 'modify_image_attributes' ), 10, 3 );
		add_filter( 'the_content', array( $this, 'modify_content_image_attributes' ) );
		add_filter( 'post_thumbnail_html', array( $this, 'wrap_post_thumbnail_with_ratio_container' ), 10, 5 );
		add_action( 'embed_oembed_html', array( $this, 'lazyload_video_embeds' ), 10, 2 );

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

		$one_pixel_gif = $this->get_placeholder_src();

		if ( isset( $attr['srcset'] ) ) {
			$attr['data-srcset'] = $attr['srcset'];
			$attr['srcset']      = $one_pixel_gif;
		} else {
			$attr['data-src'] = $attr['src'];
			$attr['src']      = $one_pixel_gif;
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
		$content = str_replace( 'srcset=', 'srcset="' . $this->get_placeholder_src() . '" data-srcset=', $content );
		$content = str_replace( 'wp-image', 'lazyload wp-image', $content );
		return $content;
	}

	/**
	 * Wrap post thumbnail in a ratio container
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
	public function wrap_post_thumbnail_with_ratio_container( $html, $post_id, $post_thumbnail_id, $size, $attr ) {
		if ( isset( $attr['ratio_container'] ) ) {
			// Remove the attr used to flag that thumbnail should be wrapped.
			$html = str_replace( 'ratio_container="' . $attr['ratio_container'] . '"' , '', $html );
			return $this->get_ratio_container( $html, $post_thumbnail_id, $size, $attr['ratio_container'] );
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
	 * Wrap an image with a ratio container.
	 *
	 * @since 0.2.0
	 *
	 * @param string $html          The image html.
	 * @param int    $attachment_id The attachment ID.
	 * @param string $size          The post thumbnail size.
	 * @param string $class         The class for the container. Default 'ratio'.
	 * @return string Image markup wrapped in a ratio container.
	 */
	public function get_ratio_container( $html, $attachment_id, $size, $class ) {
		$image = wp_get_attachment_image_src( $attachment_id, $size );
		$ratio = ( $image[2] / $image[1] ) * 100;
		$class = ( is_string( $class ) ) ? $class : 'u-ratio';

		return '<div class="' . esc_attr( $class ) . '" style="padding-bottom:' . esc_attr( $ratio ) . '">' . $html . '</div>';
	}

	/**
	 * 1px transparent gif placeholder.
	 *
	 * @since 0.2.0
	 *
	 * @return base64 encoded image src.
	 */
	public function get_placeholder_src() {
		return 'data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==';
	}
}
