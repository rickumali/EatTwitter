<?php
/**
 * generate_tweet.php 
 *
 * PHP version 5
 *
 * Each 'run' of this program produces a Tweet.
 *
 * @category EatTwitter
 * @package  EatTwitter
 * @author   Rick Umali <rickumali@gmail.com>
 * @license  Rick Umali
 * @version  1.0
 * @link     http://www.eattwitter.com/
 */
require_once './140dev_config.php';
require_once './db_lib.php';
$oDB = new db;

$yesterday_rounded = get_yesterday_rounded();
$sort_order = 'desc';
print "$yesterday_rounded\n";

$query = "
select    
  food_tags.tag as tag, count(food_tags.tag) as cnt  
from   
  tweets_food_tags,food_tags,tweets  
where    
  tweets.tweet_id = tweets_food_tags.tweet_id    
  and food_tags.food_tag_id = tweets_food_tags.food_tag_id    
  and food_tags.level = 1 
  and created_at > '$yesterday_rounded' 
group by food_tags.tag 
order by cnt $sort_order 
limit 1";
$result = $oDB->select($query);
$row = mysqli_fetch_assoc($result);
$tag = $row['tag'];
$count = $row['cnt'];
print "$count: $tag ($count)\n";

/**
 * get_yesterday_rounded()
 *
 * This gets the timestamp for 24 hours ago, rounded to the 
 * 15th minute (0, 15, 30, or 45). It rounds 'back' (see 
 * runroundtime.php for details).
 * 
 * @param Timestamp $ts An arbitrary timestamp
 *
 * @return A string containing yesterday's time stamp for SQL 
 */
function get_yesterday_rounded($ts = null) 
{
    if (!isset($ts)) {
        $ts = time();
    }
    $ts_parts = getdate($ts);
    $new_minutes = $ts_parts['minutes'] - $ts_parts['minutes'] % 15;
    $yesterday_ts_rounded = strtotime(
        '-1 day', 
        mktime(
            $ts_parts['hours'], $new_minutes, 0,
            $ts_parts['mon'], $ts_parts['mday'], $ts_parts['year']
        )
    );
    $yesterday_rounded_string = date('Y-m-d H:i:s', $yesterday_ts_rounded);
    return $yesterday_rounded_string;
}

