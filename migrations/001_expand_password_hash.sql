-- Run once on existing ArgFrame databases before deploying the authentication update.
ALTER TABLE `users` MODIFY COLUMN `password` VARCHAR(255) NOT NULL;
