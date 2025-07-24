# Daily Expense Tracker

A simple web application built with **PHP**, **MySQL**, **HTML**, **CSS**, and **JavaScript** that helps users manage and track their daily expenses.

---

## 🚀 Features

* User registration and login (with Remember Me functionality)
* Add, edit, delete expenses
* View expenses with filters (by date)
* Dashboard with today’s total expenses
* JavaScript form validation for better UX
* Secure password hashing and session handling

---

## 📂 Project Structure

```
daily_expense_tracker/
├── assets/
│   └── css/
│       └── style.css
├── db/
│   └── config.php
├── index.php (Login)
├── register.php
├── dashboard.php
├── add_expense.php
├── edit_expense.php
├── delete_expense.php
├── view_expenses.php
└── logout.php
```

---

## ⚙️ Technologies Used

* Frontend: HTML, CSS, JavaScript
* Backend: PHP
* Database: MySQL (via XAMPP)

---

## ✅ Setup Instructions

1. Clone the repository:

   ```bash
   git clone https://github.com/YOUR_USERNAME/daily_expense_tracker.git
   ```

2. Move the folder into `htdocs` if you're using XAMPP:

   ```
   C:/xampp/htdocs/daily_expense_tracker
   ```

3. Import the SQL file (create your own or use one you've set up):

   * Start **XAMPP** (Apache & MySQL)
   * Open **phpMyAdmin**
   * Create a database named `daily_expense_tracker`
   * Import your table structure or create necessary tables

4. Update database connection in `db/config.php`:

   ```php
   $conn = new mysqli("localhost", "root", "", "daily_expense_tracker");
   ```

5. Visit:

   ```
   http://localhost/daily_expense_tracker/
   ```

---

## ✍️ Author

* \[Mekhna Alphons Joby]

---



This project is for educational purposes only.
