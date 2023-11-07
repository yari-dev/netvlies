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
				'name' => __('Films'),
				'singular_name' => __('Film'),
			],
			'public' => true,
			'has_archive' => true,
			'rewrite' => ['slug' => 'films'],
			'supports' => ['title', 'editor', 'thumbnail'],
		]
	);
 }

 //Initialiseer het custom post type
 add_action('init', 'create_film_post_type');

//Taxonomie om de film genres te filteren
function create_film_taxonomies() {

	$labels = [
		'name' => _x('Genres', 'taxonomy general name', 'netvlies'),
		'singular_name' => _x('Genre', 'taxonomy singular name', 'netvlies'),
		'search_items' => __('Search Genres', 'netvlies'),
		'all_items' => __('All Genres', 'netvlies'),
		'parent_item' => __('Parent Genre', 'netvlies'),
		'parent_item_colon' => __('Parent Genre:', 'netvlies'),
		'edit_item' => __('Edit Genre', 'netvlies'),
		'update_item' => __('Update Genre', 'netvlies'),
		'add_new_item' => __('Add New Genre', 'netvlies'),
		'new_item_name' => __('New Genre Name', 'netvlies'),
		'menu_name' => __('Genre', 'netvlies'),
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

if ( ! defined( '_S_VERSION' ) ) {
	// Geef de CSS een uniek versienummer om caching tegen te gaan
	define( '_S_VERSION', '1.0.0' );
}

//Initialiseer de benodigde styles/scripts
function netvlies_films_enqueue() {
	wp_enqueue_style( 'main-css', get_stylesheet_uri(), array(), _S_VERSION );
}
add_action( 'wp_enqueue_scripts', 'netvlies_films_enqueue' );


function update_genre_terms($api_key) {
    // Fetch genres van TMDb API
    $genre_url = "https://api.themoviedb.org/3/genre/movie/list?api_key={$api_key}&language=en-US";
    $response = wp_remote_get($genre_url);
    if (is_wp_error($response)) {
        return [];
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    if (empty($data['genres'])) {
        return [];
    }

    $genre_mapping = [];
    foreach ($data['genres'] as $genre) {
        // Controleer of de genre term bestaat, maak deze desnoods aan
        $term = term_exists($genre['name'], 'genre');
        if (!$term) {
            $term = wp_insert_term($genre['name'], 'genre');
        }

        if (!is_wp_error($term)) {
            $term_id = is_array($term) ? $term['term_id'] : $term;
            $genre_mapping[$genre['id']] = $term_id;
        }
    }
    
    return $genre_mapping;
}


function fetch_films() {
	//Persoonlijke API sleutel
    $api_key = '4aa32129733a882679065638119d1564';
    $genre_mapping = update_genre_terms($api_key);

    // Variabelen voor het bijhouden van het aantal opgehaalde films en de huidige pagina
    $movies_count = 0;
    $current_page = 1;
    $total_movies_to_fetch = 50;

    // Query om alle huidige film posts op te halen en te verwijderen
    $existing_films_query = new WP_Query(array(
        'post_type' => 'film',
        'posts_per_page' => -1 // Haal alle bestaande film posts op
    ));

    // Verwijder alle bestaande film posts om de top 50 up-to-date te houden
    $posts_to_delete = wp_list_pluck($existing_films_query->posts, 'ID');
    foreach ($posts_to_delete as $post_to_delete) {
        wp_delete_post($post_to_delete, true); 
    }

    $pages_to_fetch = ceil($total_movies_to_fetch / 20); // Bereken het aantal paginas

    for ($page=1; $page <= $pages_to_fetch; $page++) {
        // Fetch films van de TMDb API, gebaseerd op pagina nummer
        $movies_url = "https://api.themoviedb.org/3/movie/popular?api_key={$api_key}&language=en-US&page={$page}";
        $response = wp_remote_get($movies_url);

        if (is_wp_error($response)) {
            return;
        }

        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);
        $movies = $data['results'] ?? [];

		// Loop door alle films heen
        foreach ($movies as $movie) {
            $genre_terms = array_map(function($genre_id) use ($genre_mapping) {
                return $genre_mapping[$genre_id] ?? null;
            }, $movie['genre_ids']);

            $genre_terms = array_filter($genre_terms); // Filter eventuele null waardes

            // Maak een nieuwe film post aan
            $post_id = wp_insert_post(array(
                'post_type'    => 'film',
                'post_title'   => wp_strip_all_tags($movie['title']),
                'post_content' => $movie['overview'],
                'post_status'  => 'publish',
                'meta_input'   => array(
                    'tmdb_id'            => $movie['id'],
                    'vote_average'       => $movie['vote_average'],
                    'vote_count'         => $movie['vote_count'],
                    'original_language'  => $movie['original_language'],
                    'release_date'       => $movie['release_date'],
                    'popularity_rank'    => ++$movies_count,
                    'page'               => $page // Bewaar het paginanummer voor frontend paginatie
                ),
            ));

            // Stel de genre terms in voor de nieuwe post als het maken van de post gelukt is
            if (!is_wp_error($post_id) && !empty($genre_terms)) {
                wp_set_post_terms($post_id, $genre_terms, 'genre', false);
            }

            // Als het maximum aantal opgehaalde films is bereikt, stop dan het proces
            if ($movies_count >= $total_movies_to_fetch) {
                break 2;
            }
        }
    }
}


// Registreer een nieuwe cron schedule om de 10 minuten
function film_cron_schedule($schedules) {
    $schedules['10min'] = array(
        'interval' => 600,
        'display' => __('Every 10 Minutes')
    );
    return $schedules;
}
add_filter('cron_schedules', 'film_cron_schedule');

// Schedule de cron actie als dat nog niet is gebeurt
if (!wp_next_scheduled('fetch_films')) {
    wp_schedule_event(time(), '10min', 'fetch_films');
}

add_action('fetch_films', 'fetch_films');
?>