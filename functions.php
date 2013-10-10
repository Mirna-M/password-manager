<?php
function db_connect ($host, $db, $user, $pass) {
    $connect = mysql_connect($host, $user, $pass);
    if($connect === false) {
        return $connect;
    }
    
    $db = mysql_select_db($db);
    if($db === false) {
        return $db;
    }
    
    mysql_query('SET NAMES UTF8');
    
    return $connect;
}

function delete_row($id) {
    $query = 'DELETE FROM user_data WHERE user_data_id='.$id;
    return mysql_query($query);
}

function our_encrypt($encrypt, $mc_key) {
        $iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256,
MCRYPT_MODE_ECB), MCRYPT_RAND);
        $passcrypt = trim(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, substr($mc_key,
256), trim($encrypt), MCRYPT_MODE_ECB, $iv));
        $encode = base64_encode($passcrypt);
        return $encode;
}

function add_user($nickname, $password) {
    $password = md5($password);
    $query = 'INSERT INTO users (user_login, user_pass)
            VALUES ("'.$nickname.'", "'.$password.'")';
    
    return mysql_query($query);
}

function add_row($user_data_user_id, $data_for, $name, $password) {
    $name = our_encrypt($name, USERNAME_MC_KEY);
    $password = our_encrypt($password, PASSWORD_MC_KEY);
    $query = 'INSERT INTO user_data (user_data_for, user_data_name,
            user_data_password, user_data_user_id)
            VALUES ("'.$data_for.'", "'.$name.'", "'.$password.'", "'.$user_data_user_id.'")';
    
    $result = mysql_query($query);
    if($result === false) {
        return $result;
    } else {
        return mysql_insert_id();
    }
}

function update_row($id, $data_for, $name, $password) {
    $name = our_encrypt($name, 'USERNAME_MC_KEY');
    $password = our_encrypt($password, 'PASSWORD_MC_KEY');
    
    $query = 'UPDATE user_data SET user_data_for="'.$data_for.'",
              user_data_name="'.$name.'", user_data_password="'
              .$password.'" WHERE user_data_id='.$id;
    
    return mysql_query($query);
}

function select_to_array ($query) {
    $return = array ();
    $result = mysql_query($query);
    while (($row = mysql_fetch_assoc($result))) {
        $return[] = $row;
    }
    return $return;
}

function our_decrypt($decrypt, $mc_key) {
        $decoded = base64_decode($decrypt);
        $iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256,
MCRYPT_MODE_ECB), MCRYPT_RAND);
        $decrypted = trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, substr($mc_key,
256), trim($decoded), MCRYPT_MODE_ECB, $iv));
        return $decrypted;
}



?>
