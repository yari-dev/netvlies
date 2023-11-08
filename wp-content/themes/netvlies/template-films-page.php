<?php
/* Template Name: Films Page */

get_header();

// Fetch genres van de 'genre' taxonomie
$genres = get_terms(array(
    'taxonomy' => 'genre',
    'hide_empty' => false,
));

?>

<div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">

        <section id="film-search-container">
            <input type="text" id="search-input" placeholder="Films zoeken...">
            <select id="genre-select">
                <option value="">Alle genres</option>
                <?php foreach ($genres as $genre): ?>
                    <option value="<?php echo esc_attr($genre->slug); ?>">
                        <?php echo esc_html($genre->name); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </section>

        <!-- De div waar de film lijst zal worden getoond -->
        <div id="films-list">
            <!-- Films worden via JavaScript geladen -->
        </div>

        <!-- Paginatie knoppen -->
        <div id="films-pagination">
            <button id="pagination-prev" disabled>Vorige</button>
            <button id="pagination-next" disabled>Volgende</button>
            <!-- Paginatie knoppen worden via JavaScript geladen -->
        </div>

    </main>
</div>

<?php
get_sidebar();
get_footer();
?>
