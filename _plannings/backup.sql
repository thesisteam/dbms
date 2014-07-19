SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema dbass
-- -----------------------------------------------------
DROP SCHEMA IF EXISTS `dbass` ;
CREATE SCHEMA IF NOT EXISTS `dbass` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `dbass` ;

-- -----------------------------------------------------
-- Table `dbass`.`userpower`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `dbass`.`userpower` ;

CREATE TABLE IF NOT EXISTS `dbass`.`userpower` (
  `id` INT UNSIGNED NOT NULL,
  `label` VARCHAR(30) NOT NULL,
  `description` LONGTEXT NULL COMMENT 'This is where you describe the privilege this user holds.',
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  UNIQUE INDEX `label_UNIQUE` (`label` ASC))
ENGINE = InnoDB
AUTO_INCREMENT = 1;


-- -----------------------------------------------------
-- Table `dbass`.`user`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `dbass`.`user` ;

CREATE TABLE IF NOT EXISTS `dbass`.`user` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(20) NOT NULL,
  `password` VARCHAR(450) NOT NULL,
  `email` VARCHAR(60) NOT NULL,
  `secquestion` VARCHAR(1125) NOT NULL,
  `secanswer` VARCHAR(450) NOT NULL,
  `status` INT UNSIGNED NOT NULL COMMENT 'Given value of account status' /* comment truncated */ /*0 = Active
1 = Inactive
2 = Pending*/,
  `is_online` TINYINT UNSIGNED NOT NULL,
  `userpower_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`, `username`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  UNIQUE INDEX `username_UNIQUE` (`username` ASC),
  INDEX `user_fk_userpower_idx` (`userpower_id` ASC),
  CONSTRAINT `user_fk_userpower_id`
    FOREIGN KEY (`userpower_id`)
    REFERENCES `dbass`.`userpower` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 1
ROW_FORMAT = DEFAULT;


-- -----------------------------------------------------
-- Table `dbass`.`course`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `dbass`.`course` ;

CREATE TABLE IF NOT EXISTS `dbass`.`course` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` VARCHAR(11) NOT NULL,
  `name` VARCHAR(30) NOT NULL COMMENT 'Qualified 30 characters Course name, unique, of course',
  `description` LONGTEXT NULL COMMENT 'The description of this course',
  `teacher_id` INT UNSIGNED NOT NULL COMMENT '(FK) The teacher who authored this course.',
  `starttime` TIME NULL,
  `endtime` TIME NULL,
  `is_active` TINYINT NULL,
  PRIMARY KEY (`id`, `code`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  UNIQUE INDEX `code_UNIQUE` (`code` ASC),
  INDEX `course_fk_user_id_idx` (`teacher_id` ASC),
  CONSTRAINT `course_fk_user_id`
    FOREIGN KEY (`teacher_id`)
    REFERENCES `dbass`.`user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `dbass`.`sy`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `dbass`.`sy` ;

CREATE TABLE IF NOT EXISTS `dbass`.`sy` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `year` INT UNSIGNED NOT NULL,
  `description` MEDIUMTEXT NULL,
  `admin_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  UNIQUE INDEX `year_UNIQUE` (`year` ASC),
  INDEX `sy_fk_user_id_idx` (`admin_id` ASC),
  CONSTRAINT `sy_fk_user_id`
    FOREIGN KEY (`admin_id`)
    REFERENCES `dbass`.`user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `dbass`.`studyfield`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `dbass`.`studyfield` ;

CREATE TABLE IF NOT EXISTS `dbass`.`studyfield` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `description` LONGTEXT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  UNIQUE INDEX `name_UNIQUE` (`name` ASC))
ENGINE = InnoDB
AUTO_INCREMENT = 1
ROW_FORMAT = DEFAULT;


-- -----------------------------------------------------
-- Table `dbass`.`studentprofile`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `dbass`.`studentprofile` ;

