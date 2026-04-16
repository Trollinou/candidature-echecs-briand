<?php
/**
 * PHPUnit Bootstrap for Unit Testing
 */

// Define basic WP constants
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', realpath( __DIR__ . '/../' ) . '/' );
}

// Mock WordPress functions
if ( ! function_exists( 'add_action' ) ) {
	function add_action( $tag, $callback, $priority = 10, $accepted_args = 1 ) {
		$GLOBALS['wp_actions'][$tag][] = [
			'callback'      => $callback,
			'priority'      => $priority,
			'accepted_args' => $accepted_args,
		];
	}
}

if ( ! function_exists( 'add_meta_box' ) ) {
	function add_meta_box( $id, $title, $callback, $screen = null, $context = 'advanced', $priority = 'default', $callback_args = null ) {
		$GLOBALS['wp_meta_boxes'][$screen][$context][$priority][$id] = [
			'title'    => $title,
			'callback' => $callback,
			'args'     => $callback_args,
		];
	}
}

if ( ! function_exists( 'get_post_meta' ) ) {
	function get_post_meta( $post_id, $key = '', $single = false ) {
		return $GLOBALS['wp_post_meta'][$post_id][$key] ?? '';
	}
}

if ( ! function_exists( 'esc_html' ) ) {
	function esc_html( $text ) {
		return htmlspecialchars( (string) $text, ENT_QUOTES, 'UTF-8' );
	}
}

if ( ! function_exists( 'wp_date' ) ) {
	function wp_date( $format, $timestamp = null, $timezone = null ) {
		if ( null === $timestamp ) {
			$timestamp = time();
		}
		return date( $format, $timestamp );
	}
}

if ( ! function_exists( 'plugin_dir_path' ) ) {
	function plugin_dir_path( $file ) {
		return dirname( $file ) . '/';
	}
}

if ( ! function_exists( 'plugin_dir_url' ) ) {
	function plugin_dir_url( $file ) {
		return 'https://example.com/wp-content/plugins/candidature-echecs-briand/';
	}
}

if ( ! function_exists( '__' ) ) {
	function __( $text, $domain = 'default' ) {
		return $text;
	}
}

// Mock WP_Post class
if ( ! class_exists( 'WP_Post' ) ) {
	class WP_Post {
		public int $ID;
		public function __construct( int $id ) {
			$this->ID = $id;
		}
	}
}

// Autoloader for the plugin
require_once __DIR__ . '/../candidature-echecs-briand.php';
