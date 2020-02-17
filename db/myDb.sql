CREATE TABLE factUser (
	userId 			SERIAL PRIMARY KEY,
	userName		VARCHAR(100) NOT NULL,
	password		VARCHAR(100) NOT NULL,
	admin			BOOLEAN DEFAULT FALSE
);

CREATE TABLE factCategory (
	categoryId 		SERIAL PRIMARY KEY,
	categoryName	VARCHAR(100) NOT NULL
);

CREATE TABLE factGame (
	gameId 			SERIAL PRIMARY KEY,
	gameName		VARCHAR(100) NOT NULL,
	minPlayers 		INTEGER NOT NULL, -- Minimum number of players
	maxPlayers 		INT NOT NULL, -- Maximum number of players
	minDuration		INT NOT NULL, -- Average game duration in minutes
	minAge			INT NOT NULL -- Recommended minimum age of players
);

CREATE TABLE dimUserCategoryMapping (
	userCategoryId 	SERIAL PRIMARY KEY,
	userId 			INT NOT NULL REFERENCES factUser (userId),
	categoryId		INT NOT NULL REFERENCES factCategory (categoryId)
);

CREATE TABLE dimUserGameMapping (
	userGameId 		SERIAL PRIMARY KEY,
	userId			INT NOT NULL REFERENCES factUser (userId),
	gameId			INT NOT NULL REFERENCES factGame (gameId),
	favorite		BOOLEAN DEFAULT FALSE,
	summary			VARCHAR(250) -- Optional field for brief reference of game 
);

CREATE TABLE dimUserGameCategoryMapping (
	userGameCategoryId 	SERIAL PRIMARY KEY,
	userId 			INT NOT NULL REFERENCES factUser (userId),
	gameId			INT NOT NULL REFERENCES factGame (gameId),
	categoryId		INT NOT NULL REFERENCES factCategory (categoryId)
);



INSERT INTO factUser (userName, password, admin) VALUES ('Jim', 'adminPassword', true);
INSERT INTO factUser (userName, password, admin) VALUES ('Steve', 'userPassword', false);

INSERT INTO factCategory (categoryName) VALUES ('Party');
INSERT INTO factCategory (categoryName) VALUES ('Strategy');
INSERT INTO factCategory (categoryName) VALUES ('Card');
INSERT INTO factCategory (categoryName) VALUES ('Kid-Friendly');
INSERT INTO factCategory (categoryName) VALUES ('Dice');
INSERT INTO factCategory (categoryName) VALUES ('Easy to Learn');
INSERT INTO factCategory (categoryName) VALUES ('Get to Know You');
INSERT INTO factCategory (categoryName) VALUES ('Guys Night');
INSERT INTO factCategory (categoryName) VALUES ('Cooperative');
INSERT INTO factCategory (categoryName) Values ('Deck Building');

INSERT INTO factGame (gameName, minPlayers, maxPlayers, minDuration, minAge) VALUES ('Clue', 2, 6, 15, 8);
INSERT INTO factGame (gameName, minPlayers, maxPlayers, minDuration, minAge) VALUES ('Monopoly', 2, 6, 60, 6);
INSERT INTO factGame (gameName, minPlayers, maxPlayers, minDuration, minAge) VALUES ('Settlers of Catan', 3, 4, 60, 10);
INSERT INTO factGame (gameName, minPlayers, maxPlayers, minDuration, minAge) VALUES ('Risk', 2, 6, 60, 10);
INSERT INTO factGame (gameName, minPlayers, maxPlayers, minDuration, minAge) VALUES ('Pandemic', 2, 4, 30, 10);
INSERT INTO factGame (gameName, minPlayers, maxPlayers, minDuration, minAge) VALUES ('Wizard', 3, 6, 30, 10);
INSERT INTO factGame (gameName, minPlayers, maxPlayers, minDuration, minAge) VALUES ('Uno', 2, 10, 5, 7);

