<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2022-06-16 06:56:10 --> 404 Page Not Found: Assets/dist
ERROR - 2022-06-16 08:39:16 --> 404 Page Not Found: admin/Stations/index
ERROR - 2022-06-16 09:07:57 --> Severity: Warning --> call_user_func_array() expects parameter 1 to be a valid callback, class 'Station' does not have a method 'index_get' D:\xampp\htdocs\iglque\application\libraries\REST_Controller.php 739
ERROR - 2022-06-16 09:15:23 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near '.`admin_role_id`, `ci_admin_roles`.`admin_role_title`
FROM `ci_users`
JOIN `c...' at line 1 - Invalid query: SELECT `ci_users`.`role`, `ci_users`.`firstname`, `ci_users`.`lastname`, `ci_users`.`username`, `ci_users`.`email`, `ci_users`.`assigned_station`.`ci_admin_roles`.`admin_role_id`, `ci_admin_roles`.`admin_role_title`
FROM `ci_users`
JOIN `ci_admin_roles` ON `ci_admin_roles`.`admin_role_id` = `ci_users`.`role`
WHERE `ci_users`.`id` = '2'
ERROR - 2022-06-16 09:22:17 --> Severity: error --> Exception: syntax error, unexpected '$result' (T_VARIABLE) D:\xampp\htdocs\iglque\application\models\Api_model.php 110
ERROR - 2022-06-16 09:22:44 --> Severity: error --> Exception: syntax error, unexpected '$result' (T_VARIABLE) D:\xampp\htdocs\iglque\application\models\Api_model.php 116
ERROR - 2022-06-16 09:23:36 --> Query error: Unknown column 'marshal_user_id' in 'where clause' - Invalid query: SELECT *
FROM `queue_list`
WHERE `marshal_user_id` = '2'
ERROR - 2022-06-16 09:29:18 --> Severity: Notice --> Undefined variable: station_details D:\xampp\htdocs\iglque\application\controllers\api\v1\Station.php 236
ERROR - 2022-06-16 11:12:59 --> Severity: Warning --> call_user_func_array() expects parameter 1 to be a valid callback, class 'Station' does not have a method 'index_post' D:\xampp\htdocs\iglque\application\libraries\REST_Controller.php 739
ERROR - 2022-06-16 11:17:54 --> 404 Page Not Found: Assets/dist
ERROR - 2022-06-16 11:22:29 --> Severity: error --> Exception: syntax error, unexpected '{', expecting ';' D:\xampp\htdocs\iglque\application\controllers\api\v1\Station.php 256
ERROR - 2022-06-16 11:22:30 --> Severity: error --> Exception: syntax error, unexpected '{', expecting ';' D:\xampp\htdocs\iglque\application\controllers\api\v1\Station.php 256
ERROR - 2022-06-16 11:22:31 --> Severity: error --> Exception: syntax error, unexpected '{', expecting ';' D:\xampp\htdocs\iglque\application\controllers\api\v1\Station.php 256
ERROR - 2022-06-16 11:25:55 --> Severity: Notice --> Undefined variable: form_data D:\xampp\htdocs\iglque\application\controllers\api\v1\Station.php 284
ERROR - 2022-06-16 11:25:55 --> Severity: Warning --> Invalid argument supplied for foreach() D:\xampp\htdocs\iglque\application\controllers\api\v1\Station.php 284
ERROR - 2022-06-16 11:26:49 --> Severity: Notice --> Undefined variable: param D:\xampp\htdocs\iglque\application\controllers\api\v1\Station.php 284
ERROR - 2022-06-16 11:26:49 --> Severity: Notice --> Trying to access array offset on value of type null D:\xampp\htdocs\iglque\application\controllers\api\v1\Station.php 284
ERROR - 2022-06-16 11:26:49 --> Severity: Warning --> Invalid argument supplied for foreach() D:\xampp\htdocs\iglque\application\controllers\api\v1\Station.php 284
ERROR - 2022-06-16 11:30:21 --> Severity: Notice --> Undefined variable: queue_id D:\xampp\htdocs\iglque\application\controllers\api\v1\Station.php 288
ERROR - 2022-06-16 11:30:21 --> Severity: Notice --> Undefined variable: queue_value D:\xampp\htdocs\iglque\application\controllers\api\v1\Station.php 289
ERROR - 2022-06-16 11:30:21 --> Query error: Column 'queue_id' cannot be null - Invalid query: INSERT INTO `queue_details` (`user_id`, `queue_id`, `queue_value`) VALUES ('2', NULL, NULL)
ERROR - 2022-06-16 11:31:07 --> Severity: Notice --> Undefined index: userid D:\xampp\htdocs\iglque\application\controllers\api\v1\Station.php 287
ERROR - 2022-06-16 11:31:07 --> Query error: Column 'user_id' cannot be null - Invalid query: INSERT INTO `queue_details` (`user_id`, `queue_id`, `queue_value`) VALUES (NULL, '1', 50)
ERROR - 2022-06-16 11:32:21 --> Severity: Notice --> Undefined index: userid D:\xampp\htdocs\iglque\application\controllers\api\v1\Station.php 287
ERROR - 2022-06-16 11:59:32 --> Query error: Unknown column 'marshal_userid' in 'field list' - Invalid query: INSERT INTO `queue_details` (`marshal_userid`, `marshal_station_code`, `queue_name`, `queue_type`) VALUES (3, '1001-CNG-1000-ST103', 'Queue1', 'bus')
ERROR - 2022-06-16 12:39:39 --> Severity: error --> Exception: syntax error, unexpected '$this' (T_VARIABLE) D:\xampp\htdocs\iglque\application\controllers\api\v1\Station.php 312
ERROR - 2022-06-16 14:42:08 --> Severity: Notice --> Undefined variable: params D:\xampp\htdocs\iglque\application\controllers\api\v1\Auth.php 94
ERROR - 2022-06-16 14:42:08 --> Severity: error --> Exception: Argument 1 passed to CI_Form_validation::set_data() must be of the type array, null given, called in D:\xampp\htdocs\iglque\application\controllers\api\v1\Auth.php on line 94 D:\xampp\htdocs\iglque\system\libraries\Form_validation.php 267
ERROR - 2022-06-16 14:42:18 --> Severity: Notice --> Undefined variable: params D:\xampp\htdocs\iglque\application\controllers\api\v1\Auth.php 94
ERROR - 2022-06-16 14:42:18 --> Severity: error --> Exception: Argument 1 passed to CI_Form_validation::set_data() must be of the type array, null given, called in D:\xampp\htdocs\iglque\application\controllers\api\v1\Auth.php on line 94 D:\xampp\htdocs\iglque\system\libraries\Form_validation.php 267
ERROR - 2022-06-16 15:08:26 --> Severity: Notice --> Undefined variable: station_code D:\xampp\htdocs\iglque\application\controllers\api\v1\Station.php 315
ERROR - 2022-06-16 15:36:21 --> Severity: error --> Exception: syntax error, unexpected '$station_code' (T_VARIABLE) D:\xampp\htdocs\iglque\application\controllers\api\v1\Station.php 385
ERROR - 2022-06-16 15:41:36 --> Query error: Unknown column '1001-CNG-1000-ST001' in 'where clause' - Invalid query: UPDATE `station_info` SET `auto_arms` = '2', `auto_queues` = '2', `bus_arms` = '2', `bus_queues` = '2', `non_bus_arms` = '2', `non_bus_queues` = '2', `bus_filling_time` = '2', `non_bus_filling_time` = '2', `num_of_marshals` = '6'
WHERE `1001-CNG-1000-ST001` IS NULL
ERROR - 2022-06-16 15:59:44 --> Severity: error --> Exception: syntax error, unexpected ';', expecting ')' D:\xampp\htdocs\iglque\application\controllers\api\v1\Station.php 393
