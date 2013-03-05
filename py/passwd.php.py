#!/usr/bin/python 
# -*- coding: utf-8 -*-

# Copyright © 2013 Florestan Bredow <florestan.bredow@daiko.fr>
# This work is free. You can redistribute it and/or modify it under the
# terms of the Do What The Fuck You Want To Public License, Version 2,
# as published by Sam Hocevar. See the COPYING file for more details.

import sys,os

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
retcode1 = os.system("echo -e " + password + " | smbldap-passwd " + login) # + " >> /var/log/changerMotDePasse.php.log")

if retcode1 != 0:
	print("Erreur lors du changement de mot de passe.");
	sys.exit(1)

print("OK")
sys.exit(0)	
