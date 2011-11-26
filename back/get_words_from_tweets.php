<?php
/**
 * get_words_from_tweets.php 
 *
 * Run like this: php get_words_from_tweets.php > /tmp/output
 *
 * This produces a raw list of all words from all tweets, ONE WORD PER 
 * LINE. To analyze this, use this UNIX command line:
 *
 * sort /tmp/output|uniq -c | sort -nr > /tmp/sorted.freq.words
 *
 * @author Rick Umali
 * @license GNU Public License
 * @version BETA 0.10
*/
require_once('./140dev_config.php');
require_once('./db_lib.php');
$oDB = new db;

// Process all tweets
$query = 'SELECT cache_id, raw_tweet FROM json_cache';
$result = $oDB->select($query);
$total = mysqli_num_rows($result);
$count = 0;
while($row = mysqli_fetch_assoc($result)) {
  $count++;		
  $cache_id = $row['cache_id'];
  // Each JSON payload for a tweet from the API was stored in the database  
  // by serializing it as text and saving it as base64 raw data
  $tweet_object = unserialize(base64_decode($row['raw_tweet']));
		
  // Gather tweet data from the JSON object
  // $oDB->escape() escapes ' and " characters, and blocks characters that
  // could be used in a SQL injection attempt
  $tweet_id = $tweet_object->id_str;
  $tweet_text = $oDB->escape($tweet_object->text);
  // print "Handled $tweet_id ($count out of $total)\n";
  fwrite(STDERR, "Tweet $count (out of $total)\n");

  $tok = strtok($tweet_text, " \n\t");
  while ($tok !== false) {
    $tok = strtolower($tok); # Lowercase token
    $tok = iconv('UTF-8', 'ASCII//TRANSLIT', $tok); # Remove all accents
    $tok = preg_replace("/[^a-z]/", "", $tok); # Remove all non-alphas
    print "$tok\n";
    $tok = strtok(" \n\t");
  }
} 
?>
