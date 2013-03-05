php-smbldap-adduser
===================

Php interface to add new users and change password.

Add to /etc/sudoers

%www-data ALL = NOPASSWD:/[Path_To]/py/ajouterUtilisateurs.php.py,NOPASSWD:/usr/sbin/smbldap-userlist,NOPASSWD:/[Path_To]/py/changeMotDePasse.php.py

