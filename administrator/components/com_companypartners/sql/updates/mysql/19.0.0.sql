ALTER TABLE `#__companypartners_partners` ADD COLUMN  `web` varchar(100) NOT NULL DEFAULT '' AFTER `alias`;
ALTER TABLE `#__companypartners_partners` ADD COLUMN  `email` varchar(100) NOT NULL DEFAULT '' AFTER `web`;
ALTER TABLE `#__companypartners_partners` ADD COLUMN  `phone` varchar(50) NOT NULL DEFAULT '' AFTER `email`;