<?php
require('header.html');
require_once('./140dev_config.php');
require_once('./db_lib.php');
$oDB = new db;
$view=$_GET['view'];
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
$result = $oDB->select("select food_tags.tag as tag,count(food_tags.tag) as cnt from tweets_food_tags,food_tags,tweets where tweets.tweet_id = tweets_food_tags.tweet_id and food_tags.food_tag_id = tweets_food_tags.food_tag_id and food_tags.parent_id = $view_id and food_tags.level = 1 group by food_tags.tag order by cnt desc");
if (mysqli_num_rows($result) == 0) {
  ob_end_clean();
  header('Location: /eattwitter/thankyou.html');
  exit();
}
print "<h2>$view</h2>\n";
while($row = mysqli_fetch_assoc($result)) {
  print "<li><a href=\"/eattwitter/tag/$row[tag]\">$row[tag]</a> - $row[cnt]\n";
}
require('footer.html');
?>
