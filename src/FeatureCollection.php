<?php
/**
 * Feature collection.
 *
 * @package    Blackbird
 * @subpackage Plugin
 * @since      0.1.0
 */

namespace Sup\Blackbird;

/**
 * Feature collection class.
 */
class FeatureCollection implements \ArrayAccess, \Countable, \Iterator {
	/**
	 * Features.
	 *
	 * @since 0.1.0
	 * @var array
	 */
	protected $features;

	/**
	 * Register a feature.
	 *
	 * @since 0.1.0
	 *
	 * @param  \Sup\Blackbird\Feature\AbstractFeature $feature Feature object.
	 * @return $this
	 */
	public function register( $feature ) {
		$this->features[ $feature->get_id() ] = $feature;
		return $this;
	}

	/**
	 * Whether a feature is supported.
	 *
	 * @since 1.0.0
	 *
	 * @param string $feature_id Feature identifier.
	 * @return bool
	 */
	public function is_supported( $feature_id ) {
		if ( isset( $this->features[ $feature_id ] ) ) {
			return $this->features[ $feature_id ]->is_supported();
		}

		return false;
	}

	/**
	 * Retrieve all feature ids.
	 *
	 * @since 0.1.0
	 *
	 * @return array
	 */
	public function keys() {
		return array_keys( $this->features );
	}

	/**
	 * Retrieve the number of registered features.
	 *
	 * @since 0.1.0
	 *
	 * @return int
	 */
	public function count() {
		return count( $this->features );
	}

	/**
	 * Retrieve the current feature in an iterator.
	 *
	 * @since 0.1.0
	 *
	 * @return \Bandstand\AbstractFeature
	 */
	public function current() {
		return current( $this->features );
	}

	/**
	 * Retrieve the key of the current feature in an iterator.
	 *
	 * @since 0.1.0
	 *
	 * @return string
	 */
	public function key() {
		return key( $this->features );
	}

	/**
	 * Move the pointer to the next feature.
	 *
	 * @since 0.1.0
	 */
	public function next() {
		next( $this->features );
	}

	/**
	 * Reset to the first feature.
	 *
	 * @since 0.1.0
	 */
	public function rewind() {
		reset( $this->features );
	}

	/**
	 * Check if the current position is valid.
	 *
	 * @since 0.1.0
	 *
	 * @return bool
	 */
	public function valid() {
		return key( $this->features ) !== null;
	}

	/**
	 * Whether an item exists at the given offset.
	 *
	 * @since 0.1.0
	 *
	 * @param string $offset Item identifier.
	 * @return bool
	 */
	public function offsetExists( $offset ) {
		return isset( $this->features[ $offset ] );
	}

	/**
	 * Retrieve a feature.
	 *
	 * @since 0.1.0
	 *
	 * @param string $offset Item identifier.
	 * @return array
	 */
	public function offsetGet( $offset ) {
		return isset( $this->features[ $offset ] ) ? $this->features[ $offset ] : null;
	}

	/**
	 * Register a feature.
	 *
	 * @since 0.1.0
	 *
	 * @param string $offset Item identifier.
	 * @param array  $value Item data.
	 */
	public function offsetSet( $offset, $value ) {
		$this->features[ $offset ] = $value;
	}

	/**
	 * Remove a feature.
	 *
	 * @since 0.1.0
	 *
	 * @param string $offset Item identifier.
	 */
	public function offsetUnset( $offset ) {
		unset( $this->features[ $offset ] );
	}
}
