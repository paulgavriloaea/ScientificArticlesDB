# Scientific Articles Database Management System

A comprehensive database management system for scientific articles, built with PHP, MySQL, and Bootstrap. This project allows users to manage scientific publications through a web interface with full CRUD operations.

## Features

- Complete CRUD Operations: Create, read, update, and delete scientific articles
- Multi-table Database: Structured database with proper relationships
- Dynamic Forms: Add multiple authors dynamically
- Search Functionality: Find articles by DOI
- User-Friendly Interface: Responsive design with Bootstrap
- Data Validation: Client and server-side validation
- Export/Import: Database backup capabilities

## Technologies Used

- XAMPP - Local server environment
- PHP - Server-side scripting
- MySQL - Database management
- phpMyAdmin - Database administration
- Bootstrap 4 - Frontend framework
- jQuery - JavaScript library for dynamic interactions
- PDO - Secure database access

## Installation & Setup

### Prerequisites
- XAMPP installed on your system
- Web browser (Chrome, Firefox, etc.)

### Step-by-Step Installation

1. Start XAMPP Services
   - Launch XAMPP Control Panel
   - Start Apache and MySQL services

2. Place Project Files
   - Copy the ScientificArticlesDB directory to:
     XAMPP/xamppfiles/htdocs/

3. Create Database
   - Open your browser and navigate to:
     http://localhost/ScientificArticlesDB/install.php
   - This will automatically create the MySQL database structure

4. Access the Application
   - Visit the main control panel:
     http://localhost/ScientificArticlesDB/public/index.php

## Database Structure

### Main Tables:

- articole - Stores article information (DOI, title, journal, year, country)
- autori - Stores author information
- domenii - Stores research domains
- coresp1 - Junction table for article-author relationships
- coresp2 - Junction table for article-domain relationships
- draft - Stores draft articles

### Database Schema:
