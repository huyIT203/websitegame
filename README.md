<<<<<<< HEAD
admin : quanghuy    
user: user
seller: seller
pass: IQBOLSHOH
=======
>>>>>>> ee3cb50251df4c620503b054574d9cd6ba57652a
## Project Introduction

This project is a PHP-MySQL-based marketplace application that allows users to register as buyers or sellers and interact within the platform. It provides features for managing products, orders, and user accounts.

## How to Run the Project

1. **Install XAMPP**: Download and install [XAMPP](https://www.apachefriends.org/index.html) to set up a local server environment.
2. **Clone the Repository**: Clone this project into the `htdocs` folder of your XAMPP installation.
    ```bash
<<<<<<< HEAD
=======
    git clone[ https://github.com/your-repo/php-mysql-marketplace.git](https://github.com/huyIT203/websitegame)
>>>>>>> ee3cb50251df4c620503b054574d9cd6ba57652a
    ```
3. **Start XAMPP**: Open XAMPP and start the Apache and MySQL services.
4. **Import the Database**:
    - Open `phpMyAdmin` in your browser (usually at `http://localhost/phpmyadmin`).
    - Create a new database (e.g., `marketplace`).
    - Import the provided SQL file (`database.sql`) into the newly created database.
5. **Configure Database Connection**:
    - Open the project folder and locate the configuration file (e.g., `config.php`).
    - Update the database credentials to match your local setup.
6. **Access the Application**:
    - Open your browser and navigate to `http://localhost/php-mysql-marketplace-main/`.

## Technologies Used

- **Programming Language**: PHP
- **Database**: MySQL
- **Frontend**: HTML, CSS, JavaScript
- **Server**: Apache (via XAMPP)

## Default Credentials

- **Admin**: 
  - Username: `quanghuy`
  - Password: `IQBOLSHOH`
- **User**: 
  - Username: `user`
  - Password: `IQBOLSHOH`
- **Seller**: 
  - Username: `seller`
  - Password: `IQBOLSHOH`

## Import the Database

To import the database, follow these steps:

1. Open your browser and navigate to `http://localhost/phpmyadmin`.
2. Log in to `phpMyAdmin` using your MySQL credentials.
3. Click on the **Databases** tab and create a new database (e.g., `marketplace`).
4. Select the newly created database from the list.
5. Click on the **Import** tab in the top menu.
6. Click the **Choose File** button and select the `database.sql` file from the project folder.
7. Click the **Go** button to import the database structure and data.

Once the import is complete, you can proceed with configuring the database connection in the project.

## Configure Database Connection

To configure the database connection, follow these steps:

1. Open the project folder in your code editor.
2. Locate the `config.php` file (or equivalent configuration file).
3. Update the database connection details to match your local setup. For example:

    ```php
    <?php
    $host = 'localhost';
    $db = 'marketplace'; // Replace with your database name
    $user = 'root'; // Replace with your MySQL username
    $pass = ''; // Replace with your MySQL password

    try {
        $conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
    ?>
    ```

4. Save the changes to the `config.php` file.
5. Ensure the database credentials match the ones you used when setting up the database in `phpMyAdmin`.

<<<<<<< HEAD
Once configured, the application should be able to connect to the database successfully.

=======
>>>>>>> ee3cb50251df4c620503b054574d9cd6ba57652a
## Run the Project on Localhost

To access the project on your local server, open your browser and navigate to the following URL:

<<<<<<< HEAD
[http://localhost/php_websitegame/](http://localhost/php_websitegame/)
=======
[http://localhost/php_websitegame/](http://localhost/php_websitegame/)
Once configured, the application should be able to connect to the database successfully.
>>>>>>> ee3cb50251df4c620503b054574d9cd6ba57652a
