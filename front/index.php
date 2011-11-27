<html>
<head>
<title>Eat Twitter (v0.1)</title>
</head>
<body>
<ul>
<h2>Eat Twitter</h2>
<?php
require_once('./140dev_config.php');
require_once('./db_lib.php');
$oDB = new db;
$result = $oDB->select("select food_tags.tag as tag,count(food_tags.tag) as cnt from tweets_food_tags,food_tags,tweets where tweets.tweet_id = tweets_food_tags.tweet_id and food_tags.food_tag_id = tweets_food_tags.food_tag_id and food_tags.level = 2 group by food_tags.tag order by cnt desc");
while($row = mysqli_fetch_assoc($result)) {
  print "<li><a href=\"category.php?view=$row[tag]\">$row[tag]</a> - $row[cnt]\n";
}
?>
<hr/>
<a href="/eattwitter/">Eat Twitter</a>
</ul>
</body>
</html>
