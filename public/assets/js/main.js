document.addEventListener('DOMContentLoaded', () => {
    // Theme setup
    const themeBtn = document.getElementById('theme-toggle-btn');
    const htmlElement = document.documentElement;

    const sunIcon = `<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="4"/><path d="M12 2v2"/><path d="M12 20v2"/><path d="m4.93 4.93 1.41 1.41"/><path d="m17.66 17.66 1.41 1.41"/><path d="M2 12h2"/><path d="M20 12h2"/><path d="m6.34 17.66-1.41 1.41"/><path d="m19.07 4.93-1.41 1.41"/></svg>`;
    const moonIcon = `<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"/></svg>`;

    function updateIcon() {
        if (!themeBtn) return;
        if (htmlElement.getAttribute('data-theme') === 'dark') {
            themeBtn.innerHTML = sunIcon;
        } else {
            themeBtn.innerHTML = moonIcon;
        }
    }

    if (localStorage.getItem('theme') === 'dark') {
        htmlElement.setAttribute('data-theme', 'dark');
    }
    updateIcon();

    if (themeBtn) {
        themeBtn.addEventListener('click', () => {
            if (htmlElement.getAttribute('data-theme') === 'light') {
                htmlElement.setAttribute('data-theme', 'dark');
                localStorage.setItem('theme', 'dark');
            } else {
                htmlElement.setAttribute('data-theme', 'light');
                localStorage.setItem('theme', 'light');
            }
            updateIcon();
        });
    }
});

// HTMX Configuration
// Intercepter les requêtes pour gérer les erreurs et SweetAlert globalement
document.body.addEventListener('htmx:afterRequest', function(evt) {
    if(evt.detail.failed) {
        Swal.fire({
            icon: 'error',
            title: 'Erreur',
            text: 'Une erreur s\'est produite lors de la requête.',
            confirmButtonColor: '#8A2BE2'
        });
    }
});
