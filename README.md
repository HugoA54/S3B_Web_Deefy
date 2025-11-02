# Deefy - Projet (S3)



## Binôme

- Ilias Boudouah  

- Hugo Antzorn



## Description

Application web PHP de gestion de playlists audio avec comptes utilisateurs, upload de pistes, statistiques et sécurité complète.

## Installation

### 1 Cloner le projet dans le dossier de votre serveur local
- git clone https://github.com/HugoA54/S3B_Web_Deefy.git
- cd S3B_Web_Deefy

###  2 Installer les dépendances PHP avec Composer :
composer install


###  3 Importer la base de données
Depuis phpMyAdmin : importer le fichier "database.sql"


###  4 Créer le fichier de configuration à partir de l’exemple
cp Config.db.exemple.ini Config.db.ini

###  5 Ouvrir le fichier Config.db.ini et renseigner vos informations
Exemple :
 - driver=mysql
 - username=root
 - password=""
 - host=localhost
 - database=NomDeVotreBase

###  6 Lancer votre serveur local (ex: XAMPP)
 puis ouvrir le projet dans le navigateur :
 http://localhost/S3B_Web_Deefy/




## Comptes de test

| Email | Mot de passe |

|--------|---------------|

| user1@mail.com| user1 |

| user2@mail.com| user2 |

| user3@mail.com| user3 |

| user4@mail.com| user4 |

| admin@mail.com| admin |



## Documents

- `rapport.pdf` → rapport détaillé avec tableau de bord et explications  

- `database.sql` → script de création et d’insertion


