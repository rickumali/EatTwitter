<?php
/**
 * getuserid.php username1 username2 ... usernameN 
 *
 * This program takes Twitter usernames, and obtains their ids.
 *
 * See:
 * 
 * https://dev.twitter.com/docs/api/1/get/users/lookup
 */
if ($argc == 1) {
  exit("No args passed!");
}

unset($argv[0]); # Removes the program from the argument array list

$lookup = "http://api.twitter.com/1/users/lookup.json?screen_name=";
$lookup .= implode(',',$argv); # Comma separate the arguments
$lookup .= "&include_entities=false";

$json = file_get_contents($lookup);
$data = json_decode($json);
if (is_array($data)) {
  foreach ($data as $user) {
    # NOTE: Use the following if using assoc=true in json_decode()
    # print $user['id'] . " => " . $user['name'] . " (" . $user['screen_name'] . ")\n";
    # $id_list[] = $user['id'];

    # NOTE: Use the following if using json_decode()'s default
    # for assoc, which returns "objects" 
    print $user->{'id'} . " => " . $user->{'name'} . " (" . $user->{'screen_name'} . ")\n";
    $id_list[] = $user->{'id'};
  }
}
print implode(',',$id_list);
