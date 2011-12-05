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
<div id="hd">
Eat Twitter
</div>
<div id="bd">
<div id="default" class="graph"></div>
<table id="foodgroupsTable" class="tablesorter">
<thead>
<tr>
<th>Food Group</th>
<th>Mentions</th>
</tr>
</thead>
<tbody>
<?php
$pie_chart_data = '';
while($row = mysqli_fetch_assoc($result)) {
  print "<tr>\n<td><a href=\"foodgroup/$row[tag]\">".$food_group[$row['tag']]."</a></td>\n<td>$row[cnt]</td></tr>\n";
  $pie_chart_data .= "{ label: \"" . $food_group[$row['tag']]  . " \",  data: $row[cnt]},\n";
}
print "</tbody>";
print "</table>";

$flot_pie_template = file_get_contents('flot_pie_template.txt');
$flot_pie_template = str_replace( '[PIE_DATA]', $pie_chart_data, $flot_pie_template );
print $flot_pie_template;
print "</div>\n"; // This is for the 'bd' 
require('footer.html');
?>
