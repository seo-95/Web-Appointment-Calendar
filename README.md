# :calendar: Web Appointment Calendar :calendar:


This is a web page for user appointment booking. Each user must register and log in to see free slots and tries to book them.
The home page displays the week calendar with colored slots for bookings. Each user has a unique color that identify him without sharing it with other users.

### Features:
After the log in each user can select the number of slots he would to book and sees them by passing the mouse on the first slot. If the available slots are not enough they will highlighted with red color, green otherwise.


### Need to know:
The system supports a maximum of 10 users.

Cookie and js must be enabled for web pages to display.

### Server configuration:

The SQL file for DB configuration is already present. 
Infos about database must be fixed inside the file `db_handler.php`

	define('DB_SERVER', 'localhost:3306');
	define('DB_USERNAME', 'root');
	define('DB_PASSWORD', '');
	define('DB_DATABASE', 'registration');



*server side tested with apache sever + phpMyAdmin* :+1: <br>
*client side tested with chrome browser, mozilla firefox, microsoft Edge and internet explorer* :+1:
