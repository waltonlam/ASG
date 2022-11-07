# TAPS Project

For git push, please use below git token.
git token: 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


Restart XAMPP Server 
/opt/lampp/lampp restart


Allow Connecting to phpmyadmin not using Localhost

If you see below error message, when try into phpyAdmin:

New XAMPP security concept:
Access to the requested directory is only available from the local network.
This setting can be configured in the file "httpd-xampp.conf".
You can do next (for XAMPP, deployed on the UNIX-system): You can try change configuration for <Directory "/opt/lampp/phpmyadmin">

# vi /opt/lampp/etc/extra/httpd-xampp.conf
and change security settings to

#LoadModule perl_module        modules/mod_perl.so

<Directory "/opt/lampp/phpmyadmin">
    AllowOverride AuthConfig Limit
    Order allow,deny
    Allow from all
    Require all granted
    ErrorDocument 403 /error/XAMPP_FORBIDDEN.html.var
</Directory>
First - comment pl module, second - change config for node Directory. After it, you should restart httpd daemon

# /opt/lampp/xampp restart
Now you can access http://[server_ip]/phpmyadmin/




To reset Ubuntu pc password
> Restart pc 
> press esc 
> Advanced options for Ubuntu
> Ubuntu, with Linux ... (recovery mode)
> In Recovery Menu, choose root
> run cmd : mount -rw -o remount /
> ls /home
> passwd (taps)
> New password:
> Retype new password:
> exit
> resume Resume normal boot



To Establish Network Connection on Ubuntu Desktop, please follow the below instruction

inet 10.17.57.103/24 

cd /etc/netplan
          
In 01-netcfg.yaml       
                                                                 
network:
 version: 2
 renderer: NetworkManager
 ethernets:
  eno1:
   dhcp4: no
   addresses:
   - 10.17.57.103/24
   gateway4: 10.17.57.1
   nameservers:
    addresses: [8.8.8.8, 8.8.4.4]

ifconfig > check ipv4(inet)

sudo ifconfig eno1 up
sudo gedit /etc/network/interfaces
     In the interfaces file, add the follwing cmd.  
      auto lo
      iface lo inet loopback
      auto eth0
      iface eth0 inet dhcp
      
sudo service network-manager restart
sudo ifconfig eno1 up

Change DNS
sudo nano /etc/resolv.conf
#Google IPv4 nameservers
nameserver 8.8.8.8
nameserver 8.8.4.4

# Google IPv6 nameservers
nameserver 2001:4860:4860::8888
nameserver 2001:4860:4860::8844

sudo lshw -C network

For NetworkManager.conf, if wired unmanaged appeared
1. sudo nano /etc/NetworkManager/NetworkManager.conf
2. Change managed=false to managed=true
3. sudo nano /usr/lib/NetworkManager/conf.d/10-globally-managed-devices.conf
4. Add except:type:ethernet to this line: unmanaged-devices=*,except:type:wifi,except:type:wwan
5. Restart network manager: sudo service network-manager restart


To allow public connection 

Modify below config file for Windows
C:\xampp\apache\conf\extra\httpd-xampp.conf

ScriptAlias /php-cgi/ "C:/xampp/php/"
<Directory "C:/xampp/php">
    AllowOverride None
    Options None
    #Require all denied   <--------------- comment # this line
    <Files "php-cgi.exe">
          Require all granted
    </Files>
</Directory>

Alias /phpmyadmin "C:/xampp/phpMyAdmin/"
<Directory "C:/xampp/phpMyAdmin">
    AllowOverride AuthConfig
    #Require local    <--------------- comment # this line
    ErrorDocument 403 /error/XAMPP_FORBIDDEN.html.var
</Directory>

Alias /webalizer "C:/xampp/webalizer/"
<Directory "C:/xampp/webalizer">
    <IfModule php_module>
    <Files "webalizer.php">
      php_admin_flag safe_mode off
    </Files>
    </IfModule>
    AllowOverride AuthConfig
    #Require local <------------ comment # this line 
    ErrorDocument 403 /error/XAMPP_FORBIDDEN.html.var
</Directory>

For Ubuntu Side

Modify below config file for Ubuntu
/opt/lampp/etc/extra/httpd-xampp.conf

# since XAMPP 1.4.3
<Directory "/opt/lampp/phpmyadmin">
    AllowOverride AuthConfig Limit
    Order allow,deny
    Allow from all
    Require all granted
    #Require local  <------ comment # this line
    ErrorDocument 403 /error/XAMPP_FORBIDDEN.html.var
</Directory>
