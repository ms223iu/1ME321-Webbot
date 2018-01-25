<?php
require_once(__DIR__ . '/classes/L2Parser.php');
require_once(__DIR__ . '/classes/L2Responder.php');
require_once(__DIR__ . '/classes/Student.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST)):
  $_POST = (array) json_decode(file_get_contents('php://input'), true);
endif;

$method = $_POST['method'];
$username = trim($_POST['username']);

// Single student validation request
if ($method == 'student' && ctype_alnum($username) && strlen($username) < 10) {
    $student = new Student($username);
    $l2responder = new L2Responder(new L2Parser($student));

    // Generate response
    echo json_encode(array(
      'username' => $student->getUsername(),
      'result' => $l2responder->getResult()
    ));
}
