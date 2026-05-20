INSERT INTO `tbl_permissions` (`id`, `name`, `guard_name`, `display_name`, `module`, `created_at`, `updated_at`) VALUES (NULL, 'author-dashboard', 'web', 'Display Dashboard of Author', 'Author', '2021-08-12 17:36:43', '2021-08-13 10:02:34');
INSERT INTO `tbl_permissions` (`id`, `name`, `guard_name`, `display_name`, `module`, `created_at`, `updated_at`) VALUES (NULL, 'author-course-statistics', 'web', 'Display statistics of Author courses', 'Author', '2021-08-12 17:36:43', '2021-08-13 10:02:34');
INSERT INTO `tbl_role_has_permissions` (`permission_id`, `role_id`) VALUES ('32', '5');
INSERT INTO `tbl_role_has_permissions` (`permission_id`, `role_id`) VALUES ('33', '5');
