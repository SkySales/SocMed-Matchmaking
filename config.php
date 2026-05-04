<?php

$user = '843581747c398000b4c429ba1f8d';

$password = '069f8435-8174-7d2a-8000-c2a0100ca8e8';

$db = 'EventsWave';

$host = 'db.fr-pari1.bengt.wasmernet.com';

$port = 10272;

$conn = mysqli_connect($host, $user, $password, $db,$port) ;

if (!$conn)
{
echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

?>
