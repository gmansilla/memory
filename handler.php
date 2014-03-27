<?php
require_once 'lib/Game.php';
class Handler {
	public $error = 0;
	public $message = "";
	public $game;
	public function __construct() {
		$this->game = $_SESSION['game'];
	}
}

header('Content-Type: application/json');
$handler = new Handler();
if (!isset($_GET['index'])) {
	$handler->error = 1;
	$handler->message = 'Not a valid card';
	echo json_encode($response);
	exit();
}
$result = $handler->game->uncoverCard($_GET['index']);
echo json_encode($result);