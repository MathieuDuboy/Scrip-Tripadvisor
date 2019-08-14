# Scrip-Tripadvisor
## Notice d'utilisation et de configuration
Veuillez télécharger un VPN (Cyberghost ou NordVPN) et veuillez le lancer avant d'executer le Scrap.

### 1. Installation de MAMP ou WAMP
Veuillez suivre les instructions ici : https://www.mamp.info/en/ ou http://www.wampserver.com/

### 2. Configuration du fichier config.php
Une fois votre serveur lancé, veuillez modifier le fichier config.php avec vos identifiants de connexion à la votre base de données. Vous trouverez les informations concernant la Bdd ici : https://github.com/MathieuDuboy/Scrip-Tripadvisor/blob/master/sql.txt

### 3. Lancement du scrap
Renseignez dans config.php 2 variables :
- L'URL de la page VILLE + RESTAURANT : Exemple : https://www.tripadvisor.fr/Restaurants-g196505-Arcachon_Gironde_Nouvelle_Aquitaine.html
- Le Nombre de pages (int) que cette page propose : Exemple : 6 ici

Lancez dans votre navigateur la page start.php et laissez tourner.

### 4. Process
Start.php : Récupère les liens des restaurants de toutes les pages de la pagination et les stocke.
Resto.php : Scrap chaque lien et récupère les inforamtions (vous pourrez améliorer cette page à votre sauce).
Fin.php : Vous signifie la fin du scrap. Vous devrez alors de nouveau modifier config.php avec 2 nouvelles variables.

### 5. Améliorations
Les images sont cryptées et impossible à récupérer dans le DOM via PHP ... j'ai essayé en vain.
Au niveau de la vitesse, en PHP avec le DOM on ne peut rien faire : il faut attendre que la page soit entièrement chargée pour que le scrap se lance. 
Les données sont récupérées sans traitement. Vous pouvez ajouter à votre sauce des modifications.

