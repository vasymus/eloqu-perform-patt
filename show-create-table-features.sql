CREATE TABLE `features`
(
    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
    `title` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
    `status` varchar(10) COLLATE utf8mb4_unicode_ci  NOT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `features_status_ranking_index`(
        (
            (
                case
                    #_utf8mb4 can be ignored
                    when (`status` = _utf8mb4 'Requested') then 1
                    when (`status` = _utf8mb4 'Planned') then 2
                    when (`status` = _utf8mb4 'Completed') then 3
                end
            )
        )
    )
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci
