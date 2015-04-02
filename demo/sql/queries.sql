CREATE TABLE `resque_test_1` (
  `id` int(10) unsigned NOT NULL auto_increment COMMENT 'Id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB COMMENT='Test Table 1';

ALTER TABLE `resque_test_1`
  ADD COLUMN `test` smallint(5) unsigned NULL COMMENT 'Test';

CREATE TABLE `resque_test_2` (
  `id` int(10) unsigned NOT NULL auto_increment COMMENT 'Id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB COMMENT='Test Table 2';

DROP TABLE `resque_test_1`;

DROP TABLE `resque_test_2`;
