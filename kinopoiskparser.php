<?php 
  $___notjson = 1;  
  $kpid = $_GET['id']; 
  $xml = simplexml_load_file("http://www.kinopoisk.ru/rating/$kpid.xml"); 
  foreach ($xml->xpath("kp_rating") as $kpr) {echo round($kpr) ." голосов: ".$kpr["num_vote"]." ";}   
  foreach ($xml->xpath("imdb_rating") as $kpr) {echo $kpr." голосов: ".$kpr["num_vote"];}   
  ?>