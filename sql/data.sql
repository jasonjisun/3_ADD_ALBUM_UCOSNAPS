CREATE TABLE user (
	user_id INT AUTO_INCREMENT PRIMARY KEY,
	username VARCHAR(255),
	first_name VARCHAR(255),
	last_name VARCHAR(255),
  email VARCHAR(255),
	password TEXT,
	date_added TIMESTAMP DEFAULT CURRENT_TIMESTAMP 
);

CREATE TABLE photos (
	photo_id INT AUTO_INCREMENT PRIMARY KEY,
	photo_name TEXT,
	title VARCHAR(255),
	username VARCHAR(255),
	description VARCHAR(255),
	date_added TIMESTAMP DEFAULT CURRENT_TIMESTAMP 
);


CREATE TABLE activity_log (
    log_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    action VARCHAR(255) NOT NULL,
    record_id INT NOT NULL,
    action_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES user(user_id)
);


CREATE TABLE collage_records (
    record_id INT AUTO_INCREMENT PRIMARY KEY,  
    student_id INT NOT NULL,                    
    student_name VARCHAR(255) NOT NULL,          
    course_name VARCHAR(255) NOT NULL,           
    date_of_enrollment DATE NOT NULL,            
    grade VARCHAR(5),                           
    status ENUM('Active', 'Graduated', 'Dropped') NOT NULL DEFAULT 'Active',  
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,  
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP  
);

INSERT INTO collage_records (student_id, student_name, course_name, date_of_enrollment, grade, status)
VALUES
(1, 'John Doe', 'Computer Science', '2022-08-15', 'A', 'Active'),
(2, 'Jane Smith', 'Mathematics', '2021-09-10', 'B+', 'Graduated'),
(3, 'Michael Johnson', 'Engineering', '2023-01-20', 'A-', 'Active'),
(4, 'Emily Davis', 'Biology', '2022-06-01', 'B', 'Active'),
(5, 'David Lee', 'Physics', '2021-11-12', 'C+', 'Dropped'),
(6, 'Sarah Brown', 'Chemistry', '2023-04-05', 'A+', 'Active'),
(7, 'James Wilson', 'Medicine', '2020-09-18', 'B-', 'Graduated'),
(8, 'Maria Garcia', 'Business Administration', '2022-07-23', 'C', 'Active'),
(9, 'Daniel Miller', 'History', '2022-10-10', 'A', 'Active'),
(10, 'Sophia Anderson', 'Economics', '2021-12-02', 'B', 'Graduated'),
(11, 'Chris Thomas', 'Literature', '2023-05-15', 'B+', 'Active'),
(12, 'Rachel Moore', 'Law', '2020-08-30', 'A-', 'Graduated'),
(13, 'Brian Jackson', 'Psychology', '2023-02-25', 'C+', 'Dropped'),
(14, 'Lisa Martinez', 'Philosophy', '2021-01-13', 'A', 'Active'),
(15, 'Robert White', 'Arts', '2022-11-05', 'B+', 'Active'),
(16, 'Jessica Harris', 'Engineering', '2022-09-21', 'A', 'Active'),
(17, 'William Clark', 'Mathematics', '2020-05-19', 'B-', 'Graduated'),
(18, 'Olivia Lewis', 'Nursing', '2023-03-30', 'C', 'Dropped'),
(19, 'Charles Walker', 'Architecture', '2021-10-14', 'A+', 'Active'),
(20, 'Victoria Young', 'Computer Science', '2022-01-08', 'B+', 'Active');

