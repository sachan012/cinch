<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('getSessionFlashDataSamePage')) {
    function getSessionFlashDataSamePage($key)
    {
        $data = getSessionUserData($key);
        setSessionUserData(array($key => ''));
        return $data;
    }
}

if (!function_exists('assets_url')) {
    function assets_url($folder, $path)
    {
        $url = base_url() . 'assets/' . $folder . '/' . $path;
        return $url;
    }
}

if (!function_exists('image_url')) {
    function image_url($folder, $name, $width = 0, $height = 0)
    {
        /*$params = '';
        if ($width > 0 && $height > 0) {
            $params = '&w=' . $width . '&h=' . $height;
        } elseif ($width > 0) {
            $params = '&w=' . $width;
        } elseif ($height > 0) {
            $params = '&h=' . $height;
        }
        $url = base_url('assets/uploads/img.php?src=' . $folder . '/' . $name . $params);
        return $url;*/

        $params = '';
        if ($width > 0 && $height > 0) {
            $params = '&width=' . $width . '&height=' . $height . '&cropratio=' . $width . ':' . $height;
        } elseif ($width > 0) {
            $params = '&width=' . $width;
        } elseif ($height > 0) {
            $params = '&height=' . $height;
        }
        $url = base_url('assets/uploads/image_crop.php?image=/assets/uploads/' . $folder . '/' . $name . $params);
        return $url;
    }
}

if (!function_exists('_inputPost')) {
    function _inputPost($elementName)
    {
        $CI =& get_instance();
        if (is_array($CI->input->post($elementName))) {
            return $CI->input->post($elementName);
        } else {
            return trim($CI->input->post($elementName));
        }
    }
}

if (!function_exists('_inputGet')) {
    function _inputGet($elementName)
    {
        $CI =& get_instance();

        if (is_array($CI->input->get($elementName))) {
            return $CI->input->get($elementName);
        } else {
            return trim($CI->input->get($elementName));
        }

    }
}

if (!function_exists('setSessionUserData')) {
    function setSessionUserData($key, $val = '')
    {
        $CI =& get_instance();
        if (is_array($key)) {
            $CI->session->set_userdata($key);
        } else {
            $CI->session->set_userdata($key, $val);
        }
    }
}

if (!function_exists('getSessionUserData')) {
    function getSessionUserData($key)
    {
        $CI =& get_instance();
        return $CI->session->userdata($key);
    }
}

if (!function_exists('getSessionFlashData')) {
    function getSessionFlashData($key)
    {
        $CI =& get_instance();
        return $CI->session->flashdata($key);
    }
}

if (!function_exists('randomGenerateString')) {
    function randomGenerateString($length = 9)
    {
        $vowels = 'AEUY123456789';
        $consonants = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        //$consonants  .= '!@#$%^&*_-()';
        $password = '';
        $alt = time() % 2;
        for ($i = 0; $i < $length; $i++) {
            if ($alt == 1) {
                $password .= $consonants[(rand() % strlen($consonants))];
                $alt = 0;
            } else {
                $password .= $vowels[(rand() % strlen($vowels))];
                $alt = 1;
            }
        }
        return $password;
    }
}

if (!function_exists('validateUserLogin')) {
    function validateUserLogin($type = 'front')
    {
        $CI =& get_instance();
        if ($type == 'front') {
            $userData = getSessionUserData('VendorLoginData');
            if ($userData['UserId'] == 0 || $userData['UserId'] == '') {
                setSessionFlashData('error', 'Please Login With Your Account.');
                if (isset($_SERVER["REDIRECT_URL"])) {
                    $redirectUrl = sprintf("%s://%s%s", isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http', $_SERVER['SERVER_NAME'], $_SERVER["REDIRECT_URL"]);
                    setSessionUserData(array('RedirectUrl' => $redirectUrl));
                }
                redirect(base_url('vendor/login'));

            }
        } else {
            $userData = getSessionUserData('AdminLoginData');
            if ($userData['id'] == 0 || $userData['id'] == '') {
                setSessionFlashData('error', 'Please Login With Your Account.');
                if (isset($_SERVER["REDIRECT_URL"])) {
                    $redirectUrl = sprintf("%s://%s%s", isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http', $_SERVER['SERVER_NAME'], $_SERVER["REDIRECT_URL"]);
                    setSessionUserData(array('RedirectUrl' => $redirectUrl));
                }
                $currentUrL = getSessionUserData('RedirectUrl');
                if ($currentUrL) {
                    redirect($currentUrL);
                } else {
                    redirect(base_url('admin'));
                }
            }
        }

    }
}

if (!function_exists('pr')) {
    function pr($arr, $isDie = false)
    {
        echo '<pre>';
        print_r($arr);
        echo '</pre>';
        if ($isDie == true) {
            die;
        }
    }
}

if (!function_exists('validateURI')) {
    function validateURI($uriSegment)
    {
        $CI =& get_instance();
        return trim($CI->uri->segment($uriSegment)) != '' && is_numeric($CI->uri->segment($uriSegment)) && $CI->uri->segment($uriSegment) > 0 ? $CI->uri->segment($uriSegment) : '';
    }
}

if (!function_exists('getStringSegment')) {
    function getStringSegment($segment)
    {
        $CI =& get_instance();
        return $CI->uri->segment($segment);
    }
}

if (!function_exists('printArray')) {
    function printArray($arr, $isDie = false)
    {
        echo '<pre>';
        print_r($arr);
        if ($isDie == true) {
            die;
        }
    }
}

if (!function_exists('getConfigValues')) {
    function getConfigValues($key)
    {
        $CI =& get_instance();
        return $CI->config->item($key);
    }
}

if (!function_exists('createPagination')) {
    function createPagination($url, $totalRows, $perPage, $segment, $queryString = '', $config = array(), $extraParameter = '')
    {
        $CI =& get_instance();
        $CI->load->library('pagination');
        $config = array();
        $config['base_url'] = base_url() . $url;
        $config['total_rows'] = $totalRows;
        $config['per_page'] = $perPage;
        $config['uri_segment'] = $segment;
        $config['extraParameter'] = $extraParameter;
        $config['reuse_query_string'] = true;
        if (!empty($queryString) && count($queryString) > 0) $config['suffix'] = '?' . http_build_query($queryString, '', "&");
        $CI->pagination->initialize($config);
        return $CI->pagination->create_links();
    }
}

if (!function_exists('createPaginationLoad')) {
    function createPaginationLoad($url, $totalRows, $perPage, $segment, $queryString = '', $config = array(), $extraParameter = '')
    {
        $CI =& get_instance();
        $config = array();
        $CI->load->library('pagination');

        $config['base_url'] = base_url() . $url;
        $config['total_rows'] = $totalRows;
        $config['per_page'] = $perPage;
        $config['uri_segment'] = $segment;
        $config['next_link'] = FALSE;
        $config['prev_link'] = FALSE;
        $config['extraParameter'] = $extraParameter;
        $config['reuse_query_string'] = true;
        if (!empty($queryString) && count($queryString) > 0) $config['suffix'] = '?' . http_build_query($queryString, '', "&");
        //pr($config);
        $CI->pagination->initialize($config);
        return $CI->pagination->create_links($queryString);
    }
}

function customPagination($overrides = [])
{
    $CI =& get_instance();
    $CI->load->library('pagination');
    $config['full_tag_open'] = '<ul class="pagination  pagination-sm m-t-none m-b-none">';
    $config['full_tag_close'] = '</ul>';
    $config['prev_link'] = 'Previous';
    $config['prev_tag_open'] = '<li>';
    $config['prev_tag_close'] = '</li>';
    $config['next_link'] = 'Next';
    $config['next_tag_open'] = '<li>';
    $config['next_tag_close'] = '</li>';
    $config['cur_tag_open'] = '<li class="active"><a href="#">';
    $config['cur_tag_close'] = '</a></li>';
    $config['num_tag_open'] = '<li>';
    $config['num_tag_close'] = '</li>';

    $config['first_tag_open'] = '<li>';
    $config['first_tag_close'] = '</li>';
    $config['last_tag_open'] = '<li>';
    $config['last_tag_close'] = '</li>';

    /*$config['first_link'] = '<i class="fa fa-chevron-left"></i> <i class="fa fa-chevron-left"></i>';
    $config['last_link'] = '<i class="fa fa-chevron-right"></i><i class="fa fa-chevron-right"></i>';*/

    foreach ($overrides as $key => $value) {
        $config[$key] = $value;
    }

    $CI->pagination->initialize($config);
}

if (!function_exists('sendMail')) {
    function sendMail($subject, $mailContent, $mailTo, $mailFromId, $mailFromName, $attachment_file = '')
    {
            $CI =& get_instance();
            $account="help.cs@maadima.com";
            $password="Nah02427";
            include("phpmailer/class.phpmailer.php");
            $mail = new PHPMailer();
            $mail->IsSMTP();
            $mail->CharSet = 'UTF-8';
            $mail->Host = "ssl://smtp.googlemail.com";
            $mail->SMTPAuth= true;
            $mail->Port = 587;
            $mail->Username= EMAIL;
            $mail->Password= PASSWORD;
            $mail->SMTPSecure = 'tls';
            $mail->From = $mailFromId;
            $mail->FromName= $mailFromName;
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $mailContent;
            $mail->addAddress($mailTo);
            if ($attachment_file != '') 
              {
                $mail->AddAttachment($attachment_file);  /* Enables you to send an attachment */
              }
            if(!$mail->send()){
             return true;
            }else{
              return false;
            }
    }
}

if (!function_exists('setSessionFlashData')) {
    function setSessionFlashData($key, $val = '')
    {
        $CI =& get_instance();
        if (is_array($key)) {
            $CI->session->set_flashdata($key);
        } else {
            $CI->session->set_flashdata($key, $val);
        }
    }
}

if (!function_exists('setSessionFlashDataSamePage')) {
    function setSessionFlashDataSamePage($key, $val = '')
    {
        setSessionUserData(array($key => $val));
    }
}


if (!function_exists('SendEmailByTemplate')) {
    function SendEmailByTemplate($template_id, $email_keywords = array(), $mailTo, $mailFrom, $mailFromName)
    {
        $CI =& get_instance();
        $strSQL = "SELECT * FROM b_email_templates WHERE id =" . $template_id;
        $resSQL = $CI->db->query($strSQL);
        if ($resSQL->num_rows() > 0) {
            $result = $resSQL->result_array();
            $msg_body = $result[0]['email_content'];
            $msg_subject = $result[0]['email_subject'];
            if (is_array($email_keywords)) {
                foreach ($email_keywords as $key => $value) {
                    $msg_subject = str_replace("[" . $key . "]", $value, $msg_subject);

                    if (strstr($msg_body, "/[" . $key . "]")) {
                        $msg_body = str_replace("/[" . $key . "]", $value, $msg_body);
                        $msg_body = str_replace("[" . $key . "]", $value, $msg_body);
                    } else {
                        $msg_body = str_replace("[" . $key . "]", $value, $msg_body);
                    }
                }
            }
            if (preg_match_all('@<img.*src="([^"]*)"[^>/]*/?>@Ui', $msg_body, $image)) {
                if (isset($image[1]) && !empty($image[1])) {
                    foreach ($image[1] as $src) {
                        //$msg_body = str_replace($src,$siteUrl.$src,$msg_body);
                    }
                }

            }
            if (preg_match("/[SITE_URL]/", $msg_body)) {
                $msg_body = str_replace('/[SITE_URL]', base_url(), $msg_body);
            }

            //$msg_body = replaceImgSrc( $msg_body);
            $siteUrl = sprintf("%s://%s", isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http', $_SERVER['SERVER_NAME']);


            /*$msg_body = replaceImgSrc($msg_body);*/
            $msg_subject = html_entity_decode(htmlentities($msg_subject));
            $msg_body = html_entity_decode(htmlentities($msg_body));
            $CI->viewData['msg_body'] = $msg_body;
            $msg_header = $CI->load->view('email/header', $CI->viewData, true);
            $full_msg_body = $msg_header;
            //CI->load->library('email');
            $account="help.cs@maadima.com";
            $password="Nah02427";
            include("phpmailer/class.phpmailer.php");
            $mail = new PHPMailer();
            $mail->IsSMTP();
            $mail->CharSet = 'UTF-8';
            $mail->Host = "ssl://smtp.googlemail.com";
            $mail->SMTPAuth= true;
            $mail->Port = 587;
            $mail->Username= EMAIL;
            $mail->Password= PASSWORD;
            $mail->SMTPSecure = 'tls';
            $mail->From = $mailFrom;
            $mail->FromName= $mailFromName;
            $mail->isHTML(true);
            $mail->Subject = $msg_subject;
            $mail->Body = $full_msg_body;
            $mail->addAddress($mailTo);
            if(!$mail->send()){
             return true;
            }else{
              return false;
            }
        }
    }
}




if (!function_exists('encrypt')) {
    function encrypt($decrypted, $password, $salt = 'otMiEQuHHyugzoK2wHopDxNthWfiJnsWL9lCC')
    {
        // Build a 256-bit $key which is a SHA256 hash of $salt and $password.
        $key = hash('SHA256', $salt . $password, true);
        // Build $iv and $iv_base64.  We use a block size of 128 bits (AES compliant) and CBC mode.  (Note: ECB mode is inadequate as IV is not used.)
        srand();
        $iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC), MCRYPT_RAND);

        if (strlen($iv_base64 = rtrim(base64_encode($iv), '=')) != 22) return false;
        // Encrypt $decrypted and an MD5 of $decrypted using $key.  MD5 is fine to use here because it's just to verify successful decryption.
        $encrypted = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $decrypted . md5($decrypted), MCRYPT_MODE_CBC, $iv));
        // We're done!
        return $iv_base64 . $encrypted;
    }
}

