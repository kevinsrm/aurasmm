-- Dump do banco de dados AuraSMM
-- Data: 2026-09-29 18:53:49

-- Estrutura da tabela users
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  `balance` decimal(10,2) NOT NULL DEFAULT 0.00,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dados da tabela users
INSERT INTO users VALUES (3, "admin", "admin@eu.com", NULL, "$2y$12$GYtbgGCky1S/v87nBTnQWOlPAw5MMo8Xc/ETSueSFVkvKwJjamJ.W", 1, 555.89, "NLwMIlawaqL3IMjQzCDLUPwfYFmaV6FTuzA5rRIsTOeSjTqVVpcu51dYnwQZ", "2026-09-27 19:49:40", "2026-09-29 18:12:32");

-- Estrutura da tabela orders
CREATE TABLE `orders` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `api_order_id` bigint(20) unsigned DEFAULT NULL,
  `service_id` bigint(20) unsigned NOT NULL,
  `service_name` varchar(255) DEFAULT NULL,
  `link` text NOT NULL,
  `quantity` int(11) NOT NULL,
  `charge` decimal(10,4) NOT NULL DEFAULT 0.0000,
  `status` varchar(255) NOT NULL DEFAULT 'Pending',
  `refund_status` varchar(255) NOT NULL DEFAULT 'none',
  `refund_reason` varchar(255) DEFAULT NULL,
  `refunded_at` timestamp NULL DEFAULT NULL,
  `refunded_by` bigint(20) unsigned DEFAULT NULL,
  `comments` text DEFAULT NULL,
  `answer_number` varchar(255) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `runs` int(11) DEFAULT NULL,
  `interval` int(11) DEFAULT NULL,
  `api_response` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dados da tabela orders
INSERT INTO orders VALUES (1, 3, 2147453606, 192, "🌍 Instagram - Seguidores Mundiais 🚀 🏅 🔓 SR [ALTA QUEDA]", "https://www.instagram.com/kev_inooi/", 10, 0.0480, "Completed", "none", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, "{\"order\":2147453606}", "2026-09-29 12:12:05", "2026-09-29 15:12:33");
INSERT INTO orders VALUES (2, 3, 2147453652, 107, "🇧🇷 Instagram - Curtidas Brasileiras 🚀 🏅 🔓 [RÁPIDO]", "https://www.instagram.com/kev_inooi/", 10, 0.0250, "Canceled", "none", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, "{\"order\":2147453652}", "2026-09-29 13:05:49", "2026-09-29 17:29:26");
INSERT INTO orders VALUES (3, 3, 2147453731, 108, "🌍 Instagram - Curtidas Mundiais 🚀 🏅 🔓 [LIQUIDAÇÃO]", "https://www.instagram.com/p/Dd2Cqdvn2fG/", 1, 0.0005, "In progress", "none", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, "{\"order\":2147453731}", "2026-09-29 14:36:24", "2026-09-29 15:12:33");
INSERT INTO orders VALUES (4, 3, 2147453743, 895, "TikTok Curtidas Mundiais | 80K/dia 🚀 🎖️ R30", "https://vt.tiktok.com/ZSbBFXDcc/", 50, 0.0515, "In progress", "none", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, "{\"order\":2147453743}", "2026-09-29 14:49:36", "2026-09-29 15:12:33");

