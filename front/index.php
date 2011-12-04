<?php
require('foodgroup_mapping.php');
$title = "";
require('header.html');
require_once('./140dev_config.php');
require_once('./db_lib.php');
$oDB = new db;
$result = $oDB->select("select food_tags.tag as tag,count(food_tags.tag) as cnt from tweets_food_tags,food_tags,tweets where tweets.tweet_id = tweets_food_tags.tweet_id and food_tags.food_tag_id = tweets_food_tags.food_tag_id and food_tags.level = 2 group by food_tags.tag order by cnt desc");
if (mysqli_num_rows($result) == 0) {
  ob_end_clean();
  header('Location: thankyou.html');
  exit();
}
?>
<h2>Eat Twitter</h2>
<?php
while($row = mysqli_fetch_assoc($result)) {
  # print "<li><a href=\"foodgroup/".$row['tag']."\">" . $food_group[$row['tag']] . "</a> - $row[cnt]\n";
  print "<li><a href=\"foodgroup/$row[tag]\">".$food_group[$row['tag']]."</a> - $row[cnt]\n";
}
require('footer.html');
?>
