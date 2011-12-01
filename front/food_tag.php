<?php
require('header.html');
require_once('./140dev_config.php');
require_once('./db_lib.php');
$oDB = new db;
$view=$_GET['view'];
$result = $oDB->select("select food_tags.food_tag_id as viewid from food_tags where tag = \"$view\"");
$row = mysqli_fetch_assoc($result);
if (mysqli_num_rows($result) == 0) {
  ob_end_clean();
  header('Location: /eattwitter/');
  exit();
}
$view_id = $row['viewid'];
$result = $oDB->select("select profile_image_url, created_at, screen_name, name, tweet_text, tweets.tweet_id from tweets, tweets_food_tags where tweets_food_tags.food_tag_id = $view_id and tweets_food_tags.tweet_id = tweets.tweet_id");
if (mysqli_num_rows($result) == 0) {
  ob_end_clean();
  header('Location: /eattwitter/thankyou.html');
  exit();
}
print "<h2>$view</h2>\n";
print "<ol>";
while($row = mysqli_fetch_assoc($result)) {
  print "<li>";
  print "$row[screen_name]: $row[tweet_text]\n";
}
print "</ol>";
require('footer.html');
?>
