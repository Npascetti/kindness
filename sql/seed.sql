/* to check what data is in the tables, run these one at a time */
select count(*) from reputation;
select count(*) from hub;
select count(*) from level;
select count(*) from user;
/*to delete values from the tables, delete in this order*/
delete from reputation;
delete from hub;
delete from level;
delete from user;

/* inserts a new level for testing.  Run these in order. */
INSERT INTO level(levelId, levelName, levelNumber) VALUES(0xBA0F48589B2A4701B517DA25CEE8ED1D, 'giver', '3');

/* updates a level for testing. */