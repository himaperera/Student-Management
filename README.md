# EduPanel — Student Management System

**EduPanel** is a modern, web-based administrative dashboard designed to manage student records efficiently. This project focuses on a clean User Experience (UX) while implementing full CRUD (Create, Read, Update, Delete) functionality using a traditional web stack.



## 🚀 Key Features

* **Administrative Access:** Secure login portal for authorized users.
* **Dynamic Dashboard:** Real-time summary of total students and active enrollments using informative stat cards.
* **Student Management (CRUD):**
    * **Create:** Add new students with specific details like Faculty and Contact info.
    * **Read:** View all student records in a structured, paginated table.
    * **Update:** Modify existing student information through a dedicated edit interface.
    * **Delete:** Remove records with a single click.
* **Responsive UI:** A professional "Glassmorphism" design style created with custom CSS for a modern feel.

## 🛠️ Tech Stack

| Layer | Technology |
| :--- | :--- |
| **Frontend** | HTML5, CSS3 (Custom Styling) |
| **Backend** | PHP |
| **Database** | MySQL |
| **Environment** | XAMPP / Apache Server |

---

## 📸 Screenshots

### 1. Admin Login Portal
The entrance to the system featuring a sleek, centered login card with input validation.
[Login Screen]
<img width="1920" height="1080" alt="Screenshot (86)" src="https://github.com/user-attachments/assets/5aaadbaa-fbb8-4664-a1b4-9e4f46a6f2ce" />


### 2. Main Dashboard & Record List
Overview of system statistics and the primary "All Students" data table.

[Dashboard]
<img width="1911" height="923" alt="image" src="https://github.com/user-attachments/assets/7d0ecd44-403f-4082-bc89-d9df914fa71c" />


### 3. Edit Record Interface
A specific view to update student details mapped to their unique ID.


[Edit Student]
<img width="1913" height="925" alt="image" src="https://github.com/user-attachments/assets/8b78dff9-16d3-4e1c-a9a5-cd182a774957" />


---

## ⚙️ Setup & Installation

1.  **Clone the Repository**
    ```bash
    git clone [https://github.com/your-username/EduPanel.git](https://github.com/your-username/EduPanel.git)
    ```

2.  **Database Configuration**
    * Open **PHPMyAdmin**.
    * Create a database named `edupanel_db`.
    * Import the provided `.sql` file (if available) or create a table named `students` with columns: `id`, `name`, `email`, `phone`, and `course`.

3.  **Configure Connection**
    * Update your connection settings in your PHP config file:
    ```php
    $conn = mysqli_connect("localhost", "root", "", "edupanel_db");
    ```

4.  **Run Locally**
    * Move the project folder to `C:\xampp\htdocs\`.
    * Start Apache and MySQL in XAMPP.
    * Visit `http://localhost/EduPanel/login.php`.

---

## 🧑‍💻 Author
**[Your Name]** *Software Engineering Student at NSBM Green University*

---
