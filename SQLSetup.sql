CREATE TABLE kontrollliste (
    id INT AUTO_INCREMENT PRIMARY KEY,
    transport VARCHAR(50),
    line VARCHAR(10),
    station VARCHAR(100),
    reports INT
);

CREATE TABLE ranking (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(255) NOT NULL,
    credit INT NOT NULL
);

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    token VARCHAR(128),
    email VARCHAR(100) NOT NULL,
    last_reported DATETIME
);

INSERT INTO kontrollliste (transport, line, station, reports)
VALUES
    ("U-Bahn", "U1", "Leopoldau", 0),
    ("U-Bahn", "U1", "Oberlaa", 0),
    ("U-Bahn", "U2", "Schottentor", 0),
    ("U-Bahn", "U2", "Seestadt", 0),
    ("U-Bahn", "U3", "Ottakring", 0),
    ("U-Bahn", "U3", "Simmering", 0),
    ("U-Bahn", "U4", "Hütteldorf", 0),
    ("U-Bahn", "U4", "Heiligenstadt", 0),
    ("U-Bahn", "U6", "Floridsdorf", 0),
    ("U-Bahn", "U6", "Siebenhirten", 0),
    ("S-Bahn", "S1", "Wiener Neustadt", 0),
    ("S-Bahn", "S1", "Gänserndorf", 0),
    ("S-Bahn", "S1", "Marchegg", 0),
    ("S-Bahn", "S2", "Mödling", 0),
    ("S-Bahn", "S2", "Mistelbach/Laa an der Thaya", 0),
    ("S-Bahn", "S3", "Wiener Neustadt", 0),
    ("S-Bahn", "S3", "Hollabrunn", 0),
    ("S-Bahn", "S4", "Wiener Neustadt", 0),
    ("S-Bahn", "S4", "Wolkersdorf", 0),
    ("S-Bahn", "S7", "Wolfsthal", 0),
    ("S-Bahn", "S7", "Mistelbach", 0),
    ("S-Bahn", "S40", "Heiligenstadt", 0),
    ("S-Bahn", "S40", "St. Pölten Bahnhof", 0),
    ("S-Bahn", "S45", "Wien Heiligenstadt", 0),
    ("S-Bahn", "S45", "Wien Hütteldorf", 0),
    ("S-Bahn", "S50", "Wien Westbahnhof", 0),
    ("S-Bahn", "S50", "Neulengbach", 0),
    ("S-Bahn", "S60", "Wien Hauptbahnhof", 0),
    ("S-Bahn", "S60", "Györ", 0),
    ("S-Bahn", "S80", "Wien Rennweg", 0),
    ("S-Bahn", "S80", "Wien Wolkersdorf", 0);