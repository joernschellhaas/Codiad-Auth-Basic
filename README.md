# Codiad Auth-Basic Plugin

## Purpose

This plugin will create `.htpasswd` file in your Codiad folder an take care that it is synced to the Codiad user DB. However users will only be added to the file when they are created or the password is changed (due to the fact that Codiad and Apache use different hashing algorithms).

## Usage

I created the plugin because I prefer using old-school Apache auth instead of the Codiad built-in auth. To use the plugin this way, you have to create a `.htaccess` file similar to this:

    AuthType Basic
    AuthName "Admin"
    AuthUserFile /path/to/codiad/.htpasswd
    require valid-user

In your `config.php` you'll have to put something like

    // External Authentification
    define("AUTH_PATH", dirname(dirname(__FILE__))."/customauth.php");

And in your `customauth.php`

    <?php $_SESSION['user'] = $_SERVER['PHP_AUTH_USER']; ?>

__Note that this plugin still requires review regarding its security.__

The plugin could be adapted to create arbitrary `.htpasswd` files

## Licensing

Distributed under the MIT-Style License. See `LICENSE.txt` file for more information. 