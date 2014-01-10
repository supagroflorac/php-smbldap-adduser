#!/usr/bin/python 
# -*- coding: utf-8 -*-

# Copyright © 2013 Florestan Bredow <florestan.bredow@daiko.fr>
# This work is free. You can redistribute it and/or modify it under the
# terms of the Do What The Fuck You Want To Public License, Version 2,
# as published by Sam Hocevar. See the COPYING file for more details.

import sys,os,subprocess

#En entrée : prenom nom login motdepasse

if len(sys.argv) != 6:
	# Manque d'arguments : on sort.
	print("Mauvais nombre d'arguments");
	sys.exit(1)

prenom = sys.argv[1]
nom = sys.argv[2]
login = sys.argv[3]
mdp = sys.argv[4]
uid = sys.argv[5]

loc_path = "/home/"+login
smb_path = "\\\\serveur\\"+login
prf_path = "\\\\serveur\\Profiles$\\"+login

# Options du COMPTE
acc_options =  " -d '" + loc_path + "' -m"	# Repertoire home et création
acc_options += " -n" 				# Pas de groupe au nom de l'utilisateur
acc_options += " -a"				# Est un utilisateur windows
acc_options += " -c '" + nom + " " + prenom + "'"
acc_options += " -u " + uid  
		# Champs GECOS (nom et prénom de l'utilisateur)
acc_options += " -o 'ou=EXTERIEURS'"		# Ajoute dans l'OU relative au l'OU utilisateurs
#acc_options += " -P"				# Demande le mdp a la fin
acc_options += " -A 0" 				# 0 ne peut changer son mdp ; 1 peut changer son MDP
acc_options += " -B 0"				# Ne doit pas changer son mot de passe
#acc_options += " --shadowMax -1"                # Le mot de passe n'expire jamais
#acc_options += " --shadowExpire -1"             # Le compte n'expire jamais
			
# Options SAMBA
smb_options =  " -C '" + smb_path + "'"		# Chemin du home sur le reseau.
#smb_options += " -M '" + ligne[3] + "'"	# Adresse Mail
smb_options += " -D 'P:'"			# Lettre du lecteur reseau.
smb_options += " -F '" + prf_path + "'"		# chemin reseau du profile
smb_options += " -N '" + prenom + "'"		# Prénom
smb_options += " -S '" + nom + "'"		# Nom

# On prepare le mot de passe pour la saisie automatique
password = "'" + mdp + "\\n" + mdp + "'" # 2 fois demande + validation

# Création de l'utilisateur
retcode1 = os.system("echo -e " + password + " | smbldap-useradd" + acc_options + smb_options + " " + login + " >> /var/log/ajouterUtilisateurs.php.log" )
retcode2 = os.system("smbldap-usermod --shadowMax -1 " + login )

if retcode1 != 0:
	print("Problème création compte : Déjà existant ?")
	sys.exit(1)

# Tout va bien, on a finis.
sys.exit(0)	
