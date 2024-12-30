# Moon Jewelry Store - PHP eCommerce Project

Welcome to **Moon Jewelry Store**, an eCommerce platform built with PHP! This guide will walk you through the steps to set up and run this project on your local machine using XAMPP.

## Table of Contents

- [Prerequisites](#prerequisites)
- [Installing and Setting Up XAMPP](#installing-and-setting-up-xampp)
- [Setting Up the Database](#setting-up-the-database)
- [How to Start a New PHP Project in XAMPP](#how-to-start-a-new-php-project-in-xampp)
- [How to Run a PHP Code Using XAMPP](#how-to-run-a-php-code-using-xampp)
- [Running the Project](#running-the-project)
- [Folder Structure](#folder-structure)
- [Features](#features)

---

## Prerequisites

Before you begin, ensure that you have the following installed:

- **XAMPP (Apache, MySQL, PHP)**: You will use XAMPP to run the PHP code and manage the database.
- **Web Browser**: For accessing the PHP project locally.
- **Text Editor**: Such as Notepad, Sublime Text, or Visual Studio Code to edit your PHP code.

If you don't have XAMPP installed, follow the instructions in the next section.

---

## Installing and Setting Up XAMPP

Follow these steps to install and set up XAMPP on your local machine:

1. **Download XAMPP**:
   - Go to the [XAMPP download page](https://www.apachefriends.org/index.html).
   - Choose the version of XAMPP suitable for your operating system (Windows, Linux, macOS) and download it.

2. **Install XAMPP**:
   - Run the downloaded installer and follow the installation instructions.
   - During the installation, make sure you select both **Apache** and **MySQL** (the services needed for this project).

3. **Start XAMPP**:
   - Once installed, open the **XAMPP Control Panel**.
   - Start the **Apache** and **MySQL** services by clicking on the "Start" button next to each.
   - The "Apache" service will run the web server, and "MySQL" will run the database server.

---

## Setting Up the Database

1. **Access phpMyAdmin**:
   - Open your browser and go to [http://localhost/phpmyadmin/](http://localhost/phpmyadmin/).
   - This will take you to the phpMyAdmin dashboard where you can manage MySQL databases.

2. **Create the Database**:
   - In phpMyAdmin, click on the **Databases** tab.
   - Under **Create database**, enter `moon_jewelry_store` as the database name.
   - Click **Create** to set up the database.

3. **Import the SQL File**:
   - Download or locate the `moon_jewelry_store.sql` file that comes with this project.
   - In phpMyAdmin, select the `moon_jewelry_store` database from the list on the left.
   - Click on the **Import** tab.
   - Choose the `moon_jewelry_store.sql` file and click **Go** to import the database structure and data.

4. **Database Configuration**:
   - In your project, locate the `.env` file (or `config.php` if you don't have `.env`).
   - Set the database connection parameters as follows:
     ```plaintext
     DB_HOST=localhost
     DB_USER=root
     DB_PASSWORD=
     DB_NAME=moon_jewelry_store
     ```

---

## How to Start a New PHP Project in XAMPP

Before you can start writing and running PHP code, make sure the Apache and MySQL servers are running in XAMPP.

1. **Start Apache and MySQL**:
   - Open the **XAMPP Control Panel** and click "Start" next to **Apache** and **MySQL**.
   - This will start both the web server (Apache) and the database server (MySQL).

2. **Create a PHP File**:
   - Open a text editor (e.g., Notepad or Sublime Text).
   - Write your PHP code, for example:
     ```php
     <?php
       echo "Hello, World!";
     ?>
     ```
   - Save the file with a `.php` extension, e.g., `program.php`.

3. **Move the PHP File to XAMPP's `htdocs` Folder**:
   - Copy the `program.php` file to the **htdocs** folder located at `C:\xampp\htdocs\`.

4. **View the PHP File in Browser**:
   - Open your browser and type `http://localhost/program.php`.
   - You should see the output of your PHP script: "Hello, World!".

---

## How to Run a PHP Code Using XAMPP

1. **Create a New Project Folder**:
   - Navigate to `C:\xampp\htdocs` and create a folder for your project, e.g., `MoonJewelryStore`.
   - This folder will store all the files related to your project.

2. **Copy Project Files**:
   - Copy all the project files (including the entire repository code) into the `MoonJewelryStore` folder.

3. **Start Apache Server**:
   - Open the **XAMPP Control Panel**.
   - Ensure **Apache** is running (click "Start" if it's not).

4. **Navigate to Your Project**:
   - In your browser, go to the following URL:  
     `http://localhost/MoonJewelryStore/`
   - You should now be able to view the homepage or the output of your project.

---

## Running the Project

Follow these steps to run the **Moon Jewelry Store** eCommerce project:

1. **Ensure XAMPP Apache and MySQL are Running**:
   - Open the XAMPP Control Panel and click "Start" next to **Apache** and **MySQL**.

2. **Place the Project in `htdocs`**:
   - Navigate to `C:\xampp\htdocs` and create a new folder named `MoonJewelryStore`.
   - Copy all the project files (from this repository) into the `MoonJewelryStore` folder.

3. **Set Up the Database**:
   - Follow the [Database Setup](#setting-up-the-database) section to create the `moon_jewelry_store` database and import the SQL file.

4. **Open the Project in Your Browser**:
   - Open your browser and go to `http://localhost/MoonJewelryStore/` to view the website.

---

## Folder Structure

The project directory is structured as follows:

PHP_ECOMMERCE_STORE_MOON/
│
├── config/
│   └── database.php
│
├── fpdf/
│
├── objects/
│
├── src/
│
├── .gitattributes
│
├── 404_page.php
├── active_product.php
├── admin_customer_list.php
├── admin_index.php
├── admin_navbar.php
├── admin_order_list.php
├── admin_product_list.php
├── admin_view_order.php
├── cart.php
├── change_password.php
├── checkout.php
├── create_product.php
├── deactive_product.php
├── edit_product.php
├── footer.php
├── index.php
├── invoice.php
├── logout.php
├── moon_jewelry_store.sql
├── navbar.php
├── orders.php
├── product_detail.php
├── README.md
├── shop.php
├── signup.php
├── thank_you.php
└── view_order.php


---

## Features

- **Product Management**: Add, edit, and delete products in the store.
- **Shopping Cart**: Customers can add products to their shopping cart.
- **Order Management**: Admin can view and manage customer orders.
- **Customer Management**: Admin can view and manage customer details.
- **User Authentication**: Customers can register, log in, and manage their profiles.
- **Responsive Design**: The store's frontend is mobile-friendly.
- **Payment Integration**: Integration with PayPal or other payment gateways can be configured.
- **Admin Dashboard**: A simple dashboard to manage products, customers, and orders.

---

