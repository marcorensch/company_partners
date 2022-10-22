ALTER TABLE `#__companypartners_partners` ADD COLUMN  `language` char(7) NOT NULL DEFAULT '*' AFTER `alias`;
ALTER TABLE `#__companypartners_partners` ADD KEY `idx_language` (`language`);