INSERT INTO dimUserCategoryMapping (userId, categoryId) VALUES ((Select userId from factUser Where userName = 'Jim'), (Select categoryId from factCategory Where categoryName = 'Party'));
INSERT INTO dimUserCategoryMapping (userId, categoryId) VALUES ((Select userId from factUser Where userName = 'Jim'), (Select categoryId from factCategory Where categoryName = 'Strategy'));
INSERT INTO dimUserCategoryMapping (userId, categoryId) VALUES ((Select userId from factUser Where userName = 'Jim'), (Select categoryId from factCategory Where categoryName = 'Card'));
INSERT INTO dimUserCategoryMapping (userId, categoryId) VALUES ((Select userId from factUser Where userName = 'Jim'), (Select categoryId from factCategory Where categoryName = 'Kid-Friendly'));
INSERT INTO dimUserCategoryMapping (userId, categoryId) VALUES ((Select userId from factUser Where userName = 'Jim'), (Select categoryId from factCategory Where categoryName = 'Dice'));
INSERT INTO dimUserCategoryMapping (userId, categoryId) VALUES ((Select userId from factUser Where userName = 'Jim'), (Select categoryId from factCategory Where categoryName = 'Easy to Learn'));
INSERT INTO dimUserCategoryMapping (userId, categoryId) VALUES ((Select userId from factUser Where userName = 'Jim'), (Select categoryId from factCategory Where categoryName = 'Get to Know You'));
INSERT INTO dimUserCategoryMapping (userId, categoryId) VALUES ((Select userId from factUser Where userName = 'Jim'), (Select categoryId from factCategory Where categoryName = 'Guys Night'));
INSERT INTO dimUserCategoryMapping (userId, categoryId) VALUES ((Select userId from factUser Where userName = 'Jim'), (Select categoryId from factCategory Where categoryName = 'Cooperative'));
INSERT INTO dimUserCategoryMapping (userId, categoryId) VALUES ((Select userId from factUser Where userName = 'Jim'), (Select categoryId from factCategory Where categoryName = 'Deck Building'));

INSERT INTO dimUserCategoryMapping (userId, categoryId) VALUES ((Select userId from factUser Where userName = 'Steve'), (Select categoryId from factCategory Where categoryName = 'Party'));
INSERT INTO dimUserCategoryMapping (userId, categoryId) VALUES ((Select userId from factUser Where userName = 'Steve'), (Select categoryId from factCategory Where categoryName = 'Strategy'));
INSERT INTO dimUserCategoryMapping (userId, categoryId) VALUES ((Select userId from factUser Where userName = 'Steve'), (Select categoryId from factCategory Where categoryName = 'Card'));
INSERT INTO dimUserCategoryMapping (userId, categoryId) VALUES ((Select userId from factUser Where userName = 'Steve'), (Select categoryId from factCategory Where categoryName = 'Kid-Friendly'));
INSERT INTO dimUserCategoryMapping (userId, categoryId) VALUES ((Select userId from factUser Where userName = 'Steve'), (Select categoryId from factCategory Where categoryName = 'Dice'));
INSERT INTO dimUserCategoryMapping (userId, categoryId) VALUES ((Select userId from factUser Where userName = 'Steve'), (Select categoryId from factCategory Where categoryName = 'Easy to Learn'));
INSERT INTO dimUserCategoryMapping (userId, categoryId) VALUES ((Select userId from factUser Where userName = 'Steve'), (Select categoryId from factCategory Where categoryName = 'Get to Know You'));
INSERT INTO dimUserCategoryMapping (userId, categoryId) VALUES ((Select userId from factUser Where userName = 'Steve'), (Select categoryId from factCategory Where categoryName = 'Guys Night'));
INSERT INTO dimUserCategoryMapping (userId, categoryId) VALUES ((Select userId from factUser Where userName = 'Steve'), (Select categoryId from factCategory Where categoryName = 'Cooperative'));
INSERT INTO dimUserCategoryMapping (userId, categoryId) VALUES ((Select userId from factUser Where userName = 'Steve'), (Select categoryId from factCategory Where categoryName = 'Deck Building'));

