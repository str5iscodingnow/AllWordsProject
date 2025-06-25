<?php
$db = new SQLite3('mio_database.sqlite');
$db->exec("DELETE FROM parole");
$db->exec("DELETE FROM sqlite_sequence WHERE name='parole'");
$db->close();
?>