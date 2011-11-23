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
}

# NOTE: The two lines below are the ORIGINAL CODE:
# $gt = new GetEatTwitterTweets(USERNAME, PASSWORD, Phirehose::METHOD_FILTER);
# $gt->setFollow(array(28164096,17444573,148496087,20661539,14880616,17008726,20778387));

$gt = new GetEatTwitterTweets(USERNAME, PASSWORD, Phirehose::METHOD_SAMPLE);
$gt->consume();
