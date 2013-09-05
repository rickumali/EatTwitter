-- 
-- This small script lists the last ten tweets that were stored in the
-- system.
--
-- $ mysql -p eattwitter < last-ten.sql
--
select tweet_id,created_at from tweets order by tweet_id desc limit 10;
