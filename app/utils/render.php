<?php
/**
 * Renders a view with or without the main layout, depending on if it's an HTMX request.
 */
function renderView($view, $data = []) {
    extract($data);
    $is_htmx = isset($_SERVER['HTTP_HX_REQUEST']) && $_SERVER['HTTP_HX_REQUEST'] == 'true';
    $is_boosted = isset($_SERVER['HTTP_HX_BOOSTED']) && $_SERVER['HTTP_HX_BOOSTED'] == 'true';
    
    // Si c'est HTMX ciblé (ex: un formulaire avec hx-target), on renvoie la vue partielle.
    // Si c'est un lien boosté, on renvoie la page entière pour que HTMX mette à jour le body (et la sidebar).
    if ($is_htmx && !$is_boosted) {
        require __DIR__ . '/../views/' . $view . '.php';
    } else {
        require __DIR__ . '/../views/layout/header.php';
        require __DIR__ . '/../views/layout/sidebar.php';
        echo '<main id="main-content" class="main-content">';
        require __DIR__ . '/../views/' . $view . '.php';
        echo '</main>';
        require __DIR__ . '/../views/layout/footer.php';
    }
}