if (!function_exists('decrypt')) {
    function decrypt($encrypted, $salt = 'otMiEQuHHyugzoK2wHopDxNthWfiJnsWL9lCC')
    {
        $password = ENCY_PASSWORD;
        // Build a 256-bit $key which is a SHA256 hash of $salt and $password.
        $key = hash('SHA256', $salt . $password, true);
        // Retrieve $iv which is the first 22 characters plus ==, base64_decoded.
        $iv = base64_decode(substr($encrypted, 0, 22) . '==');
        // Remove $iv from $encrypted.
        $encrypted = substr($encrypted, 22);
        // Decrypt the data.  rtrim won't corrupt the data because the last 32 characters are the md5 hash; thus any \0 character has to be padding.
        $decrypted = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, base64_decode($encrypted), MCRYPT_MODE_CBC, $iv), "\0\4");
        // Retrieve $hash which is the last 32 characters of $decrypted.
        $hash = substr($decrypted, -32);
        // Remove the last 32 characters from $decrypted.
        $decrypted = substr($decrypted, 0, -32);
        // Integrity check.  If this fails, either the data is corrupted, or the password/salt was incorrect.
        if (md5($decrypted) != $hash) return false;
        // Yay!
        return $decrypted;
    }
}

if (!function_exists('get_auth_token')) {
    function get_auth_token($tokenValuesAssociativeArray)
    {
        $password = ENCY_PASSWORD;
        $tokenArr = array();
        foreach ($tokenValuesAssociativeArray as $arr) {
            $tokenArr[] = $arr;
        }
        $tokenValues = json_encode($tokenArr);
        return encrypt($tokenValues, $password);
    }
}

if (!function_exists('get_local_time')) {
    function get_local_time($value = '', $row, $format = 'dS M, Y', $timezone = 'UP55')
    {
        if ($row == 'added_on') {
            $value = date($format, gmt_to_local(strtotime($value), $timezone));
        } elseif ($row == 'created_on') {
            $value = date($format, gmt_to_local(strtotime($value), $timezone));
        } elseif ($row == 'timestamp') {
            $value = date($format, gmt_to_local(strtotime($value), $timezone));
        } elseif ($row == 'last_login') {
            $value = date($format, gmt_to_local(strtotime($value), $timezone));
        } elseif ($row == 'expire_on') {
            $value = date($format, gmt_to_local(strtotime($value), $timezone));
        }
        return $value;
    }
}

if (!function_exists('get_status')) {
    function get_status($value, $row)
    {
        switch ($row->status) {
            case '0':
                return '<span title="Active" class="label bg-warning">Inactive</span>';
            case '1':
                return '<span title="Active" class="label bg-success">Active</span>';
        }
    }
}

if (!function_exists('set_local_to_gmt')) {
    function set_local_to_gmt($time = '', $format = 'Y-m-d H:i:s')
    {
        if ($time == '') {
            $time = time();
        }
        $value = date($format, $time);
        return $value;
    }
}

/*if (!function_exists('isLoggedIn')) {
    function isLoggedIn($type = 'user')
    {
        //pr($_SERVER,1);
        $CI =& get_instance();
        $CI->load->library('auth');
        if (!$CI->auth->loggedin($type)) {
            if ($type == 'admin') {
                setSessionFlashData('error', 'Whoops! This is a secure panel, Please login to access Admin Panel.');
                redirect(base_url('admin/login'));
            } else {
                setSessionFlashData('error', 'Whoops! Please login your account.');
                if (isset($_SERVER["REDIRECT_URL"])) {
                    $redirectUrl = sprintf("%s://%s%s", isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http', $_SERVER['SERVER_NAME'],
                        $_SERVER["REDIRECT_URL"]);
                    setSessionUserData(array('RedirectUrl' => $redirectUrl));
                }
                redirect(base_url(''));
            }
        }

        $id = $CI->auth->userid($type);

        if ($type == 'admin') {
            $CI->load->model('admin_model');
            $user = $CI->admin_model->get('id', $id);
        } else {
            $CI->load->model('user_model');
            $user = $CI->user_model->get_by_col('id', $id);
        }
        $CI->session->set_userdata(array('auth_' . $type . '_data' => $user));
    }
}*/

