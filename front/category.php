<html>
<head>
<title>Eat Twitter (v0.1)</title>
</head>
<body>
<ul>
<?php
$view=$_GET['view'];
print "<h2>$view</h2>\n";
require_once('./140dev_config.php');
require_once('./db_lib.php');
$oDB = new db;

$result = $oDB->select("select food_tags.food_tag_id as viewid from food_tags where tag = \"$view\"");
$row = mysqli_fetch_assoc($result);
$view_id = $row['viewid'];

$result = $oDB->select("select food_tags.tag as tag,count(food_tags.tag) as cnt from tweets_food_tags,food_tags,tweets where tweets.tweet_id = tweets_food_tags.tweet_id and food_tags.food_tag_id = tweets_food_tags.food_tag_id and food_tags.parent_id = $view_id and food_tags.level = 1 group by food_tags.tag order by cnt desc");
while($row = mysqli_fetch_assoc($result)) {
  print "<li><a href=\"food_tag.php?view=$row[tag]\">$row[tag]</a> - $row[cnt]\n";
}
?>
<hr/>
<a href="/eattwitter/">Eat Twitter</a>
</ul>
</body>
</html>
