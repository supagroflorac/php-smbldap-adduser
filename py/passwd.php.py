#!/usr/bin/python 
# -*- coding: utf-8 -*-

# Copyright © 2013 Florestan Bredow <florestan.bredow@daiko.fr>
# This work is free. You can redistribute it and/or modify it under the
# terms of the Do What The Fuck You Want To Public License, Version 2,
# as published by Sam Hocevar. See the COPYING file for more details.

import sys,os,subprocess

#En entrée : login motdepasse

if len(sys.argv) != 3:
	# Manque d'arguments : on sort.
	print("Mauvais nombre d'arguments");
	sys.exit(1)

login = sys.argv[1]
mdp = sys.argv[2]


# On prepare le mot de passe pour la saisie automatique
password = "'" + mdp + "\\n" + mdp + "'" # 2 fois demande + validation

# Création de l'utilisateur
#print("echo -e " + password + " | smbldap-passwd " + login);
#retcode1 = os.system("echo -e " + password + " | smbldap-passwd " + login) # + " >> /var/log/changerMotDePasse.php.log")

pipe = subprocess.Popen("smbldap-passwd %s 2>/dev/null 1>&2" % login, stdin=subprocess.PIPE, shell=True)
pipe.communicate("%s\n%s\n" % (mdp,mdp,))
retcode1 = pipe.returncode

retcode2 = os.system("smbldap-usermod --shadowMax -1 " + login )

if retcode1 != 0:
	print("Erreur lors du changement de mot de passe.");
	sys.exit(retcode1)

# Tout finis bien
sys.exit(0)	