if (!function_exists('id_from_slug')) {
    function id_from_slug($slug = '')
    {
        $id = false;
        if ($slug !== '') {
            $keywords = preg_split("/[,]+/", $slug);
            $count = count($keywords);
            $offset = $count - 1;
            $id = (int)$keywords[$offset];
        }
        return $id;
    }
}

if (!function_exists('getLastNumber')) {
    function getLastNumber($string)
    {
        $value = false;
        if (!is_numeric($string)) {
            $rawArray = explode('-', $string);
            $total = count($rawArray);
            $offset = $total - 1;
            if (is_numeric($rawArray[$offset])) {
                $value = $rawArray[$offset];
            }
        } else {
            $value = $string;
        }
        return $value;
    }
}

if (!function_exists('PageDetailById')) {
    function PageDetailById($id)
    {
        $ci = &get_instance();
        $query = $ci->db->get_where('b_pages', array('id' => $id));
        return $query->row_array();
    }
}

if (!function_exists('filter_params')) {
    function filter_params($paramsRaw, $dbParams)
    {
        if (array_key_exists('format', $paramsRaw)) {
            unset($paramsRaw['format']);
        }
        $filterArray = array();
        if (!empty($paramsRaw)) {
            foreach ($paramsRaw AS $key => $value) {
                if (array_key_exists($key, $dbParams)) {
                    $filterArray[$dbParams[$key]] = $value;
                }
            }
        }
        return $filterArray;
    }
}

if (!function_exists('filter_validation_errors')) {
    function filter_validation_errors()
    {
        $rawMsg = validation_errors();
        $errorMsg = '';
        if ($rawMsg) {
            $errorMsg = strip_tags(validation_errors(), '\n');
        }
        return $errorMsg;
    }
}

if (!function_exists('isValidToken')) {
    function isValidToken($token, $params = array())
    {
        $obj =& get_instance();
        $obj->load->model('common_model');
        $return = false;
        if ($token != '') {
            $rawData = decrypt($token);
            if ($rawData != false) {
                $data = explode(',', $rawData);
                if (empty($params)) {
                    $select = 'id,status,password';
                } else {
                    $select = implode(',', $params);
                }
                if (isset($data[0])) {
                    $userData = $obj->common_model->_select('b_users', $select, array($data[1] => $data[0]), 'id', 'desc');
                    if (!empty($userData)) {
                        $return = $userData[0];
                    }
                }
            }
        }

        return $return;
    }

}

if (!function_exists('dateFormat')) {
    function dateFormat($date_value)
    {
        $formatted_date = date('Y-m-d', strtotime($date_value));
        return $formatted_date;
    }
}

if (!function_exists('is_group_allowed')) {
    function is_group_allowed($perm_par, $type = 'read', $group_par = FALSE)
    {
        $ci = &get_instance();
        $ci->load->model('role_model');
        $return = $ci->role_model->is_group_allowed($perm_par, $type, $group_par);
        return $return;
    }
}

if (!function_exists('GetTemplate')) {
    function GetTemplate($template_id, $keywords = array())
    {

        $obj =& get_instance();
        $strSQL = "SELECT * FROM b_email_templates WHERE id =" . $template_id;
        $resSQL = $obj->db->query($strSQL);
        if ($resSQL->num_rows() > 0) {
            $result = $resSQL->result_array();
            $msg_body = $result[0]['email_content'];
            if (is_array($keywords)) {
                foreach ($keywords as $key => $value) {
                    $msg_body = str_replace("[" . $key . "]", $value, $msg_body);
                }
            }

            return $msg_body;
        } else {
            return false;
        }

    }
}

if (!function_exists('convert_sqltime_to_calnderdate')) {
    function convert_sqltime_to_calnderdate($datetimestamp)
    {
        return dateTimeView($datetimestamp, 'F d, Y h:i A');
    }
}

function getCatID($value)
{
    $CatID = '';
    $CI =& get_instance();
    $CI->db->select('id', false);
    $CI->db->where(array('parent_id' => '0'));
    $CI->db->like('cat_name', $value);
    $resSQL = $CI->db->get('b_categories');
    if ($resSQL->num_rows() > 0) {
        $result = $resSQL->result_array();
        if (!empty($result)) {
            $result = array_column($result, 'id');
            $CatID = "'" . implode("','", $result) . "'";
        }
    }
    return $CatID;

}

if (!function_exists('SetCondition')) {
    function SetCondition($key, $condition = true)
    {
        $CI =& get_instance();
        $CatID = '0';
        if (!empty($key)) {
            $likekey = array();
            if (is_array($key)) {
                if (!empty($key['like']) && isset($key['like'])) {
                    foreach ($key['like'] as $lkey => $value) {

                        $expLikes = explode('|', $lkey);
                        // this is for OR condition
                        if (!empty($expLikes) && count($expLikes) > 1) {
                            $orConditionArray = array();
                            foreach ($expLikes as $expLike) {
                                $orConditionArray[] = str_replace('-', '.', $expLike) . " LIKE " . $CI->db->escape($value);
                            }

                            $CI->db->where('(' . implode(' OR ', $orConditionArray) . ')');

                        } else {

                            if (strpos($lkey, '-') !== false) {
                                $lkey = str_replace("-", ".", $lkey);
                            }
                            if ($lkey == 'parent_cat_name') {
                                $lkey = 'cat_name';
                                $CatID = getCatID($value);
                                $lkey = 'parent_cat_name';
                            }
                            $likekey['like'][$lkey] = $value;
                            unset($likekey['like']['parent_cat_name']);

                        }

                    }

                    if (!empty($likekey)) {
                        $CI->db->like($likekey['like']);
                    }


                }
            }
            if (!empty($key['equal']) && isset($key['equal'])) {
                foreach ($key['equal'] as $ekey => $value) {
                    if (strpos($ekey, '-') !== false) {
                        $ekey = str_replace("-", ".", $ekey);
                    }
                    $eqalkey['equal'][$ekey] = $value;
                }
                $CI->db->where($eqalkey['equal']);
            }
            if (!empty($key['in']) && isset($key['in'])) {
                $CI->db->where_in($key['column'], $key['in']);
            }
           /* foreach ($key['range'] as $key_range => $rangeKey) {
                if ($rangeKey['from'] != '') {
                    $column = str_replace('-', '.', $key_range);
                    if ($column == 'bo.added_on' || $column == 't.added_on' || $column == 'boi.added_on' || $column == 'b_users.created_on' || $column == 'b_industrial_users.created_on' || $column == 'added_on') {
                        $CI->db->where('DATE(' . $column . ') BETWEEN "' . $rangeKey['from'] . '" and "' . $rangeKey['to'] . '"');
                    } else {
                        $CI->db->where($column . ' BETWEEN "' . $rangeKey['from'] . '" and "' . $rangeKey['to'] . '"');
                    }
                }

            }*/
            if ($CatID != '0') {
                if ($CatID == '') {
                    $CI->db->where("id IN(0)");
                } else {
                    $CI->db->where("parent_id IN($CatID)");
                }

            }
            if ($condition == true) {
                if (!empty($key['sort']) && isset($key['sort'])) {
                    $CI->db->order_by($key['sort']['field'], $key['sort']['order']);
                } else {
                    $CI->db->order_by('id', 'desc');
                }
            }
        } else {
            $CI->db->order_by('id', 'desc');
            return false;
        }
    }
}

if (!function_exists('ModifyCondition')) {
    function ModifyCondition($FormData)
    {
        $ConditionArray = array();
        if (!empty($FormData) && isset($FormData['like']) && $FormData['like'] != '') {
            foreach ($FormData['like'] as $likeKey => $like) {
                if (strpos($likeKey, '.') !== false) {
                    $likeKey = str_replace(".", "-", $likeKey);
                }
                if ($FormData['like'][$likeKey] != '') {
                    $ConditionArray['like'][$likeKey] = trim($like);
                }
            }
        }
        if (!empty($FormData) && isset($FormData['equal']) && $FormData['equal'] != '') {
            foreach ($FormData['equal'] as $key => $status) {
                if (strpos($key, '.') !== false) {
                    $key = str_replace(".", "-", $key);
                }
                if ($key == 'status' || $key == 'type' || $key == 'a-status') {
                    if ($status == '0' || $status == '1' || $status == '2' || $status == '3' || $status == '4') {
                        $ConditionArray['equal'][$key] = $status;
                    }
                } else if ($status != '') {
                    $sessionQuery['equal'][$key] = $status;
                }
            }
        }
        if (!empty($FormData) && isset($FormData['sort']) && $FormData['sort'] != '') {
            foreach ($FormData['sort'] as $equalKey => $equal) {
                if (strpos($equalKey, '.') !== false) {
                    $equalKey = str_replace(".", "-", $equalKey);
                }
                if ($equal != '') {
                    $ConditionArray['sort'][$equalKey] = trim($equal);
                }
            }
        }
        return $ConditionArray;
    }
}

