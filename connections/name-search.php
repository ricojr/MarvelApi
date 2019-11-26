<?php
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) {
  if (isset($_GET['name'])) {
    $curl = curl_init(); // Initialize a CURL session. 
    // note to self. curl need to be installed 
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);  // Return Page contents. if set 0 then no output will be returned

    $name_to_search = htmlentities(strtolower($_GET['name'])); // HuLk == hulk

    $ts = time();
    $public_key = '0f7541a86c3e6d47032becc4923a915a';
  $private_key = '44a588ed8fb1543be7dc02aca68ae5ce85700376';
    $hash = md5($ts . $private_key . $public_key);
    // hashkey is the combination of the timestamp, privatekey and publickey. this was neccessary for the marvel api to work
    $query = array(
      "name" => $name_to_search, // ""
      "orderBy" => "name",
      "limit" => "20",
      'apikey' => $public_key,
      'ts' => $ts,
      'hash' => $hash,
    );

    $marvel_url = 'http://gateway.marvel.com/v1/public/characters?' . http_build_query($query); // concatenate the array into one url link http://gateway.marvel.com/v1/public/characters?$name_to_search=name=20$public_key$$ts$hash

    curl_setopt($curl, CURLOPT_URL, $marvel_url); //grab URL and pass it to the variable.  This is your target server website address. This is the URL you want to get from the internet.

    $result = json_decode(curl_exec($curl), true);  // json_decode, convert PHP array or object into JSON representation.
        // curl_exec, It grab URL and pass it to the variable for showing output.
    curl_close($curl); // curl_close($ch) It close curl resource, and free up system resources.

    echo json_encode($result);

  } else {
    echo "Error: no name given.";
  }
} else {
  echo "Error: wrong server.";
}