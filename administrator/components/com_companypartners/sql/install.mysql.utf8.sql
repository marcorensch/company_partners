CREATE TABLE IF NOT EXISTS `#__companypartners_partners`
(
    `id`    int(11)                                                NOT NULL AUTO_INCREMENT,
    `alias` varchar(400) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT '',
    `name`  varchar(255)                                           NOT NULL DEFAULT '',
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  DEFAULT COLLATE = utf8mb4_unicode_ci;

INSERT INTO `#__companypartners_partners` (`name`)
VALUES ('nx-designs'),
       ('nx-productions'),
       ('Baier Lighting'),
       ('Hundephysiotherapie Baier');

ALTER TABLE `#__companypartners_partners` ADD COLUMN  `access` int(10) unsigned NOT NULL DEFAULT 0 AFTER `alias`;
ALTER TABLE `#__companypartners_partners` ADD KEY `idx_access` (`access`);