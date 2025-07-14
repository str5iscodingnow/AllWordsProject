<?php
require_once __DIR__ . '/vendor/autoload.php';

// Carica variabili da .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

try {
    // Connessione a PostgreSQL via PDO
    $dsn = "pgsql:host={$_ENV['PGHOST']};port={$_ENV['PGPORT']};dbname={$_ENV['PGDATABASE']};user={$_ENV['PGUSER']};password={$_ENV['PGPASSWORD']}";
    $pdo = new PDO($dsn);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Verifica che la tabella esista
    $check = $pdo->query("SELECT to_regclass('public.parole')")->fetchColumn();
    if (!$check) {
        die("❌ Tabella 'parole' non trovata. Inserisci prima una parola dalla pagina cliente.");
    }

    // Esegui la query
    $stmt = $pdo->query("SELECT id, valore FROM parole ORDER BY id ASC");
    $valori = $stmt->fetchAll(PDO::FETCH_COLUMN, 1); // prende solo la colonna 'valore'

} catch (PDOException $e) {
    die("Errore di connessione al database: " . $e->getMessage());
}
?>


<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>admin - AllWords</title>
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
                    height: 240px;
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

            .aggiornabtn {
                align-content: center;
                padding: 5px;
                margin-right: 10px;
                background-color: #8c2a2a;
                color: white;
                text-shadow: 0px 0px 4px white;
                font-size: 23px;
                font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
                text-align: center;
                border-radius: 5px;
                border: solid 1px white;
                cursor: pointer;
                user-select: none;
                transition: all 0.2s ease;
            }

            .aggiornabtn:hover {
                transform: scale3d(1.1, 1.1, 1.1);
            }

            .aggiornabtn:active {
                transform: scale3d(0.9, 0.9, 0.9);
            }

            .maketableempty {
                align-content: center;
                padding: 5px;
                margin-right: 10px;
                background-color: #ef0000;
                color: white;
                text-shadow: 0px 0px 4px white;
                font-size: 23px;
                font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
                text-align: center;
                border-radius: 5px;
                border: solid 1px white;
                cursor: pointer;
                user-select: none;
                transition: all 0.2s ease;
            }

            .maketableempty:hover {
                transform: scale3d(1.1, 1.1, 1.1);
            }

            .maketableempty:active {
                transform: scale3d(0.9, 0.9, 0.9);
            }

            .imagecontainerSX {
                width: 100%;
                display: flex;
                flex-direction: column;
                align-items: flex-start;
                padding-left: 10px;
            }

            .allwordscontainerandfilters {
                height: 350px;
                width: auto;
                display: flex;
                flex-direction: row;
            }

            .filters {
                width: auto;
                height: 100%;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: space-evenly;
            }

            .filter {
                width: 50px;
                height: 47%;
                background-color: white;
                border-radius: 5px 0px 0px 5px;
                align-content: center;
                cursor: pointer;
                padding: 5px;
            }

            .filter-text {
                color: black;
                text-shadow: 0px 0px 4px black;
                font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
                text-align: center;
                user-select: none;
            }

            .allwordscontainer {
                height: 100%;
                width: 500px;
                padding: 15px;
                color: white;
                border-radius: 5px;
                border: solid 1px white;
                overflow-y: auto;
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

            @media (max-width: 600px) {
                .allwordscontainer {
                    width: 250px;
                }
            }

            .singleword {
                display: flex;
                align-items: center;
                height: 50px;
                padding: 5px;
                margin-bottom: 15px;
                color: white;
                border: solid 1px white;
                border-right: 0px;
                font-size: 20px;
                font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
                text-wrap: nowrap;
                user-select: none;
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
                    All Words - pagina admin
                </div>
                <div class="infobox">
                    (pagina di visualizzazione risultati)
                </div>
                <div class="aggiornabtn" id="aggiornabtn">
                    AGGIORNA
                </div>
                <div class="maketableempty" id="maketableempty">
                    SVUOTA TABELLA
                </div>
                <div class="maketableempty" id="sureaboutit" style="display: none; background-color: #545454;">
                    CONFERMA
                </div>
            </div>

            <div class="imagecontainerSX">
                <img src="./inkwell_10276196.png" class="image">
                <img src="./inkwell_10276196.png" class="image">
                <img src="./inkwell_10276196.png" class="image">
            </div>

        </div>

        <div class="secondhalf">

            <div class="allwordscontainerandfilters" id="allwordscontainerandfilters">

                <div class="filters" id="filters">
                    <div class="filter" id="filter1">
                        <div class="filter-text" id="filter-text1" style="font-size: 10.7px;">
                            D E C R E S C E N T E (%)
                        </div>
                    </div>
                    <div class="filter" id="filter2">
                        <div class="filter-text" id="filter-text2" style="font-size: 17px;">
                            + R E C E N T E
                        </div>
                    </div>
                </div>
                
                <div class="allwordscontainer" id="allwordscontainer">

                </div>
            </div>
 
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

            

            let maketableempty = document.getElementById('maketableempty');
            let sureaboutit = document.getElementById('sureaboutit');
            let aggiornabtn = document.getElementById('aggiornabtn');
            const filter1 = document.getElementById('filter1');
            const filter2 = document.getElementById('filter2');
            const filtertext1 = document.getElementById('filter-text1');
            const filtertext2 = document.getElementById('filter-text2');
            const allwordscontainer = document.getElementById('allwordscontainer');
            const valori = <?php echo json_encode($valori); ?>;
            let invertito = false;
            let ordineIniziale = [];

            filtertext1.innerHTML = filtertext1.textContent.trim().split(/\s+/).join("<br>");
            filtertext2.innerHTML = filtertext2.textContent.trim().split(/\s+/).join("<br>");

            const frequenze = {};
            for (let i = 0; i < valori.length; i++) {
                const parola = valori[i];
                frequenze[parola] = (frequenze[parola] || 0) + 1;
            }

            const maxFrequenza = Math.max(...Object.values(frequenze));
            const totaleParole = valori.length;
            const calcolaWidth = f => (f / totaleParole * 100).toFixed(1) + '%';

            const randColor = () => {
                let r, g, b;
                do {
                    r = Math.floor(Math.random() * 200);
                    g = Math.floor(Math.random() * 200);
                    b = Math.floor(Math.random() * 200);
                } while (r < 120 && g < 50 && b < 50);
                return '#' + [r, g, b].map(v => v.toString(16).padStart(2, '0')).join('');
            };

            function aggiungiParolaAContainer() {

                const paroleUniche = Object.keys(frequenze);
                for (let i = 0; i < paroleUniche.length; i++) {
                    const element = paroleUniche[i];
                    const nuovaparola = document.createElement('div');
                    nuovaparola.className = 'singleword';
                    nuovaparola.id = `singleword${i}`;
                    nuovaparola.style.width = calcolaWidth(frequenze[element]);
                    nuovaparola.textContent = `${element} (${calcolaWidth(frequenze[element])})`;
                    if (frequenze[element] === totaleParole) {
                        nuovaparola.style.borderRight = 'solid 1px white';
                    }
                    nuovaparola.style.backgroundColor = randColor();
                    allwordscontainer.appendChild(nuovaparola);
                    ordineIniziale.push(nuovaparola);
                }

            }

            function interazioneTasti(tasto1, tasto2, tasto3) {

                tasto1.addEventListener('click', () => {
                    location.reload();
                });

                tasto2.addEventListener('click', () => {
                    var timer = 1;
                    maketableempty.style.display = 'none';
                    sureaboutit.style.display = 'flex';
                    sureaboutit.textContent = `ATTENDI ${timer}`;

                    const intervalId = setInterval(() => {
                        timer++;
                        sureaboutit.textContent = `ATTENDI ${timer}`;
                    }, 1000);

                    setTimeout(() => {
                        clearInterval(intervalId);
                        sureaboutit.textContent = 'CONFERMA';
                        sureaboutit.style.backgroundColor = '#939393';
                    }, 3000);
                });

                tasto3.addEventListener('click', () => {
                    if (sureaboutit.textContent == 'CONFERMA') {
                        fetch('svuota_tabella.php').then(() => location.reload());
                    }
                });

            }

            function cliccaDecrescente() {
                filter1.addEventListener('click', () => {
                    const singleWords = Array.from(document.querySelectorAll('.singleword'));

                    const ordinati = singleWords.sort((a, b) => {
                        const parolaA = a.textContent.split(' (')[0];
                        const parolaB = b.textContent.split(' (')[0];
                        return frequenze[parolaB] - frequenze[parolaA]; //decrescente
                    });

                    allwordscontainer.replaceChildren(...ordinati);

                    invertito = false;
                });
            }

            function cliccaPiuRecente() {
                filter2.addEventListener('click', () => {
                    if (invertito) {
                        alert('Già impostato');
                        return;
                    }

                    const singleWords = Array.from(document.querySelectorAll('.singleword'));

                    const ordinatiPerRecenza = singleWords.sort((a, b) => {
                        const parolaA = a.textContent.split(' (')[0];
                        const parolaB = b.textContent.split(' (')[0];

                        const ultimoIndiceA = valori.lastIndexOf(parolaA);
                        const ultimoIndiceB = valori.lastIndexOf(parolaB);

                        return ultimoIndiceB - ultimoIndiceA;
                    });

                    allwordscontainer.replaceChildren(...ordinatiPerRecenza);
                    invertito = true;
                });
            }

            
            
            aggiungiParolaAContainer();

            interazioneTasti(aggiornabtn, maketableempty, sureaboutit);

            cliccaDecrescente();

            cliccaPiuRecente();

        </script>
        
    </body>
</html>