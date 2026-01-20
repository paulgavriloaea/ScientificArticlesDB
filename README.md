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

CREATE DATABASE newdb;

CREATE TABLE articole (
doi VARCHAR(30) PRIMARY KEY,
titlu_articol VARCHAR(30) NOT NULL,
tara VARCHAR(30) NOT NULL,
jurnal VARCHAR(30),
anpub INT(4)
) ENGINE=InnoDB;

CREATE TABLE autori (
id INT NOT NULL AUTO_INCREMENT,
nume VARCHAR(30) NOT NULL,
PRIMARY KEY (id)
) ENGINE=INNODB;

CREATE TABLE coresp1(
id_autor INT,
doi_coresp VARCHAR(30),
FOREIGN KEY (id_autor) REFERENCES autori(id) ON DELETE CASCADE ON UPDATE CASCADE,
FOREIGN KEY (doi_coresp) REFERENCES articole(doi) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=INNODB;

CREATE TABLE domenii(
id INT NOT NULL AUTO_INCREMENT,
denumire VARCHAR(30) NOT NULL,
PRIMARY KEY (id)
) ENGINE=INNODB;

CREATE TABLE coresp2(
id_domeniu INT,
doi_coresp VARCHAR(30),
FOREIGN KEY (id_domeniu) REFERENCES domenii(id) ON DELETE CASCADE ON UPDATE CASCADE,
FOREIGN KEY (doi_coresp) REFERENCES articole(doi) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=INNODB;

CREATE TABLE draft(
doi VARCHAR(30),
titlu VARCHAR(30),
tara VARCHAR (30),
jurnal VARCHAR (30),
anpub INT(4),
autori VARCHAR (30),
PRIMARY KEY (doi)
) ENGINE=INNODB;


## Project Structure

ScientificArticlesDB/
├── config.php # Database configuration
├── common.php # Common functions and utilities
├── install.php # Database installation script
├── public/ # Publicly accessible files
│ ├── index.php # Main dashboard
│ ├── create.php # Create article page
│ ├── read.php # Search article page
│ ├── update.php # Update article list page
│ ├── update-single.php # Edit single article
│ └── remove.php # Delete article page
├── templates/ # Reusable templates
│ ├── header.php # Page header
│ └── footer.php # Page footer
└── README.md # Documentation

## Configuration

The database connection is configured in config.php:

<?php $host = "localhost"; $dbname = "newdb"; $username = "root"; # Default XAMPP username $password = ""; # Default XAMPP password (empty) $dsn = "mysql:host=$host;dbname=$dbname"; $options = [ PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION ]; ?>


## Features in Detail

### 1. Create Articles
- Add new scientific articles with all metadata
- Dynamic author input fields (add/remove multiple authors)
- Country and journal selection with "Other" option
- Automatic relationship management between articles and authors

### 2. Find Articles
- Search articles by DOI
- Display complete article information including authors
- Responsive results table

### 3. Update Articles
- Edit existing article details
- Manage author associations
- Update research domains
- Form validation and error handling

### 4. Delete Articles
- Remove articles with confirmation dialog
- Cascade deletion of related records
- Success/failure notifications

## Usage Examples

### Creating an Article:
1. Click "Create entry" from the main dashboard
2. Fill in DOI, title, authors (add multiple if needed)
3. Select year, country, and journal
4. Submit the form

### Searching for an Article:
1. Click "Find" from the main dashboard
2. Enter the DOI of the article
3. View complete details including all authors

### Updating an Article:
1. Click "Update" from the main dashboard
2. Click "Edit" next to the article you want to modify
3. Update the information and save changes

## Important Notes

- Backup your data before making major changes
- DOI is the primary key and cannot be changed after creation
- Authors are managed separately from article details
- Use the "Other" option for new countries/journals not in the dropdown

## Troubleshooting

### Common Issues:

1. "Connection failed" error
   - Ensure MySQL is running in XAMPP
   - Check database credentials in config.php

2. "Column not found" errors
   - Run install.php to create the database structure
   - Check table names and column names in your queries

3. Form not submitting
   - Check JavaScript console for errors
   - Ensure all required fields are filled
   - Verify jQuery is properly loaded

4. Page not loading
   - Verify file paths are correct
   - Check Apache is running in XAMPP
   - Ensure files are in the correct htdocs directory

## Recent Improvements

This project was originally developed during undergraduate studies and recently enhanced with:
- Code optimization and error correction
- Security improvements with PDO prepared statements
- Better user experience with enhanced form handling
- Database normalization with proper relationships
- Responsive design improvements

## Learning Resources

This project demonstrates:
- PHP and MySQL integration
- Database design and normalization
- CRUD operations implementation
- Form handling and validation
- Bootstrap for responsive design
- jQuery for interactive elements

## Contributing

While this is primarily a personal project, suggestions and improvements are welcome. Please ensure any changes maintain the core functionality and database integrity.

## License

This project is for educational purposes. Feel free to use and modify for learning.

## Author

Originally developed during undergraduate studies
Recently enhanced with AI assistance for code optimization and error correction

---

## Quick Start Recap

1. Start XAMPP (Apache & MySQL)
2. Navigate to: localhost/ScientificArticlesDB/install.php
3. Access app at: localhost/ScientificArticlesDB/public/index.php
4. Start managing your scientific articles database!

---

Note: Always backup your database before making structural changes or deleting records.


