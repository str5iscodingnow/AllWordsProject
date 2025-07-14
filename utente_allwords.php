<?php
require_once __DIR__ . '/vendor/autoload.php';

// Carica variabili .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Connessione a PostgreSQL via PDO
try {
    $dsn = "pgsql:host={$_ENV['PGHOST']};port={$_ENV['PGPORT']};dbname={$_ENV['PGDATABASE']};user={$_ENV['PGUSER']};password={$_ENV['PGPASSWORD']}";
    $pdo = new PDO($dsn);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Crea la tabella se non esiste
    $pdo->exec("CREATE TABLE IF NOT EXISTS parole (
        id SERIAL PRIMARY KEY,
        valore TEXT
    )");

    // Gestione invio POST
    if ($_SERVER["REQUEST_METHOD"] === "POST" && !empty($_POST['parola'])) {
        $parola = $_POST['parola'];
        $stmt = $pdo->prepare("INSERT INTO parole (valore) VALUES (:parola)");
        $stmt->execute([':parola' => $parola]);
    }

} catch (PDOException $e) {
    die("Errore di connessione al database: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>utente - AllWords</title>
        <style>

            a {
                color: #f2c4c4;
                text-decoration: none;
                padding-left: 5px;
                text-shadow: 0px 5px 13px white;
            }

            a:visited {
                color: #f2c4c4;
                padding-left: 5px;
                text-shadow: 0px 5px 13px white;
            }

            * {
                box-sizing: border-box;
            }

            html, body {
                height: 100%;
                margin: 0;
                overflow-x: hidden;
                width: 100%;
            }

            body {
                background: linear-gradient(to right, #8B0000 0%, #8B0000 25%, #B8860B 25%, #B8860B 50%, #006400 50%, #006400 75%, #00008B 75%, #00008B 100%);
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: space-between;
                overflow-x: hidden;
            }

            .firsthalf {
                width: 100%;
                max-width: 100%;
                display: flex;
                flex-direction: column;
                align-items: center;
            }

            .secondhalf {
                height: 600px;
                width: 100%;
                max-width: 100%;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: flex-end;
            }

            @media (max-width: 800px) {
                .secondhalf {
                    height: auto;
                }
            }

            .box-container {
                width: 100%;
                max-width: 100%;
                display: flex;
                justify-content: center;
                align-items: center;
                padding: 10px;
            }

            @media (max-width: 1400px) {
                .box-container {
                    flex-direction: column;
                    height: 140px;
                    justify-content: space-between;
                }
            }

            .backarrow {
                width: 50px;
                height: 50px;
                padding: 5px;
                color: white;
                font-size: 40px;
                user-select: none;
                cursor: pointer;
                transform: scaleX(-1);
                margin-left: 10px;
            }

            .welcome {
                align-content: center;
                padding: 5px;
                margin-right: 10px;
                color: white;
                text-shadow: 0px 0px 4px white;
                font-size: 30px;
                font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
                text-align: center;
                border-radius: 5px;
                border: solid 1px white;
                user-select: none;
            }

            .infobox {
                align-content: center;
                padding: 5px;
                margin-right: 10px;
                color: white;
                text-shadow: 0px 0px 4px white;
                font-size: 20px;
                font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
                text-align: center;
                user-select: none;
            }

            .imagecontainerSX {
                width: 100%;
                display: flex;
                flex-direction: column;
                align-items: flex-start;
                padding-left: 10px;
            }

            ::-webkit-scrollbar {
            width: 8px;
            }

            ::-webkit-scrollbar-track {
            background: #640502;
            }

            ::-webkit-scrollbar-thumb {
            background: #d68c8b;
            border-radius: 4px;
            }

            ::-webkit-scrollbar-thumb:hover {
            background: #f2c4c4;
            }

            .containerlabelandtext {
                width: 100%;
                max-width: 100%;
                display: flex;
                flex-direction: column;
                justify-content: space-evenly;
                align-items: center;
                padding: 10px;
            }

            .labelarea {
                align-content: center;
                max-width: 700px;
                margin-bottom: 20px;
                color: white;
                text-shadow: 0px 0px 5px white;
                font-size: 25px;
                font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
                text-align: center;
                user-select: none;
            }

            button {
                user-select: none;
            }
            
            .textarea {
                align-content: center;
                max-width: 700px;
                padding: 5px;
                margin-bottom: 20px;
                background-color: rgb(100, 2, 2);
                color: white;
                text-shadow: 0px 0px 5px white;
                font-size: 30px;
                font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
                text-align: center;
                border-radius: 5px/5px;
                border: solid 1px white;
            }

            .imagecontainerDX {
                width: 100%;
                display: flex;
                flex-direction: column;
                align-items: flex-end;
                padding-right: 10px;
            }

            .image {
                width: 25px;
                height: 48px;
                margin-bottom: 10px;
            }

            .license {
                display: inline;
                align-items: center;
                padding: 10px;
                color: white;
                font-size: 15px;
                font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
            }

        </style>
    </head>
    <body>

        <div class="firsthalf">

            <div class="box-container">
                <a class="backarrow" href="index.php">
                    ➯
                </a>
                <div class="welcome">
                    All Words - pagina utente
                </div>
                <div class="infobox">
                    (pagina di invio parole)
                </div>
            </div>

            <div class="imagecontainerSX">
                <img src="./inkwell_10276196.png" class="image">
                <img src="./inkwell_10276196.png" class="image">
                <img src="./inkwell_10276196.png" class="image">
            </div>

        </div>

        <div class="secondhalf">

            <form method="POST">
                <div class="containerlabelandtext">
                    <div class="labelarea">
                        Inserisci una parola:
                    </div>
                    <input class="textarea" maxlength="30" name="parola" id="parola1" required>
                    <button type="submit" class="textarea">Invia</button>
                </div>
            </form>
 
            <div class="imagecontainerDX">
                <img src="./inkwell_10276196.png" class="image" style="margin-top:10px;">
                <img src="./inkwell_10276196.png" class="image">
                <img src="./inkwell_10276196.png" class="image">
            </div>

            <div class="license">
                Questa web app utilizza software distribuito sotto licenza GNU GPL v3.
                Puoi consultare il codice sorgente completo su <a href="https://github.com/str5iscodingnow/AllWordsProject">GitHub</a>.
                Per maggiori dettagli sulla licenza <a href="https://gnu.org/licenses/gpl-3.0.html">clicca qui</a>.
            </div>
            
        </div>

        <script>

            function submitParola() {
                document.querySelector("form").addEventListener("submit", e => {
                    e.preventDefault();
                    const parola = parola1.value.trim();
                    if (!parola) return;
                    fetch("", {
                        method: "POST",
                        body: new URLSearchParams({ parola })
                    }).then(() => parola1.value = "");
                    alert("parola inviata correttamente!");
                });
            }
            
            submitParola();

        </script>
        
    </body>
</html>