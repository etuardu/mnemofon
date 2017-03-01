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
    $VOW = "[aàeèéiìoòuù]";
    $RULE = array(
      0 => "(z+|s+(c[ìieèé])?)",
      1 => "[td]+",
      2 => "g?n+",
      3 => "m+",
      4 => "r+",
      5 => "(l+|(?<=[^n])gl)",
      6 => "[cg]+[iìeèé]",
      7 => "(g+(?![iìeèén])|c+(?![iìeèé]))",
      8 => "[fv]+",
      9 => "[pb]+"
    );

    // ---

    $pat = "#^$VOW";
    $last_n = -1;
    foreach (str_split($num) as $n) {
      if (($n != $last_n) || ($n == 6)) {
        // the current consonant is different
        // from the last put one, or it
        // already has a trailing vowel (6 = gi|ci)
        $pat .= "*"; // 0 or more leading vowels
      } else {
        // two same consonants in a row,
        // must be separated by a vowel
        $pat .= "+"; // 1 or more leading vowels
      }
      $pat .= $RULE[$n] . $VOW;
      $last_n = $n;
    }
    if (substr($pat, strlen($pat)-1, 1) != "*") $pat .= "*";
    $pat .= "$#u";

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
    "words" => $words,
    "regex" => $pat
  );

  echo json_encode($resp, JSON_PRETTY_PRINT);

?>
