-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema hosannah_site
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema hosannah_site
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `hosannah_site` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
USE `hosannah_site` ;

-- -----------------------------------------------------
-- Table `hosannah_site`.`Users`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `hosannah_site`.`Users` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `fname` VARCHAR(250) NOT NULL,
  `lname` VARCHAR(250) NOT NULL,
  `birthdate` DATE NOT NULL,
  `gender` INT NOT NULL,
  `email` VARCHAR(64) NOT NULL,
  `username` VARCHAR(250) NOT NULL,
  `password` VARCHAR(64) NOT NULL,
  `mobile` VARCHAR(20) NOT NULL,
  `avatar` VARCHAR(255) NOT NULL DEFAULT 'defaultavatar',
  `regtime` INT(11) NOT NULL,
  `recoverycode` VARCHAR(64) NULL DEFAULT '\"\"',
  `codeexpiry` INT(11) NULL,
  `valtoken` VARCHAR(64) NULL DEFAULT NULL,
  `isactive` TINYINT(1) NOT NULL DEFAULT 0,
  `timezone` VARCHAR(80) NOT NULL,
  UNIQUE INDEX `username_UNIQUE` (`username` ASC),
  UNIQUE INDEX `email_UNIQUE` (`email` ASC),
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `hosannah_site`.`auth_rule`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `hosannah_site`.`auth_rule` (
  `name` VARCHAR(64) NOT NULL,
  `data` TEXT NULL DEFAULT NULL,
  `created_at` INT NULL DEFAULT NULL,
  `updated_at` INT NULL DEFAULT NULL,
  PRIMARY KEY (`name`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `hosannah_site`.`auth_item`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `hosannah_site`.`auth_item` (
  `name` VARCHAR(64) NOT NULL,
  `type` INT NOT NULL,
  `description` TEXT NULL DEFAULT NULL,
  `rule_name` VARCHAR(64) NULL DEFAULT NULL,
  `data` TEXT NULL DEFAULT NULL,
  `created_at` INT NULL DEFAULT NULL,
  `updated_at` INT NULL DEFAULT NULL,
  PRIMARY KEY (`name`),
  INDEX `type` (`type` ASC),
  INDEX `fk_ce6dd412-eef4-11e3-8f69-875bcd3ee3aa` (`rule_name` ASC),
  CONSTRAINT `fk_ce6dd412-eef4-11e3-8f69-875bcd3ee3aa`
    FOREIGN KEY (`rule_name`)
    REFERENCES `hosannah_site`.`auth_rule` (`name`)
    ON DELETE set null
    ON UPDATE cascade)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `hosannah_site`.`auth_item_child`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `hosannah_site`.`auth_item_child` (
  `parent` VARCHAR(64) NOT NULL,
  `child` VARCHAR(64) NOT NULL,
  PRIMARY KEY (`parent`, `child`),
  INDEX `fk_ce6e2dcc-eef4-11e3-8f69-875bcd3ee3aa` (`child` ASC),
  CONSTRAINT `fk_ce6e27f0-eef4-11e3-8f69-875bcd3ee3aa`
    FOREIGN KEY (`parent`)
    REFERENCES `hosannah_site`.`auth_item` (`name`)
    ON DELETE cascade
    ON UPDATE cascade,
  CONSTRAINT `fk_ce6e2dcc-eef4-11e3-8f69-875bcd3ee3aa`
    FOREIGN KEY (`child`)
    REFERENCES `hosannah_site`.`auth_item` (`name`)
    ON DELETE cascade
    ON UPDATE cascade)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `hosannah_site`.`auth_assignment`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `hosannah_site`.`auth_assignment` (
  `item_name` VARCHAR(64) NOT NULL,
  `user_id` VARCHAR(64) NOT NULL,
  `created_at` INT NULL DEFAULT NULL,
  PRIMARY KEY (`item_name`, `user_id`),
  CONSTRAINT `fk_ce6e6fd0-eef4-11e3-8f69-875bcd3ee3aa`
    FOREIGN KEY (`item_name`)
    REFERENCES `hosannah_site`.`auth_item` (`name`)
    ON DELETE cascade
    ON UPDATE cascade)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `hosannah_site`.`YBoardChoice`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `hosannah_site`.`YBoardChoice` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `choice` VARCHAR(200) NOT NULL,
  `poll_id` INT(10) UNSIGNED NOT NULL,
  `sort` SMALLINT(6) NOT NULL DEFAULT '0',
  `votes` INT(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  INDEX `idx_choice_poll` (`poll_id` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `hosannah_site`.`YBoardForum`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `hosannah_site`.`YBoardForum` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `cat_id` INT(10) UNSIGNED NULL DEFAULT NULL,
  `name` VARCHAR(255) NOT NULL,
  `subtitle` VARCHAR(255) NULL DEFAULT NULL,
  `type` TINYINT(4) NOT NULL DEFAULT '0',
  `public` TINYINT(4) NOT NULL DEFAULT '1',
  `locked` TINYINT(4) NOT NULL DEFAULT '0',
  `moderated` TINYINT(4) NOT NULL DEFAULT '0',
  `sort` SMALLINT(6) NOT NULL DEFAULT '0',
  `num_posts` INT(10) UNSIGNED NOT NULL DEFAULT '0',
  `num_topics` INT(10) UNSIGNED NOT NULL DEFAULT '0',
  `last_post_id` INT(10) UNSIGNED NULL DEFAULT NULL,
  `poll` TINYINT(4) NOT NULL DEFAULT '0',
  `membergroup_id` INT(10) UNSIGNED NULL DEFAULT 1,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `hosannah_site`.`YBoardIPAddress`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `hosannah_site`.`YBoardIPAddress` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ip` VARCHAR(39) NULL DEFAULT NULL,
  `address` VARCHAR(255) NULL DEFAULT NULL,
  `source` TINYINT(4) NULL DEFAULT '0',
  `count` INT(11) NULL DEFAULT '0',
  `create_time` TIMESTAMP NULL DEFAULT NULL,
  `update_time` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `ip_UNIQUE` (`ip` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `hosannah_site`.`YBoardMemberGroup`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `hosannah_site`.`YBoardMemberGroup` (
  `id` INT(10) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `description` TEXT NULL DEFAULT NULL,
  `color` VARCHAR(7) NULL DEFAULT NULL,
  `image` VARCHAR(255) NULL DEFAULT NULL,
  `group_role` VARCHAR(64) NOT NULL DEFAULT 'member' COMMENT 'Group Role',
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `hosannah_site`.`YBoardRank`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `hosannah_site`.`YBoardRank` (
  `id` INT(10) NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  `min_posts` MEDIUMINT(8) NOT NULL,
  `stars` MEDIUMINT(8) NOT NULL DEFAULT 5,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `hosannah_site`.`YBoardMember`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `hosannah_site`.`YBoardMember` (
  `id` INT(11) NOT NULL,
  `group_id` INT(10) NOT NULL DEFAULT 1,
  `rank_id` INT(10) NOT NULL DEFAULT 1,
  `location` VARCHAR(255) NULL DEFAULT NULL,
  `personal_text` VARCHAR(255) NULL DEFAULT NULL,
  `signature` TEXT NULL DEFAULT NULL,
  `show_online` TINYINT(4) NULL DEFAULT '1',
  `contact_email` TINYINT(4) NULL DEFAULT '0',
  `contact_pm` TINYINT(4) NULL DEFAULT '1',
  `first_visit` TIMESTAMP NULL DEFAULT NULL,
  `last_visit` TIMESTAMP NULL DEFAULT NULL,
  `ip` VARCHAR(255) NOT NULL,
  `blogger` VARCHAR(255) NULL DEFAULT NULL,
  `facebook` VARCHAR(255) NULL DEFAULT NULL,
  `skype` VARCHAR(255) NULL DEFAULT NULL,
  `google` VARCHAR(255) NULL DEFAULT NULL,
  `linkedin` VARCHAR(255) NULL DEFAULT NULL,
  `metacafe` VARCHAR(255) NULL DEFAULT NULL,
  `github` VARCHAR(255) NULL DEFAULT NULL,
  `orkut` VARCHAR(255) NULL DEFAULT NULL,
  `tumblr` VARCHAR(255) NULL DEFAULT NULL,
  `twitter` VARCHAR(255) NULL DEFAULT NULL,
  `website` VARCHAR(255) NULL DEFAULT NULL,
  `wordpress` VARCHAR(255) NULL DEFAULT NULL,
  `yahoo` VARCHAR(255) NULL DEFAULT NULL,
  `youtube` VARCHAR(255) NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_YBoardMember_Group_idx` (`group_id` ASC),
  INDEX `fk_YBoardMember_Rank_idx` (`rank_id` ASC),
  CONSTRAINT `fk_bbii_member_Profile`
    FOREIGN KEY (`id`)
    REFERENCES `hosannah_site`.`Users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_YBoardMember_Group`
    FOREIGN KEY (`group_id`)
    REFERENCES `hosannah_site`.`YBoardMemberGroup` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_YBoardMember_Rank`
    FOREIGN KEY (`rank_id`)
    REFERENCES `hosannah_site`.`YBoardRank` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `hosannah_site`.`YBoardTopic`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `hosannah_site`.`YBoardTopic` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `forum_id` INT(10) UNSIGNED NOT NULL,
  `user_id` INT(11) UNSIGNED NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `first_post_id` INT(10) UNSIGNED NOT NULL,
  `last_post_id` INT(10) UNSIGNED NOT NULL,
  `num_replies` INT(10) UNSIGNED NOT NULL DEFAULT '0',
  `num_views` INT(10) UNSIGNED NOT NULL DEFAULT '0',
  `approved` TINYINT(4) NOT NULL DEFAULT '0',
  `locked` TINYINT(4) NOT NULL DEFAULT '0',
  `sticky` TINYINT(4) NOT NULL DEFAULT '0',
  `global` TINYINT(4) NOT NULL DEFAULT '0',
  `moved` INT(10) UNSIGNED NOT NULL DEFAULT '0',
  `upvoted` SMALLINT(6) NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  INDEX `forum_id_INDEX` (`forum_id` ASC),
  CONSTRAINT `fk_bbii_topic_forum`
    FOREIGN KEY (`forum_id`)
    REFERENCES `hosannah_site`.`YBoardForum` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 5
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `hosannah_site`.`YBoardLogTopic`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `hosannah_site`.`YBoardLogTopic` (
  `member_id` INT(11) NOT NULL,
  `topic_id` INT(10) UNSIGNED NOT NULL,
  `forum_id` INT(10) UNSIGNED NOT NULL,
  `last_post_id` INT(10) UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`member_id`, `topic_id`),
  INDEX `idx_log_forum_id` (`forum_id` ASC),
  INDEX `fk_bbii_log_topic_Topic_idx` (`topic_id` ASC),
  CONSTRAINT `fk_bbii_log_topic_1User`
    FOREIGN KEY (`member_id`)
    REFERENCES `hosannah_site`.`YBoardMember` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_bbii_log_topic_Topic`
    FOREIGN KEY (`topic_id`)
    REFERENCES `hosannah_site`.`YBoardTopic` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `hosannah_site`.`YBoardMessage`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `hosannah_site`.`YBoardMessage` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `sendfrom` INT(11) NOT NULL,
  `sendto` INT(11) NOT NULL,
  `post_id` INT(10) UNSIGNED NULL DEFAULT NULL,
  `subject` VARCHAR(255) NOT NULL,
  `content` TEXT NOT NULL,
  `create_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `read_indicator` TINYINT(4) NOT NULL DEFAULT '0',
  `type` TINYINT(4) NOT NULL DEFAULT '0',
  `inbox` TINYINT(4) NOT NULL DEFAULT '1',
  `outbox` TINYINT(4) NOT NULL DEFAULT '1',
  `ip` VARCHAR(39) NOT NULL,
  INDEX `sendfrom_INDEX` (`sendfrom` ASC),
  INDEX `sendto_INDEX` (`sendto` ASC),
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_bbii_message_User_FROM`
    FOREIGN KEY (`sendfrom`)
    REFERENCES `hosannah_site`.`YBoardMember` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `hosannah_site`.`YBoardPost`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `hosannah_site`.`YBoardPost` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `subject` VARCHAR(255) NOT NULL,
  `content` TEXT NOT NULL,
  `user_id` INT(11) NOT NULL,
  `topic_id` INT(10) UNSIGNED NULL DEFAULT NULL,
  `forum_id` INT(10) UNSIGNED NULL DEFAULT NULL,
  `original_post` TINYINT(1) NOT NULL DEFAULT 0,
  `ip` VARCHAR(39) NULL DEFAULT NULL,
  `create_time` INT NULL DEFAULT NULL,
  `approved` TINYINT(4) NULL DEFAULT NULL,
  `change_id` INT(10) UNSIGNED NULL DEFAULT NULL,
  `change_time` INT NULL DEFAULT NULL,
  `change_reason` VARCHAR(255) NULL DEFAULT NULL,
  `upvoted` SMALLINT(6) NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  INDEX `user_id_INDEX` (`user_id` ASC),
  INDEX `topic_id_INDEX` (`topic_id` ASC),
  INDEX `create_time_INDEX` (`create_time` ASC),
  INDEX `fk_bbii_post_Forum_idx` (`forum_id` ASC),
  CONSTRAINT `fk_bbii_post_User`
    FOREIGN KEY (`user_id`)
    REFERENCES `hosannah_site`.`YBoardMember` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_bbii_post_Topic`
    FOREIGN KEY (`topic_id`)
    REFERENCES `hosannah_site`.`YBoardTopic` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_bbii_post_Forum`
    FOREIGN KEY (`forum_id`)
    REFERENCES `hosannah_site`.`YBoardForum` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 23
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `hosannah_site`.`YBoardPoll`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `hosannah_site`.`YBoardPoll` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `question` VARCHAR(200) NOT NULL,
  `post_id` INT(10) UNSIGNED NOT NULL,
  `user_id` INT(11) NOT NULL,
  `expire_date` DATE NULL DEFAULT NULL,
  `allow_revote` TINYINT(4) NOT NULL DEFAULT '0',
  `allow_multiple` TINYINT(4) NOT NULL DEFAULT '0',
  `votes` INT(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  INDEX `idx_poll_post` (`post_id` ASC),
  INDEX `fk_bbii_poll_Member_idx` (`user_id` ASC),
  CONSTRAINT `fk_bbii_poll_Post`
    FOREIGN KEY (`post_id`)
    REFERENCES `hosannah_site`.`YBoardPost` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_bbii_poll_Member`
    FOREIGN KEY (`user_id`)
    REFERENCES `hosannah_site`.`YBoardMember` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `hosannah_site`.`YBoardSession`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `hosannah_site`.`YBoardSession` (
  `id` VARCHAR(128) NOT NULL,
  `last_visit` INT NOT NULL,
  `user_id` INT(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `hosannah_site`.`YBoardSetting`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `hosannah_site`.`YBoardSetting` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `key` VARCHAR(50) NOT NULL,
  `value` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `key_UNIQUE` (`key` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `hosannah_site`.`YBoardSpider`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `hosannah_site`.`YBoardSpider` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `user_agent` VARCHAR(255) NOT NULL,
  `hits` INT(10) UNSIGNED NOT NULL DEFAULT '0',
  `last_visit` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `hosannah_site`.`YBoardUpvoted`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `hosannah_site`.`YBoardUpvoted` (
  `member_id` INT(11) NOT NULL,
  `post_id` INT(10) UNSIGNED NOT NULL,
  `author` INT(11) NOT NULL,
  INDEX `idx_upvoted_member` (`member_id` ASC),
  INDEX `idx_upvoted_post` (`post_id` ASC),
  INDEX `fk_bbii_upvoted_Author_idx` (`author` ASC),
  CONSTRAINT `fk_bbii_upvoted_Member`
    FOREIGN KEY (`member_id`)
    REFERENCES `hosannah_site`.`YBoardMember` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_bbii_upvoted_Post`
    FOREIGN KEY (`post_id`)
    REFERENCES `hosannah_site`.`YBoardPost` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_bbii_upvoted_Author`
    FOREIGN KEY (`author`)
    REFERENCES `hosannah_site`.`YBoardMember` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `hosannah_site`.`YBoardVote`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `hosannah_site`.`YBoardVote` (
  `poll_id` INT(10) UNSIGNED NOT NULL,
  `choice_id` INT(10) UNSIGNED NOT NULL,
  `user_id` INT(11) NOT NULL,
  PRIMARY KEY (`poll_id`, `choice_id`, `user_id`),
  INDEX `idx_vote_poll` (`poll_id` ASC),
  INDEX `idx_vote_user` (`user_id` ASC),
  INDEX `idx_vote_choice` (`choice_id` ASC),
  CONSTRAINT `fk_bbii_vote_Poll`
    FOREIGN KEY (`poll_id`)
    REFERENCES `hosannah_site`.`YBoardPoll` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_bbii_vote_choice`
    FOREIGN KEY (`choice_id`)
    REFERENCES `hosannah_site`.`YBoardChoice` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_bbii_vote_User`
    FOREIGN KEY (`user_id`)
    REFERENCES `hosannah_site`.`YBoardMember` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `hosannah_site`.`YBoardBan`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `hosannah_site`.`YBoardBan` (
  `id` INT(10) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) NULL DEFAULT NULL,
  `ip` VARCHAR(255) NULL DEFAULT NULL,
  `email` VARCHAR(80) NULL DEFAULT NULL,
  `message` VARCHAR(255) NULL,
  `banned_on` INT(10) NULL,
  `expires` INT(10) NULL,
  `banned_by` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_YBoardBan_Burner_idx` (`banned_by` ASC),
  INDEX `fk_YBoardBan_User_idx` (`user_id` ASC),
  CONSTRAINT `fk_YBoardBan_Burner`
    FOREIGN KEY (`banned_by`)
    REFERENCES `hosannah_site`.`YBoardMember` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_YBoardBan_User`
    FOREIGN KEY (`user_id`)
    REFERENCES `hosannah_site`.`YBoardMember` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
