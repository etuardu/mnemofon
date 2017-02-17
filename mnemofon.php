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
      0 => "(z+(?!z)|s+(?![sc])|sc[ìieèé])",
      1 => "[td]+(?![td])",
      2 => "g?n+(?!n)",
      3 => "m+(?!m)",
      4 => "r+(?!r)",
      5 => "(l+(?!l)|(?<=[^n])gl)",
      6 => "[cg]+[iìeèé]",
      7 => "(g+(?![iìeèén])|c+(?![iìeèé]))(?![cg])",
      8 => "[fv]+(?![fv])",
      9 => "[pb]+(?![pb])"
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
