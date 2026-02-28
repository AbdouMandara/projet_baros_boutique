<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Baros</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@400;500;600&family=Poppins:wght@500;600;700&display=swap" rel="stylesheet">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root {
            --primary: #8A2BE2; /* Violet dominant */
            --primary-hover: #7b22cc;
            --bg-color: #f4f5f7;
            --surface: #ffffff;
            --text-main: #333333;
            --text-muted: #666666;
            --border: #e0e0e0;
        }

        [data-theme="dark"] {
            --primary: #9d4edd;
            --primary-hover: #e0aaff;
            --bg-color: #121212;
            --surface: #1e1e1e;
            --text-main: #f0f0f0;
            --text-muted: #aaaaaa;
            --border: #333333;
        }

        body {
            font-family: 'Noto Sans', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-main);
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        h1, h2, h3 {
            font-family: 'Poppins', sans-serif;
        }

        .login-container {
            background-color: var(--surface);
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            width: 100%;
            max-width: 400px;
            border: 1px solid var(--border);
            transition: background-color 0.3s ease, border-color 0.3s ease;
        }

        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .login-header h1 {
            color: var(--primary);
            margin: 0 0 10px 0;
            font-size: 28px;
        }
        
        .login-header p {
            color: var(--text-muted);
            margin: 0;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            font-size: 14px;
        }

        .form-control {
            width: 100%;
            padding: 12px;
            border: 1px solid var(--border);
            border-radius: 8px;
            background-color: var(--bg-color);
            color: var(--text-main);
            font-size: 15px;
            box-sizing: border-box;
            outline: none;
            transition: border-color 0.2s;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 2px rgba(138, 43, 226, 0.2);
        }

        .btn {
            width: 100%;
            padding: 12px;
            background-color: var(--primary);
            color: #ffffff;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            font-family: 'Poppins', sans-serif;
        }

        /* NO TRANSFORM ON HOVER APPLIED - based on instructions */
        .btn:hover {
            background-color: var(--primary-hover);
        }

        .theme-toggle {
            position: absolute;
            top: 20px;
            right: 20px;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 24px;
            color: var(--text-main);
        }
    </style>
</head>
<body>

    <!-- Thème SVG Toggle -->
    <button class="theme-toggle" id="themeToggle" title="Changer le thème" style="display:flex; align-items:center;"></button>

    <div class="login-container">
        <div class="login-header">
            <h1>Denis - Boutique</h1>
            <p>Connectez-vous à votre espace</p>
        </div>

        <form id="loginForm">
            <div class="form-group">
                <label for="nom_complet">Nom de l'utilisateur</label>
                <input type="text" id="nom_complet" name="nom_complet" class="form-control" placeholder="ex: Abdou" required autocomplete="username">
            </div>
            
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" class="form-control" required autocomplete="current-password">
            </div>

            <button type="submit" class="btn" id="loginBtn">Se connecter</button>
        </form>
    </div>

    <script>
        // Theme toggle logic
        const themeToggle = document.getElementById('themeToggle');
        const htmlElement = document.documentElement;
        
        const sunIcon = `<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="4"/><path d="M12 2v2"/><path d="M12 20v2"/><path d="m4.93 4.93 1.41 1.41"/><path d="m17.66 17.66 1.41 1.41"/><path d="M2 12h2"/><path d="M20 12h2"/><path d="m6.34 17.66-1.41 1.41"/><path d="m19.07 4.93-1.41 1.41"/></svg>`;
        const moonIcon = `<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"/></svg>`;

        function updateIcon() {
            if (!themeToggle) return;
            if (htmlElement.getAttribute('data-theme') === 'dark') {
                themeToggle.innerHTML = sunIcon;
            } else {
                themeToggle.innerHTML = moonIcon;
            }
        }

        if (localStorage.getItem('theme') === 'dark') {
            htmlElement.setAttribute('data-theme', 'dark');
        }
        updateIcon();

        themeToggle.addEventListener('click', () => {
            if (htmlElement.getAttribute('data-theme') === 'light') {
                htmlElement.setAttribute('data-theme', 'dark');
                localStorage.setItem('theme', 'dark');
            } else {
                htmlElement.setAttribute('data-theme', 'light');
                localStorage.setItem('theme', 'light');
            }
            updateIcon();
        });

        // Form submission via Fetch
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const btn = document.getElementById('loginBtn');
            btn.disabled = true;
            btn.textContent = 'Veuillez patienter ...';

            const formData = new FormData(this);

            fetch('<?= BASE_URL ?>/auth/login_process', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Connecté !',
                        text: data.message,
                        timer: 1500,
                        showConfirmButton: false,
                        confirmButtonColor: '#8A2BE2'
                    }).then(() => {
                        window.location.reload(); 
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erreur d\'authentification',
                        text: data.message,
                        confirmButtonColor: '#8A2BE2'
                    });
                    btn.disabled = false;
                    btn.textContent = 'Se connecter';
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur réseau',
                    text: 'Impossible de joindre le serveur.',
                    confirmButtonColor: '#8A2BE2'
                });
                btn.disabled = false;
                btn.textContent = 'Se connecter';
            });
        });
    </script>
</body>
</html>
