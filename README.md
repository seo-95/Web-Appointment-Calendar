# Web Appointment Calendar


This is a web page for user appointment booking. Each user must register and log in to see free slots and tries to book them.
The home page display the week calendar with colored slots for bookings. Each user has a unique color that identify him without sharing it with other users.

The system supports a maximum of 10 users.

Cookie and js must be enabled for web pages to display.

After the log in each user can select the number of slots he would to book and sees them by passing the mouse on the first slot.



### Server configuration:

The SQL file for DB configuration is already present. 
Infos about database must be fixed inside the file `db_handler.php`

	define('DB_SERVER', 'localhost:3306');
	define('DB_USERNAME', 'root');
	define('DB_PASSWORD', '');
	define('DB_DATABASE', 'registration');
	
