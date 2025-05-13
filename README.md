# Monthly Fee Collection System

A lightweight web-based application to manage and track student monthly fee payments, built using **PHP**, **MySQL**, **HTML**, **CSS**, **Bootstrap**, and **JavaScript**.

---

## 📌 Features

### 👤 Admin Panel
- Secure login/logout
- Dashboard overview
- Manage student records (CRUD)
- Define monthly fee structures by class
- Record fee payments and view history
- Generate printable receipts
- Monthly paid/unpaid reporting
- Export data to CSV

---

## 🛠️ Tech Stack

| Layer       | Technology            |
|-------------|------------------------|
| Frontend    | HTML, CSS, Bootstrap   |
| Scripting   | JavaScript             |
| Backend     | PHP                    |
| Database    | MySQL                  |

---

## 📂 File Structure (Flat Layout)

index.php
login.php
logout.php
dashboard.php
add_student.php
edit_student.php
delete_student.php
view_students.php
fee_structure.php
record_payment.php
view_payments.php
generate_receipt.php
report_paid_unpaid.php
report_student_summary.php
export_report.php
db_connect.php
session.php
functions.php
scripts.js
README.md


---

## ⚙️ Setup Instructions

1. **Clone or Download the Project**

2. **Create a MySQL Database**
   - Create a database in XAMPP with the name "fee_collection"
   - Import the fee_collection.sql file to it .



3. **Run the App**
   - Place the project in your web server's root directory (e.g., `htdocs` if using XAMPP).
   - Visit `http://localhost/your-folder/index.php`.

---

## 🔐 Default Admin Credentials

> (You must insert admin user manually into `users` table. Example: username: `admin`, password: `admin` hashed with `password_hash()`)

---

## 🧰 Future Improvements

- Student login to view payment history
- SMS/email reminders
- PDF receipt/report generation
- AJAX form submissions

---

## 📄 License

This project is for educational use. Feel free to use or modify for your own purposes.
