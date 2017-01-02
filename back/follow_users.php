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

$yesterday = null;
$day_after_yesterday = null;
if (isset($argv[1])) {
  $yesterday = new DateTime($argv[1]);
  $day_after_yesterday = new DateTime($yesterday->format('m/d/Y'));
  $day_after_yesterday->add(new DateInterval('P1D'));
  var_dump($yesterday);
  var_dump($day_after_yesterday);
} else {
  echo "calculate yesterday\n";
  $yesterday = new DateTime();
  $yesterday->sub(new DateInterval('P1D'));
  $day_after_yesterday = new DateTime($yesterday->format('m/d/Y'));
  $day_after_yesterday->add(new DateInterval('P1D'));
  var_dump($yesterday);
  var_dump($day_after_yesterday);
}

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
  t.created_at >= '{$yesterday->format('Y-m-d 00:00:00')}'
  and t.created_at < '{$day_after_yesterday->format('Y-m-d 00:00:00')}'
SQL;

print $query;

exit(0);

  $result = $oDB->select($query);
  $total = mysqli_num_rows($result);
  print "Total: $total";
  $count = 0;
  while($row = mysqli_fetch_assoc($result)) {
    $count++;
    print "{$row['user_id']} - {$row['screen_name']}\n";
  }
