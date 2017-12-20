-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema d8abase
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema d8abase
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `d8abase` DEFAULT CHARACTER SET utf8 ;
USE `d8abase` ;

-- -----------------------------------------------------
-- Table `d8abase`.`class`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `d8abase`.`class` ;

CREATE TABLE IF NOT EXISTS `d8abase`.`class` (
  `idclass` INT(11) NOT NULL AUTO_INCREMENT,
  `className` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`idclass`))
ENGINE = InnoDB
AUTO_INCREMENT = 2
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `d8abase`.`user`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `d8abase`.`user` ;

CREATE TABLE IF NOT EXISTS `d8abase`.`user` (
  `iduser` INT(11) NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(45) NOT NULL,
  `email` VARCHAR(128) NOT NULL,
  `password` VARCHAR(256) NOT NULL,
  `timestamp` TIMESTAMP NOT NULL DEFAULT NOW(),
  `respect` INT(11) NOT NULL DEFAULT '0',
  `dollaz` DECIMAL(14,2) NOT NULL DEFAULT '0',
  `session_id` VARCHAR(27) NULL DEFAULT NULL,
  `token` VARCHAR(100) NOT NULL,
  `status` TINYINT(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`iduser`),
  UNIQUE INDEX `usercol_UNIQUE` (`email` ASC),
  UNIQUE INDEX `username_UNIQUE` (`username` ASC),
  UNIQUE INDEX `token_UNIQUE` (`token` ASC))
ENGINE = InnoDB
AUTO_INCREMENT = 4
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `d8abase`.`class_has_user`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `d8abase`.`class_has_user` ;

