#------------------------------------------------------------
# Table: user
#------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `user` (
  `id` INTEGER PRIMARY KEY AUTO_INCREMENT,
  `name` VARCHAR(255) NULL UNIQUE,
  `email` VARCHAR(255) NOT NULL UNIQUE,
  `token` VARCHAR(255) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `role` VARCHAR(10) NOT NULL DEFAULT 'ROLE_USER'
);

#------------------------------------------------------------
# Table: category
#------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `category` (
  `id` INTEGER PRIMARY KEY AUTO_INCREMENT,
  `title` VARCHAR(30) NOT NULL UNIQUE,
  `description` TEXT NOT NULL
);


#------------------------------------------------------------
# Table: article
#------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `article` (
  `id` INTEGER PRIMARY KEY AUTO_INCREMENT,
  `title` VARCHAR(80) NOT NULL,
  `content` TEXT NOT NULL,
  `img` VARCHAR(255) NOT NULL,
  `state` BOOLEAN NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modify_at` VARCHAR(255) NULL,
  `id_user` INTEGER NOT NULL,
  `id_category` INTEGER NOT NULL,

  FOREIGN KEY(id_user) REFERENCES user(id) ON DELETE CASCADE,
  FOREIGN KEY(id_category) REFERENCES category(id) ON DELETE CASCADE
);

#------------------------------------------------------------
# Table: comment
#------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `comment`(
        `id`          INTEGER PRIMARY KEY AUTO_INCREMENT NOT NULL ,
        `username`           Varchar(100) NULL ,
        `content`           TEXT NOT NULL ,
        `state`           BOOLEAN NOT NULL DEFAULT 0,
        `created_at`    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ,
        `id_user`        INTEGER NOT NULL ,
        `id_article` INTEGER NOT NULL ,
	 FOREIGN KEY (id_user) REFERENCES user(id) ON DELETE CASCADE,
 FOREIGN KEY (id_article) REFERENCES article(id) ON DELETE CASCADE
);


INSERT INTO `user`(`email`, `password`, `role`, `token` ) VALUES('admin@admin.com', '$2y$10$BemGmJkDEA1A1yO7..r9lO.bCYexg0Ezw1G9SY/EK6SFBrp9AWr0G', 'admin', '1n7GxDFQiBSUz9jGvRp7ShulNwGRqq6');

INSERT INTO `category`(`title`, `description`) VALUES('DEV', 'dev');
INSERT INTO `category`(`title`, `description`) VALUES('DESIGN', 'design');
INSERT INTO `category`(`title`, `description`) VALUES('AUTRES', 'etc...');


INSERT INTO `article`(`title`, `content`, `img`, `state`, `id_user`, `id_category`) VALUES('article_1', 'Mon article sur le developpement web', 'http://placehold.it/150x150', '1', 1, 15);
INSERT INTO `article`(`title`, `content`, `img`, `state`, `id_user`, `id_category`) VALUES('article_2', 'Mon article sur le web design', 'http://placehold.it/150x150', '1', 1, 16);
INSERT INTO `article`(`title`, `content`, `img`, `state`, `id_user`, `id_category`) VALUES('article_3', 'Blablabla', 'http://placehold.it/150x150', '1', 1, 17);
INSERT INTO `article`(`title`, `content`, `img`, `state`, `id_user`, `id_category`) VALUES('article_4', 'Mon article sur le developpement web', 'http://placehold.it/150x150', '0', 1, 15);
INSERT INTO `article`(`title`, `content`, `img`, `state`, `id_user`, `id_category`) VALUES('article_5', 'Mon article sur le web design', 'http://placehold.it/150x150', '0', 1, 16);
INSERT INTO `article`(`title`, `content`, `img`, `state`, `id_user`, `id_category`) VALUES('article_6', 'Blablabla', 'http://placehold.it/150x150', '1', 1, 17);

INSERT INTO `comment`(`username`, `content`,`state`, `id_user`, `id_article`) VALUES('admin', 'super article', '0', 1, 31);
INSERT INTO `comment`(`username`, `content`,`state`, `id_user`, `id_article`) VALUES('admin', 'super article', '0', 1, 32);
INSERT INTO `comment`(`username`, `content`,`state`, `id_user`, `id_article`) VALUES('admin', 'super article', '0', 1, 33);
INSERT INTO `comment`(`username`, `content`,`state`, `id_user`, `id_article`) VALUES('admin', 'super article', '0', 1, 34);

INSERT INTO `comment`(`username`, `content`,`state`, `id_user`, `id_article`) VALUES('admin', 'super article', '1', 1, 31);
INSERT INTO `comment`(`username`, `content`,`state`, `id_user`, `id_article`) VALUES('admin', 'super article', '1', 1, 32);
INSERT INTO `comment`(`username`, `content`,`state`, `id_user`, `id_article`) VALUES('admin', 'super article', '1', 1, 33);
INSERT INTO `comment`(`username`, `content`,`state`, `id_user`, `id_article`) VALUES('admin', 'super article', '1', 1, 34);
