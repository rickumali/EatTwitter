<?php
# db_reset.php
# 
# This deletes the tables and resets the parsed field in the json_cache. 
#
# Run this if you want to reprocess all the tweets in the json_cache.
#
# NOTE: To rebuild the food_tags table, rerun the load_vocabulary.php 
# script.
require_once('./140dev_config.php');
require_once('./db_lib.php');
$oDB = new db;
$result = mysqli_query( $oDB->dbh, "TRUNCATE table tweet_mentions" );
$result = mysqli_query( $oDB->dbh, "TRUNCATE table tweet_tags" );
$result = mysqli_query( $oDB->dbh, "TRUNCATE table tweet_urls" );
$result = mysqli_query( $oDB->dbh, "TRUNCATE table tweets" );
$result = mysqli_query( $oDB->dbh, "TRUNCATE table users" );
$result = mysqli_query( $oDB->dbh, "TRUNCATE table tweets_food_tags" );
$result = mysqli_query( $oDB->dbh, "UPDATE json_cache SET parsed=0" );
?>