CREATE TABLE IF NOT EXISTS `dbass`.`studentprofile` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `student_id` INT UNSIGNED NOT NULL,
  `studyfield_id` INT UNSIGNED NOT NULL,
  UNIQUE INDEX `student_id_UNIQUE` (`student_id` ASC),
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  INDEX `studentprofile_fk_user_studyfield_id_idx` (`studyfield_id` ASC),
  CONSTRAINT `studentprofile_fk_user_id`
    FOREIGN KEY (`student_id`)
    REFERENCES `dbass`.`user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `studentprofile_fk_user_studyfield_id`
    FOREIGN KEY (`studyfield_id`)
    REFERENCES `dbass`.`studyfield` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `dbass`.`studentcourse`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `dbass`.`studentcourse` ;

CREATE TABLE IF NOT EXISTS `dbass`.`studentcourse` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `course_id` INT UNSIGNED NOT NULL,
  `student_id` INT UNSIGNED NOT NULL,
  `sy_id` INT UNSIGNED NOT NULL,
  `entry_date` DATE NOT NULL,
  `status` INT UNSIGNED NOT NULL COMMENT 'Possible values' /* comment truncated */ /*[0] - Pending
[1] - Enrolled
[2] - Banned*/,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  INDEX `studentcourse_fk_user_id_idx` (`student_id` ASC),
  INDEX `studentcourse_fk_course_id_idx` (`course_id` ASC),
  INDEX `studentcourse_fk_sy_id_idx` (`sy_id` ASC),
  CONSTRAINT `studentcourse_fk_course_id`
    FOREIGN KEY (`course_id`)
    REFERENCES `dbass`.`course` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `studentcourse_fk_user_id`
    FOREIGN KEY (`student_id`)
    REFERENCES `dbass`.`user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `studentcourse_fk_sy_id`
    FOREIGN KEY (`sy_id`)
    REFERENCES `dbass`.`sy` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `dbass`.`attendance`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `dbass`.`attendance` ;

CREATE TABLE IF NOT EXISTS `dbass`.`attendance` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `createdate` DATE NOT NULL,
  `createtime` TIME NOT NULL,
  `course_id` INT UNSIGNED NOT NULL,
  `student_id` INT UNSIGNED NOT NULL,
  `remark` INT NOT NULL COMMENT '[0] - Absent' /* comment truncated */ /*[1] - Present
[2] - Late*/,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  INDEX `attendance_fk_course_id_idx` (`course_id` ASC),
  INDEX `attendance_fk_user_id_idx` (`student_id` ASC),
  CONSTRAINT `attendance_fk_course_id`
    FOREIGN KEY (`course_id`)
    REFERENCES `dbass`.`course` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `attendance_fk_user_id`
    FOREIGN KEY (`student_id`)
    REFERENCES `dbass`.`user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 1;


-- -----------------------------------------------------
-- Table `dbass`.`gscheme`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `dbass`.`gscheme` ;

CREATE TABLE IF NOT EXISTS `dbass`.`gscheme` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(40) NOT NULL,
  `description` LONGTEXT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  UNIQUE INDEX `name_UNIQUE` (`name` ASC))
ENGINE = InnoDB
AUTO_INCREMENT = 1;


-- -----------------------------------------------------
-- Table `dbass`.`gschemecomponent`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `dbass`.`gschemecomponent` ;

CREATE TABLE IF NOT EXISTS `dbass`.`gschemecomponent` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `percentage` VARCHAR(45) NULL,
  `taskable` TINYINT NOT NULL COMMENT 'If this is taskable, it means that this component\'s computation will be based on online tasks.' /* comment truncated */ /*Otherwise, computation will be based on manual inputs of teacher on `pointrecord` table*/,
  `notes` MEDIUMTEXT NULL,
  `gscheme_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  UNIQUE INDEX `name_UNIQUE` (`name` ASC),
  INDEX `fk_gscheme_id_idx` (`gscheme_id` ASC),
  CONSTRAINT `fk_gschemecomponent_gscheme_id`
    FOREIGN KEY (`gscheme_id`)
    REFERENCES `dbass`.`gscheme` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 1
COMMENT = 'GRADING-COMPONENTS for corresponding GRADING-SCHEME';


-- -----------------------------------------------------
-- Table `dbass`.`profile`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `dbass`.`profile` ;

CREATE TABLE IF NOT EXISTS `dbass`.`profile` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT UNSIGNED NOT NULL,
  `fname` VARCHAR(40) NOT NULL,
  `mname` VARCHAR(40) NULL,
  `lname` VARCHAR(40) NOT NULL,
  `gender` VARCHAR(6) NOT NULL,
  `address1` LONGTEXT NOT NULL,
  `address2` LONGTEXT NULL,
  `city` VARCHAR(30) NOT NULL,
  `province` VARCHAR(50) NOT NULL,
  `birthdate` DATE NOT NULL,
  `mobile` VARCHAR(15) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `user_id_UNIQUE` (`user_id` ASC),
  CONSTRAINT `fk_user_id`
    FOREIGN KEY (`user_id`)
    REFERENCES `dbass`.`user` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `dbass`.`d_gscheme_course`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `dbass`.`d_gscheme_course` ;

