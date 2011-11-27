<?php
# load_vocabulary.php
# 
# This populates the database with the vocabulary used by the tagging
# system.
require_once('./140dev_config.php');
require_once('./db_lib.php');
$oDB = new db;

$lines = file("vocabulary.list", FILE_IGNORE_NEW_LINES|FILE_SKIP_EMPTY_LINES);
foreach ($lines as $line_num => $line) {
  if (substr($line, 0, 1) != "#") {
    $line = chop($line);
    $content = explode(":", $line);
    $level = 0;
    foreach ($content as $tag) {
      $level++;
      // Check if the tag is already in the food_tags table.
      // check if it exists in food_tags table.
      $result = $oDB->select('SELECT food_tag_id from food_tags where tag="' . $tag . '"');
      $num_rows = mysqli_num_rows($result);
      if ($num_rows == 1) {
        // TODO: Potentially update the tag if it's in there. Either
        // way, grab the ID.
        $row = mysqli_fetch_assoc($result);
        $id = $row['food_tag_id'];
      } else if ($num_rows == 0) {			
        $field_values = 'tag = "' . $tag . '", ' .
          'level = "' . $level . '"';
        $oDB->insert('food_tags',$field_values);
        $id = $oDB->dbh->insert_id;
      } else {
        print "Error condition: \'$tag\' has more than one row!?\n";
      }
      if ($level > 1) {
        # At this point, if the level is NOT the lowest, then 
        # set the previous element's parent_id to this ID
        $oDB->update('food_tags','parent_id=' . $id,'food_tag_id=' . $child_id);
      }
      # Save the current ID as the child ID for next run through 
      # the loop.
      $child_id = $id;
    }
  }
}
?>
