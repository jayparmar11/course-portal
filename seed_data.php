<?php
require_once './includes/db.php';

// Seed users
$db->query("INSERT INTO users (name, email, password_hash, role) VALUES
    ('Admin User', 'admin@example.com', '" . password_hash('admin123', PASSWORD_BCRYPT) . "', 'admin'),
    ('Student User', 'student@example.com', '" . password_hash('student123', PASSWORD_BCRYPT) . "', 'student')");

// Fetch student user_id
$studentIdResult = $db->query("SELECT id FROM users WHERE email = 'student@example.com'");
$studentId = $studentIdResult->fetch_assoc()['id'];

// Seed courses
$db->query("INSERT INTO courses (title, description, faculty) VALUES
    ('Web Development', 'Learn HTML, CSS, and JavaScript', 'John Doe'),
    ('Data Science', 'Introduction to Data Analysis and Machine Learning', 'Jane Smith'),
    ('Database Management', 'Learn SQL and Database Design', 'Alice Johnson'),
    ('Cybersecurity Basics', 'Understand the fundamentals of cybersecurity', 'Bob Brown'),
    ('Cloud Computing', 'Learn about cloud platforms and services', 'Eve Adams'),
    ('Artificial Intelligence', 'Introduction to AI and Machine Learning', 'Frank White'),
    ('Mobile App Development', 'Build apps for Android and iOS', 'Grace Green'),
    ('Game Development', 'Learn to create games using Unity', 'Hank Black'),
    ('Digital Marketing', 'Understand SEO and online marketing', 'Ivy Blue'),
    ('Graphic Design', 'Learn Photoshop and Illustrator', 'Jack Red'),
    ('Cybersecurity Advanced', 'Advanced topics in cybersecurity', 'Bob Brown'),
    ('Big Data Analytics', 'Introduction to big data tools', 'Jane Smith'),
    ('Blockchain Basics', 'Learn blockchain technology', 'Alice Johnson'),
    ('DevOps Practices', 'Understand CI/CD and DevOps tools', 'John Doe'),
    ('Internet of Things', 'Learn IoT concepts and applications', 'Eve Adams'),
    ('Quantum Computing', 'Introduction to quantum computing', 'Frank White'),
    ('Software Testing', 'Learn manual and automated testing', 'Grace Green'),
    ('Ethical Hacking', 'Learn penetration testing', 'Hank Black'),
    ('Augmented Reality', 'Introduction to AR development', 'Ivy Blue'),
    ('Virtual Reality', 'Learn VR development', 'Jack Red'),
    ('Data Visualization', 'Create visualizations with Tableau', 'Jane Smith'),
    ('Machine Learning Advanced', 'Advanced ML techniques', 'Alice Johnson'),
    ('Web Security', 'Learn to secure web applications', 'Bob Brown'),
    ('UI/UX Design', 'Learn user interface and experience design', 'Eve Adams'),
    ('Project Management', 'Learn Agile and Scrum methodologies', 'Frank White'),
    ('Database Optimization', 'Optimize database performance', 'Grace Green'),
    ('Network Security', 'Learn to secure networks', 'Hank Black'),
    ('Cloud Security', 'Secure cloud environments', 'Ivy Blue'),
    ('Data Engineering', 'Learn data pipelines and ETL', 'Jack Red')");

// Seed enrollments
$db->query("INSERT INTO enrollments (user_id, course_id) VALUES
    ($studentId, 1),
    ($studentId, 2)");

// Seed feedback
$db->query("INSERT INTO feedback (user_id, course_id, ratings, comment) VALUES
    ($studentId, 1, '{\"teaching\":5,\"interaction\":4,\"materials\":5,\"overall\":5}', 'Great course!'),
    ($studentId, 2, '{\"teaching\":4,\"interaction\":4,\"materials\":4,\"overall\":4}', 'Very informative.')");

echo "Dummy data seeded successfully.";
