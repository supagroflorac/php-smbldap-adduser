php-smbldap-adduser
===================

Php interface to add new users and change password.

Add to /etc/sudoers

%www-data ALL = NOPASSWD:/usr/sbin/smbldap-usershow, NOPASSWD:/usr/sbin/smbldap-userlist, NOPASSWD:/[Path_To]/py/adduser.php.py, NOPASSWD:/[Path_To]/py/passwd.php.py

