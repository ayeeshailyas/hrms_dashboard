# Human Resourse Management System Dashboard 

HRMS Dashboard built with **Core PHP** and **MySQL**.  
It provides modules for employee management, attendance tracking, payroll, expenses, and internal notices.

> Note: This project is intended to run **locally**. It is not packaged for production deployment.
---

## Modules / Tabs

### Dashboard

### 1) Settings
- General Setting
- Set Working Days
- Holiday List
- Leave Category
- Personal Event

### 2) Department
- Add Department
- Department List

### 3) Mail Box
- Inbox

### 4) Employee
- Add Employee
- Employee List
- Employee Award

### 5) Attendance
- Manage Attendance
- Attendance Report

### 6) Payroll
- Manage Salary Details
- Employee Salary List
- Make Payment
- Generate Payslip

### 7) Expense
- Add Expense
- Expense Report

### 8) Notice Board
- Notice Board
- Add Notice
- Manage Notice

---

## Tech Stack
- PHP (recommended: 8.0+)
- MySQL / MariaDB
- Apache (XAMPP/WAMP/Laragon recommended for local setup)

---

## Requirements (Local)
- XAMPP / WAMP / Laragon (Apache + MySQL + PHP)
- PHP 8.0+ recommended
- MySQL/MariaDB
- Browser (Chrome)

---

## Database
- SQL file included: **`hrms_dashboard.sql`**

---

## Installation (Local Setup)

### 1) Place the project in your web server directory
**XAMPP (Windows):**
- `C:\xampp\htdocs\hrms-dashboard\`

**Laragon:**
- `C:\laragon\www\hrms-dashboard\`

### 2) Create a database
Open phpMyAdmin and create a database, for example:
- `hrms_dashboard`

### 3) Import the SQL file
In phpMyAdmin:
1. Select the database `hrms_dashboard`
2. Go to **Import**
3. Choose `hrms_dashboard.sql`
4. Click **Go**

### 4) Update database credentials in the project files (Important)
  
Database connection values are written directly inside one or more PHP files.

#### How to find where the DB connection is written
Search the project for common database connection keywords:

- `mysqli_connect`
- `new mysqli`
- `PDO`
- `localhost`
- `DB_HOST`, `DB_USER`, `DB_PASS`, `DB_NAME`

**Option A (VS Code)**
- Press `Ctrl + Shift + F`
- Search: `mysqli_connect` (then `new mysqli`, then `PDO`)
- Update host, username, password, database name accordingly

**Option B (Command line)**
From the project folder:
```bash
grep -R "mysqli_connect" .
grep -R "new mysqli" .
grep -R "PDO" .
```
Then update the credentials in the found file(s), for example:

Host: localhost

Username: root

Password: (empty on many local setups)

Database: hrms_dashboard

## Run the Project

Start Apache & MySQL from XAMPP/WAMP/Laragon, then open:

http://localhost/hrms-dashboard/
(Replace hrms-dashboard with your actual folder name.)

## Notes / Limitations
This repository should contain demo/sample data only. Do not commit real employee data.
For cleaner structure in future, consider moving DB credentials into a single config file and excluding it via .gitignore.
