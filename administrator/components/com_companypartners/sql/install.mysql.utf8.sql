CREATE TABLE IF NOT EXISTS `#__companypartners_partners`
(
    `id`            int(11)                                                NOT NULL AUTO_INCREMENT,
    `alias`         varchar(400) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT '',
    `title`         varchar(255)                                           NOT NULL DEFAULT '',
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  DEFAULT COLLATE = utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `#__companypartners_groups`
(
    `id`            int(11)                   NOT NULL AUTO_INCREMENT,
    `title`         varchar(255)              NOT NULL,
    `alias`         varchar(400) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT '',
    `description`   mediumtext                NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  DEFAULT COLLATE = utf8mb4_unicode_ci;

/* Groups Support for Partners implemented normalized */
CREATE TABLE IF NOT EXISTS `#__companypartners_partner_group`
(
    `id`            int(11)                   NOT NULL AUTO_INCREMENT,
    `partner_id`    int(11)                   NOT NULL,
    `group_id`      int(11)                   NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  DEFAULT COLLATE = utf8mb4_unicode_ci;

ALTER TABLE `#__companypartners_partners` ADD COLUMN  `access` int(10) unsigned NOT NULL DEFAULT 0 AFTER `alias`;
ALTER TABLE `#__companypartners_partners` ADD KEY `idx_access` (`access`);
ALTER TABLE `#__companypartners_partners` ADD COLUMN  `catid` int(11) NOT NULL DEFAULT 0 AFTER `alias`;
ALTER TABLE `#__companypartners_partners` ADD COLUMN  `state` tinyint(3) NOT NULL DEFAULT 0 AFTER `alias`;
ALTER TABLE `#__companypartners_partners` ADD KEY `idx_catid` (`catid`);
ALTER TABLE `#__companypartners_partners` ADD COLUMN  `published` tinyint(1) NOT NULL DEFAULT 0 AFTER `alias`;
ALTER TABLE `#__companypartners_partners` ADD COLUMN  `publish_up` datetime AFTER `alias`;
ALTER TABLE `#__companypartners_partners` ADD COLUMN  `publish_down` datetime AFTER `alias`;
ALTER TABLE `#__companypartners_partners` ADD KEY `idx_state` (`published`);
ALTER TABLE `#__companypartners_partners` ADD COLUMN `language` char(7) NOT NULL DEFAULT '' AFTER `alias`;
ALTER TABLE `#__companypartners_partners` ADD KEY `idx_language` (`language`);
ALTER TABLE `#__companypartners_partners` ADD COLUMN `groups` varchar(255) NOT NULL DEFAULT '' AFTER `title`;
ALTER TABLE `#__companypartners_partners` ADD COLUMN `ordering` INT( 11 ) NOT NULL DEFAULT 0 AFTER `alias`;
