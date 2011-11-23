<?php
require_once('lib/Phirehose.php');
require('environment.php'); # This contains the username/password

class GetEatTwitterTweets extends Phirehose
{

  /**
   * Enqueue each status
   *
   * @param string $status
   */
  public function enqueueStatus($status)
  {
    $folder = date('Y-m-d');
    $data = json_decode($status, true);
    if (is_array($data) && isset($data['id_str'])) {
      if (!is_dir($folder)) {
        mkdir($folder);
      }
      file_put_contents($folder . "/" . $data['id_str'] . ".json", $status);
    }
  }

  /**
   * Enqueue filter predicates
   *
   */
  public function checkFilterPredicates()
  {
    if (is_file("follow.list")) {
      $lines = file("follow.list");
      foreach ($lines as $line_num => $line) {
        if (substr($line, 0, 1) != "#") {
          $line = chop($line);
          # Any characters after the space are ignored
          $id = strtok($line, " "); 
          $follow_list[] = $id;
        }
      }
      $this->setFollow($follow_list);
    }
  }

}

# NOTE: The two lines below are the ORIGINAL CODE:
$gt = new GetEatTwitterTweets(USERNAME, PASSWORD, Phirehose::METHOD_FILTER);
$gt->consume();