CREATE TABLE IF NOT EXISTS `d8abase`.`class_has_user` (
  `class_idclass` INT(11) NOT NULL,
  `user_iduser` INT(11) NOT NULL,
  `isClassRep` TINYINT(1) NOT NULL DEFAULT 0,
  `accepted` TINYINT(1) NULL DEFAULT 0,
  PRIMARY KEY (`class_idclass`, `user_iduser`),
  INDEX `fk_class_has_user_user1_idx` (`user_iduser` ASC),
  INDEX `fk_class_has_user_class1_idx` (`class_idclass` ASC),
  CONSTRAINT `fk_class_has_user_class1`
    FOREIGN KEY (`class_idclass`)
    REFERENCES `d8abase`.`class` (`idclass`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_class_has_user_user1`
    FOREIGN KEY (`user_iduser`)
    REFERENCES `d8abase`.`user` (`iduser`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `d8abase`.`file`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `d8abase`.`file` ;

CREATE TABLE IF NOT EXISTS `d8abase`.`file` (
  `idfile` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(200) NOT NULL,
  `type` VARCHAR(45) NOT NULL,
  `size` BIGINT(20) NOT NULL,
  `data` MEDIUMBLOB NOT NULL,
  PRIMARY KEY (`idfile`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `d8abase`.`homework`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `d8abase`.`homework` ;

CREATE TABLE IF NOT EXISTS `d8abase`.`homework` (
  `idhomework` INT(11) NOT NULL AUTO_INCREMENT,
  `class_idclass` INT(11) NOT NULL,
  `date` TIMESTAMP NOT NULL DEFAULT NOW(),
  `name` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`idhomework`),
  INDEX `fk_homework_class1_idx` (`class_idclass` ASC),
  CONSTRAINT `fk_homework_class1`
    FOREIGN KEY (`class_idclass`)
    REFERENCES `d8abase`.`class` (`idclass`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `d8abase`.`upload`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `d8abase`.`upload` ;

CREATE TABLE IF NOT EXISTS `d8abase`.`upload` (
  `idupload` INT(11) NOT NULL AUTO_INCREMENT,
  `user_iduser` INT(11) NOT NULL,
  `respect_cost` INT(11) NOT NULL DEFAULT 0,
  `dollaz_cost` DECIMAL(14,2) NOT NULL DEFAULT 0,
  `timestamp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `description` VARCHAR(200) NULL DEFAULT NULL,
  `file_idfile` INT(11) NULL DEFAULT NULL,
  `homework_idhomework` INT(11) NOT NULL,
  `respect_earned` INT(11) NOT NULL DEFAULT 0,
  `dollaz_earned` DECIMAL(14,2) NOT NULL DEFAULT 0,
  PRIMARY KEY (`idupload`),
  INDEX `fk_upload_user1_idx` (`user_iduser` ASC),
  INDEX `fk_upload_file1_idx` (`file_idfile` ASC),
  INDEX `fk_upload_homework1_idx` (`homework_idhomework` ASC),
  CONSTRAINT `fk_upload_file1`
    FOREIGN KEY (`file_idfile`)
    REFERENCES `d8abase`.`file` (`idfile`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_upload_homework1`
    FOREIGN KEY (`homework_idhomework`)
    REFERENCES `d8abase`.`homework` (`idhomework`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_upload_user1`
    FOREIGN KEY (`user_iduser`)
    REFERENCES `d8abase`.`user` (`iduser`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `d8abase`.`user_bought_upload`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `d8abase`.`user_bought_upload` ;

CREATE TABLE IF NOT EXISTS `d8abase`.`user_bought_upload` (
  `user_iduser` INT(11) NOT NULL,
  `upload_idupload` INT(11) NOT NULL,
  PRIMARY KEY (`user_iduser`, `upload_idupload`),
  INDEX `fk_user_has_upload_upload2_idx` (`upload_idupload` ASC),
  INDEX `fk_user_has_upload_user2_idx` (`user_iduser` ASC),
  CONSTRAINT `fk_user_has_upload_upload2`
    FOREIGN KEY (`upload_idupload`)
    REFERENCES `d8abase`.`upload` (`idupload`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_user_has_upload_user2`
    FOREIGN KEY (`user_iduser`)
    REFERENCES `d8abase`.`user` (`iduser`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `d8abase`.`user_reported_upload`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `d8abase`.`user_reported_upload` ;

CREATE TABLE IF NOT EXISTS `d8abase`.`user_reported_upload` (
  `user_iduser` INT(11) NOT NULL,
  `upload_idupload` INT(11) NOT NULL,
  `note` VARCHAR(200) NULL DEFAULT NULL,
  PRIMARY KEY (`user_iduser`, `upload_idupload`),
  INDEX `fk_user_has_upload_upload1_idx` (`upload_idupload` ASC),
  INDEX `fk_user_has_upload_user1_idx` (`user_iduser` ASC),
  CONSTRAINT `fk_user_has_upload_upload1`
    FOREIGN KEY (`upload_idupload`)
    REFERENCES `d8abase`.`upload` (`idupload`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_user_has_upload_user1`
    FOREIGN KEY (`user_iduser`)
    REFERENCES `d8abase`.`user` (`iduser`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

USE `d8abase` ;

-- -----------------------------------------------------
-- procedure get_user_username
-- -----------------------------------------------------

USE `d8abase`;
DROP procedure IF EXISTS `d8abase`.`get_user_username`;

DELIMITER $$
USE `d8abase`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_user_username`(IN `username_in` VARCHAR(45))
    NO SQL
BEGIN
	SELECT iduser as iduser, password as password, session_id as session_id, status as status FROM user
    	WHERE username=username_in;
END$$

DELIMITER ;

-- -----------------------------------------------------
-- procedure insert_class
-- -----------------------------------------------------

USE `d8abase`;
DROP procedure IF EXISTS `d8abase`.`insert_class`;

DELIMITER $$
USE `d8abase`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_class`(IN `classname_in` VARCHAR(45), IN `iduser_in` INT, OUT `id_out` INT)
    NO SQL
BEGIN
	INSERT INTO class (className)
    	VALUES (classname_in);
    SET id_out=last_insert_id();
    INSERT INTO class_has_user (class_idclass, user_iduser, isClassRep, accepted)
    	VALUES (id_out, iduser_in, 1, 1);
END$$

DELIMITER ;

-- -----------------------------------------------------
-- procedure insert_user
-- -----------------------------------------------------

USE `d8abase`;
DROP procedure IF EXISTS `d8abase`.`insert_user`;

DELIMITER $$
USE `d8abase`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_user`(IN `username_in` VARCHAR(45), IN `email_in` VARCHAR(128), IN `passwd_in` VARCHAR(256), IN `token_in` VARCHAR(100), OUT `id_out` INT)
    NO SQL
BEGIN
	INSERT INTO user (username, email, password, token, dollaz)
    	VALUES (username_in, email_in, passwd_in, token_in, 100);
    set id_out=last_insert_id();
END$$

DELIMITER ;

-- -----------------------------------------------------
-- procedure update_user_session_id
-- -----------------------------------------------------

USE `d8abase`;
DROP procedure IF EXISTS `d8abase`.`update_user_session_id`;

DELIMITER $$
USE `d8abase`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_user_session_id`(IN `id_in` INT, IN `session_id_in` VARCHAR(27))
    NO SQL
BEGIN
	UPDATE user
    	SET session_id=session_id_in
        	where id_in=iduser;
END$$

DELIMITER ;

-- -----------------------------------------------------
-- procedure insert_homework
-- -----------------------------------------------------

USE `d8abase`;
DROP procedure IF EXISTS `d8abase`.`insert_homework`;

DELIMITER $$
USE `d8abase`$$
CREATE PROCEDURE `insert_homework` (in `class_in` int, in `date_in` datetime, in `name_in` varchar(45), out `id_out` int)
BEGIN
	insert into homework (class_idclass, date, name)
		values (class_in, date_in, name_in);
	SET id_out=last_insert_id();
END$$

DELIMITER ;

-- -----------------------------------------------------
-- procedure insert_upload_with_file
-- -----------------------------------------------------

USE `d8abase`;
DROP procedure IF EXISTS `d8abase`.`insert_upload_with_file`;

DELIMITER $$
USE `d8abase`$$
CREATE PROCEDURE `insert_upload_with_file` (in `user_in` int, in `respect_in` int, in `dollaz_in` decimal(14,2), in `description_in` varchar(200), in `homework_in` int, in `file_name` varchar(200), in `file_type` varchar(45), in `file_size` int, in `file_data` mediumblob, OUT `id_out` INT)
BEGIN
	DECLARE var int;
	CALL insert_file(file_name, file_type, file_size, file_data, var);
    insert into upload (user_iduser, respect_cost, dollaz_cost, description, file_idfile, homework_idhomework)
		values (user_in, respect_in, dollaz_in, description_in, var, homework_in);
	set id_out=last_insert_id();
    insert into user_bought_upload(user_iduser, upload_idupload)
		values(user_in, id_out);
END$$

DELIMITER ;

-- -----------------------------------------------------
-- procedure insert_file
-- -----------------------------------------------------

USE `d8abase`;
DROP procedure IF EXISTS `d8abase`.`insert_file`;

DELIMITER $$
USE `d8abase`$$
CREATE PROCEDURE `insert_file` (IN `name_in` varchar(200), IN `type_in` varchar(45), in `size_in` bigint(20), in `data_in` mediumblob, OUT `id_out` int)
BEGIN
	insert into file (name, type, size, data)
		values (name_in, type_in, size_in, data_in);
	set id_out=last_insert_id();
END$$

DELIMITER ;

-- -----------------------------------------------------
-- procedure exist_homework_id
-- -----------------------------------------------------

USE `d8abase`;
DROP procedure IF EXISTS `d8abase`.`exist_homework_id`;

DELIMITER $$
USE `d8abase`$$
CREATE PROCEDURE `exist_homework_id` (in `id_in` int)
BEGIN
	select 1 as response from homework
		where idhomework=id_in;
END$$

DELIMITER ;

-- -----------------------------------------------------
-- procedure get_dashboard_info_user
-- -----------------------------------------------------

USE `d8abase`;
DROP procedure IF EXISTS `d8abase`.`get_dashboard_info_user`;

DELIMITER $$
USE `d8abase`$$
CREATE PROCEDURE `get_dashboard_info_user` (in `iduser_in` int)
BEGIN
select u.username as username, u.dollaz as dollaz, u.respect as respect from user u
	where u.iduser = iduser_in;
END$$

DELIMITER ;

-- -----------------------------------------------------
-- procedure get_user_info
-- -----------------------------------------------------

USE `d8abase`;
DROP procedure IF EXISTS `d8abase`.`get_user_info`;

DELIMITER $$
USE `d8abase`$$
CREATE PROCEDURE `get_user_info` (in `iduser_in` int)
BEGIN
	select username as username, email as email, dollaz as dollaz, respect as respect, timestamp as timestamp from user
		where iduser = iduser_in;
END$$

DELIMITER ;

-- -----------------------------------------------------
-- procedure get_homework_info
-- -----------------------------------------------------

USE `d8abase`;
DROP procedure IF EXISTS `d8abase`.`get_homework_info`;

DELIMITER $$
USE `d8abase`$$
CREATE PROCEDURE `get_homework_info` (in `iduser_in` int)
BEGIN
select h.idhomework as homeworkId, h.name as homeworkName, c.className as homeworkClass, h.date as homeworkDate from user u
	inner join class_has_user c_u
		on u.iduser = c_u.user_iduser
	inner join class c
		on c_u.class_idclass = c.idclass
	inner join homework h
		on h.class_idclass = c.idclass
	where h.date >= NOW() and u.iduser = iduser_in and c_u.accepted = 1;
END$$

DELIMITER ;

-- -----------------------------------------------------
-- procedure get_classes_info
-- -----------------------------------------------------

USE `d8abase`;
DROP procedure IF EXISTS `d8abase`.`get_classes_info`;

DELIMITER $$
USE `d8abase`$$
CREATE PROCEDURE `get_classes_info` (in `iduser_in` int)
BEGIN
select c.className as className, c.idclass as classId, c_u.accepted as accepted, c_u.isClassRep as rep from user u
	inner join class_has_user c_u
		on u.iduser = c_u.user_iduser
	inner join class c
		on c_u.class_idclass = c.idclass
	where u.iduser = iduser_in;
END$$

DELIMITER ;

-- -----------------------------------------------------
-- procedure update_user_class_acceptance
-- -----------------------------------------------------

USE `d8abase`;
DROP procedure IF EXISTS `d8abase`.`update_user_class_acceptance`;

DELIMITER $$
USE `d8abase`$$
CREATE PROCEDURE `update_user_class_acceptance` (in `iduser_in` int, in `idclass_in` int)
BEGIN
	update class_has_user
		set accepted = 1
			where user_iduser = iduser_in and class_idclass = idclass_in;
END$$

DELIMITER ;

-- -----------------------------------------------------
-- procedure insert_class_user_invite
-- -----------------------------------------------------

USE `d8abase`;
DROP procedure IF EXISTS `d8abase`.`insert_class_user_invite`;

DELIMITER $$
USE `d8abase`$$
CREATE PROCEDURE `insert_class_user_invite` (in `username_in` varchar(45), in `idclass_in` int)
BEGIN
	insert into class_has_user (class_idclass, user_iduser)
		values (idclass_in, (select u.iduser from user u where u.username = username_in));
END$$

DELIMITER ;

-- -----------------------------------------------------
-- procedure insert_upload_report
-- -----------------------------------------------------

USE `d8abase`;
DROP procedure IF EXISTS `d8abase`.`insert_upload_report`;

DELIMITER $$
USE `d8abase`$$
CREATE PROCEDURE `insert_upload_report` (in `user_iduser_in` int, in `upload_idupload_in` int, in `note_in` varchar(200))
BEGIN
	insert into user_reported_upload (user_iduser, upload_idupload, note)
		values (user_iduser_in, upload_idupload_in, note_in);
END$$

DELIMITER ;

-- -----------------------------------------------------
-- procedure get_user_token
-- -----------------------------------------------------

USE `d8abase`;
DROP procedure IF EXISTS `d8abase`.`get_user_token`;

DELIMITER $$
USE `d8abase`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_user_token`(IN `iduser_in` INT)
    NO SQL
BEGIN
	SELECT token as token FROM user
    	WHERE iduser=iduser_in;
END$$

DELIMITER ;

-- -----------------------------------------------------
-- procedure update_user_status
-- -----------------------------------------------------

USE `d8abase`;
DROP procedure IF EXISTS `d8abase`.`update_user_status`;

DELIMITER $$
USE `d8abase`$$
CREATE PROCEDURE `update_user_status` (in `iduser_in` int)
BEGIN
	update user
		set status = 1
			where iduser = iduser_in;
END$$

DELIMITER ;

-- -----------------------------------------------------
-- procedure update_user_class_decline
-- -----------------------------------------------------

USE `d8abase`;
DROP procedure IF EXISTS `d8abase`.`update_user_class_decline`;

DELIMITER $$
USE `d8abase`$$
CREATE PROCEDURE `update_user_class_decline` (in `iduser_in` int, in `idclass_in` int)
BEGIN
	delete from class_has_user
		where user_iduser = iduser_in and class_idclass = idclass_in;
END$$

DELIMITER ;

-- -----------------------------------------------------
-- procedure get_class_members
-- -----------------------------------------------------

USE `d8abase`;
DROP procedure IF EXISTS `d8abase`.`get_class_members`;

DELIMITER $$
USE `d8abase`$$
CREATE PROCEDURE `get_class_members` (in `idclass_in` int)
BEGIN
	select u.username as username, c.isClassRep as rep, c.accepted as acc from class_has_user c
		inner join user u
			on c.user_iduser = u.iduser
		where
			c.class_idclass = idclass_in
		order by c.isClassRep desc, c.accepted desc, u.username asc;
END$$

DELIMITER ;

-- -----------------------------------------------------
-- procedure get_upload_info_uploads
-- -----------------------------------------------------

USE `d8abase`;
DROP procedure IF EXISTS `d8abase`.`get_upload_info_uploads`;

DELIMITER $$
USE `d8abase`$$
CREATE PROCEDURE `get_upload_info_uploads` (in `idhomework_in` int)
BEGIN
	select * from upload u
		where u.homework_idhomework = idhomework_in
		order by u.timestamp asc;
END$$

DELIMITER ;

-- -----------------------------------------------------
-- procedure download_file
-- -----------------------------------------------------

USE `d8abase`;
DROP procedure IF EXISTS `d8abase`.`download_file`;

DELIMITER $$
USE `d8abase`$$
CREATE PROCEDURE `download_file` (in `idupload_in` int)
BEGIN
	select f.* from file f
		inner join upload u
			on u.file_idfile = f.idfile
		where u.idupload = idupload_in;
END$$

DELIMITER ;

-- -----------------------------------------------------
-- procedure insert_upload_bought
-- -----------------------------------------------------

USE `d8abase`;
DROP procedure IF EXISTS `d8abase`.`insert_upload_bought`;

DELIMITER $$
USE `d8abase`$$
CREATE PROCEDURE `insert_upload_bought` (in `iduser_in` int, in `idupload_in` int, in `respect_in` int)
BEGIN
	insert into user_bought_upload(user_iduser, upload_idupload)
		values(iduser_in, idupload_in);
	set @dollaz_cost = (select dollaz_cost from upload where idupload = idupload_in);
	update user u
		set u.respect = (u.respect + respect_in), u.dollaz = (u.dollaz + @dollaz_cost)
			where u.iduser = (select up.user_iduser from upload up where up.idupload = idupload_in);
	update user u
		set u.dollaz = (u.dollaz - @dollaz_cost)
			where u.iduser = iduser_in;
END$$

DELIMITER ;

-- -----------------------------------------------------
-- procedure get_dashboard_info_homework
-- -----------------------------------------------------

USE `d8abase`;
DROP procedure IF EXISTS `d8abase`.`get_dashboard_info_homework`;

DELIMITER $$
USE `d8abase`$$
CREATE PROCEDURE `get_dashboard_info_homework` (in `iduser_in` int)
BEGIN
select h.idhomework as homeworkId, h.name as homeworkName, c.className as homeworkClass, h.date as homeworkDate from user u
	inner join class_has_user c_u
		on u.iduser = c_u.user_iduser
	inner join class c
		on c_u.class_idclass = c.idclass
	inner join homework h
		on h.class_idclass = c.idclass
	where h.date >= NOW() and u.iduser = iduser_in and c_u.accepted = 1
    order by h.date asc;
END$$

DELIMITER ;

-- -----------------------------------------------------
-- procedure get_buy_info
-- -----------------------------------------------------

USE `d8abase`;
DROP procedure IF EXISTS `d8abase`.`get_buy_info`;

DELIMITER $$
USE `d8abase`$$
CREATE PROCEDURE `get_buy_info` (in `idupload_in` int, in `iduser_in` int)
BEGIN
	select up.respect_cost, up.dollaz_cost, (select respect from user where iduser = iduser_in) as respect_user, (select dollaz from user where iduser = iduser_in) as dollaz_user, u.respect as respect_seller from upload up
		left join user u
			on up.user_iduser = u.iduser
        where up.idupload = idupload_in;
END$$

DELIMITER ;

-- -----------------------------------------------------
-- procedure get_upload_info_buy
-- -----------------------------------------------------

USE `d8abase`;
DROP procedure IF EXISTS `d8abase`.`get_upload_info_buy`;

DELIMITER $$
USE `d8abase`$$
CREATE PROCEDURE `get_upload_info_buy` (in `idhomework_in` int, in `iduser_in` int)
BEGIN
	select b.* from user_bought_upload b
		inner join upload up
			on b.upload_idupload = up.idupload
		where up.homework_idhomework = idhomework_in and b.user_iduser = iduser_in;
END$$

DELIMITER ;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
