CREATE TABLE `cerberus_mapped_object` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `resource_id` VARCHAR(255) NULL,
  `resource_type` VARCHAR(255) NULL,
  `subject_id` VARCHAR(255) NULL,
  `subject_type` VARCHAR(255) NULL,
  `allowed_actions` TEXT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC));