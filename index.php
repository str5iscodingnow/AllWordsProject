<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>All Words - Benvenuto</title>
    <style>
        * {
            box-sizing: border-box;
        }

        html, body {
            height: 100%;
            margin: 0;
        }

        body {
            background: linear-gradient(to right, #8B0000 0%, #8B0000 25%, #B8860B 25%, #B8860B 50%, #006400 50%, #006400 75%, #00008B 75%, #00008B 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
        }

        .container {
            text-align: center;
            color: white;
        }

        .title {
            font-size: 48px;
            margin-bottom: 30px;
            text-shadow: 0px 0px 10px white;
        }

        .nav-button {
            display: inline-block;
            margin: 20px;
            padding: 15px 30px;
            background-color: #8c2a2a;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            border: solid 1px white;
            font-size: 20px;
            text-shadow: 0px 0px 4px white;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .nav-button:hover {
            transform: scale(1.1);
            background-color: #a83333;
        }

        .nav-button:active {
            transform: scale(0.9);
        }

        /* Autenticazione full screen */
        .auth-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(100, 2, 2, 0.95);
            display: none;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }

        .auth-container {
            text-align: center;
            color: white;
            background-color: rgb(100, 2, 2);
            padding: 40px;
            border-radius: 10px;
            border: solid 2px white;
            box-shadow: 0px 0px 20px rgba(255, 255, 255, 0.3);
        }

        .auth-title {
            font-size: 36px;
            margin-bottom: 20px;
            text-shadow: 0px 0px 10px white;
        }

        .auth-label {
            font-size: 20px;
            margin-bottom: 15px;
            text-shadow: 0px 0px 4px white;
        }

        .auth-input {
            padding: 10px;
            margin-bottom: 20px;
            background-color: rgb(100, 2, 2);
            color: white;
            text-shadow: 0px 0px 5px white;
            font-size: 20px;
            font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
            text-align: center;
            border-radius: 5px;
            border: solid 1px white;
            width: 250px;
        }

        .auth-input::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

    </style>
</head>
<body>
    <!-- Overlay di autenticazione full screen -->
    <div class="auth-overlay" id="auth-overlay">
        <div class="auth-container">
            <div class="auth-title">Autenticazione Admin</div>
            <div class="auth-label">Inserisci la password per accedere:</div>
            <input type="password" class="auth-input" id="auth-password" maxlength="10" placeholder="Password">
        </div>
    </div>

    <div class="container">
        <h1 class="title">All Words</h1>
        <p style="font-size: 24px; margin-bottom: 40px;">Scegli la tua sezione:</p>

        <a href="utente_allwords.php" class="nav-button">Pagina Utente<br><small>(Inserisci parole)</small></a>

        <div class="nav-button" id="admin-access-button">Accesso Admin<br><small>(Richiede password)</small></div>
    </div>

    <script>
        const authOverlay = document.getElementById('auth-overlay');
        const authPassword = document.getElementById('auth-password');
        const adminAccessButton = document.getElementById('admin-access-button');

        // Mostra overlay autenticazione quando si clicca su "Accesso Admin"
        adminAccessButton.addEventListener('click', () => {
            authOverlay.style.display = 'flex';
            authPassword.focus();
        });

        // Event listener per la password
        authPassword.addEventListener('keypress', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                const password = authPassword.value.trim();
                if (password === 'admin123') {
                    window.location.href = "admin_allwords.php";
                } else {
                    alert('Password errata!');
                    authPassword.value = '';
                    authPassword.focus();
                }
            }
        });

        // Nasconde overlay se si clicca fuori
        authOverlay.addEventListener('click', function(event) {
            if (event.target === authOverlay) {
                authOverlay.style.display = 'none';
                authPassword.value = '';
            }
        });
    </script>
</body>
</html>