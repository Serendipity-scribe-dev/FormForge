# FormForge - AI Form Generator using PHP backend
An intelligent web-based tool that generates complete working forms using natural language prompts.
It leverages the Gemini API to automatically create:
 - ✅ Frontend + Backend PHP code
 - ✅ MySQL Database schema
 - ✅ Setup instructions
 - ✅ Downloadable project files

---
 ## Features
  - ✨ Generate full-stack forms from a simple prompt
  - 📦 Download ready-to-use .php and .sql files
  - 🧩 Automatically matches database schema with PHP logic
  - 🗂️ Organized file generation with timestamped folders
  - 🔐 Secure file download handling
  - ⚡ Fast generation using Gemini Flash model

---
## Tech Stack 
 - Frontend: HTML, CSS, JavaScript
 - Backend: PHP
 - Database: MySQL
 - AI API: Google Gemini API
---
## Project Structure 
```
form_generator/
│
├── index.php # UI for prompt input
├── generate.php # Calls Gemini API & generates files
├── download.php # Handles secure file download
│
├── generated/ # Stores generated projects
│   └── <timestamp_folder>/ │
        ├── form.php
│       └── database.sql
└── README.md
```
---
## Setup Instructions 
1️⃣ Clone or Download the Project

Place the project inside your server directory:
 - XAMPP → htdocs/
 - WAMP → www/

2️⃣ Add Your Gemini API Key

Open generate.php and replace: $apiKey = "YOUR_API_KEY";

3️⃣ Start Server
 - Start Apache + MySQL
 - Open browser:
```http://localhost/form_generator/```

4️⃣ Generate Form

Enter prompt (example):

 - Order form with product, quantity, address and payment
 - Click Generate

5️⃣ Download Files
 - Download:
   - form.php
   - database.sql

6️⃣ Setup Database
 - Open phpMyAdmin
 - Import database.sql
7️⃣ Run Generated Form

Place form.php in your server folder and open: ```http://localhost/form.php```

---
## Example prompt
User registration form with name, email, password and mobile number

---

## Screenshots
<img width="1887" height="893" alt="Screenshot 2026-05-03 230015" src="https://github.com/user-attachments/assets/427d5ed5-dd4d-4d2f-aac0-8d8927d13e0f" />
<img width="1919" height="965" alt="Screenshot 2026-05-03 223030" src="https://github.com/user-attachments/assets/b2b57cf5-6be3-4974-8ab7-1fd6eaa4cdec" />
<img width="844" height="865" alt="Screenshot 2026-05-03 222905" src="https://github.com/user-attachments/assets/8b2d14c1-95ba-4d40-b7c6-ce6eff02dd50" />
<img width="654" height="635" alt="image" src="https://github.com/user-attachments/assets/f38cdb76-dc9f-464c-bbfa-963dbfb433ea" />
<img width="819" height="518" alt="Screenshot 2026-05-03 222959" src="https://github.com/user-attachments/assets/4e5fa3e2-d007-4bb9-8fa1-8664383d8ff6" />

---
## 👨‍💻License 
Project created by Nandisa Das, PBA Institute.
