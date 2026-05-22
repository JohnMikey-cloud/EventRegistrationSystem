# # Event Registration System

A web-based portal designed to streamline event registration, management, and attendance tracking. This system features a dual-interface architecture tailored for both student attendees and administrative coordinators.

1. Core Modules & Team Contributions
- Authentication Module (`/auth`) – Secure user registration, login systems, and role-based session validation.
- Student Dashboard (`/student`) – Profile management, browse upcoming events, real-time registration, and digital certificate generation.
- Admin Module (`/admin`) – Full CRUD (Create, Read, Update, Delete) capabilities for event creation, venue allocation, and attendee roster tracking.
- Global Core Utilities (`/includes`) – Shared database connection configurations, security guards, and global UI headers/footers.

2. Tech Stack Used
- Backend / Logic: PHP
- Database Management: MySql
- Frontend / Web Design: HTML, CSS, and Bootstrap

3. How to Run the Project Locally

Follow these step-by-step instructions to deploy the project on your local machine for evaluation:

1. Server Setup
  1.1 Download or clone this repository to your computer.
  1.2 Move the extracted project folder (`event_system`) into your local XAMPP web server directory:
   - Windows: `C:\xampp\htdocs\`
   - macOS: `/Applications/XAMPP/htdocs/`
  1.3 Open the XAMPP Control Panel and start both Apache and MySQL.

2. Database Migration
  2.1 Open your web browser and navigate to the database management panel: `http://localhost/phpmyadmin/`
  2.2 Click on "New" in the left sidebar to create a fresh database.
  2.3 Name the database exactly: `event_system` and click **Create**.
  2.4 Select your newly created `event_system` database, click on the **Import** tab at the top menu.
  2.5 Click "Choose File" and select the `event_system.sql` file located in the root directory of this project folder.
  2.6 Scroll to the bottom and click Import (Go).

3. Launching the Application
  3.1 Open a new tab in your web browser.
  3.2 Navigate to the application gateway landing page: `http://localhost/event_system/`
  3.3 The system is now fully live and ready for testing!
