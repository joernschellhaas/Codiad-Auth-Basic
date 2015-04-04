<?php
/*
 * Copyright (c) Codiad & Andr3as & Jörn Schellhaas, distributed
 * as-is and without warranty under the MIT License. 
 * See [root]/license.md for more information. This information must remain intact.
 */
 
    define("PWDFILE", "../../.htpasswd");

    require_once('../../common.php');
    
    checkSession();

    require_once('../../components/user/class.user.php');

    require('../../components/user/controller.php');
    
    //error_reporting(E_ALL);

     // create .htaccess
    switch($_GET['action']){
        case 'create':
        case 'password':
        case 'delete':
            // read .htaccess
            $lines = array();
            $htpasswd = array();
            if(is_file(PWDFILE)) $lines=explode("\n", file_get_contents(PWDFILE));
            foreach($lines as $l){
                $up = explode(":", $l, 2);
                if(count($up) == 2 && $up[0] != '' && $up[1] != '') $htpasswd[$up[0]]=$up[1];
            }

            switch($_GET['action']){
                
                case 'create':
                case 'password':
                    $htpasswd[$User->username] = crypt_apr1_md5($_POST['password']);
                    break;
                    
                case 'delete':
                    if(isset($htpasswd[$User->username])) unset($htpasswd[$User->username]);
                    break;

            }
            
            // write .htaccess
            $lines = array();
            foreach($htpasswd as $user=>$pass){
                $lines[] = $user.':'.$pass;
            }
            file_put_contents(PWDFILE, implode("\n", $lines));
    }

    // END

            
    
    // Thanks to php.net and mikey_nich@hotmail.com !
    function crypt_apr1_md5($plainpasswd) {
        $salt = substr(str_shuffle("abcdefghijklmnopqrstuvwxyz0123456789"), 0, 8);
        $len = strlen($plainpasswd);
        $text = $plainpasswd.'$apr1$'.$salt;
        $bin = pack("H32", md5($plainpasswd.$salt.$plainpasswd));
        for($i = $len; $i > 0; $i -= 16) { $text .= substr($bin, 0, min(16, $i)); }
        for($i = $len; $i > 0; $i >>= 1) { $text .= ($i & 1) ? chr(0) : $plainpasswd{0}; }
        $bin = pack("H32", md5($text));
        for($i = 0; $i < 1000; $i++) {
            $new = ($i & 1) ? $plainpasswd : $bin;
            if ($i % 3) $new .= $salt;
            if ($i % 7) $new .= $plainpasswd;
            $new .= ($i & 1) ? $bin : $plainpasswd;
            $bin = pack("H32", md5($new));
        }
        $tmp = '';
        for ($i = 0; $i < 5; $i++) {
            $k = $i + 6;
            $j = $i + 12;
            if ($j == 16) $j = 5;
            $tmp = $bin[$i].$bin[$k].$bin[$j].$tmp;
        }
        $tmp = chr(0).chr(0).$bin[11].$tmp;
        $tmp = strtr(strrev(substr(base64_encode($tmp), 2)),
        "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/",
        "./0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz");
        return "$"."apr1"."$".$salt."$".$tmp;
    }
    

?>