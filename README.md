Scientific Articles Database Management System
=============================================

A web-based database system for managing scientific articles, built with PHP, MySQL, and Bootstrap. This project allows users to create, read, update, and delete (CRUD) scientific publications through a user-friendly interface.

Features:
---------
- Full CRUD Operations: Create, view, edit, and delete articles
- Dynamic Author Input: Add multiple authors dynamically
- Search by DOI: Quickly find articles using DOI
- Multi-Table Database: Properly structured with relationships
- Responsive Design: Works on desktop and mobile (Bootstrap)
- Data Validation: Both client-side and server-side
- Backup & Import: Export and import database data

Technologies Used:
-----------------
- XAMPP – Local server environment
- PHP – Server-side scripting
- MySQL – Database management
- phpMyAdmin – Database administration
- Bootstrap 4 – Responsive frontend framework
- jQuery – Dynamic forms and interactions
- PDO – Secure database access

Installation:
-------------
1. Start XAMPP (Apache & MySQL)
2. Copy `ScientificArticlesDB` into `xampp/htdocs/`
3. Open `http://localhost/ScientificArticlesDB/install.php` to create the database
4. Access the app at `http://localhost/ScientificArticlesDB/public/index.php`

Database Structure:
------------------
Tables:
- articole: Stores article info (DOI, title, journal, year, country)
- autori: Stores author info
- domenii: Stores research domains
- coresp1: Article-author relationships
- coresp2: Article-domain relationships
- draft: Stores draft articles

Database Example:
-----------------
CREATE DATABASE newdb;
CREATE TABLE articole (...);
CREATE TABLE autori (...);
...

Usage:
------
1. Create Article: Fill in DOI, title, authors, year, country, journal
2. Find Article: Search by DOI
3. Update Article: Edit and save changes
4. Delete Article: Confirm deletion (cascades to related tables)

Notes:
------
- Backup before major changes
- DOI is unique and cannot be changed
- Use “Other” in dropdowns for new countries or journals

Troubleshooting:
----------------
- Connection failed → Check MySQL and credentials
- Column not found → Run install.php
- Form not submitting → Check browser console and required fields
- Page not loading → Verify file paths and Apache

Learning Outcomes:
-----------------
- PHP & MySQL integration
- Database design and normalization
- CRUD operations
- Bootstrap & jQuery
- Secure coding with PDO

License:
--------
For educational purposes. Free to use and modify.

Author:
-------
Originally developed during undergraduate studies, improved with AI-assisted optimization.
