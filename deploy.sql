-- kdos: bare in mind to use strict mode when altering data
ALTER TABLE `deployer_test` 
CHANGE COLUMN dummy totaldummy varchar(40) NOT NULL;
