<?php
// Carica variabili ambiente dal file .env
require_once __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__, 'config.env');
$dotenv->load();

// Crea la connessione PDO a PostgreSQL
try {
    $dsn = "pgsql:host={$_ENV['PGHOST']};port={$_ENV['PGPORT']};dbname={$_ENV['PGDATABASE']};user={$_ENV['PGUSER']};password={$_ENV['PGPASSWORD']}";
    $pdo = new PDO($dsn);

    // Imposta error mode
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    die("Errore di connessione: " . $e->getMessage());
}
?>