if (!function_exists('getUrl')) {
    function getUrl($url)
    {
        if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] != '') {
            $url = $_SERVER['HTTP_REFERER'];
        }
        return $url;

    }

}

if (!function_exists('strWordCut')) {
    function strWordCut($string, $length, $end = '....')
    {
        $string = strip_tags($string);

        if (strlen($string) > $length) {

            // truncate string
            $stringCut = substr($string, 0, $length);

            // make sure it ends in a word so assassinate doesn't become ass...
            $string = substr($stringCut, 0, strrpos($stringCut, ' ')) . $end;
        }
        return $string;
    }
}

if (!function_exists('getUserInfo')) {
    function getUserInfo($userId, $type = 'users')
    {
        $obj =& get_instance();

        $strSQL = "SELECT users.* FROM b_" . $type . " as users WHERE users.id =" . $userId;

        $resSQL = $obj->db->query($strSQL);

        if ($resSQL->num_rows() > 0) {
            $result = $resSQL->row_array();
            return $result;
        } else {
            return false;
        }

    }
}

if (!function_exists('apiAuthentication')) {
    function apiAuthentication($type = 'users')
    {

        $headers = getallheaders();
        $CI =& get_instance();
        $params = $CI->post();
        $loginUserData = array();
        $authKey = isset($params['Authkey']) && $params['Authkey'] != '' ? $params['Authkey'] : '';
        $accessToken = isset($params['Accesstoken']) && $params['Accesstoken'] != '' ? $params['Accesstoken'] : '';
        $userId = isset($params['Userid']) && $params['Userid'] != '' ? $params['Userid'] : '';
        $requested_headers = array('userid' => $userId, 'authkey' => $authKey);
        $errorRes = array();


        if ($authKey == '' || ($authKey != API_AUTH_KEY)) {
            $errorRes = [
                'status' => FALSE,
                'message' => 'bad request',
                'data' => '',
            ];
        } elseif ($userId > 0 && $accessToken == '') {
            $errorRes = [
                'status' => FALSE,
                'logout' => TRUE,
                'message' => 'Logout',
                'data' => '',
            ];
        } elseif ($userId > 0) {
            $loginUserData = getUserInfo($userId, $type);
            if (empty($loginUserData) || (isset($loginUserData['token']) && $accessToken != $loginUserData['token']) || ($loginUserData['status'] != '1')) {
                $errorRes = [
                    'status' => FALSE,
                    'logout' => TRUE,
                    'message' => 'Logout',
                    'data' => '',
                ];
            }
        } elseif ($userId != '') {
            $errorRes = [
                'status' => FALSE,
                'logout' => TRUE,
                'message' => 'Logout2',
                'data' => '',
            ];
        }
        $response = array('requested_headers' => $requested_headers, 'loginUserData' => $loginUserData, 'response' => $errorRes);
        return $response;

    }
}

if (!function_exists('setSelect')) {
    function setSelect($field, $value)
    {
        if (_inputPost($field) == $value || _inputGet($field) == $value) {
            return "selected=''selected";
        } else {
            return '';
        }
    }
}

if (!function_exists('getLatLong')) {
    function getLatLong($address)
    {
        if (!empty($address)) {
            //Formatted address
            $formattedAddr = str_replace(' ', '+', $address);
            //Send request and receive json data by address
            $geocodeFromAddr = file_get_contents('http://maps.googleapis.com/maps/api/geocode/json?address=' . $formattedAddr . '&sensor=false');
            $output = json_decode($geocodeFromAddr);
            //Get latitude and longitute from json data

            if (isset($output->results[0]) && !empty($output->results[0])) {
                $data['latitude'] = $output->results[0]->geometry->location->lat;
                $data['longitude'] = $output->results[0]->geometry->location->lng;
                return $data;
            } else {
                return false;
            }

            /*$data['latitude'] = $output->results[0]->geometry->location->lat;
            $data['longitude'] = $output->results[0]->geometry->location->lng;
            //Return latitude and longitude of the given address
            if (!empty($data)) {
                return $data;
            } else {
                return false;
            }*/
        } else {
            return false;
        }
    }
}

/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
/*::                                                                         :*/
/*::  This routine calculates the distance between two points (given the     :*/
/*::  latitude/longitude of those points). It is being used to calculate     :*/
/*::  the distance between two locations using GeoDataSource(TM) Products    :*/
/*::                                                                         :*/
/*::  Definitions:                                                           :*/
/*::    South latitudes are negative, east longitudes are positive           :*/
/*::                                                                         :*/
/*::  Passed to function:                                                    :*/
/*::    lat1, lon1 = Latitude and Longitude of point 1 (in decimal degrees)  :*/
/*::    lat2, lon2 = Latitude and Longitude of point 2 (in decimal degrees)  :*/
/*::    unit = the unit you desire for results                               :*/
/*::           where: 'M' is statute miles (default)                         :*/
/*::                  'K' is kilometers                                      :*/
/*::                  'N' is nautical miles                                  :*/
/*::  Worldwide cities and other features databases with latitude longitude  :*/
/*::  are available at http://www.geodatasource.com                          :*/
/*::                                                                         :*/
/*::  For enquiries, please contact sales@geodatasource.com                  :*/
/*::                                                                         :*/
/*::  Official Web site: http://www.geodatasource.com                        :*/
/*::                                                                         :*/
/*::         GeoDataSource.com (C) All Rights Reserved 2015		   		     :*/
/*::                                                                         :*/
/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/

function distance($lat1, $lon1, $lat2, $lon2, $unit)
{
    if ($lat1 != '' && $lat2 != '' && $lon1 != '' && $lon2 != '') {
        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $unit = strtoupper($unit);

        if ($unit == "K") {
            return round(($miles * 1.609344), 2);
        } else if ($unit == "N") {
            return round(($miles * 0.8684), 2);
        } else {
            return round($miles, 2);
        }
    } else {
        return 0;
    }


}


if (!function_exists('SkyEncrypt')) {
    function SkyEncrypt($data)
    {
        $key = '650b4SKYhzb7ttl8';
        $blockSize = mcrypt_get_block_size('rijndael-128', 'ecb');
        $len = strlen($data);
        $pad = $blockSize - ($len % $blockSize);
        $data .= str_repeat(chr($pad), $pad);
        $encData = mcrypt_encrypt('rijndael-128', $key, $data, 'ecb');
        return base64_encode($encData);
    }
}

if (!function_exists('SkyDecrypt')) {
    function SkyDecrypt($data)
    {
        $key = '650b4SKYhzb7ttl8';
        $data = base64_decode($data);
        $data = mcrypt_decrypt('rijndael-128', $key, $data, 'ecb');
        $block = mcrypt_get_block_size('rijndael-128', 'ecb');
        $len = strlen($data);
        $pad = ord($data[$len - 1]);
        return substr($data, 0, strlen($data) - $pad);
    }
}

if (!function_exists('SkyEncrypt')) {
    function SkyEncrypt($data)
    {
        $key = '650b4SKYhzb7ttl8';
        $blockSize = mcrypt_get_block_size('rijndael-128', 'ecb');
        $len = strlen($data);
        $pad = $blockSize - ($len % $blockSize);
        $data .= str_repeat(chr($pad), $pad);
        $encData = mcrypt_encrypt('rijndael-128', $key, $data, 'ecb');
        return base64_encode($encData);
    }
}

if (!function_exists('dateTimeView')) {
    function dateTimeView($datetime, $format = 'F d, Y H:i:s', $timezone = 'asia/kolkata')
    {
        if (getSessionUserData('timezone') != '') {
            $timezone = getSessionUserData('timezone');
        }
        //$datetime = date("Y-m-d H:i:s");
        $utc = new DateTime($datetime);
        $utc->setTimezone(new DateTimeZone($timezone));
        echo $utc->format($format);
    }
}

if (!function_exists('dateTimeReturn')) {
    function dateTimeReturn($datetime, $format = 'F d, Y H:i:s', $timezone = 'asia/kolkata')
    {
        $utc = new DateTime($datetime);
        $utc->setTimezone(new DateTimeZone($timezone));
        return $utc->format($format);
    }
}

if (!function_exists('timeStampReturn')) {
    function timeStampReturn($datetime, $timezone = 'UTC')
    {
        $start_date = new DateTime($datetime, new DateTimeZone($timezone));
        $timeStamp = $start_date->format('U');
        return $timeStamp;
    }
}

function createDateRangeArray($strDateFrom, $strDateTo)
{
    $aryRange = array();
    $iDateFrom = mktime(1, 0, 0, substr($strDateFrom, 5, 2), substr($strDateFrom, 8, 2), substr($strDateFrom, 0, 4));
    $iDateTo = mktime(1, 0, 0, substr($strDateTo, 5, 2), substr($strDateTo, 8, 2), substr($strDateTo, 0, 4));

    if ($iDateTo >= $iDateFrom) {
        array_push($aryRange, date('Y-m-d', $iDateFrom)); // first entry
        while ($iDateFrom < $iDateTo) {
            $iDateFrom += 86400; // add 24 hours
            array_push($aryRange, date('Y-m-d', $iDateFrom));
        }
    }
    return $aryRange;
}

