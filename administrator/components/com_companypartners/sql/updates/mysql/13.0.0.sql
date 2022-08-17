ALTER TABLE `#__companypartners_partners` ADD COLUMN  `published` tinyint(1) NOT NULL DEFAULT 0 AFTER `alias`;
ALTER TABLE `#__companypartners_partners` ADD COLUMN  `publish_up` datetime AFTER `alias`;
ALTER TABLE `#__companypartners_partners` ADD COLUMN  `publish_down` datetime AFTER `alias`;
ALTER TABLE `#__companypartners_partners` ADD KEY `idx_state` (`published`);