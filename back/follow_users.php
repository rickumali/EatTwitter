<?php
/**
* follow_users.php
* Follow users
* Calculates the new users from the yesterday's 24 hour period that meet
* a critiera, and follows them
* @author Rick Umali
* @license GNU Public License
* @version BETA 0.10
*/
require_once('./140dev_config.php');

require_once(CODE_DIR . 'libraries/twitteroauth/autoloader.php');
require_once('./db_lib.php');

use Abraham\TwitterOAuth\TwitterOAuth;

$yesterday = null;
$day_after_yesterday = null;
if (isset($argv[1])) {
  $yesterday = new DateTime($argv[1]);
  $day_after_yesterday = new DateTime($yesterday->format('m/d/Y'));
  $day_after_yesterday->add(new DateInterval('P1D'));
} else {
  $yesterday = new DateTime();
  $yesterday->sub(new DateInterval('P1D'));
  $day_after_yesterday = new DateTime($yesterday->format('m/d/Y'));
  $day_after_yesterday->add(new DateInterval('P1D'));
}

$oDB = new db;

$query = <<<SQL
select
  distinct t.user_id, u.screen_name,
  u.followers_count
from 
  tweets t
join
  tweets_food_tags tft
on
  t.tweet_id = tft.tweet_id
join
  users u
on
  t.user_id = u.user_id
where
  t.created_at >= '{$yesterday->format('Y-m-d 00:00:00')}'
  and t.created_at < '{$day_after_yesterday->format('Y-m-d 00:00:00')}'
  and u.followers_count >= 1000
order by u.followers_count desc
limit 20
SQL;

$connection = new TwitterOAuth(TWITTER_CONSUMER_KEY, TWITTER_CONSUMER_SECRET, OAUTH_TOKEN, OAUTH_SECRET);

  $result = $oDB->select($query);
  $total = mysqli_num_rows($result);
  $count = 0;
  while($row = mysqli_fetch_assoc($result)) {
    $count++;
    error_log("follow_users.php: {$row['user_id']} - {$row['screen_name']} - {$row['followers_count']}\n");
    $status = $connection->post("friendships/create", array("screen_name" => $row['screen_name']));
    if ($connection->lastHttpCode() != 200) {
      error_log("follow_users.php: Problemo: {$row['screen_name']}: {$connection->lastHttpCode()}\n");
    }
  }