if (!function_exists('timeView')) {
    function timeView($seconds, $format = false)
    {
        // pass seconds
        if ($seconds == 0) {
            return '00:00';
        } else {
            $intervals = array('hour' => 3600, 'minute' => 60);

            $minute = floor($seconds / $intervals['minute']);

            $hour = floor($seconds / $intervals['hour']);

            $hours = floor($seconds / $intervals['hour']);
            $minute = floor(($seconds - ($hours * $intervals['hour'])) / $intervals['minute']);

            $hours_output = $hours > 9 ? $hours : '0' . $hours;
            $minute_output = $minute > 9 ? $minute : '0' . $minute;

            //return date($format,$seconds);

            if ($format == true) {
                if ($hours_output >= 12 && $minute_output > 0) {
                    return $hours_output . ":" . $minute_output . " PM";
                } else {
                    return $hours_output . ":" . $minute_output . " AM";
                }
            } else {
                return $hours_output . ":" . $minute_output;
            }

        }


    }
}


function refineSearchString($strForRefine)
{

    $srcName = '';
    $refinedString = '';
    $disAllowChars = array(';', ':', '/', ',', '>', '<', '!', '@', '#', '$', '%', '^', '&', '*', '(', ')', '\'', '_', '=', '"');
    $refinedString = str_replace($disAllowChars, ' ', preg_replace('/[^A-Za-z0-9\-\.\']/', ' ', $strForRefine));
    //$refinedString = str_replace('-',' ',preg_replace('/[^A-Za-z0-9\-\']/',' ', $strForRefine));
    if (strlen(trim($refinedString)) > 0) {
        $srcNam_old = explode(' ', $refinedString);
        if ($srcNam_old) {
            foreach ($srcNam_old as $s) {
                if (strlen($s) >= 1) {
                    $srcName[] = $s;
                }
            }
        }
    }
    return $srcName;

}

function MakeSearchqry($field_name = array(), $value)
{
    $obj = &get_instance();
    $BeforeRefinedString = $value;
    $if_condition = '';
    $refinedStringArr = refineSearchString($value);
    if ($field_name && $refinedStringArr) {
        $value = implode(' ', $refinedStringArr);
        $f = 0;
        $count = 1;
        $finalElseClause = "0";
        $if_condition = '';
        $brackitclosecount = '';


        foreach ($field_name as $field) {
            if (count($refinedStringArr)) {
                //$this->db->escape($BeforeRefinedString)
                $if_condition .= "if( " . $field . " = " . $obj->db->escape($BeforeRefinedString) . " ," . $count . ",if( " . $field . " LIKE " . $obj->db->escape($BeforeRefinedString . '%') . "," . ($count + 1) . ",if( " . $field . " LIKE " . $obj->db->escape('%' . $BeforeRefinedString . '%') . "," . ($count + 2) . ",";
                $brackitclosecount = $brackitclosecount + 3;
                $count = $count + 2;
                $count++;


                if (count($refinedStringArr) > 0) {
                    $array_and = array();
                    foreach ($refinedStringArr as $StringArr) {
                        $array_and[] = ' ' . $field . ' LIKE "%' . $StringArr . '%" ';
                    }
                    if (!empty($array_and)) {
                        $if_condition .= " if(" . implode(' AND ', $array_and) . ", " . $count . ", ";
                        $brackitclosecount++;
                        $count++;
                    }
                }

                if (count($refinedStringArr) > 1) {
                    $innerCount = $count;
                    for ($i = 0; $i < count($refinedStringArr); $i++) {
                        $if_condition .= " if(" . $field . " LIKE '%" . $refinedStringArr[$i] . "%', " . $innerCount . ", ";
                        $brackitclosecount++;
                        $innerCount++;
                    }
                    $count++;
                }
            }

            if (count($field_name) == ($f + 1)) {
                $if_condition .= $finalElseClause . str_repeat(" ) ", $brackitclosecount);
            }

            $f++;
        }
    }

    return $if_condition;

}

if (!function_exists('_xssClean')) {
    function _xssClean($data)
    {
        $CI =& get_instance();
        if (is_array($data)) {
            foreach ($data as $key => $val) {
                $data[$key] = $CI->security->xss_clean($val);
            }
        }
        return $data;
    }
}

if (!function_exists('checkValues')) {
    function checkValues($value)
    {
        // Use this function on all those values where you want to check for both sql injection and cross site scripting
        //Trim the value
        $value = trim($value);

        // Stripslashes
        if (get_magic_quotes_gpc()) {
            $value = stripslashes($value);
        }

        // Convert all &lt;, &gt; etc. to normal html and then strip these
        $value = strtr($value, array_flip(get_html_translation_table(HTML_ENTITIES)));
        //$this->db->escape();
        // Strip HTML Tags
        $value = strip_tags($value);

        // Quote the value
        $value = mysql_real_escape_string($value);
        return $value;

    }
}

