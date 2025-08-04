# Course Management & Feedback Portal

This project combines multiple modules, multi-page navigation, form handling, AJAX-based data flow, authentication, and admin vs. user roles—giving a trainee solid hands-on experience in building a complete web application.

---

## Project Description

A **Course Management & Feedback Portal** where:

- **Students** can register/login, browse available courses, enroll in courses, and submit feedback.
- **Admins** can manage users, courses, and view aggregated feedback.
- Both **frontend and backend** must be designed by the trainee using **HTML, CSS, JavaScript, AJAX, PHP, and MySQL**.

---

## Core Modules & Pages

### 1. Authentication
- `/register.html` – Student Registration (HTML + JS Validation + PHP for user creation)
- `/login.html` – Login for both Admin and Students
- **PHP session handling** for authentication

### 2. Student Dashboard (`/student/dashboard.php`)
- View available courses
- Enroll in courses (AJAX + PHP + MySQL)
- View enrolled courses
- Submit feedback for enrolled courses
- View previously submitted feedback

### 3. Course Catalog (`/student/courses.php`)
- Paginated list of all courses
- Course details modal (fetched via AJAX)
- “Enroll” button → triggers AJAX to enroll in course

### 4. Feedback Module
- `/student/feedback.php`  
  - Shows list of courses student is enrolled in  
  - Click **“Give Feedback”** → open form:
    - Course Name (auto-filled)
    - Ratings: Teaching, Interaction, Materials, Overall
    - Comment
  - Submit via AJAX to PHP handler

- `/student/view_feedback.php`  
  - List of submitted feedback entries (AJAX pagination)  
  - Option to delete (optional)

### 5. Admin Panel
- `/admin/dashboard.php`  
  - Total students, courses, feedback count (use PHP queries)

- `/admin/manage_courses.php`  
  - Add/edit/delete courses

- `/admin/manage_students.php`  
  - View student list, deactivate account

- `/admin/view_feedback.php`  
  - Filter by course/faculty/date  
  - Show feedback analytics (e.g., average scores with **Chart.js**)

### 6. Logout
- `/logout.php` (destroys session and redirects)

---

## Database Design (MySQL)

**Tables:**
- `users` (id, name, email, password_hash, role ['admin','student'])
- `courses` (id, title, description, faculty, created_at)
- `enrollments` (id, user_id, course_id, enrolled_at)
- `feedback` (id, user_id, course_id, ratings, comment, created_at)

---

## Security Requirements

- Password hashing using `password_hash()` in PHP  
- SQL Injection protection using **prepared statements**  
- Session handling for authentication  
- Basic access control (admin vs. student)

---

## Features to Showcase AJAX

- Enroll in course **without page reload**  
- Submit feedback via AJAX  
- Load feedback or course data dynamically (e.g., modal popups)  
- Filter/search feedback in admin panel with AJAX

---

## UI/UX Requirements

- Responsive layout (use Flex/Grid in CSS)  
- Form validation (JS + graceful degradation with PHP fallback)  
- Loading spinners or modals for AJAX events  
- Clean, user-friendly interface

---

## Suggested Folder Structure

```
/course-portal/
├── /admin/
│ ├── dashboard.php
│ ├── manage_courses.php
│ ├── view_feedback.php
│ └── manage_students.php
│
├── /student/
│ ├── dashboard.php
│ ├── courses.php
│ ├── feedback.php
│ ├── view_feedback.php
│ └── enroll_ajax.php
│
├── /auth/
│ ├── login.html
│ ├── register.html
│ ├── login.php
│ ├── register.php
│ └── logout.php
│
├── /assets/
│ ├── /css/
│ ├── /js/
│ └── /images/
│
├── /includes/
│ ├── db.php
│ ├── auth.php
│ └── header.php
│
└── README.md
```