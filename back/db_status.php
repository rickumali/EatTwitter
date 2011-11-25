<?php
# This is from:
# http://dev.mysql.com/doc/refman/5.1/en/show-table-status.html
#
# I had to add quotes in all the array indicies.
require_once('./db_config.php');
mysql_connect($db_host,$db_user,$db_password);
$result = mysql_query("SHOW TABLE STATUS FROM $db_name;");
while($array = mysql_fetch_array($result)) {
$total = $array['Data_length']+$array['Index_length'];
echo '
Table: '.$array['Name'].'<br />
Total Rows: '.$array['Rows'].'<br />
Data Size: '.$array['Data_length'].'<br />
Index Size: '.$array['Index_length'].'<br />
Total Size: '.$total.'<br />
Average Size Per Row: '.$array['Avg_row_length'].'<br /><br />
';
}
?> 