CREATE TABLE IF NOT EXISTS `dbass`.`d_gscheme_course` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `gscheme_id` INT UNSIGNED NOT NULL,
  `course_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `course_id_UNIQUE` (`course_id` ASC),
  INDEX `fk_course_id_idx` (`gscheme_id` ASC),
  CONSTRAINT `fk_gschemecourse_gscheme_id`
    FOREIGN KEY (`gscheme_id`)
    REFERENCES `dbass`.`gscheme` (`id`)
    ON DELETE RESTRICT
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_gschemecourse_course_id`
    FOREIGN KEY (`course_id`)
    REFERENCES `dbass`.`course` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = 'Many courses can only have 1 scheme applied';


-- -----------------------------------------------------
-- Table `dbass`.`gperiod`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `dbass`.`gperiod` ;

CREATE TABLE IF NOT EXISTS `dbass`.`gperiod` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `name_UNIQUE` (`name` ASC))
ENGINE = InnoDB
AUTO_INCREMENT = 1;


-- -----------------------------------------------------
-- Table `dbass`.`d_course_gperiod`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `dbass`.`d_course_gperiod` ;

CREATE TABLE IF NOT EXISTS `dbass`.`d_course_gperiod` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `course_id` INT UNSIGNED NOT NULL,
  `gperiod_id` INT UNSIGNED NOT NULL,
  `notes` LONGTEXT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_course_id_idx` (`course_id` ASC),
  INDEX `fk_gperiod_id_idx` (`gperiod_id` ASC),
  CONSTRAINT `fk_coursegperiod_course_id`
    FOREIGN KEY (`course_id`)
    REFERENCES `dbass`.`course` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_coursegperiod_gperiod_id`
    FOREIGN KEY (`gperiod_id`)
    REFERENCES `dbass`.`gperiod` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 1
COMMENT = 'Grade computations';


-- -----------------------------------------------------
-- Table `dbass`.`recordpoint`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `dbass`.`recordpoint` ;

CREATE TABLE IF NOT EXISTS `dbass`.`recordpoint` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `student_id` INT UNSIGNED NOT NULL,
  `course_id` INT UNSIGNED NOT NULL,
  `gschemecomponent_id` INT UNSIGNED NOT NULL,
  `period_id` INT UNSIGNED NOT NULL,
  `score` DECIMAL(3) NOT NULL,
  `total` DECIMAL(3) NOT NULL,
  `notes` MEDIUMTEXT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_course_id_idx` (`course_id` ASC),
  INDEX `fk_student_id_idx` (`student_id` ASC),
  INDEX `fk_gschemecomponent_id_idx` (`gschemecomponent_id` ASC),
  INDEX `fk_d_course_gperiod_id_idx` (`period_id` ASC),
  CONSTRAINT `fk_recordpoint_course_id`
    FOREIGN KEY (`course_id`)
    REFERENCES `dbass`.`course` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_recordpoint_student_id`
    FOREIGN KEY (`student_id`)
    REFERENCES `dbass`.`user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_recordpoint_gschemecomponent_id`
    FOREIGN KEY (`gschemecomponent_id`)
    REFERENCES `dbass`.`gschemecomponent` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_recoirdpoint_d_course_gperiod_id`
    FOREIGN KEY (`period_id`)
    REFERENCES `dbass`.`d_course_gperiod` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 1;


-- -----------------------------------------------------
-- Table `dbass`.`thread`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `dbass`.`thread` ;

CREATE TABLE IF NOT EXISTS `dbass`.`thread` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `course_id` INT UNSIGNED NOT NULL,
  `author_id` INT UNSIGNED NOT NULL,
  `createddate` DATE NOT NULL,
  `createdtime` TIME NOT NULL,
  `message` LONGTEXT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_course_id_idx` (`course_id` ASC),
  INDEX `fk_user_id_idx` (`author_id` ASC),
  CONSTRAINT `fk_thread_course_id`
    FOREIGN KEY (`course_id`)
    REFERENCES `dbass`.`course` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_thread_user_id`
    FOREIGN KEY (`author_id`)
    REFERENCES `dbass`.`user` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `dbass`.`threadcomment`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `dbass`.`threadcomment` ;

CREATE TABLE IF NOT EXISTS `dbass`.`threadcomment` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `thread_id` INT UNSIGNED NOT NULL,
  `author_id` INT UNSIGNED NOT NULL,
  `posteddate` DATE NOT NULL,
  `postedtime` TIME NOT NULL,
  `message` MEDIUMTEXT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_thread_id_idx` (`thread_id` ASC),
  INDEX `fk_user_id_idx` (`author_id` ASC),
  CONSTRAINT `fk_threadcomment_thread_id`
    FOREIGN KEY (`thread_id`)
    REFERENCES `dbass`.`thread` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_threadcomment_user_id`
    FOREIGN KEY (`author_id`)
    REFERENCES `dbass`.`user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `dbass`.`coursesched`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `dbass`.`coursesched` ;

