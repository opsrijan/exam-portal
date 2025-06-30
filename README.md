# 📝 Exam Portal Website

An online examination portal built using **HTML**, **CSS**, **PHP**, and **MySQL**. This system enables students to register, log in, take timed multiple-choice exams, and view results. Admins can manage exams, questions, and monitor student performance.

---

## 🔧 Tech Stack

- **Frontend**: HTML5, CSS3  
- **Backend**: PHP 7+  
- **Database**: MySQL  
- **Server**: Apache (Recommended: XAMPP or LAMP stack)

---

## 🚀 Features

### 👨‍🎓 Student Panel
- Student registration and login
- View available exams
- Take exams with timer support
- See score and performance report

### 🛠️ Admin Panel
- Admin login
- Create and manage exams
- Add/edit/delete questions
- View student attempts and results

---

## 🛠️ Installation & Setup

### 1. Clone the Repository

git clone https://github.com/<your-username>/exam-portal.git
cd exam-portal

### 2. Move to Server Directory
Place the project inside your XAMPP htdocs folder or LAMP www directory.

### 3. Configure the Database
Start Apache and MySQL using XAMPP or your preferred stack.

Open phpMyAdmin and import the provided examportal.sql file.

Update database credentials in includes/db_connect.php if necessary:
// includes/db_connect.php
$host = "localhost";
$user = "root";
$password = "";
$database = "exam_portal";

### 4. Access the Portal
Open a browser and go to:
http://localhost/exam-portal/

### 🔐 Default Admin Credentials
Username: admin
Password: admin123

(You can change this in the MySQL database after login.)


📄 License
This project is licensed under the MIT License.
You are free to use, modify, and distribute it with attribution.

🤝 Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss your ideas.
