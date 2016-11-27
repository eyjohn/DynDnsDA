# DynDnsDA
Dynamic DNS Service for DirectAdmin based web hosting services.

This scripts are designed to run on a webserver running the DirectAdmin control panel. The scripts will use the DirectAdmin API to configure the DNS for a given hostname and can be used for hosting dynamic DNS services.

You will need some basic understanding of how to setup and manage websites using DirectAdmin to use these scripts.

**NOTE**: These scripts do NOT enforce any authentication or encryption, you are STRONGLY encouraged to setup some sort of authentication and ideally encryption for your dynamic dns setup, otherwise some one can steal your dynamic dns allocation or worse!

# Requirements
You must have a direct admin account where you can manage DNS through the direct admin web API and where you can reach the API from the server itself (this is normally the case). 

# How To Use

## Generate Configuration file.

Generate a file named `config.inc.php` and put it in the same directory as the other files.

Here is an example file:

```php
<?php
return array(
    "username" => "username",
    "password" => "password",
    "url" => "https://myhostingprovider.com:2222",    
    "domain" => "domain.name.in.admin.panel.to.use"
);
?>
```
Where the username/password are the DirectAdmin panel username a password and the URL is the direct admin panel url.
The domain will be the domain that is configured on your DirectAdmin account which you can select as soon as you login (or is selected by default).

## Upload files
Simply upload the two php files and your configuration to your directadmin hosting service, and make sure they are accessible (check the checkip.php file). 

## Check your TTL
By default the TTL is generally configured to 14400 seconds (4 hours), although your IP probably doesn't change too frequently, it is still recommended to have a smaller TTL, for example 300 seconds (5 min). You can configure this in the DirectAdmin panel.


This is all that is required, please note that this service will be able to add or delete all "A" record entries of the domain for which it has been configured. 


## Configure your DDNS Client
(Instructions to come)
I have managed to successfully configure this service on my LuCi/openwrt powered router (Turris Omnia).
