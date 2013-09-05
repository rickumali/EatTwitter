EATTWITTER
==========

Introduction
------------

This code you're looking at is the back-end code for a website I created
called Eat Twitter. You can visit it here:

http://www.eattwitter.com/

EatTwitter aggregates the tweets of a select list of cooks. These tweets are
then examined word-by-word, aggregating all ingredients and organizing them
into food groups.

Supporting Code
---------------

As a framework, I used the [140dev Twitter
Framework](http://140dev.com/free-twitter-api-source-code-library/). This code
was invaluable for just getting the main ideas working. This framework in turn
uses [Phirehose](https://github.com/fennb/phirehose), which makes it easy to
use the Twitter Streaming API.

Stuff I Did
-----------

### Taxonomy

I spent time with my wife working out the vocabulary and the taxonomy. 

### Tagging Schema

I modified the framework sample database to use a tagging schema. 

I used the 'Toxi' solution described here:

http://tagging.pui.ch/post/37027745720/tags-database-schemas

This involved creating a junction table to unite the tag with the tweet. The
tags are related to the tweets in a 'many-to-many' relationship.

In addition, I implemented a hierarchical structure, so that tags can have a
simple parent-child relationship. This enables me to organize things by
foodgroup or by ingredient.

### Vocabulary Loading

I needed to write a program to load the vocabulary into the database. 

### Drupal Module Development

I wrote a Drupal module to print the ingredients and tweets in a various forms. 
The modules emitted blocks that generated JavaScript.

I used [Flot](http://www.flotcharts.org/) for the simple pie charts.

As I write this, the Drupal modules are not on Github, so if you're curious
about those, let me know. 
