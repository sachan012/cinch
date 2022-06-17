<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2022-06-17 08:30:55 --> Query error: Unknown column 'ci_users.username' in 'field list' - Invalid query: SELECT `queue_list`.`id`, `queue_list`.`queue_name`, `queue_list`.`queue_type`, `queue_list`.`queue_last_updated_value`, `queue_list`.`queue_updated_datetime`, `ci_users`.`username`
FROM `queue_list`
WHERE `queue_list`.`marshal_station_code` = '1001-CNG-1000-ST103'
ERROR - 2022-06-17 08:55:40 --> Query error: Unknown column 'station_code' in 'field list' - Invalid query: SELECT `station_code`
FROM `queue_details`
WHERE `queue_details`.`marshal_station_code` = '1001-CNG-1000-ST103'
ERROR - 2022-06-17 08:55:54 --> Query error: Unknown column 'marshal_station_code' in 'field list' - Invalid query: SELECT `marshal_station_code`
FROM `queue_details`
WHERE `queue_details`.`marshal_station_code` = '1001-CNG-1000-ST103'
ERROR - 2022-06-17 08:56:25 --> Query error: Unknown column 'queue_details.marshal_station_code' in 'where clause' - Invalid query: SELECT `marshal_station_code`
FROM `queue_list`
WHERE `queue_details`.`marshal_station_code` = '1001-CNG-1000-ST103'
ERROR - 2022-06-17 08:59:18 --> Query error: Unknown column 'ci_users.marshal_userid' in 'field list' - Invalid query: SELECT `queue_list`.`id`, `queue_list`.`queue_name`, `queue_list`.`queue_type`, `queue_list`.`queue_last_updated_value`, `queue_list`.`queue_updated_datetime`, `ci_users`.`marshal_userid`
FROM `queue_list`
JOIN `ci_users` ON `ci_users`.`id` = `queue_list`.`marshal_userid`
WHERE `queue_list`.`marshal_userid` = '1'
ERROR - 2022-06-17 09:05:23 --> Query error: Not unique table/alias: 'ci_users' - Invalid query: SELECT `queue_list`.`id`, `queue_list`.`queue_name`, `queue_list`.`queue_type`, `queue_list`.`queue_last_updated_value`, `queue_list`.`queue_updated_datetime`, `queue_list`.`marshal_userid`, `ci_users`.`username`
FROM `queue_list`
JOIN `ci_users` ON `ci_users`.`assigned_station` = `queue_list`.`marshal_station_code`
JOIN `ci_users` ON `ci_users`.`id` = `queue_list`.`marshal_userid`
WHERE `queue_list`.`marshal_station_code` = '1001-CNG-1000-ST103'
ERROR - 2022-06-17 09:06:43 --> Query error: Not unique table/alias: 'ci_users' - Invalid query: SELECT `queue_list`.`id`, `queue_list`.`queue_name`, `queue_list`.`queue_type`, `queue_list`.`queue_last_updated_value`, `queue_list`.`queue_updated_datetime`, `queue_list`.`marshal_userid`, `ci_users`.`username`
FROM `queue_list`
JOIN `ci_users` ON `ci_users`.`assigned_station` = `queue_list`.`marshal_station_code`
JOIN `ci_users` ON `ci_users`.`id` = `queue_list`.`marshal_userid`
WHERE `queue_list`.`marshal_station_code` = '1001-CNG-1000-ST103'
