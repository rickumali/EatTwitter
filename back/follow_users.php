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

require_once(CODE_DIR . 'libraries/twitteroauth/autoload.php');
require_once('./db_lib.php');

$oDB = new db;

$query = <<<SQL
select 
  distinct t.tweet_id, t.user_id, u.screen_name
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
  t.created_at >= '2016-12-28 00:00:00'
  and t.created_at < '2016-12-29 00:00:00'
SQL;

  $result = $oDB->select($query);
  $total = mysqli_num_rows($result);
  print "Total: $total";
  $count = 0;
  while($row = mysqli_fetch_assoc($result)) {
    $count++;
    print "{$row['user_id']} - {$row['screen_name']}\n";
  }
