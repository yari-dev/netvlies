document.addEventListener('DOMContentLoaded', function () {
    var nonce = search_films_obj.nonce; // De nonce uit search_films_obj
    var restUrl = search_films_obj.rest_url + 'search_films/'; // De basis REST URL uit search_films_obj gevolgd door 'search_films/'

    var searchInput = document.getElementById('search-input'); // Het zoekveld
    var genreSelect = document.getElementById('genre-select'); // De genre select dropdown
    var filmsList = document.getElementById('films-list'); // De lijst waar films getoond worden
    var paginationNext = document.getElementById('pagination-next'); // De volgende pagina knop
    var paginationPrev = document.getElementById('pagination-prev'); // De vorige pagina knop
    var currentPage = 1; // De huidige pagina
    var totalPages; // Het totaal aantal pagina's

    function fetchFilms(searchTerm, genre, page) {
        var url = new URL(restUrl),
            params = { s: searchTerm, genre: genre, page: page };
        Object.keys(params).forEach(key => url.searchParams.append(key, params[key])); // Voeg zoektermen toe aan de URL

        fetch(url, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-WP-Nonce': nonce // Voeg de WP nonce toe voor veiligheid
            }
        })
        .then(response => response.json())
        .then(data => {
            updateFilmsList(data.films); // Update de film lijst met de opgehaalde films
            totalPages = data.totalPages; // Stel het totaal aantal pagina's in
            updatePaginationControls(currentPage, totalPages); // Update de paginering knoppen
        })
        .catch((error) => {
            console.error('Error:', error); // Log errors naar de console
        });
    }

    function updateFilmsList(films) {
        filmsList.innerHTML = ''; // Maak de huidige film lijst leeg
        
        // Doorloop alle films en voeg ze toe aan het grid
        films.forEach(function (film) {
            var listItem = document.createElement('li');
            listItem.classList.add('film-item');
    
            var titleElement = document.createElement('h3');
            var contentElement = document.createElement('div');
    
            titleElement.innerText = film.title;
            contentElement.innerHTML = film.content;
    
            listItem.appendChild(titleElement);
            listItem.appendChild(contentElement);
    
            filmsList.appendChild(listItem);
        });
    }    

    function updatePaginationControls(currentPage, totalPages) {
        paginationPrev.disabled = currentPage === 1; // Schakel de vorige knop uit als het de eerste pagina is
        paginationNext.disabled = currentPage === totalPages; // Schakel de volgende knop uit als het de laatste pagina is
    }

    searchInput.addEventListener('keyup', function (e) {
        if (e.key === 'Enter') {
            currentPage = 1; // Reset de pagina bij een nieuwe zoekopdracht
            fetchFilms(searchInput.value, genreSelect.value, currentPage); // Voer een zoekopdracht uit met de nieuwe termen
        }
    });

    genreSelect.addEventListener('change', function () {
        currentPage = 1; // Reset de pagina bij een nieuwe zoekopdracht
        fetchFilms(searchInput.value, genreSelect.value, currentPage); // Voer een zoekopdracht uit met het geselecteerde genre
    });

    paginationNext.addEventListener('click', function () {
        if (currentPage < totalPages) {
            currentPage++; // Ga naar de volgende pagina
            fetchFilms(searchInput.value, genreSelect.value, currentPage); // Haal films op van de volgende pagina
        }
    });

    paginationPrev.addEventListener('click', function () {
        if (currentPage > 1) {
            currentPage--; // Ga naar de vorige pagina
            fetchFilms(searchInput.value, genreSelect.value, currentPage); // Haal films op van de vorige pagina
        }
    });

    // Laad de initiële lijst met films
    fetchFilms('', '', currentPage);
});
