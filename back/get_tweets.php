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
    file_put_contents(time() . ".json", $status);
    $data = json_decode($status, true);
    if (is_array($data) && isset($data['user']['screen_name'])) {
      print $data['user']['screen_name'] . ': ';
      print urldecode($data['text']) . "\n";
    }
  }
}

$gt = new GetEatTwitterTweets(USERNAME, PASSWORD, Phirehose::METHOD_FILTER);
$gt->setFollow(array(28164096,17444573,148496087,20661539,14880616,17008726,20778387));
$gt->consume();
