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
  header('Location: /thankyou.html');
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
$pie_chart_data = rtrim($pie_chart_data, "\n,");
?>
</tbody>
</table>

<?php
$foodgroup_pie_template = file_get_contents('foodgroup_pie_template.txt');
$foodgroup_pie_template = str_replace( '[PIE_DATA]', $pie_chart_data, $foodgroup_pie_template );
print $foodgroup_pie_template;
?>
</div> <!-- This is for the 'bd' -->
<?php
require('footer.html'); // Contains closing div for doc
?>
