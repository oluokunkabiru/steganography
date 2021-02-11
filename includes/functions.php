<?php
function convertMeToBinary($me){
    $fileToConvert = (string)$me;
    $l = strlen($fileToConvert);
    $result = "";
    while($l--){
        $result = str_pad(decbin(ord($fileToConvert[$l])),8, '0',  STR_PAD_LEFT ).$result;
    }
    return $result;
}
  
  // function toString($binary){
  //   return pack('H*',base_convert($binary,2,16));
  // }

  function toString($str) {
    $text_array = explode("\r\n", chunk_split($str, 8));
    $newstring = '';
    for ($n = 0; $n < count($text_array) - 1; $n++) {
        $newstring .= chr(base_convert($text_array[$n], 2, 10));
    }
    return $newstring;
}
?>