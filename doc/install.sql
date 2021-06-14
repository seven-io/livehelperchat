CREATE TABLE `lhc_sms77_chat`
(
    `chat_id`   bigint(20) unsigned NOT NULL,
    `ctime`     int(11)             NOT NULL,
    `id`        bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `phone`     varchar(35)         NOT NULL,
    `tphone_id` int(11)             NOT NULL,
    `utime`     int(11)             NOT NULL,
    PRIMARY KEY (`id`),
    KEY `phone_utime` (`phone`, `utime`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

CREATE TABLE `lhc_sms77_phone`
(
    `api_key`           varchar(90),
    `base_phone`        varchar(35)         NOT NULL,
    `chat_timeout`      int(11) unsigned    NOT NULL,
    `dep_id`            int(11) unsigned    NOT NULL,
    `from`              varchar(35)         NOT NULL,
    `id`                bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `phone`             varchar(35)         NOT NULL,
    `responder_timeout` int(11) unsigned    NOT NULL,
    PRIMARY KEY (`id`),
    KEY `phone` (`phone`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8;
