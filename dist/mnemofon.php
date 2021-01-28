<?php

  $number = trim($_GET['n']);

  if (!ctype_digit($number)) {
    $resp = array(
      "err" => 1,
      "errtxt" => "Not a valid integer"
    );
    die(json_encode($resp));
  };

  // --

  $db = new SQLite3('diz_ita.db');

  $results = $db->query("SELECT word FROM words WHERE digits='$number'");
  $words = [];
  while ($row = $results->fetchArray()) {
    $words[] = $row[0];
  }

  // --

  $resp = array(
    "err" => 0,
    "words" => $words,
    "regex" => $pat
  );

  echo json_encode($resp, JSON_PRETTY_PRINT);

?>
