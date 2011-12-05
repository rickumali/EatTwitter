<?php
require('foodgroup_mapping.php');
$view=$_GET['view'];
$title = "Food Group: $food_group[$view] at ";
require('header.html');
require_once('./140dev_config.php');
require_once('./db_lib.php');
$oDB = new db;
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
print "<div id=\"bd\">\n";
print "<h2>Food Group: $food_group[$view]</h2>\n";
print "<div id=\"default\" class=\"graph\"></div>\n";
print "<table id=\"foodgroupsTable\" class=\"tablesorter\">\n";
print "<thead>\n";
print "<tr>\n";
print "<th>Tag</th>\n";
print "<th>Mentions</th>\n";
print "</tr>\n";
print "</thead>\n";
print "<tbody>\n";
$pie_chart_data = '';
while($row = mysqli_fetch_assoc($result)) {
  print "<tr>\n<td><a href=\"/eattwitter/tag/$row[tag]\">$row[tag]</a></td>\n<td>$row[cnt]</td></tr>\n";
  $pie_chart_data .= "{ label: \"" . $row['tag']  . " \",  data: $row[cnt]},\n";
}
print "</tbody>\n";
print "</table>\n";
$tag_pie_template = file_get_contents('tag_pie_template.txt');
$tag_pie_template = str_replace( '[PIE_DATA]', $pie_chart_data, $tag_pie_template );
print $tag_pie_template;
print "</div>\n"; // This is for the 'bd'
require('footer.html'); // Contains closing div for doc
?>