CREATE TABLE IF NOT EXISTS `dbass`.`coursesched` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `course_id` INT UNSIGNED NOT NULL,
  `starttime` TIME NOT NULL,
  `endtime` TIME NOT NULL,
  `notes` LONGTEXT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_course_id_idx` (`course_id` ASC),
  CONSTRAINT `fk_coursesched_course_id`
    FOREIGN KEY (`course_id`)
    REFERENCES `dbass`.`course` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `dbass`.`task`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `dbass`.`task` ;

CREATE TABLE IF NOT EXISTS `dbass`.`task` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `course_id` INT UNSIGNED NOT NULL,
  `period_id` INT UNSIGNED NOT NULL,
  `title` MEDIUMTEXT NOT NULL,
  `message` LONGTEXT NOT NULL,
  `deaddate` DATE NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_course_id_idx` (`course_id` ASC),
  INDEX `fk_period_id_idx` (`period_id` ASC),
  CONSTRAINT `fk_task_course_id`
    FOREIGN KEY (`course_id`)
    REFERENCES `dbass`.`course` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_task_period_id`
    FOREIGN KEY (`period_id`)
    REFERENCES `dbass`.`d_course_gperiod` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 1;


-- -----------------------------------------------------
-- Table `dbass`.`taskattachment`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `dbass`.`taskattachment` ;

CREATE TABLE IF NOT EXISTS `dbass`.`taskattachment` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `task_id` INT UNSIGNED NOT NULL,
  `user_id` INT UNSIGNED NOT NULL,
  `tokenvalue` MEDIUMTEXT NOT NULL,
  `lastdowndate` DATE NOT NULL,
  `downcount` INT UNSIGNED NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  INDEX `fk_user_id_idx` (`user_id` ASC),
  INDEX `fk_task_id_idx` (`task_id` ASC),
  CONSTRAINT `fk_taskattachment_user_id`
    FOREIGN KEY (`user_id`)
    REFERENCES `dbass`.`user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_taskattachment_task_id`
    FOREIGN KEY (`task_id`)
    REFERENCES `dbass`.`task` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 1;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- -----------------------------------------------------
-- Data for table `dbass`.`userpower`
-- -----------------------------------------------------
START TRANSACTION;
USE `dbass`;
INSERT INTO `dbass`.`userpower` (`id`, `label`, `description`) VALUES (0, 'Admin', 'The system admin that manages every existing resource in the system.');
INSERT INTO `dbass`.`userpower` (`id`, `label`, `description`) VALUES (1, 'Instructor', 'Manages respective courses and student entries/records.');
INSERT INTO `dbass`.`userpower` (`id`, `label`, `description`) VALUES (2, 'Student', 'Account for students availing course enrollments in the system.');

COMMIT;


-- -----------------------------------------------------
-- Data for table `dbass`.`gperiod`
-- -----------------------------------------------------
START TRANSACTION;
USE `dbass`;
INSERT INTO `dbass`.`gperiod` (`id`, `name`) VALUES (1, 'Prelim');
INSERT INTO `dbass`.`gperiod` (`id`, `name`) VALUES (2, 'Midterm');
INSERT INTO `dbass`.`gperiod` (`id`, `name`) VALUES (3, 'Semi-finals');
INSERT INTO `dbass`.`gperiod` (`id`, `name`) VALUES (4, 'Finals');

COMMIT;

