PRAGMA foreign_keys = ON;

DROP TABLE IF EXISTS Review;
DROP TABLE IF EXISTS Submissions;
DROP TABLE IF EXISTS Users;

CREATE TABLE Users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    Name TEXT NOT NULL,
    Email TEXT NOT NULL UNIQUE,
    Password TEXT NOT NULL,
    affiliation TEXT,
    Role TEXT
);

CREATE TABLE Submissions (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    User_id INTEGER NOT NULL,
    title TEXT NOT NULL,
    paper_type TEXT,
    accepted BOOLEAN DEFAULT 0,
    abstract TEXT,
    FOREIGN KEY (User_id) REFERENCES Users(id)
);

CREATE TABLE Review (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    submission_id INTEGER NOT NULL,
    results INTEGER,
    FOREIGN KEY (user_id) REFERENCES Users(id),
    FOREIGN KEY (submission_id) REFERENCES Submissions(id)
);

INSERT INTO Users (Name, Email, Password, affiliation, Role)
VALUES
('Jane Smith', 'jane.smith@example.com', '$2y$10$k7r7gTQxZpUo6x2Y6V2J5u0ZqF6F7Ww1YJ1Z7YkY1l9u7H8bQdFQW', 'University of Tasmania', 'Author'),
('Ali Khan', 'ali.khan@example.com', '$2y$10$k7r7gTQxZpUo6x2Y6V2J5u0ZqF6F7Ww1YJ1Z7YkY1l9u7H8bQdFQW', 'University of Tasmania', 'Author'),
('Mia Chen', 'mia.chen@example.com', '$2y$10$k7r7gTQxZpUo6x2Y6V2J5u0ZqF6F7Ww1YJ1Z7YkY1l9u7H8bQdFQW', 'University of Tasmania', 'Author'),
('Sam Patel', 'sam.patel@example.com', '$2y$10$k7r7gTQxZpUo6x2Y6V2J5u0ZqF6F7Ww1YJ1Z7YkY1l9u7H8bQdFQW', 'University of Tasmania', 'Reviewer'),
('Ishan Bhusal', 'ishan.bhusal@example.com', '$2y$10$k7r7gTQxZpUo6x2Y6V2J5u0ZqF6F7Ww1YJ1Z7YkY1l9u7H8bQdFQW', 'University of Tasmania', 'Reviewer');

INSERT INTO Submissions (User_id, title, paper_type, accepted, abstract)
VALUES
(1, 'Modern Web Security Patterns', 'Paper', 1, 'This paper reviews common web vulnerabilities and proposes secure-by-design patterns for modern web applications.'),
(2, 'Progressive Web Apps for Offline Learning', 'Working group', 0, 'We present a working group proposal to explore PWA features for offline-first education platforms.'),
(3, 'Optimising React Performance', 'Poster', 1, 'A poster summarising practical techniques to reduce re-renders and improve perceived performance in React apps.'),
(1, 'Conference UX Design', 'Poster', 1, 'This poster explores user experience design ideas for conference websites.');

INSERT INTO Review (user_id, submission_id, results)
VALUES
(4, 1, 85),
(4, 2, 78),
(5, 3, 90);