if (!function_exists('get_lat_long_from_address')) {
    function get_lat_long_from_address($address)
    {
        $response = array('latitude' => '', 'longitude' => '');
        $geo = file_get_contents('http://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode($address) . '&sensor=false');

        // Convert the JSON to an array
        $geo = json_decode($geo, true);

        if ($geo['status'] == 'OK') {
            // Get Lat & Long
            $response['latitude'] = $geo['results'][0]['geometry']['location']['lat'];
            $response['longitude'] = $geo['results'][0]['geometry']['location']['lng'];
        }

        return $response;

    }
}

if (!function_exists('closetags')) {
    function closetags($html)
    {
        preg_match_all('#<(?!meta|img|br|hr|input\b)\b([a-z]+)(?: .*)?(?<![/|/ ])>#iU', $html, $result);
        $openedtags = $result[1];
        preg_match_all('#</([a-z]+)>#iU', $html, $result);
        $closedtags = $result[1];
        $len_opened = count($openedtags);
        if (count($closedtags) == $len_opened) {
            return $html;
        }
        $openedtags = array_reverse($openedtags);
        for ($i = 0; $i < $len_opened; $i++) {
            if (!in_array($openedtags[$i], $closedtags)) {
                $html .= '</' . $openedtags[$i] . '>';
            } else {
                unset($closedtags[array_search($openedtags[$i], $closedtags)]);
            }
        }
        return $html;
    }
}

function replaceImgSrc($img_tag)
{
    $doc = new DOMDocument();
    $doc->loadHTML($img_tag);
    $tags = $doc->getElementsByTagName('img');
    foreach ($tags as $tag) {
        $old_src = $tag->getAttribute('src');
        $old_src = str_replace('', '', trim($old_src));
        $new_src_url = preg_replace('/([^:])(\/{2,})/', '$1/', base_url() . $old_src);
        $tag->setAttribute('src', $new_src_url);
    }

    return $doc->saveHTML();
}

if (!function_exists('getSlugByTitle')) {
    function getSlugByTitle($table, $column_name, $slug)
    {
        $obj =& get_instance();
        $strSQL = 'SELECT title FROM ' . $table . ' where ' . $column_name . ' = "' . $slug . '"';
        $resSQL = $obj->db->query($strSQL);
        if ($resSQL->num_rows()) {
            $title = $resSQL->row_array()['title'];
            return $title;
        } else {
            return 'N/A';
        }
    }
}

if (!function_exists('randomGenerateNumber')) {
    function randomGenerateNumber($length = 6)
    {
        $vowels = '123456789';
        $consonants = '0123456789';
        //$consonants  .= '!@#$%^&*_-()';
        $password = '';
        $alt = time() % 2;
        for ($i = 0; $i < $length; $i++) {
            if ($alt == 1) {
                $password .= $consonants[(rand() % strlen($consonants))];
                $alt = 0;
            } else {
                $password .= $vowels[(rand() % strlen($vowels))];
                $alt = 1;
            }
        }
        return $password;
    }
}

if (!function_exists('secret_mail')) {
    function secret_mail($email)
    {
        $start = '';
        $end = '';
        $prop = 2;
        $domain = substr(strrchr($email, "@"), 1);
        $mailname = str_replace($domain, '', $email);
        $name_l = strlen($mailname);
        $domain_l = strlen($domain);
        for ($i = 0; $i <= $name_l / $prop - 1; $i++) {
            $start .= 'x';
        }

        for ($i = 0; $i <= $domain_l / $prop - 1; $i++) {
            $end .= 'x';
        }

        return substr_replace($mailname, $start, 2, $name_l / $prop) . substr_replace($domain, $end, 2, $domain_l / $prop);
    }
}

if (!function_exists('formattedAmount')) {
    function formattedAmount($amount, $precision = 2)
    {
        $sign = '';
        if ($amount < 0) {
            $sign = '-';
        }
        if ($amount == '') {
            $amount = 0;
        }
        // Remove anything that isn't a number or decimal point.
        $amount = trim(preg_replace('/([^0-9\.])/i', '', $amount));
        /*$symbol = '₹';*/
        /*$symbol = "<i class='fas fa-dollar-sign'></i>&nbsp;";*/

        $symbol = "R&nbsp;";

        return $sign . $symbol . number_format($amount, $precision, '.', ',');
    }
}

if (!function_exists('getAllSliders')) {
    function getAllSliders()
    {
        $obj =& get_instance();
        $strSQL = "SELECT image FROM b_sliders WHERE status = '1' ORDER BY id ASC ";
        $resSQL = $obj->db->query($strSQL);
        if ($resSQL->num_rows() > 0) {
            $result = $resSQL->result_array();
            return $result;
        } else {
            return false;
        }

    }
}

function time_elapsed_string($datetime, $full = false)
{
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}


if (!function_exists('getAdminRoleInfo')) {
    function getAdminRoleInfo($roleid, $table)
    {
        $obj =& get_instance();

        $strSQL = "SELECT role_id, roll_name FROM " . $table . " WHERE role_id =" . $roleid;

        $resSQL = $obj->db->query($strSQL);

        if ($resSQL->num_rows() > 0) {
            $result = $resSQL->row_array();
            return $result;
        } else {
            return false;
        }

    }

if (!function_exists('isLoggedIn')) {
    function isLoggedIn()
        {
            if (getSessionUserData("logged_in") == FALSE && getSessionUserData("id") == "" && empty(getSessionUserData("id"))) 
                {
                    redirect(base_url());  
                }
        }
    }



if (!function_exists('getAllTableData')) {
    function getAllTableData($table)
        {
            $obj =& get_instance();

            $strSQL = "SELECT * FROM " . $table;

            $resSQL = $obj->db->query($strSQL);

            if ($resSQL->num_rows() > 0) {
                $result = $resSQL->result();
                return $result;
            } else {
                return false;
            }
        }
    }


if (!function_exists('slugify')) {
        function slugify($text)
        {
          // replace non letter or digits by -
          $text = preg_replace('~[^\pL\d]+~u', '-', $text);

          // transliterate
          $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

          // remove unwanted characters
          $text = preg_replace('~[^-\w]+~', '', $text);

          // trim
          $text = trim($text, '-');

          // remove duplicate -
          $text = preg_replace('~-+~', '-', $text);

          // lowercase
          $text = strtolower($text);

          if (empty($text)) {
            return 'n-a';
          }

          return $text;
        }
}

   
if (!function_exists('getWhereWithId')) {  
    function getWhereWithId($table, $where){  
       $CI =& get_instance();
        $CI->load->database();
       return $CI->db->where($where)->get($table)->row_array();
        
    }
}


if (!function_exists('getWhereWithIdWithColumns')) {
    function getWhereWithIdWithColumns($table, $where, $columns){  
       $CI =& get_instance();
        $CI->load->database();
        return $CI->db->select($columns)->where($where)->get($table)->row_array();
    }
}

if (!function_exists('getWhereWithIdWithColumnsAll')) {
    function getWhereWithIdWithColumnsAll($table, $where, $columns){  
       $CI =& get_instance();
        $CI->load->database();
        return $CI->db->select($columns)->where($where)->get($table)->result_array();
    }
}

if (!function_exists('getWhereWithIdWithAll')) {
    function getWhereWithIdWithAll($table, $where){  
       $CI =& get_instance();
        $CI->load->database();
        return $CI->db->where($where)->get($table)->result_array();
    }
}

if (!function_exists('getWhereWithIdWithAllByOrder')) {
    function getWhereWithIdWithAllByOrder($table, $column, $where){  
       $CI =& get_instance();
        $CI->load->database();
        return $CI->db->where($where)->order_by($column, "desc")->get($table)->result_array();
    }
}


if (!function_exists('getleads')) {
    function getleads($table, $column, $where, $page=1, $keyword){  
        $CI =& get_instance();
         if(isset($page) && $page == 1 )
           {
             $startPoint = 0;
           }
       else
           {
             $startPoint = PAGE*($page-1)+1;
           }
        $CI->load->database();
        if(isset($keyword) && !empty($keyword))
            {
              return $CI->db->where($where)->where("(`project_details` LIKE '%".$keyword."%' ESCAPE '!'
            OR  `cidb_rating` LIKE '%".$keyword."%' ESCAPE '!'
            OR  `client` LIKE '%".$keyword."%' ESCAPE '!'
            OR  `closing_date` LIKE '%".$keyword."%' ESCAPE '!'
            OR  `deposit` LIKE '%".$keyword."%' ESCAPE '!'
            OR  `site_briefing_location` LIKE '%".$keyword."%' ESCAPE '!')")->order_by($column, "desc")->limit(PAGE, $startPoint)->get($table)->result_array();
            }
        else
            {
              return $CI->db->where($where)->order_by($column, "desc")->limit(PAGE, $startPoint)->get($table)->result_array();
            }
    }
}


if (!function_exists('getWhereWithIdWithAllByOrderWithPagination')) {
    function getWhereWithIdWithAllByOrderWithPagination($table, $column, $where, $page=1, $keyword){  
       $CI =& get_instance();
       if(isset($page) && $page == 1 )
           {
             $startPoint = 0;
           }
       else
           {
             $startPoint = PAGE*($page-1)+1;
           }
        $CI->load->database();
        if(isset($keyword) && !empty($keyword))
            {
              return $CI->db->where($where)->where("(`SiteCode` LIKE '%".$keyword."%' ESCAPE '!'
            OR  `TransactionId` LIKE '%".$keyword."%' ESCAPE '!'
            OR  `TransactionReference` LIKE '%".$keyword."%' ESCAPE '!'
            OR  `Amount` LIKE '%".$keyword."%' ESCAPE '!'
            OR  `Status` LIKE '%".$keyword."%' ESCAPE '!'
            OR  `packtype` LIKE '%".$keyword."%' ESCAPE '!'
            OR  `email` LIKE '%".$keyword."%' ESCAPE '!' OR  `name` LIKE '%".$keyword."%' ESCAPE '!')")->order_by($column, "desc")->limit(PAGE, $startPoint)->get($table)->result_array();
            }
        else
            {
              return $CI->db->where($where)->order_by($column, "desc")->limit(PAGE, $startPoint)->get($table)->result_array();
            }
        }
    }



if (!function_exists('getQuotes')) {
    function getQuotes($table, $column, $where, $page=1, $keyword){  
       $CI =& get_instance();
       if(isset($page) && $page == 1 )
           {
             $startPoint = 0;
           }
       else
           {
             $startPoint = PAGE*($page-1)+1;
           }
        $CI->load->database();
        if(isset($keyword) && !empty($keyword))
            {
              return $CI->db->where($where)->where("(`quote_type_name` LIKE '%".$keyword."%' ESCAPE '!'
            OR  `name` LIKE '%".$keyword."%' ESCAPE '!'
            OR  `surname` LIKE '%".$keyword."%' ESCAPE '!'
            OR  `contact` LIKE '%".$keyword."%' ESCAPE '!'
            OR  `email` LIKE '%".$keyword."%' ESCAPE '!')")->order_by($column, "desc")->limit(PAGE, $startPoint)->get($table)->result_array();
            }
        else
            {
              return $CI->db->where($where)->order_by($column, "desc")->limit(PAGE, $startPoint)->get($table)->result_array();
            }
        }
    }


if (!function_exists('getQuotesExtension')) {   // geting the quote extension  request
    function getQuotesExtension($table, $column, $where, $page=1, $keyword){  
       $CI =& get_instance();
       if(isset($page) && $page == 1 )
           {
             $startPoint = 0;
           }
       else
           {
             $startPoint = PAGE*($page-1)+1;
           }
        $CI->load->database();
        if(isset($keyword) && !empty($keyword))
            {
              return $CI->db->where($where)->where("(`policy_number` LIKE '%".$keyword."%' ESCAPE '!'
            OR  `insurer` LIKE '%".$keyword."%' ESCAPE '!'
            OR  `project_description` LIKE '%".$keyword."%' ESCAPE '!'
            OR  `extension_request` LIKE '%".$keyword."%' ESCAPE '!')")->order_by($column, "desc")->limit(PAGE, $startPoint)->get($table)->result_array();
            }
        else
            {
              return $CI->db->where($where)->order_by($column, "desc")->limit(PAGE, $startPoint)->get($table)->result_array();
            }
        }
    }




if (!function_exists('getConsentForm')) {   // geting the get consent form  request
    function getConsentForm($table, $column, $where, $page=1, $keyword){  
       $CI =& get_instance();
       if(isset($page) && $page == 1 )
           {
             $startPoint = 0;
           }
       else
           {
             $startPoint = PAGE*($page-1)+1;
           }
        $CI->load->database();
        if(isset($keyword) && !empty($keyword))
            {
              return $CI->db->where($where)->where("(`full_name` LIKE '%".$keyword."%' ESCAPE '!')")->order_by($column, "desc")->limit(PAGE, $startPoint)->get($table)->result_array();
            }
        else
            {
              return $CI->db->where($where)->order_by($column, "desc")->limit(PAGE, $startPoint)->get($table)->result_array();
            }
        }
    }



if (!function_exists('getOrders')) {
    function getOrders($table, $column, $where, $page=1, $keyword){  
       $CI =& get_instance();
       if(isset($page) && $page == 1 )
           {
             $startPoint = 0;
           }
       else
           {
             $startPoint = PAGE*($page-1)+1;
           }
        $CI->load->database();
        if(isset($keyword) && !empty($keyword))
            {
              return $CI->db->where($where)->where("(`contact_person` LIKE '%".$keyword."%' ESCAPE '!'
            OR  `name_of_client` LIKE '%".$keyword."%' ESCAPE '!'
            OR  `contract_number` LIKE '%".$keyword."%' ESCAPE '!'
            OR  `contract_amount` LIKE '%".$keyword."%' ESCAPE '!'
            OR  `contractors_name` LIKE '%".$keyword."%' ESCAPE '!' OR  `contract_description` LIKE '%".$keyword."%' ESCAPE '!')")->order_by($column, "desc")->limit(PAGE, $startPoint)->get($table)->result_array();
            }
        else
            {
              return $CI->db->where($where)->order_by($column, "desc")->limit(PAGE, $startPoint)->get($table)->result_array();
            }
        }
    }


/*---------------------supplier data--------------------------------*/

if (!function_exists('getSupplier')) {
    function getSupplier($table, $column, $where, $page=1, $keyword){  
       $CI =& get_instance();
       if(isset($page) && $page == 1 )
           {
             $startPoint = 0;
           }
       else
           {
             $startPoint = PAGE*($page-1)+1;
           }
        $CI->load->database();
        if(isset($keyword) && !empty($keyword))
            {
              return $CI->db->where($where)->where("(`category_name` LIKE '%".$keyword."%' ESCAPE '!'
            OR  `company` LIKE '%".$keyword."%' ESCAPE '!'
            OR  `telephone` LIKE '%".$keyword."%' ESCAPE '!'
            OR  `email` LIKE '%".$keyword."%' ESCAPE '!'
            OR  `address` LIKE '%".$keyword."%' ESCAPE '!')")->order_by($column, "desc")->limit(PAGE, $startPoint)->get($table)->result_array();
            }
        else
            {
              return $CI->db->where($where)->order_by($column, "desc")->limit(PAGE, $startPoint)->get($table)->result_array();
            }
        }
    }



/*---------------------project list data--------------------------------*/

if (!function_exists('getprojectList')) {
    function getprojectList($table, $column, $where, $page=1, $keyword){  
       $CI =& get_instance();
       if(isset($page) && $page == 1 )
           {
             $startPoint = 0;
           }
       else
           {
             $startPoint = PAGE*($page-1)+1;
           }
        $CI->load->database();
        if(isset($keyword) && !empty($keyword))
            {
              return $CI->db->where($where)->where("(`project_description` LIKE '%".$keyword."%' ESCAPE '!'
            OR  `client` LIKE '%".$keyword."%' ESCAPE '!'
            OR  `site_manager_forman` LIKE '%".$keyword."%' ESCAPE '!'
            OR  `project_addreweather` LIKE '%".$keyword."%' ESCAPE '!')")->order_by($column, "desc")->limit(PAGE, $startPoint)->get($table)->result_array();
            }
        else
            {
              return $CI->db->where($where)->order_by($column, "desc")->limit(PAGE, $startPoint)->get($table)->result_array();
            }
        }
    }



/*---------------------project list diary data--------------------------------*/

if (!function_exists('getDiaryList')) {
    function getDiaryList($table, $column, $where, $page=1, $keyword){  
       $CI =& get_instance();
       if(isset($page) && $page == 1 )
           {
             $startPoint = 0;
           }
       else
           {
             $startPoint = PAGE*($page-1)+1;
           }
        $CI->load->database();
        if(isset($keyword) && !empty($keyword))
            {
              return $CI->db->where($where)->where("(`weather` LIKE '%".$keyword."%' ESCAPE '!'
            OR  `visitors` LIKE '%".$keyword."%' ESCAPE '!'
            OR  `plant_hired` LIKE '%".$keyword."%' ESCAPE '!'
            OR  `materials` LIKE '%".$keyword."%' ESCAPE '!'
            OR  `work_progress` LIKE '%".$keyword."%' ESCAPE '!'
            OR  `delays` LIKE '%".$keyword."%' ESCAPE '!'
            OR  `inspection` LIKE '%".$keyword."%' ESCAPE '!'
            OR  `accidents_incidents` LIKE '%".$keyword."%' ESCAPE '!'
            OR  `instruction_variations` LIKE '%".$keyword."%' ESCAPE '!')")->order_by($column, "desc")->limit(PAGE, $startPoint)->get($table)->result_array();
            }
        else
            {
              return $CI->db->where($where)->order_by($column, "desc")->limit(PAGE, $startPoint)->get($table)->result_array();
            }
        }
    }



/*---------------------project list diary data for admin--------------------------------*/

if (!function_exists('getDiaryListForClientAdmin')) {
    function getDiaryListForClientAdmin($table, $column, $where, $page=1, $keyword){  
       $CI =& get_instance();
       if(isset($page) && $page == 1 )
           {
             $startPoint = 0;
           }
       else
           {
             $startPoint = PAGE*($page-1)+1;
           }
        $CI->load->database();
        if(isset($keyword) && !empty($keyword))
            {
              return $CI->db->where($where)->where("(`weather` LIKE '%".$keyword."%' ESCAPE '!'
            OR  `visitors` LIKE '%".$keyword."%' ESCAPE '!'
            OR  `plant_hired` LIKE '%".$keyword."%' ESCAPE '!'
            OR  `materials` LIKE '%".$keyword."%' ESCAPE '!'
            OR  `work_progress` LIKE '%".$keyword."%' ESCAPE '!'
            OR  `delays` LIKE '%".$keyword."%' ESCAPE '!'
            OR  `inspection` LIKE '%".$keyword."%' ESCAPE '!'
            OR  `accidents_incidents` LIKE '%".$keyword."%' ESCAPE '!'
            OR  `instruction_variations` LIKE '%".$keyword."%' ESCAPE '!')")->order_by($column, "desc")->limit(PAGE, $startPoint)->get($table)->result_array();
            }
        else
            {
              return $CI->db->where($where)->order_by($column, "desc")->limit(PAGE, $startPoint)->get($table)->result_array();
            }
        }
    }




/*---------------------End User list data--------------------------------*/

if (!function_exists('getEndUserList')) {
    function getEndUserList($table, $column, $where, $page=1, $keyword){  
       $CI =& get_instance();
       if(isset($page) && $page == 1 )
           {
             $startPoint = 0;
           }
       else
           {
             $startPoint = PAGE*($page-1)+1;
           }
        $CI->load->database();
        if(isset($keyword) && !empty($keyword))
            {
              return $CI->db->where($where)->where("(`contact_person_first_name` LIKE '%".$keyword."%' ESCAPE '!'
            OR  `contact_person_last_name` LIKE '%".$keyword."%' ESCAPE '!'
            OR  `cell_number` LIKE '%".$keyword."%' ESCAPE '!'
            OR  `email` LIKE '%".$keyword."%' ESCAPE '!'
            )")->order_by($column, "desc")->limit(PAGE, $startPoint)->get($table)->result_array();
            }
        else
            {
              return $CI->db->where($where)->order_by($column, "desc")->limit(PAGE, $startPoint)->get($table)->result_array();
            }
        }
    }

/*---------------------All project list for client admin--------------------------------*/

if (!function_exists('getClientAdminProjectList')) {
    function getClientAdminProjectList($table, $column, $where, $page=1, $keyword){  
       $CI =& get_instance();
       if(isset($page) && $page == 1 )
           {
             $startPoint = 0;
           }
       else
           {
             $startPoint = PAGE*($page-1)+1;
           }
        $CI->load->database();
        if(isset($keyword) && !empty($keyword))
            {
              return $CI->db->where_in("end_user_id",$where)->where("(`project_description` LIKE '%".$keyword."%' ESCAPE '!'
            OR  `client` LIKE '%".$keyword."%' ESCAPE '!'
            OR  `site_manager_forman` LIKE '%".$keyword."%' ESCAPE '!'
            OR  `project_addreweather` LIKE '%".$keyword."%' ESCAPE '!')")->order_by($column, "desc")->limit(PAGE, $startPoint)->get($table)->result_array();
            }
        else
            {
              return $CI->db->where_in("end_user_id",$where)->order_by($column, "desc")->limit(PAGE, $startPoint)->get($table)->result_array();
            }
        }
    }



if (!function_exists('getBoq')) {
    function getBoq($table, $column, $where, $page=1, $keyword){  
        $CI =& get_instance();
         if(isset($page) && $page == 1 )
           {
             $startPoint = 0;
           }
       else
           {
             $startPoint = PAGE*($page-1)+1;
           }
        $CI->load->database();
        if(isset($keyword) && !empty($keyword))
            {
              return $CI->db->where($where)->where("(`project_number` LIKE '%".$keyword."%' ESCAPE '!'
            OR  `project_name` LIKE '%".$keyword."%' ESCAPE '!'
            OR  `client_name` LIKE '%".$keyword."%' ESCAPE '!'
            OR  `project_type_name` LIKE '%".$keyword."%' ESCAPE '!')")->order_by($column, "desc")->limit(PAGE, $startPoint)->get($table)->result_array();
            }
        else
            {
              return $CI->db->where($where)->order_by($column, "desc")->limit(PAGE, $startPoint)->get($table)->result_array();
            }
    }
}





if (!function_exists('getClientAdminProjectListForSelect')) {
    function getClientAdminProjectListForSelect($table, $column, $where){  
       $CI =& get_instance();
       
        $CI->load->database();
      
              return $CI->db->where_in("end_user_id",$where)->where("(`project_description` LIKE '%".$keyword."%' ESCAPE '!'
            OR  `client` LIKE '%".$keyword."%' ESCAPE '!'
            OR  `site_manager_forman` LIKE '%".$keyword."%' ESCAPE '!'
            OR  `project_addreweather` LIKE '%".$keyword."%' ESCAPE '!')")->order_by($column, "desc")->get($table)->result_array();
         
        
        }
    }




if (!function_exists('SendEmailByTest')) {
    function SendEmailByTest()
    {  
            $CI =& get_instance();

            $mailFrom = "monuk@triazinesoft.com";
            $mailTo = "monuk@triazinesoft.com";
            $mailFromName = "Monu kumar";
            $msg_subject = "Test Subject";
            $msg_body = "Hi this is monu kumar";
            $CI->viewData['msg_body'] = $msg_body;
            $msg_header = $CI->load->view('email/header', $CI->viewData, true);
            $full_msg_body = $msg_header;
            $CI->load->library('email');
            // does not have to be gmail
            $config['protocol'] = "smtp";
            $config['smtp_host'] = 'mail.triazinesoft.com';
            $config['smtp_port'] = '587';
            $config['smtp_user'] = 'rinfo@triazinesoft.com';
            $config['smtp_pass'] = 'triazine@123';
            $config['mailtype'] = 'html';
            $config['charset'] = 'utf-8';
            $config['newline'] = "\r\n";
            $config['wordwrap'] = TRUE;
            $CI->email->initialize($config);
            $CI->email->set_newline("\r\n");
            $CI->email->from($mailFrom, $mailFromName);
            $CI->email->to($mailTo);
            $CI->email->subject($msg_subject);
            $CI->email->message($full_msg_body);
            @$CI->email->send();

            return true;
       
        }

    }


    if (!function_exists('peckExpirationdate')) {
    function peckExpirationdate($satrtdate, $enddate)  // checking the plan validity
    {  
            $CI =& get_instance();
            $accessdate = date('Y-m-d');
            $accessdate=date('Y-m-d', strtotime($accessdate));
            //echo $paymentDate; // echos today! 
            $accessDateBegin = date('Y-m-d', strtotime($satrtdate));
            $endDateEnd = date('Y-m-d', strtotime($enddate));

            if (($accessdate >= $accessDateBegin) && ($accessdate <= $endDateEnd)){
                return 1; 
            }else{
                return 0;  
            }
       
        }

    }



if (!function_exists('getMinutes')) {
    function getMinutes($satrtdatetime, $enddatetime)  // checking the plan validity
        {  
             $time1 = strtotime($enddatetime);  // 2012-12-06 23:56
             $time2 = strtotime($satrtdatetime);  // 2012-12-06 00:21
             $minutes =  ($time1 - $time2)/60;
             return  $minutes;   
           
        }

    }



    //notification($body, $title, $registrationIds, $path);
    // sending the notification to cutomer on order or quote creation ...

    if (!function_exists('notification')) {
        function notification($body, $title, $registrationIds)
            {    
               
                define('API_ACCESS_KEY', 'AAAAe3FQ9GM:APA91bGtXkamS1cPfKLywygdwlXavxoFJL711HC0kU8XAmQTjlIJdbaBXeX686ElOWQJ261osqiXmMZj6HAAN69xaHM_wnycSKtitZmr3ruSOUpM_C91jyqutVgltr7BEbWtVkU-Lzvc');  // server key
                $msg = array
                (
                    'body' => $body,
                    'title' => $title,
                    /*'image' => 'https://cdn.pixabay.com/photo/2015/12/01/20/28/road-1072823__340.jpg',*/
                );
                $fields = array
                (
                    'registration_ids' => array($registrationIds),
                    'notification' => $msg
                );
                $headers = array
                (
                    'Authorization: key=' . API_ACCESS_KEY,
                    'Content-Type: application/json'
                );
                #Send Reponse To FireBase Server
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
                $result = curl_exec($ch);
                if ($result === FALSE) {
                    die('Oops! FCM Send Error: ' . curl_error($ch));
                }
                curl_close($ch);
                $result = json_decode($result); 
                return $result->success; 
            }
        }


    //payment search 
    if (!function_exists('paymentSearch')) 
        {
        function paymentSearch($keyword,$table, $column, $where, $page=1)  //payment search 
        {  
                $CI =& get_instance();
                if(isset($page) && $page == 1 )
                   {
                     $startPoint = 0;
                   }
               else
                   {
                     $startPoint = PAGE*($page-1)+1;
                   }
                $CI->load->database();
                return $CI->db->where($where)->order_by($column, "desc")->like('SiteCode', $keyword, 'both')->or_like("TransactionId", $keyword, "both")->or_like("TransactionReference", $keyword, "both")->or_like("Amount", $keyword, "both")->or_like("Status", $keyword, "both")->or_like("packtype", $keyword, "both")->or_like("email", $keyword, "both")->or_like("name", $keyword, "both")->limit(PAGE, $startPoint)->get($table)->result_array();
           
            }

        }

    if (!function_exists('totalPaymentSearch')) 
        {
        function totalPaymentSearch($keyword,$table, $column, $where)  //payment search 
        {  
                $CI =& get_instance();
                $CI->load->database();
                return $CI->db->where($where)->order_by($column, "desc")->like('SiteCode', $keyword, 'both')->or_like("TransactionId", $keyword, "both")->or_like("TransactionReference", $keyword, "both")->or_like("Amount", $keyword, "both")->or_like("Status", $keyword, "both")->or_like("packtype", $keyword, "both")->or_like("email", $keyword, "both")->or_like("name", $keyword, "both")->get($table)->result_array();
           
            }

        }


if (!function_exists('sendSms')) {
     function sendSms($mobile = "+919717943954", $message = "Hi", $type=1)
        {
                if($type == 1)
                {
                     $textmessage = "Maadima Leads Address : ". trim($message);
                }
                else
                {
                     $textmessage = "Maadima Supplier Address : ". trim($message);
                }
               
                ini_set('display_errors', 1);
                ini_set('display_startup_errors', 1);
                error_reporting(E_ALL);
                $xml="<x:Envelope ";
                $xml.="xmlns:x='http://schemas.xmlsoap.org/soap/envelope/' ";
                    $xml.="xmlns:soa='https://soap.gsm.co.za/'> ";
                    $xml.="<x:Header/> ";
                    $xml.="<x:Body>";
                        $xml.="<soa:SendSMS>";
                            $xml.="<soa:username>Altarplus</soa:username> ";
                            $xml.="<soa:password>Matome28</soa:password> ";
                            $xml.="<soa:number>".$mobile."</soa:number> ";
                            $xml.="<soa:message>".$textmessage."</soa:message> ";
                        $xml.="</soa:SendSMS> ";
                    $xml.="</x:Body> ";
                $xml.="</x:Envelope> ";
                $data = $xml;
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, "https://soap.gsm.co.za/xml2sms.asmx");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type:text/xml; charset=utf-8", "Content-Length: " . strlen($data)));
                $output = curl_exec($ch);
                curl_close($ch);
                //echo $output;
                //$fileContents = trim(str_replace('"', "'", $output));
                //$fileContents = trim(str_replace('soap:', "", $fileContents));
                //$response_body=$fileContents;
                //libxml_use_internal_errors(true);
               /* $sxe = simplexml_load_string($response_body);
                echo '<pre>';
                print_r($sxe->Body->SendSMSResponse->SendSMSResult->aatsms->submitresult);
                die;*/
                return true;
        }
}



if (!function_exists('get_len_of_word')) {
    function get_len_of_word($str,$number){ 

            $array_str = explode(" ", $str);
               if(isset($array_str[$number]))
               {
                 return implode(" ",array_slice($array_str, 0, $number));
               }
             return $str; 
      
        }
    }




}




