BABOUR SHOP - INSTALLATION
1. Upload the BABOUR_SHOP folder to XAMPP htdocs or your PHP hosting.
2. Create/import database/babour.sql in MySQL/phpMyAdmin.
3. Edit config/database.php with your MySQL host, database, username and password.
4. Open /register.php and create an account.
5. To make an admin, run in phpMyAdmin:
   UPDATE users SET role='admin', balance=10000 WHERE username='YOUR_USERNAME';
6. Open /admin/ to manage products.
7. This project is fully independent from SA-MP. It does not connect to or modify a SA-MP server.
