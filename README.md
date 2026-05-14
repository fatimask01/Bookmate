# Bookmate - Online Library Management System

A web-based Library Management System developed as a Final Year Project
for BCA (Bachelor of Computer Applications), 2025-26.

---

## Project Overview
Bookmate is a PHP-MySQL based system that streamlines library operations —
managing books, authors, students, and issue/return records through a
clean, role-based web interface.

---

## Project Documentation (Blackbook Index)

| Sr. No. | Content                                                                                                          |  Page No. |
|---------|------------------------------------------------------------------------------------------------------------------|-----------|
|    1    | Synopsis                                                                                                         | 17        |
|    2    | Software Development Life Cycle (SDLC)                                                                           | 24        |
|    3    | System Planning (Gantt Chart)                                                                                    | 30        |
|    4    | System Analysis (Requirement Gathering, Waterfall Model, Feasibility Study, Tools & Technology,                  | 34        |
|         |Software Specification)                                                                                           |           |
|    5    | System Design (Decomposition Diagram, Flow Chart, ER Diagram, Database Schema, UI Screenshots)                   | 47        |
|    6    | System Coding (Code + Validation Code)                                                                           | 59        |
|    7    | System Testing / Quality Assuranc                                                                                | 130       |
|    8    | Implementation (Installation Steps, User Guide)                                                                  | 150       |
|    9    | Conclusion                                                                                                       | 172       |
|    10   | Future Enhancement                                                                                               | 173       |
|    11   | References and Bibliography                                                                                      | 174       |

---

## Features
- Role-based login: Student and Admin
- Add, update, delete Books and Authors
- Student registration and management
- Book issue and return tracking
- Category-wise book listing
- Student self-service: profile, change password, view issued books
- Input validation and quality assurance tested

---

## Tech Stack
| Layer    | Technology         |
|----------|--------------------|
| Frontend | HTML, CSS          |
| Backend  | PHP                |
| Database | MySQL              |
| Server   | Apache (XAMPP)     |
| IDE      | Visual Studio Code |
| DB Tool  | phpMyAdmin         |

---

## Database Structure
Database name: `library`

| Table                | Description               |
|----------------------|---------------------------|
| admin                | Admin credentials         |
| tblauthors           | Author records            |  
| tblbooks             | Book details              |
| tblcategory          | Book categories           |
| tblissuedbookdetails | Issue and return records  |
| tblstudents          | Student records           |

---

## Setup Instructions
1. Install XAMPP and start Apache + MySQL
2. Clone or download this repository
3. Place the folder inside `C:/xampp/htdocs/`
4. Open phpMyAdmin at `localhost/phpmyadmin`
5. Create a database named `library`
6. Import `library.sql` into that database
7. Open browser and go to `localhost/library`

---

## Login Instructions

### Student Accounts
| Username | Password | Email             |
|----------|----------|-------------------|
| SID001   | 1234     | xyz@gmail.com     |
| SID002   | test     | Test123@gmail.com |
| SID003   | block    | block@123         |

### Admin Account
| Username | Password | Email           | 
|----------|----------|-----------------|
| Admin    | admin123 | admin@gmail.com |

> Select the correct Login Type from the dropdown before submitting.

---

## Certifications
- College Certificate (Issued by [College Name])
- Client Certificate (Issued by [Client/Organization Name])

---

## Developer
- **Name:** SHAIKH FATIMA MOINUDDIN
- **Course:** BCA Semester VI
- **Year:** 2026
- **College:** SHRI MD SHAH MAHILA COLLEGE
- **GitHub:** [fatimask01](https://github.com/fatimask01)



---

## License
This project was developed for academic purposes under BCA Final Year Project guidelines.