INSERT INTO dimUserGameMapping (userId, gameId) VALUES ((Select userId from factUser Where userName = 'Jim'), (Select gameId from factGame Where gameName = 'Clue'));
INSERT INTO dimUserGameMapping (userId, gameId) VALUES ((Select userId from factUser Where userName = 'Jim'), (Select gameId from factGame Where gameName = 'Monopoly'));
INSERT INTO dimUserGameMapping (userId, gameId) VALUES ((Select userId from factUser Where userName = 'Jim'), (Select gameId from factGame Where gameName = 'Settlers of Catan'));
INSERT INTO dimUserGameMapping (userId, gameId) VALUES ((Select userId from factUser Where userName = 'Jim'), (Select gameId from factGame Where gameName = 'Risk'));
INSERT INTO dimUserGameMapping (userId, gameId) VALUES ((Select userId from factUser Where userName = 'Jim'), (Select gameId from factGame Where gameName = 'Pandemic'));
INSERT INTO dimUserGameMapping (userId, gameId) VALUES ((Select userId from factUser Where userName = 'Jim'), (Select gameId from factGame Where gameName = 'Wizard'));
INSERT INTO dimUserGameMapping (userId, gameId) VALUES ((Select userId from factUser Where userName = 'Jim'), (Select gameId from factGame Where gameName = 'Uno'));

INSERT INTO dimUserGameCategoryMapping (userId, gameId, categoryId) VALUES ((Select userId from factUser Where userName = 'Jim'), (Select gameId from factGame Where gameName = 'Clue'), (Select categoryId from factCategory Where categoryName = 'Kid-Friendly'));
INSERT INTO dimUserGameCategoryMapping (userId, gameId, categoryId) VALUES ((Select userId from factUser Where userName = 'Jim'), (Select gameId from factGame Where gameName = 'Clue'), (Select categoryId from factCategory Where categoryName = 'Easy to Learn'));
INSERT INTO dimUserGameCategoryMapping (userId, gameId, categoryId) VALUES ((Select userId from factUser Where userName = 'Jim'), (Select gameId from factGame Where gameName = 'Monopoly'), (Select categoryId from factCategory Where categoryName = 'Kid-Friendly'));
INSERT INTO dimUserGameCategoryMapping (userId, gameId, categoryId) VALUES ((Select userId from factUser Where userName = 'Jim'), (Select gameId from factGame Where gameName = 'Monopoly'), (Select categoryId from factCategory Where categoryName = 'Easy to Learn'));
INSERT INTO dimUserGameCategoryMapping (userId, gameId, categoryId) VALUES ((Select userId from factUser Where userName = 'Jim'), (Select gameId from factGame Where gameName = 'Settlers of Catan'), (Select categoryId from factCategory Where categoryName = 'Strategy'));
INSERT INTO dimUserGameCategoryMapping (userId, gameId, categoryId) VALUES ((Select userId from factUser Where userName = 'Jim'), (Select gameId from factGame Where gameName = 'Settlers of Catan'), (Select categoryId from factCategory Where categoryName = 'Guys Night'));
INSERT INTO dimUserGameCategoryMapping (userId, gameId, categoryId) VALUES ((Select userId from factUser Where userName = 'Jim'), (Select gameId from factGame Where gameName = 'Risk'), (Select categoryId from factCategory Where categoryName = 'Strategy'));
INSERT INTO dimUserGameCategoryMapping (userId, gameId, categoryId) VALUES ((Select userId from factUser Where userName = 'Jim'), (Select gameId from factGame Where gameName = 'Pandemic'), (Select categoryId from factCategory Where categoryName = 'Cooperative'));
INSERT INTO dimUserGameCategoryMapping (userId, gameId, categoryId) VALUES ((Select userId from factUser Where userName = 'Jim'), (Select gameId from factGame Where gameName = 'Wizard'), (Select categoryId from factCategory Where categoryName = 'Card'));
INSERT INTO dimUserGameCategoryMapping (userId, gameId, categoryId) VALUES ((Select userId from factUser Where userName = 'Jim'), (Select gameId from factGame Where gameName = 'Uno'), (Select categoryId from factCategory Where categoryName = 'Kid-Friendly'));
INSERT INTO dimUserGameCategoryMapping (userId, gameId, categoryId) VALUES ((Select userId from factUser Where userName = 'Jim'), (Select gameId from factGame Where gameName = 'Uno'), (Select categoryId from factCategory Where categoryName = 'Card'));
INSERT INTO dimUserGameCategoryMapping (userId, gameId, categoryId) VALUES ((Select userId from factUser Where userName = 'Jim'), (Select gameId from factGame Where gameName = 'Uno'), (Select categoryId from factCategory Where categoryName = 'Easy to Learn'));
