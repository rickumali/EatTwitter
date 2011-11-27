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
// print "$view's ID is $view_id\n";

$result = $oDB->select("select
profile_image_url, created_at, screen_name,
name, tweet_text, tweets.tweet_id
from tweets, tweets_food_tags
where
tweets_food_tags.food_tag_id = $view_id and tweets_food_tags.tweet_id = tweets.tweet_id");
print "<ol>";
while($row = mysqli_fetch_assoc($result)) {
  print "<li>";
  print "$row[screen_name]: $row[tweet_text]\n";
}
print "</ol>";
?>
<hr/>
<a href="/eattwitter/">Eat Twitter</a>
</ul>
</body>
</html>
