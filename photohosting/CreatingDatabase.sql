CREATE DATABASE IF NOT EXISTS project;
USE project;
CREATE TABLE IF NOT EXISTS usersOfSite (
    userID INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(50) NOT NULL UNIQUE,
    userPassword VARCHAR(50) NOT NULL,
    salt VARCHAR(32) NOT NULL,
    activeHex varchar(32) NOT NULL,
	userStatus tinyint(1) NOT NULL DEFAULT 1,
    nickname VARCHAR(20) NOT NULL,
    userDescription VARCHAR(150) NULL
)ENGINE=InnoDB CHARACTER SET cp1251;
CREATE TABLE IF NOT EXISTS tags (
    tID INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    tagText VARCHAR(20) NOT NULL UNIQUE
)ENGINE=InnoDB CHARACTER SET cp1251;
CREATE TABLE IF NOT EXISTS publication(
    pubID INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    userID INT,
    nickname VARCHAR(20) NOT NULL,
    pubName VARCHAR(30) NOT NULL,
    pubDescription VARCHAR(200),
    creationTime DATETIME DEFAULT NOW(),
    views INT NOT NULL DEFAULT 0,
    tag INT,
    postImage VARCHAR (11) NOT NULL,
    FOREIGN KEY (userID) REFERENCES usersOfSite (userID) ON DELETE CASCADE,
    FOREIGN KEY (tag) REFERENCES tags (tID) ON DELETE CASCADE
)ENGINE=InnoDB CHARACTER SET cp1251;
CREATE TABLE IF NOT EXISTS likes(
    likeID INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    userID INT,
    pubID INT,
    FOREIGN KEY (userID) REFERENCES usersOfSite (userID) ON DELETE CASCADE,
    FOREIGN KEY (pubID) REFERENCES publication (pubID) ON DELETE CASCADE
)ENGINE=InnoDB CHARACTER SET cp1251;
CREATE TABLE IF NOT EXISTS comments(
    commID INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    userID INT,
    pubID INT,
    commText VARCHAR(150) NOT NULL,
    creationTime DATETIME NOT NULL DEFAULT NOW(),
    FOREIGN KEY (userID) REFERENCES usersOfSite (userID) ON DELETE CASCADE,
    FOREIGN KEY (pubID) REFERENCES publication (pubID) ON DELETE CASCADE
)ENGINE=InnoDB CHARACTER SET cp1251;
CREATE TABLE IF NOT EXISTS subscriprions (
    subID INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    authorID INT,
    userID INT,
    FOREIGN KEY (authorID) REFERENCES usersOfSite (userID) ON DELETE CASCADE,
    FOREIGN KEY (userID) REFERENCES usersOfSite (userID) ON DELETE CASCADE
) ENGINE=InnoDB CHARACTER SET cp1251;
CREATE TABLE IF NOT EXISTS times (
	timeID INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    userID INT,
    sentTime DATETIME NOT NULL DEFAULT NOW(),
    expiryTime DATETIME NOT NULL,
    FOREIGN KEY (userID) REFERENCES usersOfSite (userID) ON DELETE CASCADE
)ENGINE=InnoDB CHARACTER SET cp1251;
INSERT INTO tags (tagText) VALUES ('Природа');
INSERT INTO tags (tagText) VALUES ('Архітектура');
INSERT INTO tags (tagText) VALUES ('Люди');
INSERT INTO tags (tagText) VALUES ('Тварини');
INSERT INTO tags (tagText) VALUES ('Техніка');
INSERT INTO tags (tagText) VALUES ('Космос');
INSERT INTO tags (tagText) VALUES ('Інтер\'єр');
INSERT INTO tags (tagText) VALUES ('Рисунки');
INSERT INTO tags (tagText) VALUES ('Їжа');