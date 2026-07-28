## 📄 Project Documentation

This repository includes a complete technical documentation for the **Filipino Cookbook API**. The documentation provides a comprehensive guide to the project, including the system overview, installation procedures, project structure, API endpoint documentation, testing, troubleshooting, Git and GitHub workflow, and other supporting technical information.

You can access and download the complete documentation here:

**➡️ [Filipino Cookbook API Documentation (PDF)](./Filipino_Cookbook_API_Documentation.pdf)**

> **Note:** If the PDF opens in your browser, click the **Download** button to save a copy locally.

# Filipino Cookbook API

A secure RESTful API developed using **PHP**, **Slim Framework 4**, and **MySQL** that provides structured access to Filipino recipes, food categories, ingredients, and origins. The API follows REST principles, returns JSON responses, and uses API Key Authentication to protect secured endpoints.

This project was developed as part of the **BS Information Technology** program to demonstrate the implementation of CRUD operations, API security, database integration, and proper software development practices.

---

# Table of Contents

- [API Description](#api-description)
- [Objectives](#objectives)
- [Features](#features)
- [Technologies Used](#technologies-used)
- [Project Structure](#project-structure)
- [Folder Description](#folder-description)
- [System Requirements](#system-requirements)
- [Installation Guide](#installation-guide)
- [Project Setup](#project-setup)

---

# API Description

The **Filipino Cookbook API** is a RESTful web service built using **PHP**, **Slim Framework 4**, and **MySQL**. It serves as the backend of a Filipino cookbook application by providing endpoints for managing recipes, categories, ingredients, and food origins.

The API allows authorized client applications to:

- Retrieve all Filipino food records
- Retrieve a specific food by its ID
- Search foods by name
- Retrieve a random Filipino food
- Retrieve food categories
- Retrieve ingredients
- Retrieve food origins
- Create new food records
- Update existing food records
- Delete food records

All API responses are returned in **JSON format**, making the project suitable for web, desktop, and mobile applications.

To improve security and reliability, the project implements:

- API Key Authentication
- Prepared SQL Statements
- JSON Responses
- Proper HTTP Status Codes
- Exception Handling
- Modular Route Organization

---

# Objectives

This project aims to develop a secure RESTful API that manages Filipino recipe data while demonstrating proper API development practices.

Specifically, the project aims to:

- Develop RESTful endpoints for managing cookbook data.
- Implement Create, Read, Update, and Delete (CRUD) operations.
- Protect secured endpoints using API Key Authentication.
- Return standardized JSON responses.
- Connect the API to a MySQL database using PDO.
- Organize the application using the Slim Framework routing system.
- Test every endpoint using Thunder Client.

---

# Features

| Feature | Description |
|----------|-------------|
| Retrieve All Foods | Returns all Filipino food records stored in the database. |
| Retrieve Food by ID | Retrieves a specific food using its unique ID. |
| Search Foods | Searches foods using a complete or partial food name. |
| Retrieve Random Food | Returns one randomly selected Filipino dish. |
| Retrieve Categories | Returns all available food categories. |
| Retrieve Ingredients | Returns all available ingredients. |
| Retrieve Origins | Returns all available food origins. |
| Add New Food | Creates a new Filipino food record. |
| Update Food | Updates an existing food record. |
| Delete Food | Deletes an existing food record. |
| API Key Authentication | Restricts unauthorized access to protected endpoints. |
| JSON Responses | Returns structured JSON responses for every request. |
| Prepared Statements | Helps protect the database against SQL Injection attacks. |
| Organized Routes | Separates API endpoints into dedicated route files for maintainability. |

---

# Technologies Used

| Technology | Purpose |
|------------|---------|
| PHP 8.x | Server-side programming language |
| Slim Framework 4 | RESTful API Framework |
| MySQL | Relational Database Management System |
| PDO | Secure database connectivity |
| Composer | PHP dependency manager |
| Apache | Local web server |
| XAMPP | Local development environment |
| JSON | API request and response format |
| Thunder Client | API testing |
| Git | Version control |
| GitHub | Repository hosting |
| Visual Studio Code | Source code editor |

---

# Project Structure

```text
filipino-cookbook-api/
│
├── config/
│   └── database.php
│
├── middleware/
│   └── ApiKeyMiddleware.php
│
├── public/
│   ├── .htaccess
│   └── index.php
│
├── routes/
│   ├── categoryRoutes.php
│   ├── foodRoutes.php
│   ├── ingredientRoutes.php
│   └── originRoutes.php
│
├── storage/
│
├── vendor/
│
├── composer.json
├── composer.lock
└── README.md
```

---

# Folder Description

| Folder/File | Description |
|-------------|-------------|
| **config/** | Contains the database connection configuration used by the application. |
| **middleware/** | Contains the API Key Authentication middleware that protects secured endpoints. |
| **public/** | Public web directory containing the application's entry point and Apache rewrite configuration. |
| **routes/** | Contains all API endpoint definitions grouped by resource. |
| **storage/** | Reserved for application storage and future enhancements. |
| **vendor/** | Composer-generated directory containing Slim Framework and other project dependencies. |
| **composer.json** | Defines all PHP packages required by the project. |
| **composer.lock** | Stores the exact versions of installed Composer packages. |
| **README.md** | Contains the project's installation guide and technical documentation. |

---

# Route Overview

| Route File | Description |
|------------|-------------|
| **foodRoutes.php** | Handles CRUD operations for Filipino food records. |
| **categoryRoutes.php** | Retrieves food categories. |
| **ingredientRoutes.php** | Retrieves available ingredients. |
| **originRoutes.php** | Retrieves food origins. |

---

# System Requirements

Before running the project, make sure the following software is installed.

| Software | Required |
|----------|:--------:|
| Windows 10 or Windows 11 | ✔ |
| PHP 8.x | ✔ |
| Apache | ✔ |
| MySQL | ✔ |
| XAMPP | ✔ |
| Composer | ✔ |
| Git | ✔ |
| Visual Studio Code | ✔ |
| Thunder Client | ✔ |

---

# Installation Guide

Install the following software before setting up the project:

1. **Visual Studio Code**
   - Download from: https://code.visualstudio.com/

2. **XAMPP**
   - Download from: https://www.apachefriends.org/
   - Install using the recommended settings.
   - Start **Apache** and **MySQL** from the XAMPP Control Panel.

3. **Composer**
   - Download from: https://getcomposer.org/
   - During installation, select:
     ```text
     C:\xampp\php\php.exe
     ```
   - Verify the installation:
     ```cmd
     composer --version
     ```

4. **Git**
   - Download from: https://git-scm.com/
   - Verify the installation:
     ```cmd
     git --version
     ```

---

# Project Setup

## Method 1 – Clone the Repository (Recommended)

Open **Command Prompt** and navigate to the XAMPP web directory.

```cmd
cd /d C:\xampp\htdocs
```

Clone the repository.

```cmd
git clone https://github.com/YOUR_GITHUB_USERNAME/filipino-cookbook-api.git
```

Navigate to the project directory.

```cmd
cd filipino-cookbook-api
```

Verify that the project files were downloaded successfully.

```cmd
dir
```

## Method 2 – Download as ZIP

1. Open the GitHub repository.
2. Click the **Code** button.
3. Select **Download ZIP**.
4. Extract the ZIP file.
5. Move the extracted folder to:

```text
C:\xampp\htdocs
```

## Open the Project

Launch **Visual Studio Code**, then open the project folder.

```text
C:\xampp\htdocs\filipino-cookbook-api
```

Open a terminal and confirm that you are inside the project directory.

```cmd
dir
```

# Composer Installation

The Filipino Cookbook API uses **Composer** to manage project dependencies, including the Slim Framework and its required packages.

## Install Project Dependencies

Open **Command Prompt** and navigate to the project folder.

```cmd
cd /d C:\xampp\htdocs\filipino-cookbook-api
```

Install all required dependencies.

```cmd
composer install
```

Composer will automatically:

- Read the `composer.json` file.
- Download the required Slim Framework packages.
- Create the `vendor` directory.
- Generate the Composer autoloader.

After installation, the project directory should contain the following folder:

```text
vendor/
```

---

## Verify the Installation

Check that Slim Framework has been installed correctly.

```cmd
composer show slim/slim
```

Verify the installed PSR-7 package.

```cmd
composer show slim/psr7
```

List all installed Composer packages.

```cmd
composer show
```

If Composer is installed correctly, information about the installed packages will be displayed.

---

# Database Configuration

The application connects to a MySQL database using PHP Data Objects (PDO).

The database connection settings are stored in:

```text
config/database.php
```

Configure the database according to your local XAMPP installation.

Example configuration:

```php
$host = "localhost";
$dbname = "filipino_cookbook_api";
$username = "root";
$password = "";
```

> **Note**
>
> The default XAMPP installation uses **root** as the MySQL username with no password.

---

# Database Setup

## Step 1 — Start MySQL

Open the **XAMPP Control Panel**.

Start the following services:

- Apache
- MySQL

Both services should display a green status indicator.

---

## Step 2 — Open phpMyAdmin

Open your browser.

Navigate to:

```text
http://localhost/phpmyadmin
```

---

## Step 3 — Create the Database

Click **New**.

Create a database named:

```text
filipino_cookbook_api
```

Use the default collation.

Click **Create**.

---

## Step 4 — Import the Database

Select the newly created database.

Click the **Import** tab.

Choose the project's SQL database file.

Click **Go**.

After the import is complete, verify that all tables were created successfully.

The imported database should contain tables similar to:

- foods
- categories
- ingredients
- origins
- food_ingredients

---

# Database Relationships

The API uses a relational database structure.

```text
categories
      │
      ▼
    foods
      ▲
      │
origins

foods
   │
   ▼
food_ingredients
   ▲
   │
ingredients
```

Relationship summary:

- One category can contain multiple foods.
- One origin can be associated with multiple foods.
- One food can contain multiple ingredients.
- One ingredient can belong to multiple foods.

The many-to-many relationship between foods and ingredients is handled through the `food_ingredients` junction table.

---

# Running the API

There are two supported methods for running the project.

## Option 1 — Using XAMPP (Recommended)

Ensure that Apache and MySQL are running.

Open the following URL:

```text
http://localhost/filipino-cookbook-api/public/
```

If your project folder has a different name, replace `filipino-cookbook-api` with your folder name.

---

## Option 2 — PHP Built-in Server

Open Command Prompt.

Navigate to the project folder.

```cmd
cd /d C:\xampp\htdocs\filipino-cookbook-api
```

Start the PHP development server.

```cmd
php -S localhost:8080 -t public
```

Open the API using:

```text
http://localhost:8080
```

---

# Base URL

Depending on how the project is executed, use one of the following base URLs.

| Environment | Base URL |
|-------------|----------|
| XAMPP | `http://localhost/filipino-cookbook-api/public` |
| PHP Built-in Server | `http://localhost:8080` |

All endpoint URLs are appended to the selected base URL.

Example:

```text
GET http://localhost/filipino-cookbook-api/public/api/foods
```

or

```text
GET http://localhost:8080/api/foods
```

---

# Authentication

The Filipino Cookbook API protects its secured endpoints using **API Key Authentication**.

Every protected request must include a valid API key in the request headers.

Example:

```http
x-api-key: YOUR_API_KEY
Accept: application/json
```

> Replace `YOUR_API_KEY` with the actual API key configured in your application.

Requests without a valid API key will be rejected.

Example response:

```json
{
    "status": "error",
    "message": "Unauthorized access."
}
```

HTTP Status:

```text
401 Unauthorized
```

---

# Testing the API with Thunder Client

Open **Visual Studio Code**.

Launch **Thunder Client**.

Create a new request.

Select the desired HTTP method.

Enter the endpoint URL.

Example:

```text
GET http://localhost/filipino-cookbook-api/public/api/foods
```

Add the required request headers.

```http
x-api-key: YOUR_API_KEY
Accept: application/json
```

For POST and PUT requests, also include:

```http
Content-Type: application/json
```

If the endpoint requires a request body, open the **Body** tab, select **JSON**, then enter the required JSON payload.

Click **Send**.

Verify that the response includes:

- Correct HTTP Status Code
- Valid JSON Response
- Expected Data
- Appropriate Success or Error Message

# API Endpoint Documentation

The following endpoints are available in the Filipino Cookbook API.

---

# API Root

Returns a welcome message indicating that the API is running.

## Endpoint

```http
GET /
```

## URL

```text
http://localhost/filipino-cookbook-api/public/
```

## Authentication

Not Required

## Headers

```http
Accept: application/json
```

## Example Request

```http
GET http://localhost/filipino-cookbook-api/public/
```

## Successful Response

**HTTP Status**

```text
200 OK
```

```json
{
    "message": "Welcome to the Filipino Cookbook API"
}
```

---

# Retrieve All Foods

Returns all Filipino food records stored in the database.

## Endpoint

```http
GET /api/foods
```

## Authentication

Required

## Headers

```http
x-api-key: YOUR_API_KEY
Accept: application/json
```

## Example Request

```http
GET http://localhost/filipino-cookbook-api/public/api/foods
```

## Successful Response

**HTTP Status**

```text
200 OK
```

```json
[
    {
        "food_id": 1,
        "food_name": "Adobo",
        "category_name": "Main Dish",
        "origin_name": "Philippines",
        "instructions": "Cook chicken with soy sauce, vinegar, garlic, and spices.",
        "ingredients": [
            "Chicken",
            "Soy Sauce",
            "Vinegar",
            "Garlic"
        ]
    }
]
```

## Error Response

```text
401 Unauthorized
```

```json
{
    "status":"error",
    "message":"Unauthorized access."
}
```

---

# Retrieve Food by ID

Returns a specific food using its ID.

## Endpoint

```http
GET /api/foods/{id}
```

## Path Parameter

| Parameter | Type | Description |
|-----------|------|-------------|
| id | Integer | Food ID |

## Authentication

Required

## Headers

```http
x-api-key: YOUR_API_KEY
Accept: application/json
```

## Example Request

```http
GET http://localhost/filipino-cookbook-api/public/api/foods/1
```

## Successful Response

```text
200 OK
```

```json
{
    "food_id":1,
    "food_name":"Adobo",
    "category_name":"Main Dish",
    "origin_name":"Philippines",
    "instructions":"Cook chicken with soy sauce, vinegar, garlic, and spices.",
    "ingredients":[
        "Chicken",
        "Soy Sauce",
        "Vinegar",
        "Garlic"
    ]
}
```

## Error Response

```text
404 Not Found
```

```json
{
    "status":"error",
    "message":"Food not found."
}
```

---

# Search Food

Searches foods using a full or partial name.

## Endpoint

```http
GET /api/foods/search/{name}
```

## Path Parameter

| Parameter | Type | Description |
|-----------|------|-------------|
| name | String | Food name |

## Authentication

Required

## Headers

```http
x-api-key: YOUR_API_KEY
Accept: application/json
```

## Example Request

```http
GET http://localhost/filipino-cookbook-api/public/api/foods/search/adobo
```

## Successful Response

```text
200 OK
```

```json
[
    {
        "food_id":1,
        "food_name":"Adobo",
        "category_name":"Main Dish",
        "origin_name":"Philippines"
    }
]
```

## Error Response

```text
404 Not Found
```

```json
{
    "status":"error",
    "message":"Food not found."
}
```

---

# Retrieve Categories

Returns all food categories.

## Endpoint

```http
GET /api/categories
```

## Authentication

Required

## Headers

```http
x-api-key: YOUR_API_KEY
Accept: application/json
```

## Example Request

```http
GET http://localhost/filipino-cookbook-api/public/api/categories
```

## Successful Response

```json
[
    {
        "category_id":1,
        "category_name":"Main Dish"
    },
    {
        "category_id":2,
        "category_name":"Dessert"
    }
]
```

---

# Retrieve Ingredients

Returns all available ingredients.

## Endpoint

```http
GET /api/ingredients
```

## Authentication

Required

## Headers

```http
x-api-key: YOUR_API_KEY
Accept: application/json
```

## Example Request

```http
GET http://localhost/filipino-cookbook-api/public/api/ingredients
```

## Successful Response

```json
[
    {
        "ingredient_id":1,
        "ingredient_name":"Chicken"
    },
    {
        "ingredient_id":2,
        "ingredient_name":"Soy Sauce"
    }
]
```

---

# Retrieve Origins

Returns all available food origins.

## Endpoint

```http
GET /api/origins
```

## Authentication

Required

## Headers

```http
x-api-key: YOUR_API_KEY
Accept: application/json
```

## Example Request

```http
GET http://localhost/filipino-cookbook-api/public/api/origins
```

## Successful Response

```json
[
    {
        "origin_id":1,
        "origin_name":"Ilocos Region"
    },
    {
        "origin_id":2,
        "origin_name":"Bicol Region"
    }
]
```

---

# Retrieve Random Food

Returns one randomly selected Filipino food.

## Endpoint

```http
GET /api/foods/random
```

## Authentication

Required

## Headers

```http
x-api-key: YOUR_API_KEY
Accept: application/json
```

## Example Request

```http
GET http://localhost/filipino-cookbook-api/public/api/foods/random
```

## Successful Response

```json
{
    "food_id":3,
    "food_name":"Sinigang",
    "category_name":"Soup",
    "origin_name":"Philippines",
    "ingredients":[
        "Pork",
        "Tomato",
        "Tamarind"
    ]
}
```
# Add New Food

Creates a new Filipino food record and stores it in the database.

## Endpoint

```http
POST /api/foods
```

## Authentication

Required

## Headers

```http
x-api-key: YOUR_API_KEY
Accept: application/json
Content-Type: application/json
```

## Request Body

```json
{
    "food_name": "Chicken Adobo",
    "category_id": 1,
    "origin_id": 1,
    "instructions": "Marinate the chicken with soy sauce, vinegar, garlic, and spices. Simmer until fully cooked.",
    "ingredients": [
        "Chicken",
        "Soy Sauce",
        "Vinegar",
        "Garlic"
    ]
}
```

## Request Body Parameters

| Field | Type | Required | Description |
|---------|------|:--------:|-------------|
| food_name | String | ✔ | Name of the Filipino food |
| category_id | Integer | ✔ | Existing category ID |
| origin_id | Integer | ✔ | Existing origin ID |
| instructions | String | ✔ | Cooking instructions |
| ingredients | Array | ✔ | List of ingredient names |

## Example Request

```http
POST http://localhost/filipino-cookbook-api/public/api/foods
```

## Successful Response

**HTTP Status**

```text
201 Created
```

```json
{
    "status": "success",
    "message": "Food added successfully."
}
```

## Error Responses

### Duplicate Food

```text
409 Conflict
```

```json
{
    "status": "error",
    "message": "Food already exists."
}
```

### Invalid Data

```text
400 Bad Request
```

```json
{
    "status": "error",
    "message": "Invalid or incomplete food data."
}
```

---

# Update Food

Updates an existing Filipino food record.

## Endpoint

```http
PUT /api/foods/{id}
```

## Path Parameter

| Parameter | Type | Description |
|-----------|------|-------------|
| id | Integer | Food ID |

## Authentication

Required

## Headers

```http
x-api-key: YOUR_API_KEY
Accept: application/json
Content-Type: application/json
```

## Request Body

```json
{
    "food_name": "Updated Chicken Adobo",
    "category_id": 1,
    "origin_id": 1,
    "instructions": "Cook the chicken until tender using soy sauce, vinegar, garlic, and seasonings.",
    "ingredients": [
        "Chicken",
        "Soy Sauce",
        "Vinegar",
        "Garlic",
        "Bay Leaf"
    ]
}
```

## Example Request

```http
PUT http://localhost/filipino-cookbook-api/public/api/foods/1
```

## Successful Response

**HTTP Status**

```text
200 OK
```

```json
{
    "status": "success",
    "message": "Food updated successfully."
}
```

## Error Responses

### Food Not Found

```text
404 Not Found
```

```json
{
    "status": "error",
    "message": "Food not found."
}
```

### Invalid Request

```text
400 Bad Request
```

```json
{
    "status": "error",
    "message": "Invalid or incomplete food data."
}
```

---

# Delete Food

Deletes an existing Filipino food record.

## Endpoint

```http
DELETE /api/foods/{id}
```

## Path Parameter

| Parameter | Type | Description |
|-----------|------|-------------|
| id | Integer | Food ID |

## Authentication

Required

## Headers

```http
x-api-key: YOUR_API_KEY
Accept: application/json
```

## Example Request

```http
DELETE http://localhost/filipino-cookbook-api/public/api/foods/10
```

## Successful Response

**HTTP Status**

```text
200 OK
```

```json
{
    "status": "success",
    "message": "Food deleted successfully."
}
```

## Error Response

```text
404 Not Found
```

```json
{
    "status": "error",
    "message": "Food not found."
}
```

---

# HTTP Status Codes

The API returns standard HTTP status codes to indicate the result of each request.

| Status Code | Description | Usage |
|------------:|-------------|-------|
| **200 OK** | Request completed successfully. | Successful GET, PUT, DELETE requests |
| **201 Created** | A new resource was successfully created. | POST requests |
| **400 Bad Request** | Invalid or incomplete request data. | Invalid JSON or missing fields |
| **401 Unauthorized** | API Key is missing or invalid. | Authentication failure |
| **404 Not Found** | Requested resource does not exist. | Invalid endpoint or missing food |
| **405 Method Not Allowed** | Unsupported HTTP method. | Incorrect request method |
| **409 Conflict** | Resource already exists. | Duplicate food record |
| **500 Internal Server Error** | Unexpected server or database error. | Database or application exceptions |

---

# Testing the API

All API endpoints were tested using **Thunder Client** in Visual Studio Code.

Each endpoint was verified to ensure:

- Correct HTTP method
- Proper authentication
- Valid JSON response
- Appropriate HTTP status code
- Correct database operation
- Proper error handling

The project includes screenshots demonstrating successful testing of all implemented endpoints.

---


```text
screenshots/
│
├── 01-home.png
├── 02-get-all-foods.png
├── 03-get-food-by-id.png
├── 04-search-food.png
├── 05-get-categories.png
├── 06-get-ingredients.png
├── 07-get-origins.png
├── 08-random-food.png
├── 09-add-food.png
├── 10-update-food.png
├── 11-delete-food.png
├── 12-invalid-api-key.png
├── 13-food-not-found.png
└── 14-invalid-request.png
```

---

# Example Testing Evidence

| Screenshot | Description |
|------------|-------------|
| Home Endpoint | Shows the API welcome message. |
| Retrieve All Foods | Displays all available Filipino food records. |
| Retrieve Food by ID | Retrieves a specific food record. |
| Search Food | Demonstrates searching for foods by name. |
| Categories | Displays all available food categories. |
| Ingredients | Displays all available ingredients. |
| Origins | Displays all available food origins. |
| Random Food | Returns a randomly selected Filipino food. |
| Add Food | Demonstrates successful creation of a food record. |
| Update Food | Demonstrates successful modification of a food record. |
| Delete Food | Demonstrates successful deletion of a food record. |
| Invalid API Key | Shows the authentication error response. |
| Food Not Found | Shows the response for a non-existent food ID. |
| Invalid Request | Shows validation or malformed request errors. |

# Git and GitHub Workflow

This project uses **Git** for version control and **GitHub** for repository hosting. The following commands can be used to configure Git and upload the project to GitHub.

---

## Configure Git

Before using Git, configure your GitHub username and email.

Check the installed Git version.

```cmd
git --version
```

Configure your GitHub username.

```cmd
git config --global user.name "YOUR_GITHUB_USERNAME"
```

Configure your GitHub email.

```cmd
git config --global user.email "YOUR_GITHUB_EMAIL"
```

Verify the configuration.

```cmd
git config --global user.name
git config --global user.email
```

---

## Initialize the Repository

Navigate to the project folder.

```cmd
cd /d C:\xampp\htdocs\filipino-cookbook-api
```

Initialize Git.

```cmd
git init
```

Verify the repository status.

```cmd
git status
```

---

## Stage Project Files

Stage all project files.

```cmd
git add .
```

Verify staged files.

```cmd
git status
```

---

## Commit Changes

Create the initial commit.

```cmd
git commit -m "Initial commit"
```

---

## Rename the Default Branch

```cmd
git branch -M main
```

---

## Connect to GitHub

Add the GitHub repository as the remote origin.

```cmd
git remote add origin https://github.com/YOUR_GITHUB_USERNAME/YOUR_REPOSITORY_NAME.git
```

Verify the remote repository.

```cmd
git remote -v
```

---

## Push to GitHub

Upload the project.

```cmd
git push -u origin main
```

For future updates, use the following commands.

```cmd
git add .
git commit -m "Describe the changes made"
git push
```

---

# Repository Verification

Before submitting or sharing the project, verify that the repository contains the following files and folders.

## Required Files

- README.md
- composer.json
- composer.lock
- config/
- middleware/
- public/
- routes/
- vendor/
- storage/

---

## Verify Composer

Ensure that Composer dependencies are installed.

```cmd
composer install
```

---

## Verify the API

Confirm that:

- The API starts successfully.
- Database connection works correctly.
- API endpoints return valid JSON responses.
- API Key Authentication functions properly.
- CRUD operations work as expected.
- HTTP status codes are returned correctly.

---

# Troubleshooting

## 404 Not Found

### Possible Causes

- Incorrect endpoint URL
- Apache is not running
- Incorrect project folder name
- Missing `.htaccess` configuration

### Solution

Verify that the URL is correct.

Example:

```text
http://localhost/filipino-cookbook-api/public/api/foods
```

---

## Composer Not Found

### Error

```text
'composer' is not recognized as an internal or external command.
```

### Solution

- Reinstall Composer.
- Restart Command Prompt.
- Verify Composer installation.

```cmd
composer --version
```

---

## PHP Not Found

### Error

```text
'php' is not recognized as an internal or external command.
```

### Solution

Add PHP to the Windows PATH environment variable or use the full PHP executable path.

Verify installation.

```cmd
php -v
```

---

## Database Connection Failed

### Possible Causes

- MySQL is not running
- Incorrect database credentials
- Database does not exist

### Solution

- Start MySQL using XAMPP.
- Verify the configuration in `config/database.php`.
- Confirm that the `filipino_cookbook_api` database has been imported successfully.

---

## Unauthorized Access

### Error

```json
{
    "status": "error",
    "message": "Unauthorized access."
}
```

### Solution

- Verify that the required API key is included in the request headers.
- Confirm that the API key matches the value expected by the application.

---

## Internal Server Error

### Error

```text
500 Internal Server Error
```

### Possible Causes

- Database connection problem
- SQL query failure
- Missing dependency
- PHP runtime error

### Solution

- Verify database connectivity.
- Run `composer install`.
- Check Apache and PHP error logs.
- Review the application source code for runtime exceptions.

---

# Security Features

The Filipino Cookbook API incorporates several security practices to improve application reliability and protect sensitive operations.

Implemented security features include:

- API Key Authentication
- Prepared SQL Statements
- Parameterized Queries
- JSON Response Standardization
- Exception Handling
- Proper HTTP Status Codes
- Modular Route Organization

These practices help reduce common security risks such as unauthorized access and SQL injection attacks.

---

# Future Enhancements

The project may be extended with additional features, including:

- User authentication using JWT
- Role-based access control
- Image upload support for recipes
- Pagination for large datasets
- Advanced search and filtering
- API documentation using Swagger/OpenAPI
- Rate limiting
- Request logging
- Docker containerization
- Unit and integration testing

---

# Contributing

Contributions are welcome.

To contribute:

1. Fork the repository.
2. Create a new branch.
3. Implement your changes.
4. Commit your changes.
5. Push the branch to your fork.
6. Open a Pull Request for review.

---

# License

This project was developed for educational purposes.

You may use, modify, and extend the project for learning and academic activities.

---

# Developer Information

The following information identifies the developer and repository associated with this project.

| Field | Information |
|-------|-------------|
| **Student Name** | Jackie Rose S. Maqui |
| **Course and Section** | BS Information Technology — 4C |
| **GitHub Username** | DMMMSU-Jackie |
| **Repository Name** | filipino-cookbook-api-maqui |
| **Repository Link** | https://github.com/DMMMSU-Jackie/filipino-cookbook-api-maqui |
| **Date Completed** | July 2026 |

---

# Repository Information

| Item | Description |
|------|-------------|
| **Project Title** | Filipino Cookbook API |
| **Project Type** | RESTful Web API |
| **Programming Language** | PHP 8.x |
| **Framework** | Slim Framework 4 |
| **Database** | MySQL |
| **Authentication** | API Key Authentication |
| **Response Format** | JSON |
| **Version** | 1.0.0 |
| **Status** | Completed |

---

# Acknowledgements

This project was developed using the following technologies and tools:

- PHP
- Slim Framework 4
- MySQL
- Composer
- Apache
- XAMPP
- Visual Studio Code
- Thunder Client
- Git
- GitHub

Special thanks to the developers and maintainers of these technologies for providing reliable open-source tools that support modern RESTful API development.

---

# License

This project was developed for educational purposes as part of the requirements for the **Bachelor of Science in Information Technology** program.

The source code may be used for learning, reference, and academic purposes. Any reuse, modification, or redistribution should properly acknowledge the original developer and repository.

---

# Contact

For questions, suggestions, or collaboration regarding this project, please visit the GitHub repository below.

**GitHub Profile**

https://github.com/DMMMSU-Jackie

**Repository**

https://github.com/DMMMSU-Jackie/filipino-cookbook-api-maqui
