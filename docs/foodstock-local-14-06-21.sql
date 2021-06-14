/*
SQLyog Ultimate v13.1.1 (64 bit)
MySQL - 8.0.21 : Database - foodstock
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`foodstock` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;

USE `foodstock`;

/*Table structure for table `addresses` */

DROP TABLE IF EXISTS `addresses`;

CREATE TABLE `addresses` (
  `id` int NOT NULL AUTO_INCREMENT,
  `merchant_id` int NOT NULL,
  `formattedAddress` varchar(255) DEFAULT NULL,
  `country` char(2) DEFAULT NULL,
  `state` char(2) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `neighborhood` varchar(255) DEFAULT NULL,
  `streetName` varchar(255) DEFAULT NULL,
  `streetNumber` varchar(255) DEFAULT NULL,
  `postalCode` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_addresses_merchants1_idx` (`merchant_id`),
  CONSTRAINT `fk_addresses_merchants1` FOREIGN KEY (`merchant_id`) REFERENCES `merchants` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=111531 DEFAULT CHARSET=utf8;

/*Table structure for table `brokers` */

DROP TABLE IF EXISTS `brokers`;

CREATE TABLE `brokers` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `logo` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `endpoint` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `authenticationApi` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `merchantApi` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `merchantsApi` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `usercodeApi` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `enabled` tinyint NOT NULL DEFAULT '0',
  `guidelines` text COLLATE utf8mb4_general_ci,
  `access_token` text COLLATE utf8mb4_general_ci,
  `expires` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `client_centralized_id` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'IFOOD',
  `client_centralized_secret` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'IFOOD',
  `client_distributed_id` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'IFOOD',
  `client_distributed_secret` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'IFOOD',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Table structure for table `companies` */

DROP TABLE IF EXISTS `companies`;

CREATE TABLE `companies` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `logo` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Table structure for table `configs` */

DROP TABLE IF EXISTS `configs`;

CREATE TABLE `configs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `key` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  `decription` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ix_keyconfig` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Table structure for table `customers` */

DROP TABLE IF EXISTS `customers`;

CREATE TABLE `customers` (
  `id` int NOT NULL AUTO_INCREMENT,
  `order_id` int NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `ordersCountOnRestaurant` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_customers_orders1_idx` (`order_id`),
  KEY `ix_compras` (`ordersCountOnRestaurant`),
  CONSTRAINT `fk_customers_orders1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=111541 DEFAULT CHARSET=utf8;

/*Table structure for table `deliveryaddresses` */

DROP TABLE IF EXISTS `deliveryaddresses`;

CREATE TABLE `deliveryaddresses` (
  `id` int NOT NULL AUTO_INCREMENT,
  `order_id` int NOT NULL,
  `formattedAddress` varchar(255) DEFAULT NULL,
  `country` char(2) DEFAULT NULL,
  `state` char(2) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `neighborhood` varchar(255) DEFAULT NULL,
  `streetName` varchar(255) DEFAULT NULL,
  `streetNumber` varchar(255) DEFAULT NULL,
  `postalCode` int DEFAULT NULL,
  `complement` varchar(255) DEFAULT NULL,
  `latitude` double DEFAULT NULL,
  `longitude` double DEFAULT NULL,
  `endereco` text,
  PRIMARY KEY (`id`),
  KEY `fk_deliveryaddresses_orders1_idx` (`order_id`),
  KEY `ix_cep` (`postalCode`),
  FULLTEXT KEY `ix_endereco` (`endereco`),
  CONSTRAINT `fk_deliveryaddresses_orders1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=83920 DEFAULT CHARSET=utf8;

/*Table structure for table `departments` */

DROP TABLE IF EXISTS `departments`;

CREATE TABLE `departments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `empresa_id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `color` varchar(255) DEFAULT NULL,
  `label` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_departments_empresas1_idx` (`empresa_id`),
  CONSTRAINT `fk_departments_empresas1` FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

/*Table structure for table `empresas` */

DROP TABLE IF EXISTS `empresas`;

CREATE TABLE `empresas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `enterprise` */

DROP TABLE IF EXISTS `enterprise`;

CREATE TABLE `enterprise` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `eventos` */

DROP TABLE IF EXISTS `eventos`;

CREATE TABLE `eventos` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `descricao` varchar(255) NOT NULL,
  `data_inicio` timestamp NOT NULL,
  `data_fim` timestamp NOT NULL,
  `link` varchar(500) DEFAULT NULL,
  `imagem` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_active` tinyint NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8;

/*Table structure for table `events` */

DROP TABLE IF EXISTS `events`;

CREATE TABLE `events` (
  `id` int NOT NULL AUTO_INCREMENT,
  `order_id` int NOT NULL,
  `idEvent` varchar(255) DEFAULT NULL,
  `code` varchar(255) DEFAULT NULL,
  `correlationId` bigint DEFAULT NULL,
  `createdAt` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_events_orders1_idx` (`order_id`),
  CONSTRAINT `fk_events_orders1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=169781 DEFAULT CHARSET=utf8;

/*Table structure for table `failed_jobs` */

DROP TABLE IF EXISTS `failed_jobs`;

CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `ifood_brokers` */

DROP TABLE IF EXISTS `ifood_brokers`;

CREATE TABLE `ifood_brokers` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `restaurant_id` bigint NOT NULL,
  `broker_id` bigint NOT NULL,
  `merchant_id` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `corporateName` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `validated_at` timestamp NULL DEFAULT NULL,
  `validated` tinyint NOT NULL DEFAULT '0',
  `enabled` tinyint NOT NULL DEFAULT '1',
  `acknowledgment` tinyint NOT NULL DEFAULT '1',
  `merchant_json` json DEFAULT NULL,
  `userCode` char(9) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `authorizationCode` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `authorizationCodeVerifier` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `verificationUrlComplete` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `usercode_expires` timestamp NULL DEFAULT NULL,
  `accessToken` varchar(1000) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `refreshToken` varchar(500) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `expiresIn` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_rappi_brokers_brokers1_idx` (`broker_id`),
  KEY `fk_rappi_brokers_restaurants1_idx` (`restaurant_id`),
  KEY `ix_merchant_id` (`merchant_id`),
  KEY `ix_user_code` (`userCode`),
  CONSTRAINT `fk_rappi_brokers_brokers10` FOREIGN KEY (`broker_id`) REFERENCES `brokers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_rappi_brokers_restaurants10` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Table structure for table `invites` */

DROP TABLE IF EXISTS `invites`;

CREATE TABLE `invites` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `code` char(6) COLLATE utf8mb4_general_ci NOT NULL,
  `used` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Table structure for table `items` */

DROP TABLE IF EXISTS `items`;

CREATE TABLE `items` (
  `id` int NOT NULL AUTO_INCREMENT,
  `order_id` int NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `quantity` int DEFAULT NULL,
  `price` float(10,2) DEFAULT NULL,
  `subItemsPrice` float(10,2) DEFAULT NULL,
  `totalPrice` float(10,2) DEFAULT NULL,
  `discount` float(10,2) DEFAULT NULL,
  `addition` float(10,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_items_orders1_idx` (`order_id`),
  KEY `ix_nameitem` (`name`),
  CONSTRAINT `fk_items_orders1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=138802 DEFAULT CHARSET=utf8;

/*Table structure for table `merchants` */

DROP TABLE IF EXISTS `merchants`;

CREATE TABLE `merchants` (
  `id` int NOT NULL AUTO_INCREMENT,
  `order_id` int NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_merchants_orders1_idx` (`order_id`),
  CONSTRAINT `fk_merchants_orders1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=111552 DEFAULT CHARSET=utf8;

/*Table structure for table `migrations` */

DROP TABLE IF EXISTS `migrations`;

CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `model_has_permissions` */

DROP TABLE IF EXISTS `model_has_permissions`;

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `model_has_roles` */

DROP TABLE IF EXISTS `model_has_roles`;

CREATE TABLE `model_has_roles` (
  `role_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `order_summaries` */

DROP TABLE IF EXISTS `order_summaries`;

CREATE TABLE `order_summaries` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `order` json NOT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `order_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_order_summaries_orders1_idx` (`order_id`),
  CONSTRAINT `fk_order_summaries_orders1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Table structure for table `orders` */

DROP TABLE IF EXISTS `orders`;

CREATE TABLE `orders` (
  `id` int NOT NULL AUTO_INCREMENT,
  `company_id` int NOT NULL,
  `idIfood` char(32) DEFAULT NULL,
  `reference` varchar(255) DEFAULT NULL,
  `shortReference` int DEFAULT NULL,
  `createdAt` timestamp NULL DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `subTotal` float(10,2) DEFAULT NULL,
  `totalPrice` float(10,2) DEFAULT NULL,
  `deliveryFee` float(10,2) DEFAULT NULL,
  `deliveryDateTime` timestamp NULL DEFAULT NULL,
  `preparationTimeInSeconds` int DEFAULT NULL,
  `localizer` int DEFAULT NULL,
  `json` text,
  `rawJson` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_idifood` (`idIfood`),
  KEY `ix_dateorder` (`createdAt`),
  KEY `ix_reference` (`reference`),
  KEY `fk_orders_company1_idx` (`company_id`),
  CONSTRAINT `fk_orders_company1` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=113669 DEFAULT CHARSET=utf8;

/*Table structure for table `password_resets` */

DROP TABLE IF EXISTS `password_resets`;

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `payments` */

DROP TABLE IF EXISTS `payments`;

CREATE TABLE `payments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `payment_id` int NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `code` varchar(255) DEFAULT NULL,
  `value` float(10,2) DEFAULT NULL,
  `prepaid` tinyint DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_payments_orders1_idx` (`payment_id`),
  CONSTRAINT `fk_payments_orders1` FOREIGN KEY (`payment_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=126820 DEFAULT CHARSET=utf8;

/*Table structure for table `permissions` */

DROP TABLE IF EXISTS `permissions`;

CREATE TABLE `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `personal_access_tokens` */

DROP TABLE IF EXISTS `personal_access_tokens`;

CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `production_line_versions` */

DROP TABLE IF EXISTS `production_line_versions`;

CREATE TABLE `production_line_versions` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `restaurant_id` bigint NOT NULL,
  `version` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `is_active` tinyint NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_production_line_versions_restaurants1_idx` (`restaurant_id`),
  CONSTRAINT `fk_production_line_versions_restaurants1` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Table structure for table `production_lines` */

DROP TABLE IF EXISTS `production_lines`;

CREATE TABLE `production_lines` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `role_id` bigint unsigned NOT NULL,
  `production_line_id` bigint DEFAULT NULL COMMENT 'Indica se o passo é sub-passo de outro (mantém mesma tela).',
  `restaurant_id` bigint NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `step` int NOT NULL,
  `clickable` tinyint NOT NULL DEFAULT '1' COMMENT 'Pode-se ver detalhes do pedido e passar para próximo passo.',
  `see_previous` tinyint NOT NULL DEFAULT '0' COMMENT 'Vê itens do passo anterior no painel do passo corrente.',
  `next_on_click` tinyint NOT NULL DEFAULT '0' COMMENT '- Ao clicar no item, passa para o próximo passo automaticamente.\n- Se o próximo passo é filho do passo atual, muda de cor (passo), mantém o mesmo painel e mostra detalhes do pedido.\n- Se o próximo passo é independente, passa pedido para próximo passo sem painel.',
  `can_pause` tinyint NOT NULL DEFAULT '0' COMMENT 'Pode pausar o processo.',
  `color` char(7) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `version` int DEFAULT NULL,
  `is_active` tinyint DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `production_line_version_id` bigint NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_production_lines_production_lines1_idx` (`production_line_id`),
  KEY `fk_production_lines_restaurants1_idx` (`restaurant_id`),
  KEY `fk_production_lines_roles1_idx` (`role_id`),
  KEY `ix_restaurant_isactive_step` (`restaurant_id`,`step`,`is_active`),
  KEY `fk_production_lines_productio_line_versions1_idx` (`production_line_version_id`),
  CONSTRAINT `fk_production_lines_productio_line_versions1` FOREIGN KEY (`production_line_version_id`) REFERENCES `production_line_versions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_production_lines_production_lines1` FOREIGN KEY (`production_line_id`) REFERENCES `production_lines` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_production_lines_restaurants1` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_production_lines_roles1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=385 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Table structure for table `production_movements` */

DROP TABLE IF EXISTS `production_movements`;

CREATE TABLE `production_movements` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `production_line_id` bigint NOT NULL,
  `current_step_number` int NOT NULL,
  `restaurants_id` bigint NOT NULL,
  `order_summary_id` bigint NOT NULL,
  `orders_id` int NOT NULL,
  `next_step_id` bigint NOT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `dispached_at` timestamp NULL DEFAULT NULL,
  `production_line_versions_id` bigint NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_production_moviments_production_lines1_idx` (`production_line_id`),
  KEY `fk_production_movements_restaurants1_idx` (`restaurants_id`),
  KEY `fk_production_movements_orders1_idx` (`orders_id`),
  KEY `fk_production_movements_production_lines1_idx` (`next_step_id`),
  KEY `fk_production_movements_order_summaries1_idx` (`order_summary_id`),
  KEY `fk_production_movements_production_line_versions1_idx` (`production_line_versions_id`),
  CONSTRAINT `fk_production_movements_order_summaries1` FOREIGN KEY (`order_summary_id`) REFERENCES `order_summaries` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_production_movements_orders1` FOREIGN KEY (`orders_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_production_movements_production_line_versions1` FOREIGN KEY (`production_line_versions_id`) REFERENCES `production_line_versions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_production_movements_production_lines1` FOREIGN KEY (`next_step_id`) REFERENCES `production_lines` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_production_movements_restaurants1` FOREIGN KEY (`restaurants_id`) REFERENCES `restaurants` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_production_moviments_production_lines1` FOREIGN KEY (`production_line_id`) REFERENCES `production_lines` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Table structure for table `productionlines` */

DROP TABLE IF EXISTS `productionlines`;

CREATE TABLE `productionlines` (
  `id` int NOT NULL AUTO_INCREMENT,
  `status_id` int DEFAULT NULL COMMENT 'Indica em qual status esta linha de produção inicia',
  `empresa_id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_productionlines_empresas1_idx` (`empresa_id`),
  KEY `fk_productionlines_statuses1_idx` (`status_id`),
  CONSTRAINT `fk_productionlines_empresas1` FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_productionlines_statuses1` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*Table structure for table `productiontime` */

DROP TABLE IF EXISTS `productiontime`;

CREATE TABLE `productiontime` (
  `day` date NOT NULL,
  `totalTime` int NOT NULL,
  `initialStatus` int NOT NULL,
  `finalStatus` int NOT NULL,
  PRIMARY KEY (`day`),
  KEY `ix_day_if_status` (`day`,`initialStatus`,`finalStatus`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `productitems` */

DROP TABLE IF EXISTS `productitems`;

CREATE TABLE `productitems` (
  `id` int NOT NULL AUTO_INCREMENT,
  `product_id` int NOT NULL,
  `item_id` int DEFAULT NULL,
  `subitem_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status_id` int DEFAULT NULL COMMENT 'Indica quem qual status do processo esse produto jogou o pedido.',
  PRIMARY KEY (`id`),
  UNIQUE KEY `iq_product_item` (`item_id`,`product_id`),
  KEY `fk_productitems_items1_idx` (`item_id`),
  KEY `fk_productitems_product1_idx` (`product_id`),
  KEY `fk_productitems_statuses1_idx` (`status_id`),
  KEY `fk_productitems_subitems1_idx` (`subitem_id`),
  CONSTRAINT `fk_productitems_items1` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_productitems_product1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_productitems_statuses1` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_productitems_subitems1` FOREIGN KEY (`subitem_id`) REFERENCES `subitems` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=311803 DEFAULT CHARSET=utf8;

/*Table structure for table `products` */

DROP TABLE IF EXISTS `products`;

CREATE TABLE `products` (
  `id` int NOT NULL AUTO_INCREMENT,
  `productionline_id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `alternateName` varchar(255) DEFAULT NULL,
  `observations` text,
  `minimunStock` int NOT NULL DEFAULT '0',
  `currentStock` int NOT NULL DEFAULT '0',
  `monitorStock` tinyint NOT NULL DEFAULT '1',
  `parent_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_product_name` (`name`),
  KEY `fk_production_productionlines1_idx` (`productionline_id`),
  KEY `fk_products_products1_idx` (`parent_id`),
  KEY `ix_nameproduct` (`name`),
  KEY `ix_monitorstock` (`monitorStock`),
  CONSTRAINT `fk_production_productionlines1` FOREIGN KEY (`productionline_id`) REFERENCES `productionlines` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_products_products1` FOREIGN KEY (`parent_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=858 DEFAULT CHARSET=utf8;

/*Table structure for table `rappi_brokers` */

DROP TABLE IF EXISTS `rappi_brokers`;

CREATE TABLE `rappi_brokers` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `restaurant_id` bigint NOT NULL,
  `broker_id` bigint NOT NULL,
  `client_id` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `client_secret` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `validated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `validated` tinyint NOT NULL DEFAULT '0',
  `enabled` tinyint NOT NULL DEFAULT '1',
  `acknowledgment` tinyint NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `fk_rappi_brokers_brokers1_idx` (`broker_id`),
  KEY `fk_rappi_brokers_restaurants1_idx` (`restaurant_id`),
  CONSTRAINT `fk_rappi_brokers_brokers1` FOREIGN KEY (`broker_id`) REFERENCES `brokers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_rappi_brokers_restaurants1` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Table structure for table `restaurants` */

DROP TABLE IF EXISTS `restaurants`;

CREATE TABLE `restaurants` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `complement` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `cep` char(12) COLLATE utf8mb4_general_ci NOT NULL,
  `cnpj` varchar(15) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `site` varchar(500) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_restaurants_users1_idx` (`user_id`),
  CONSTRAINT `fk_restaurants_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Table structure for table `role_has_permissions` */

DROP TABLE IF EXISTS `role_has_permissions`;

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `roles` */

DROP TABLE IF EXISTS `roles`;

CREATE TABLE `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `sessions` */

DROP TABLE IF EXISTS `sessions`;

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `statuses` */

DROP TABLE IF EXISTS `statuses`;

CREATE TABLE `statuses` (
  `id` int NOT NULL AUTO_INCREMENT,
  `department_id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `colour` varchar(255) NOT NULL,
  `description` text,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `status_id` int DEFAULT NULL,
  `change_status_open` tinyint DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fk_statuses_departments1_idx` (`department_id`),
  KEY `ix_name` (`name`),
  KEY `fk_statuses_statuses1_idx` (`status_id`),
  CONSTRAINT `fk_statuses_departments1` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_statuses_statuses1` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

/*Table structure for table `statusesorders` */

DROP TABLE IF EXISTS `statusesorders`;

CREATE TABLE `statusesorders` (
  `id` int NOT NULL AUTO_INCREMENT,
  `status_id` int NOT NULL,
  `order_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `skipped_proccess` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_order_status` (`status_id`,`order_id`),
  KEY `fk_statusesorders_orders1_idx` (`order_id`),
  KEY `fk_statusesorders_statuses1_idx` (`status_id`),
  CONSTRAINT `fk_statusesorders_orders1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_statusesorders_statuses1` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=432998 DEFAULT CHARSET=utf8;

/*Table structure for table `subitems` */

DROP TABLE IF EXISTS `subitems`;

CREATE TABLE `subitems` (
  `id` int NOT NULL AUTO_INCREMENT,
  `item_id` int NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `quantity` int DEFAULT NULL,
  `price` float(10,2) DEFAULT NULL,
  `totalPrice` float(10,2) DEFAULT NULL,
  `discount` float(10,2) DEFAULT NULL,
  `addition` float(10,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_subitems_items1_idx` (`item_id`),
  KEY `ix_namesubitem` (`name`),
  CONSTRAINT `fk_subitems_items1` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=217178 DEFAULT CHARSET=utf8;

/*Table structure for table `team_invitations` */

DROP TABLE IF EXISTS `team_invitations`;

CREATE TABLE `team_invitations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `team_id` bigint unsigned NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `team_invitations_team_id_email_unique` (`team_id`,`email`),
  CONSTRAINT `team_invitations_team_id_foreign` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `team_user` */

DROP TABLE IF EXISTS `team_user`;

CREATE TABLE `team_user` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `team_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `team_user_team_id_user_id_unique` (`team_id`,`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `teams` */

DROP TABLE IF EXISTS `teams`;

CREATE TABLE `teams` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `personal_team` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `teams_user_id_index` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `two_factor_secret` text COLLATE utf8mb4_unicode_ci,
  `two_factor_recovery_codes` text COLLATE utf8mb4_unicode_ci,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `current_team_id` bigint unsigned DEFAULT NULL,
  `profile_photo_path` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `vcustomers` */

DROP TABLE IF EXISTS `vcustomers`;

/*!50001 DROP VIEW IF EXISTS `vcustomers` */;
/*!50001 DROP TABLE IF EXISTS `vcustomers` */;

/*!50001 CREATE TABLE  `vcustomers`(
 `id` int ,
 `name` varchar(255) ,
 `ordersCountOnRestaurant` int 
)*/;

/*View structure for view vcustomers */

/*!50001 DROP TABLE IF EXISTS `vcustomers` */;
/*!50001 DROP VIEW IF EXISTS `vcustomers` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vcustomers` AS select `customers`.`id` AS `id`,`customers`.`name` AS `name`,`customers`.`ordersCountOnRestaurant` AS `ordersCountOnRestaurant` from `customers` */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
