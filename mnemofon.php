<?php

  $number = trim($_GET['n']);

  if (!ctype_digit($number)) {
    $resp = array(
      "err" => 1,
      "errtxt" => "Not a valid integer"
    );
    die(json_encode($resp));
  };

  function num2pattern($num) {
    $VOW = "[aàeèéiìoòuù]*";
    $RULE = array(
      0 => "(z+|s+(?!c)|sc[ie])",
      1 => "[td]+",
      2 => "g?n+",
      3 => "m+",
      4 => "r+",
      5 => "(l+|(?<=[^n])gl)",
      6 => "[cg]+[ie]",
      7 => "(g+(?![ien])|c+(?![ie]))",
      8 => "[fv]+",
      9 => "[pb]+"
    );

    // ---

    $pat = "#^$VOW";
    foreach (str_split($num) as $n) {
      $pat .= $RULE[$n] . $VOW;
    }
    $pat .= "$#";

    return $pat;
  }

  $pat = num2pattern($number);

  $f = fopen("diz", "r");
  $words = [];
  while ($l = trim(fgets($f))) {
    if (preg_match($pat, $l)) {
      $words[] = $l;
    }
  }
  fclose($f);

  $resp = array(
    "err" => 0,
    "words" => $words
  );

  echo json_encode($resp, JSON_PRETTY_PRINT);

?>
