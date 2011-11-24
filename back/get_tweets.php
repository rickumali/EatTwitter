<?php
/**
* get_tweets.php
* Collect tweets from the Twitter streaming API
* This must be run as a continuous background process
* Latest copy of this code: http://140dev.com/free-twitter-api-source-code-library/
* @author Adam Green <140dev@gmail.com>
* @license GNU Public License
* @version BETA 0.10
*/
require_once('./140dev_config.php');

// Extend the Phirehose class to capture tweets in the json_cache MySQL table
require_once(CODE_DIR . 'libraries/phirehose/phirehose.php');
class Consumer extends Phirehose
{
  // A database connection is established at launch and kept open permanently
  public $oDB;
  public function db_connect() {
    require_once('./db_lib.php');
    $this->oDB = new db;
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

  // This function is called automatically by the Phirehose class
  // when a new tweet is received with the JSON data in $status
  public function enqueueStatus($status) {
    $tweet_object = json_decode($status);
    $tweet_id = $tweet_object->id_str;

    // If there's a ", ', :, or ; in object elements, serialize() gets corrupted 
    // You should also use base64_encode() before saving this
    $raw_tweet = base64_encode(serialize($tweet_object));
		
    $field_values = 'raw_tweet = "' . $raw_tweet . '", ' .
      'tweet_id = ' . $tweet_id;
    $this->oDB->insert('json_cache',$field_values);
  }
}

// Open a persistent connection to the Twitter streaming API
// Basic authentication (screen_name, password) is still used by this API
$stream = new Consumer(STREAM_ACCOUNT, STREAM_PASSWORD, Phirehose::METHOD_FILTER);

// Establish a MySQL database connection
$stream->db_connect();

// Start collecting tweets
// Automatically call enqueueStatus($status) with each tweet's JSON data
$stream->consume();

?>