-- Estrutura da tabela deposits
CREATE TABLE `deposits` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `payment_id` varchar(255) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `qr_code` text DEFAULT NULL,
  `qr_code_base64` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `deposits_user_id_foreign` (`user_id`),
  CONSTRAINT `deposits_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dados da tabela deposits
INSERT INTO deposits VALUES (10, 3, 1352680621, 50.00, "cancelled", "00020126580014br.gov.bcb.pix0136b76aa9c2-2ec4-4110-954e-ebfe34f05b61520400005303986540550.005802BR5925ST975940328606JfVGvShDoS36006OsrQco62230519mpqrinter13526806216304F697", "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", "2026-09-27 20:27:26", "2026-09-28 17:21:59");
INSERT INTO deposits VALUES (12, 3, 1328292604, 20.00, "cancelled", "00020126580014br.gov.bcb.pix0136b76aa9c2-2ec4-4110-954e-ebfe34f05b61520400005303986540520.005802BR5925ST975940328606JfVGvShDoS36006OsrQco62230519mpqrinter132829260463047963", "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", "2026-09-28 17:29:18", "2026-09-28 17:39:18");
INSERT INTO deposits VALUES (13, 3, 1328304464, 20.00, "cancelled", "00020126580014br.gov.bcb.pix0136b76aa9c2-2ec4-4110-954e-ebfe34f05b61520400005303986540520.005802BR5925ST975940328606JfVGvShDoS36006OsrQco62230519mpqrinter13283044646304B933", "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", "2026-09-29 17:43:12", "2026-09-29 18:06:26");
INSERT INTO deposits VALUES (14, 3, 1352822007, 20.00, "pending", "00020126580014br.gov.bcb.pix0136b76aa9c2-2ec4-4110-954e-ebfe34f05b61520400005303986540520.005802BR5925ST975940328606JfVGvShDoS36006OsrQco62230519mpqrinter13528220076304AC98", "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", "2026-09-29 18:09:28", "2026-09-29 18:09:28");
INSERT INTO deposits VALUES (15, 3, 1328304828, 250.00, "pending", "00020126580014br.gov.bcb.pix0136b76aa9c2-2ec4-4110-954e-ebfe34f05b615204000053039865406250.005802BR5925ST975940328606JfVGvShDoS36006OsrQco62230519mpqrinter13283048286304945E", "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", "2026-09-29 18:10:47", "2026-09-29 18:10:47");

-- Estrutura da tabela coupons
CREATE TABLE `coupons` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `max_uses` int(11) NOT NULL DEFAULT 1,
  `used_count` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `coupons_code_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dados da tabela coupons
INSERT INTO coupons VALUES (5, "PROMO2", 2.00, 3, 2, 1, "2026-09-27 16:12:58", "2026-09-28 18:00:49");
INSERT INTO coupons VALUES (6, "PROMO4", 4.00, 1, 1, 1, "2026-09-29 17:32:25", "2026-09-29 17:36:45");
INSERT INTO coupons VALUES (7, "PROMO50", 50.00, 100, 1, 1, "2026-09-29 18:12:14", "2026-09-29 18:12:32");

-- Estrutura da tabela coupon_user
CREATE TABLE `coupon_user` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `coupon_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `coupon_user_coupon_id_foreign` (`coupon_id`),
  KEY `coupon_user_user_id_foreign` (`user_id`),
  CONSTRAINT `coupon_user_coupon_id_foreign` FOREIGN KEY (`coupon_id`) REFERENCES `coupons` (`id`) ON DELETE CASCADE,
  CONSTRAINT `coupon_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dados da tabela coupon_user
INSERT INTO coupon_user VALUES (3, 5, 3, "2026-09-28 18:00:49", "2026-09-28 18:00:49");
INSERT INTO coupon_user VALUES (4, 6, 3, "2026-09-29 17:36:45", "2026-09-29 17:36:45");
INSERT INTO coupon_user VALUES (5, 7, 3, "2026-09-29 18:12:32", "2026-09-29 18:12:32");

-- Estrutura da tabela settings
CREATE TABLE `settings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) NOT NULL,
  `value` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_key_unique` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dados da tabela settings
