
DROP TABLE IF EXISTS bookings;
DROP TABLE IF EXISTS colors;
DROP TABLE IF EXISTS users;


CREATE TABLE users(
			id INTEGER NOT NULL AUTO_INCREMENT UNIQUE,
			email VARCHAR(255) NOT NULL UNIQUE,
			password VARCHAR(255) NOT NULL,
			PRIMARY KEY (id, email),
			key(email)
)
;


CREATE TABLE bookings(
			dayOfWeek VARCHAR(255) NOT NULL,
			slot int NOT NULL,
			nOfDay INTEGER NOT NULL, /* to allow db to understand the natural order of the day in the week */
			email VARCHAR(255), /*nullable*/
			t_id VARCHAR(255), /*nullable and in YYYY-MM-DD HH-MM-SS format ==> lexicographic order */
			PRIMARY KEY (dayOfWeek, slot),
			FOREIGN KEY (email) REFERENCES users(email) ON DELETE CASCADE
)
;


CREATE TABLE colors(
			colorRGB VARCHAR(255) NOT NULL,
			email VARCHAR(255) UNIQUE, /* a user can has only one associated color */ 
			PRIMARY KEY (colorRGB),
			FOREIGN KEY (email) REFERENCES users(email) ON DELETE CASCADE
)
;

/*time slots for the week (initially all slots are free)*/
INSERT INTO bookings VALUES
	('Monday', 8, 1, null, null),
	('Monday', 9, 1, null, null),
	('Monday', 10, 1, null, null),
	('Monday', 11, 1, null, null),
	('Monday', 12, 1, null, null),
	('Monday', 13, 1, null, null),
	('Monday', 14, 1, null, null),
	('Monday', 15, 1, null, null),
	('Monday', 16, 1, null, null),
	('Monday', 17, 1, null, null),
	('Tuesday', 8, 2, null, null),
	('Tuesday', 9, 2, null, null),
	('Tuesday', 10, 2, null, null),
	('Tuesday', 11, 2, null, null),
	('Tuesday', 12, 2, null, null),
	('Tuesday', 13, 2, null, null),
	('Tuesday', 14, 2, null, null),
	('Tuesday', 15, 2, null, null),
	('Tuesday', 16, 2, null, null),
	('Tuesday', 17, 2, null, null),
	('Wednesday', 8, 3, null, null),
	('Wednesday', 9, 3, null, null),
	('Wednesday', 10, 3, null, null),
	('Wednesday', 11, 3, null, null),
	('Wednesday', 12, 3, null, null),
	('Wednesday', 13, 3, null, null),
	('Wednesday', 14, 3, null, null),
	('Wednesday', 15, 3, null, null),
	('Wednesday', 16, 3, null, null),
	('Wednesday', 17, 3, null, null),
	('Thursday', 8, 4, null, null),
	('Thursday', 9, 4, null, null),
	('Thursday', 10, 4, null, null),
	('Thursday', 11, 4, null, null),
	('Thursday', 12, 4, null, null),
	('Thursday', 13, 4, null, null),
	('Thursday', 14, 4, null, null),
	('Thursday', 15, 4, null, null),
	('Thursday', 16, 4, null, null),
	('Thursday', 17, 4, null, null),
	('Friday', 8, 5, null, null),
	('Friday', 9, 5, null, null),
	('Friday', 10, 5, null, null),
	('Friday', 11, 5, null, null),
	('Friday', 12, 5, null, null),
	('Friday', 13, 5, null, null),
	('Friday', 14, 5, null, null),
	('Friday', 15, 5, null, null),
	('Friday', 16, 5, null, null),
	('Friday', 17, 5, null, null)
;

/* predefined values for colors */
INSERT INTO colors (colorRGB, email) VALUES
		("#0347c3", null), /*blue */
		("#ff7b25", null), /*orange */
		("#ffef96", null), /*sand */
		("#7e4a35", null), /*brown */
		("#96897f", null), /*grey */
		("#3b3a30", null), /*black */
		("#f7cac9", null), /*pink */
		("#ff3399", null), /*fucsia */
		("#bdcebe", null), /*light grey */
		("#f4fe00", null)  /*yellow */
;









