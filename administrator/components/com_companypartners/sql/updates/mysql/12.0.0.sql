ALTER TABLE `#__companypartners_partners` ADD COLUMN  `catid` int(11) NOT NULL DEFAULT 0 AFTER `alias`;

ALTER TABLE `#__companypartners_partners` ADD KEY `idx_catid` (`catid`);