INSERT INTO settings VALUES (1, "smm_api_key", "dbe48289d6d94754380128791a3824a4628489962707031a450f9ed11ec226e6", "2026-09-25 22:07:56", "2026-09-25 22:07:56");
INSERT INTO settings VALUES (2, "mercadopago_token", "TEST-4824716538140206-092518-47cfdd9023d06990a5b15f98e4781ef6-1510101146", "2026-09-25 22:07:56", "2026-09-25 22:07:56");
INSERT INTO settings VALUES (3, "profit_margin", 100, "2026-09-27 15:37:36", "2026-09-29 18:13:07");
INSERT INTO settings VALUES (4, "ad_google_tag_id", "", "2026-09-27 20:02:05", "2026-09-27 20:02:05");
INSERT INTO settings VALUES (5, "ad_fb_pixel_id", "", "2026-09-27 20:02:05", "2026-09-27 20:02:05");
INSERT INTO settings VALUES (6, "ad_tiktok_pixel_id", "", "2026-09-27 20:02:05", "2026-09-27 20:02:05");
INSERT INTO settings VALUES (7, "ad_custom_head_script", "", "2026-09-27 20:02:05", "2026-09-27 20:02:05");
INSERT INTO settings VALUES (8, "ad_custom_body_script", "", "2026-09-27 20:02:05", "2026-09-27 20:02:05");
INSERT INTO settings VALUES (9, "seo_title", "AuraSMM - Painel SMM", "2026-09-27 20:02:05", "2026-09-27 20:02:05");
INSERT INTO settings VALUES (10, "seo_description", "Painel SMM de revenda de seguidores, curtidas e visualizações com os melhores preços.", "2026-09-27 20:02:06", "2026-09-27 20:02:06");
INSERT INTO settings VALUES (11, "seo_keywords", "painel smm, seguidores, instagram, tiktok, youtube, curtidas", "2026-09-27 20:02:06", "2026-09-27 20:02:06");
INSERT INTO settings VALUES (12, "seo_og_image", "", "2026-09-27 20:02:06", "2026-09-27 20:02:06");
INSERT INTO settings VALUES (13, "support_whatsapp", 5511999999999, "2026-09-27 20:02:06", "2026-09-29 18:06:03");

