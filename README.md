# 📚 Library Management System

The **Library Management System** is a web-based library management platform developed using **PHP and MySQL**. It provides separate functionalities and privileges for **Administrators, Librarians, and Users**, making it easier to manage books, users, reservations, issuing, returning, and library notifications.

The system allows users to browse available books and send reservation requests. Librarians can review and accept requests, after which users receive email notifications and can visit the library physically to collect their reserved books.

## 👥 User Roles

### 👨‍💼 Admin

The **Admin** has overall control and privileges throughout the system.

Admin can:

* Manage users and librarians
* Manage books and book records
* Monitor library activities
* Manage and oversee reservations
* Manage issued and returned books
* View and manage user information
* Control important system operations
* Access overall system functionality

### 📖 Librarian

The **Librarian** manages day-to-day library operations.

Librarians can:

* View available books
* Manage book availability
* View reservation requests
* Accept or manage book requests
* Issue books to users
* Manage book returns
* Monitor issued books
* Track due dates
* Assist users with book collection and returns

### 👤 User

Users can:

* Register and log in to the system
* Browse available books
* Search for books
* View book details
* Send book reservation requests
* Check the status of their requests
* Receive email notifications
* Visit the library to collect an approved book
* View currently issued books
* Track return/due dates
* View collected and previously issued books
* Manage their profile

## 🔄 Book Reservation & Collection Workflow

The system follows a simple reservation process:

```text
User Logs In
     ↓
Browse Available Books
     ↓
Select a Book
     ↓
Send Reservation Request
     ↓
Librarian Reviews Request
     ↓
Request Accepted
     ↓
Email Notification Sent
     ↓
User Visits Library
     ↓
Book is Physically Collected / Issued
     ↓
User Returns Book Before Due Date
```

The system separates the **online reservation process** from the **physical collection of books**. Users reserve books through the website and then visit the library to collect them after approval.

## 📧 Email Notification System

The system uses **PHPMailer** for email functionality.

Email notifications are used for important library activities, including:

* 📩 Reservation request notifications
* ✅ Notification when a book request is accepted
* 📚 Book collection/issue notifications
* ⏰ Due-date reminders
* ⚠️ Alerts when a book has passed its return date
* 🔔 Other important library-related notifications

This helps keep users informed about the status of their books and requests.

## ⏰ Book Return & Due-Date Alerts

The system keeps track of book due dates.

When a book reaches or passes its return date, the system can alert the user to return the book to the library. This helps the library keep track of issued books and encourages users to return books on time.

## 🔐 Access Control

Different users have different privileges within the system:

```text
                    ┌───────────────┐
                    │     ADMIN     │
                    │ Overall Access│
                    └───────┬───────┘
                            │
              ┌─────────────┴─────────────┐
              ↓                           ↓
      ┌───────────────┐           ┌───────────────┐
      │   LIBRARIAN   │           │     USER      │
      │ Library       │           │ Books &       │
      │ Operations    │           │ Reservations  │
      └───────────────┘           └───────────────┘
```

## 🛠️ Technologies Used

* **PHP** — Backend and application logic
* **MySQL** — Database management
* **HTML5** — Page structure
* **CSS3** — Styling and interface
* **JavaScript** — Client-side functionality
* **PHPMailer** — Email notifications and communication
* **Composer** — PHP dependency management
* **WAMP/XAMPP** — Local development environment

## ⭐ Key Features

* 🔐 Admin, Librarian, and User roles
* 📚 Book management
* 🔎 Book browsing and searching
* 📖 Book reservation system
* ✅ Reservation approval system
* 📧 Automated email notifications
* ⏰ Due-date tracking
* 🔔 Overdue book alerts
* 📕 Issued book management
* 🔄 Book return management
* 👤 User profile management
* 📊 Library activity management
* 🛡️ Role-based access control

## 🎯 Project Objective

The main objective of this project is to **digitize and simplify library operations** while maintaining the physical collection process.

Users can conveniently search and reserve books online, while librarians and administrators can manage requests, books, issues, returns, and notifications through the system.

The project provides a centralized platform for managing library resources and improving communication between **users, librarians, and administrators**.

## 🚀 Future Enhancements

Possible future improvements include:

* 📱 Mobile-friendly/PWA version
* 📊 Advanced library analytics
* 🔍 Advanced book search and filtering
* 📷 Barcode/QR code-based book issuing
* 📅 Online appointment for book collection
* 💳 Fine calculation and payment management
* 📈 Detailed admin reports
* 🔔 Push notifications
* ☁️ Cloud deployment
* 📚 Book recommendation system

---

## 📌 Project Status

🚧 **Library Management System is an ongoing project.**

The system is designed to provide a complete platform for managing library books, reservations, users, librarians, issuing, returns, and email-based notifications.

### 💡 Built to make library management simpler, faster, and more organized.

**Browse → Reserve → Get Approved → Collect → Read → Return**
