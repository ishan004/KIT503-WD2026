DROP TABLE IF EXISTS Review;
DROP TABLE IF EXISTS Submissions;
DROP TABLE IF EXISTS Users;

-- Activity 1: Creating Tables
CREATE TABLE Users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    Name TEXT NOT NULL,
    Email TEXT UNIQUE,
    affiliation TEXT,
    Role TEXT
);

CREATE TABLE Submissions (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    User_id INTEGER,
    title TEXT NOT NULL,
    paper_type TEXT,
    accepted BOOLEAN,
    abstract TEXT,
    FOREIGN KEY (User_id) REFERENCES Users(id)
);

CREATE TABLE Review (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER,
    submission_id INTEGER,
    results INTEGER,
    FOREIGN KEY (user_id) REFERENCES Users(id),
    FOREIGN KEY (submission_id) REFERENCES Submissions(id)
);

-- Activity 2: Inserting Data into Tables
INSERT INTO Users (Name, Email, affiliation, Role)
VALUES
('Jane Smith', 'jane.smith@example.com', 'University of Tasmania', 'Author'),
('Ali Khan', 'ali.khan@example.com', 'University of Tasmania', 'Author'),
('Mia Chen', 'mia.chen@example.com', 'University of Tasmania', 'Author'),
('Sam Patel', 'sam.patel@example.com', 'University of Tasmania', 'Reviewer'),
('Ishan Bhusal', 'ishan.bhusal@example.com', 'University of Tasmania', 'Reviewer');

INSERT INTO Submissions (User_id, title, paper_type, accepted, abstract)
VALUES
(1, 'Modern Web Security Patterns', 'Paper', 1, 'This paper reviews common web vulnerabilities and proposes secure-by-design patterns for modern web applications.'),
(2, 'Progressive Web Apps for Offline Learning', 'Working group', 0, 'We present a working group proposal to explore PWA features for offline-first education platforms.'),
(3, 'Optimising React Performance', 'Poster', 1, 'A poster summarising practical techniques to reduce re-renders and improve perceived performance in React apps.'),
(4, 'Conference UX Design', 'Poster', 1, 'This poster explores user experience design ideas for conference websites.');

INSERT INTO Review (user_id, submission_id, results)
VALUES
(4, 1, 85),
(4, 2, 78),
(4, 3, 90);

-- Activity 3: Querying the Database
SELECT * FROM Submissions;

SELECT DISTINCT Users.*
FROM Users
JOIN Review ON Users.id = Review.user_id;

SELECT Submissions.*
FROM Submissions
JOIN Review ON Submissions.id = Review.submission_id
JOIN Users ON Users.id = Review.user_id
WHERE Users.Name = 'Sam Patel';

-- Activity 4: Updating Records
UPDATE Users
SET affiliation = 'UTAS'
WHERE Name = 'Sam Patel';

UPDATE Submissions
SET accepted = 1
WHERE title = 'Progressive Web Apps for Offline Learning';

-- Activity 5: Deleting Records
-- Dependent records must be removed before deleting the parent record
DELETE FROM Review WHERE submission_id = 3;
DELETE FROM Submissions WHERE id = 3;
DELETE FROM Submissions WHERE User_id = 2;
DELETE FROM Users WHERE Name = 'Ali Khan';

-- Activity 6: Advanced Queries
SELECT Submissions.title, COUNT(Review.id) AS total_reviews
FROM Submissions
LEFT JOIN Review ON Submissions.id = Review.submission_id
GROUP BY Submissions.id, Submissions.title;

SELECT Submissions.*
FROM Submissions
LEFT JOIN Review ON Submissions.id = Review.submission_id
WHERE Review.id IS NULL;

SELECT Submissions.title, AVG(Review.results) AS average_score
FROM Submissions
LEFT JOIN Review ON Submissions.id = Review.submission_id
GROUP BY Submissions.id, Submissions.title;