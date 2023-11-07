<?php
/**
 * Functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package WordPress
 * @subpackage Netvlies
 * @since Netvlies 1.0
 */

 //Custom post type voor films
 function create_film_post_type() {
	register_post_type('film',
		[
			'labels' => [
				'name' => __('Films')
				'singular_name' => __('Film')
			],
			'public' => true,
			'has_archive' => true,
			'rewrite' => ['slug' => 'films']
			'supports' => ['title', 'editor', 'thumbnail'],
		]
	);
 }

 //Initialiseer het custom post type
 add_action('init', 'create_film_post_type');

//Taxonomie om de film genres te filteren
function create_film_taxonomies() {

	$labels = [
		'name' => _x('Genres', 'taxonomy general name'),
		'singular_name' => _x('taxonomy singular name'),
		'search_items' => __('Search Genres'),
		'all_items' => __('All Genres'),
		'parent_item' => __('Parent Genre'),
		'parent_item_colon' => __('Parent Genre:'),
		'edit_item' => __('Edit Genre'),
		'update_item' => __('Update Genre'),
		'add_new_genre' => __('Add New Genre'),
		'new_item_name' => __('New Genre Name'),
		'menu_name' => __('Genre'),
	];

	$args = [
		'hierarchical' => true,
		'labels' => $labels,
		'show_ui' => true,
		'show_admin_column' => true,
		'query_var' => true,
		'rewrite' => ['slug' => 'genre'],
	];
	//Custom taxonomie registreren
	register_taxonomy('genre', ['film'], $args);
	
}
//Initialiseer de custom taxonomie
add_action('init', 'create_film_taxonomies', 0);