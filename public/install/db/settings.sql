-- -------------------------------
-- ---- SETINGS STRUCTURE --------
-- -------------------------------

-- insert data
-- INSERT INTO `users` (`id`, `username`, `slug`, `password`, `first_name`, `last_name`, `about_me`, `email`, `role`, `image_id`, `created_at`, `updated_at`) VALUES (1, 'M4', 'm4', '$2y$10$ZdHYo1dv/RXS.Zj68oF3Je4IDC6O.oUwyOaLlO8uish9pXjCwPlbG', 'Matej', 'Vrzala', 'developer', 'vrzala.matej@gmail.com', '4', NULL, '2017-02-11 09:00:21', '2017-02-11 09:00:21');
INSERT INTO `settings` (`name`) VALUES ('title');
INSERT INTO `settings` (`name`) VALUES ('description');
INSERT INTO `settings` (`name`) VALUES ('tags');
INSERT INTO `settings` (`name`) VALUES ('online');
INSERT INTO `settings` (`name`) VALUES ('navigation');

