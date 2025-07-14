<?php
require_once __DIR__ . '/vendor/autoload.php';

// Carica le variabili d'ambiente da .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

try {
    // Connessione al database PostgreSQL via PDO
    $dsn = "pgsql:host={$_ENV['PGHOST']};port={$_ENV['PGPORT']};dbname={$_ENV['PGDATABASE']};user={$_ENV['PGUSER']};password={$_ENV['PGPASSWORD']}";
    $pdo = new PDO($dsn);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Svuota la tabella parole
    $pdo->exec("TRUNCATE TABLE parole RESTART IDENTITY");

    echo "✅ Tabella 'parole' svuotata con successo.";

} catch (PDOException $e) {
    die("Errore nella connessione o svuotamento della tabella: " . $e->getMessage());
}
?>
