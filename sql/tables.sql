DROP TABLE IF EXISTS `user`;
DROP TABLE IF EXISTS hub;
DROP TABLE IF EXISTS reputation;
DROP TABLE IF EXISTS `level`;

CREATE TABLE 'user' (
	userId BINARY(16) NOT NULL,
	userActiviationToken CHAR(32) NOT NULL,
	userBio VARCHAR(3000),
	userEmail VARCHAR(256) NOT NULL,
	userFirstName VARCHAR(64),
	userHash CHAR(128) NOT NULL,
	userImage VARCHAR(128),
	userLastName VARCHAR(64),
	userSalt VARCHAR(64) NOT NULL,
	userUserName VARCHAR(128) NOT NULL,
	UNIQUE (userEmail),
	PRIMARY KEY (userId)
);

CREATE TABLE hub (
	hubId BINARY(16) NOT NULL,
	hubUserId BINARY(16) NOT NULL,
	hubLocation VARCHAR(264) NOT NULL,
	hubName VARCHAR(128) NOT NULL,
	UNIQUE(hubName),
	INDEX(hubName),
	INDEX(hubUserId),
	FOREIGN KEY (hubUserId) REFERENCES 'user'(userId),
	PRIMARY KEY (hubId)
);

CREATE TABLE reputation (
	reputationId BINARY(16) NOT NULL,
	reputationHubId BINARY(16),
	reputationLevelId BINARY(16) NOT NULL,
	reputationUserId BINARY(16),
	reputationPoint TINYINT(8),
	FOREIGN KEY (reputationHubId) REFERENCES hub(hubId),
	FOREIGN KEY (reputationLevelId) REFERENCES level(levelId),
	FOREIGN KEY (reputationUserId) REFERENCES 'user'(userId),
	PRIMARY KEY (reputationId)
);

CREATE TABLE 'level' (
	levelId BINARY(16) NOT NULL,
	levelName VARCHAR(64) NOT NULL,
	levelNumber TINYINT(8) NOT NULL,
	PRIMARY KEY (levelId)
);