-- Estrutura da tabela notifications
CREATE TABLE `notifications` (
  `id` char(36) NOT NULL,
  `type` varchar(255) NOT NULL,
  `notifiable_type` varchar(255) NOT NULL,
  `notifiable_id` bigint(20) unsigned NOT NULL,
  `data` text NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dados da tabela notifications
INSERT INTO notifications VALUES ("00be41e9-8351-4c43-a3bd-a199efa18255", "App\\Notifications\\NewCouponNotification", "App\\Models\\User", 1, "{\"title\":\"Novo Cupom Dispon\\u00edvel!\",\"message\":\"Use o cupom PROMO2 para ganhar R$ 3,00 de saldo!\",\"coupon_id\":4}", "2026-09-27 16:12:18", "2026-09-27 16:12:13", "2026-09-27 16:12:18");
INSERT INTO notifications VALUES ("1c24a78c-a8d0-42e6-b0c5-0b1b32f6ade2", "App\\Notifications\\NewCouponNotification", "App\\Models\\User", 1, "{\"title\":\"Novo Cupom Dispon\\u00edvel!\",\"message\":\"Use o cupom PROMO2 para ganhar R$ 2,00 de saldo!\",\"coupon_id\":5}", "2026-09-27 16:13:02", "2026-09-27 16:12:58", "2026-09-27 16:13:02");
INSERT INTO notifications VALUES ("3aba3354-bd44-4c4c-a727-75e7f9666f72", "App\\Notifications\\NewCouponNotification", "App\\Models\\User", 3, "{\"title\":\"Novo Cupom Dispon\\u00edvel!\",\"message\":\"Use o cupom PROMO50 para ganhar R$ 50,00 de saldo!\",\"coupon_id\":7}", "2026-09-29 18:12:19", "2026-09-29 18:12:15", "2026-09-29 18:12:19");
INSERT INTO notifications VALUES ("3ea62677-5a0a-4291-b259-92f6903bcef7", "App\\Notifications\\NewCouponNotification", "App\\Models\\User", 2, "{\"title\":\"Novo Cupom Dispon\\u00edvel!\",\"message\":\"Use o cupom PROMO2 para ganhar R$ 3,00 de saldo!\",\"coupon_id\":4}", NULL, "2026-09-27 16:12:13", "2026-09-27 16:12:13");
INSERT INTO notifications VALUES ("4ed605ef-562e-4548-a87d-6228c256daba", "App\\Notifications\\NewCouponNotification", "App\\Models\\User", 1, "{\"title\":\"Novo Cupom Dispon\\u00edvel!\",\"message\":\"Use o cupom PROMO3 para ganhar R$ 3,00 de saldo!\",\"coupon_id\":3}", "2026-09-25 23:13:47", "2026-09-25 23:13:39", "2026-09-25 23:13:47");
INSERT INTO notifications VALUES ("5e085984-55cc-4cd7-b562-79536db7db51", "App\\Notifications\\NewCouponNotification", "App\\Models\\User", 2, "{\"title\":\"Novo Cupom Dispon\\u00edvel!\",\"message\":\"Use o cupom PROMO3 para ganhar R$ 3,00 de saldo!\",\"coupon_id\":3}", "2026-09-25 23:16:09", "2026-09-25 23:13:39", "2026-09-25 23:16:09");
INSERT INTO notifications VALUES ("7aab2cf4-f4ef-4a60-875b-b49855d2ea2d", "App\\Notifications\\NewCouponNotification", "App\\Models\\User", 1, "{\"title\":\"Novo Cupom Dispon\\u00edvel!\",\"message\":\"Use o cupom PROMO50 para ganhar R$ 50,00 de saldo!\",\"coupon_id\":7}", NULL, "2026-09-29 18:12:15", "2026-09-29 18:12:15");
INSERT INTO notifications VALUES ("91893901-38e4-4b00-bfbd-dffdbd1af169", "App\\Notifications\\NewCouponNotification", "App\\Models\\User", 2, "{\"title\":\"Novo Cupom Dispon\\u00edvel!\",\"message\":\"Use o cupom PROMO50 para ganhar R$ 50,00 de saldo!\",\"coupon_id\":7}", NULL, "2026-09-29 18:12:15", "2026-09-29 18:12:15");
INSERT INTO notifications VALUES ("97a20e49-de06-4bce-80ad-a8a1fcf2ad84", "App\\Notifications\\NewCouponNotification", "App\\Models\\User", 2, "{\"title\":\"Novo Cupom Dispon\\u00edvel!\",\"message\":\"Use o cupom PROMO2 para ganhar R$ 2,00 de saldo!\",\"coupon_id\":5}", NULL, "2026-09-27 16:12:58", "2026-09-27 16:12:58");
INSERT INTO notifications VALUES ("a1c3ffcc-9eca-485a-b7a4-732614393f89", "App\\Notifications\\NewCouponNotification", "App\\Models\\User", 3, "{\"title\":\"Novo Cupom Dispon\\u00edvel!\",\"message\":\"Use o cupom PROMO4 para ganhar R$ 4,00 de saldo!\",\"coupon_id\":6}", "2026-09-29 17:36:31", "2026-09-29 17:32:25", "2026-09-29 17:36:31");
INSERT INTO notifications VALUES ("cb3cb4ee-6493-4706-bf82-4d159fdd65a5", "App\\Notifications\\NewCouponNotification", "App\\Models\\User", 1, "{\"title\":\"Novo Cupom Dispon\\u00edvel!\",\"message\":\"Use o cupom PROMO4 para ganhar R$ 4,00 de saldo!\",\"coupon_id\":6}", "2026-09-29 17:32:51", "2026-09-29 17:32:25", "2026-09-29 17:32:51");
INSERT INTO notifications VALUES ("e382ccb1-2874-4c98-a018-9b340a2a5256", "App\\Notifications\\NewCouponNotification", "App\\Models\\User", 2, "{\"title\":\"Novo Cupom Dispon\\u00edvel!\",\"message\":\"Use o cupom PROMO4 para ganhar R$ 4,00 de saldo!\",\"coupon_id\":6}", NULL, "2026-09-29 17:32:25", "2026-09-29 17:32:25");

-- Estrutura da tabela jobs
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Estrutura da tabela cache
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Estrutura da tabela migrations
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dados da tabela migrations
INSERT INTO migrations VALUES (1, "0001_01_01_000000_create_users_table", 1);
INSERT INTO migrations VALUES (2, "0001_01_01_000001_create_cache_table", 1);
INSERT INTO migrations VALUES (3, "0001_01_01_000002_create_jobs_table", 1);
INSERT INTO migrations VALUES (4, "2026_09_24_182550_create_orders_table", 1);
INSERT INTO migrations VALUES (5, "2026_09_25_202339_add_fields_to_users_and_create_app_tables", 1);
INSERT INTO migrations VALUES (6, "2026_09_25_222407_create_notifications_table", 2);
INSERT INTO migrations VALUES (7, "2026_09_28_120000_add_refund_fields_to_orders_table", 3);

