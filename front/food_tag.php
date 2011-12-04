<?php
$view=$_GET['view'];
$title = "$view at ";
require('header.html');
require_once('./140dev_config.php');
require_once('./db_lib.php');
require_once('./twitter_display_config.php' );
require_once('./linkify.php');
$oDB = new db;
$pS = $oDB->dbh->prepare("select food_tags.food_tag_id as viewid from food_tags where tag = ?");
$pS->bind_param('s',$view);
$pS->execute();
$pS->bind_result($view_id);

$count = 0;
while ($pS->fetch()) {
  $count++;
}
if ($count == 0) {
  ob_end_clean();
  header('Location: /eattwitter/');
  exit();
}
$result = $oDB->select("select profile_image_url, created_at, screen_name, name, tweet_text, tweets.tweet_id, entities from tweets, tweets_food_tags where tweets_food_tags.food_tag_id = $view_id and tweets_food_tags.tweet_id = tweets.tweet_id ORDER BY tweet_id DESC");
if (mysqli_num_rows($result) == 0) {
  ob_end_clean();
  header('Location: /eattwitter/thankyou.html');
  exit();
}
print "<h2>Tweets mentioning '$view'</h2>\n";
print "<ol>";

$tweet_template = file_get_contents('tweet_template.txt');

while($row = mysqli_fetch_assoc($result)) {
  print "<li>";

  // create a fresh copy of the empty template
  $current_tweet = $tweet_template;
        
  // Fill in the template with the current tweet
  $current_tweet = str_replace( '[profile_image_url]', 
    $row['profile_image_url'], $current_tweet);
  $current_tweet = str_replace( '[created_at]', 
    twitter_time($row['created_at']), $current_tweet);                  
  $current_tweet = str_replace( '[screen_name]', 
          $row['screen_name'], $current_tweet);  
  $current_tweet = str_replace( '[name]', 
    $row['name'], $current_tweet);    
  $current_tweet = str_replace( '[user_mention_title]', 
    USER_MENTION_TITLE . ' ' . $row['screen_name'] . ' (' . $row['name'] . ')', 
    $current_tweet);  
  $current_tweet = str_replace( '[tweet_display_title]', 
    TWEET_DISPLAY_TITLE, $current_tweet);  

  // The entities object was stored as base64_encode(serialize())
  $entities = unserialize(base64_decode($row['entities']));
  $current_tweet = str_replace( '[tweet_text]', 
    linkify($row['tweet_text'], $entities), $current_tweet);  
                
  // Include each tweet's id so site.js can request older or newer tweets
  $current_tweet = str_replace( '[tweet_id]', 
    $row['tweet_id'], $current_tweet); 

  print $current_tweet;
}
print "</ol>";

// Convert the tweet creation date/time to Twitter format
// This eliminates annoying server vs. browser time zone differences
function twitter_time($time) {
  $delta = time() - strtotime($time);
  if ($delta < 60) {
    return 'less than a minute ago';
  } else if ($delta < 120) {
    return 'about a minute ago';
  } else if ($delta < (45 * 60)) {
    return floor($delta / 60) . ' minutes ago';
  } else if ($delta < (90 * 60)) {
    return 'about an hour ago.';
  } else if ($delta < (24 * 60 * 60)) {
    return floor($delta / 3600) . ' hours ago';
  } else if ($delta < (48 * 60 * 60)) {
    return '1 day ago';
  } else {
    return floor($delta / 86400) . ' days ago';
  }
}

require('footer.html');
?>
