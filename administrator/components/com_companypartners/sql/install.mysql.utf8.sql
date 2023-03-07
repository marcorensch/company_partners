CREATE TABLE IF NOT EXISTS `#__companypartners_partners`
(
    `id`           int(11)                                                NOT NULL AUTO_INCREMENT,
    `alias`        varchar(400) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT '',
    `title`        varchar(255)                                           NOT NULL DEFAULT '',
    `access`       int(10) unsigned                                       NOT NULL DEFAULT 0,
    `catid`        int(11)                                                NOT NULL DEFAULT 0,
    `state`        tinyint(3)                                             NOT NULL DEFAULT 0,
    `published`    tinyint(1)                                             NOT NULL DEFAULT 0,
    `publish_up`   datetime,
    `publish_down` datetime,
    `language`     char(7)                                                NOT NULL DEFAULT '',
    `groups`       varchar(255)                                           NOT NULL DEFAULT '',
    `ordering`     INT(11)                                                NOT NULL DEFAULT 0,
    `params`       text                                                   NOT NULL,
    `web`          varchar(100)                                           NOT NULL DEFAULT '',
    `email`        varchar(100)                                           NOT NULL DEFAULT '',
    `phone`        varchar(50)                                            NOT NULL DEFAULT '',
    `logo`         varchar(255)                                           NOT NULL DEFAULT '',
    `image`        varchar(255)                                           NOT NULL DEFAULT '',

    PRIMARY KEY (`id`),
    KEY `idx_access` (`access`),
    KEY `idx_catid` (`catid`),
    KEY `idx_state` (`published`),
    KEY `idx_language` (`language`)

) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  DEFAULT COLLATE = utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `#__companypartners_groups`
(
    `id`           int(11)                                                NOT NULL AUTO_INCREMENT,
    `title`        varchar(255)                                           NOT NULL,
    `alias`        varchar(400) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT '',
    `state`        tinyint(1)                                             NOT NULL DEFAULT 0,
    `access`       int(10) unsigned                                       NOT NULL DEFAULT 0,
    `language`     char(7)                                                NOT NULL DEFAULT '',
    `description`  mediumtext                                             NOT NULL,
    `published`    tinyint(1)                                             NOT NULL DEFAULT 0,
    `ordering`     int(11)                                                NOT NULL DEFAULT 0,
    `params`       text                                                   NOT NULL,
    `publish_up`   datetime,
    `publish_down` datetime,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  DEFAULT COLLATE = utf8mb4_unicode_ci;

/* groups Support for partners implemented normalized */
CREATE TABLE IF NOT EXISTS `#__companypartners_partner_group`
(
    `id`         int(11) NOT NULL AUTO_INCREMENT,
    `partner_id` int(11) NOT NULL,
    `group_id`   int(11) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  DEFAULT COLLATE = utf8mb4_unicode_ci;
