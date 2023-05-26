<?php
error_reporting(E_ERROR | E_PARSE);
// подключаем библиотеку Steam Condenser
// include the Steam Condenser library
require_once './lib/steam-condenser.php'; 
 // подключаем библиотеку Rcon
 // include the Rcon library
require_once './lib/Rcon.php';

// IP-адрес и порт сервера Conan Exiles
// IP address and port of the Conan Exiles server
$serverIP = 'IP';
$serverPort = PORT;

$server = new GoldSrcServer('IP', 'PORT');
$server->initialize();
$playerObjects = array_values($server->getPlayers());

$names = array();

foreach ($playerObjects as $player) {
	// Удаление части после #
	// Remove part after #
    $name = explode('#', $player->getName())[0]; 
    $names[] = $name;
}
 // Объединение имен через запятую
 // Combine names separated by commas
$namesString = implode(',', $names);

$rcon = new rcon('PASSWORD', 'IP', PORT);
if ($rcon->connected) {
	echo '<p>RCON: connected!</p>';
	$arr = explode(",", $namesString);
		$result = $rcon->send('listplayers');
		// Разделение строки на массив строк по символу перевода строки
		// Split the string into an array of strings based on the newline character
		$rows = explode("\n", $result);
		
		// Извлечение значения столбца "Char name" из каждой строки
		// Extract the value of the "Char name" column from each row
		foreach ($rows as $row) {
			// Разделение строки на массив значений по символу |
			// Split the string into an array of values by character |
			$columns = explode("|", $row);
		
			// Извлечение значения столбца "Char name" из индекса 1
			// Retrieve the value of the "Char name" column from index 1
			$charName = trim($columns[1]);
			$result = $rcon->send('directmessage "'.$_GET['author'].'" "' . $charName . '" "'.$_GET['message'].'"');
		}
		echo $_GET['author'].",".$_GET['message']."</br>";
}
?>