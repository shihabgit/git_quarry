<?php

// DEFINING CONSTANTS FOR EASY CODING.

//Constant to determine 'Active' Status.
define('ACTIVE', 1);

//Constant to determine 'Inactive' Status.
define('INACTIVE', 2);

//Constant to determine that there is a warning to the ADMIN about a fraud. It is used in worklogs.
define('WARNING', 1);

//Constant to determine that there is nothing to warn ADMIN in the worklog, ie:- a 'NORMAL' worklog.
define('NORMAL', 2);

// Intermediate condition between WARNING and NORMAL. It is to notify ADMIN about that there is a chance for fraud.
define('CHANCE', 3);

// emp_category value of ADMIN
define('ADMIN', 1);

// emp_category value of PARTNER
define('PARTNER', 2);

// emp_category value of STAFF
define('STAFF', 3);

// emp_category value of 
define('DRIVER', 4);

// emp_category value of 
define('LOADER', 5);








/*
+--------------------------------------------------------------------------+
| Default Settings                                                         |
+--------------------------------------------------------------------------+
| Determining is the s/w allows multiple firm/workcentre fecility.         |
+--------------------------------------------------------------------------+
*/
$config['allow_multiple']	= TRUE;



/*
+--------------------------------------------------------------------------+
| Version                                                                  |
+--------------------------------------------------------------------------+
| Determining the version the software.                                    |
+--------------------------------------------------------------------------+
*/
$config['version']	= '1.0'; 



/*
+--------------------------------------------------------------------------+
| Timezone                                                                 |
+--------------------------------------------------------------------------+
| Determining what the timezone to be used in the software.                |
+--------------------------------------------------------------------------+
*/
$config['timezone']	= 'Indian/Mahe'; //http://php.net/manual/en/timezones.indian.php


/*
+---------------------------------------------------------------------------------------------------+
| Software environment;                                                                             |
+---------------------------------------------------------------------------------------------------+
| Determining the software environment.                                                             |
| There are two type of environments. (1)Development (2)Production.                                 |
| Development environment : Indicates the developer is developing the software.                     |
| Production environment: Running, stable s/w. (After hosted it must be set as 'Production').       |
+---------------------------------------------------------------------------------------------------+
*/
$config['environment']	= 'Development'; //  Development,Production








/* End of file settings.php */
/* Location: ./application/config/settings.php */
