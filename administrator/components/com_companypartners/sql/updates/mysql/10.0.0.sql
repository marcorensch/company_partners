ALTER TABLE `#__companypartners_partners` ADD COLUMN  `access` int(10) unsigned NOT NULL DEFAULT 0 AFTER `alias`;

ALTER TABLE `#__companypartners_partners` ADD KEY `idx_access` (`access`);
