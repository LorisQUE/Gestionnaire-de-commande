# Projet de synthèse 2ème année - Cahier des charges

Informations AWS
```
ec2-3-89-13-11.compute-1.amazonaws.com

gestion-commande.c3csa4hunhjs.us-east-1.rds.amazonaws.com

user1
default1
base1
```

## Expression des besoins
### Périmètre du logiciel

L'entreprise cliente est une société spécialisée dans la fabrication, l'assemblage et la vente de table de ping-pong
et de fournitures de pièces et d'accessoires pour le ping-pong. La société souhaite s'équiper d'un système de
gestion d'entreprise de leurs pièces afin de pouvoir créer automatiquement des commandes sur devis tout en
gérant les achats et les stocks, en remplacement de divers logiciels et tableaux disparates et non connectés entre
eux.

### Organisation de l'entreprise

La société est composée de deux services, **un atelier et un service commercial**.
Chaque service utilise le logiciel de façon différente :

- L'atelier gère les différentes pièces : ajout, suppression, modification et hiérarchisation.
- Le service commercial gère les devis et les commandes.

### Composition des pièces détachées

L'ensemble des pièces de l'entreprise (identifiées par une référence unique), est constitué :

- de pièces livrables aux clients (qui sont toujours fabriquées ou assemblées au sein de l'entreprise),
- de pièces intermédiaires (fabriquées au sein de l'entreprise, mais non commercialisables),
- de matières premières,
- de pièces achetées à l'extérieur (non commercialisables).
Le bureau des méthodes de l'atelier désire connaître les pièces composant une pièce fabriquée. Il peut entrer dans
la composition d'une pièce :
- des matières premières,
- des pièces achetées,
- des pièces intermédiaires.
On veut également connaître la quantité d'une pièce A utilisée pour créer une pièce B, ainsi que le libellé de la
pièce et son prix unitaire de vente (pour les pièces commercialisées).

### Fabrication des pièces détachées

Les pièces fabriquées par l'entreprise se référent à une gamme de fabrication. Une gamme sert à créer une seule
pièce. Elle est supervisée par une seule personne, le responsable. En cas de malfaçon, on doit pouvoir, en partant
de la pièce, retrouver le responsable, qui est le responsable de plusieurs pièces.
Une pièce ne peut être produite que par une seule gamme constituée d'une suite d'opérations à exécuter. Une
opération peut être utilisée dans plusieurs gammes.
L'atelier de fabrication est constitué de postes de travail et d'un local où sont stockées les machines. Un poste de
travail est occupé à un instant donné par un seul ouvrier qui n'est pas forcément toujours le même. Un ouvrier est
polyvalent : il peut être qualifié sur plusieurs postes de travail. On ne désire connaître que les postes de travail
sur lesquels un ouvrier est qualifié. On ne peut monter une machine que sur les postes de travail techniquement
adaptés. Un poste de travail est adapté à plusieurs machines.
Le bureau des méthodes prévoit pour chaque opération à exécuter le poste de travail, la machine à utiliser et le
temps de travail nécessaire. La réalisation peut se faire sur un autre poste, avec une autre machine et un autre
temps de travail. On souhaite avoir tous les renseignements relatifs à la fabrication des pièces ainsi que l'historique
des réalisations.

### Gestion des devis et commandes

L'entreprise livre des pièces aux clients qui lui ont passé commande. Au préalable, un devis est établi
obligatoirement pour le client et peut concerner plusieurs pièces. Un devis, après étude par le client, peut faire
l'objet d'une ou plusieurs commandes. Chaque commande correspond à un ou plusieurs devis. Toutefois, chaque
ligne d'une commande doit correspondre à une ligne complète du devis (même pièce, même quantité).
Une pièce ne peut apparaître que sur une ligne d'une commande. De plus, le devis comporte un délai. Ce délai
garantit le prix de vente de la pièce. On ne peut donc passer commande d'une ligne d'un devis que si la date de la
commande n'est pas postérieure au délai fixé.
La direction souhaite conserver la trace des devis effectués ainsi que des commandes passées. Les montants des
devis et commandes doivent bien entendu rester fixes et être indépendants des changements de prix des pièces.


### Gestion des achats

L'entreprise souhaite conserver la trace de ses achats de pièces. Une pièce achetée n'a qu'un seul fournisseur
possible. Elle a un prix catalogue. Toutefois, il se peut que le prix d'achat de la pièce varie en fonction de la
commande. Une commande peut comporter plusieurs pièces. Elle doit être livrée en totalité, ainsi on gardera la
date de livraison prévue et la date de livraison réelle.

### Comptabilité

Le service comptabilité de l'entreprise souhaite pouvoir avoir des factures correspondant aux commandes au
format PDF pour archivage séparé. De plus, il sera possible de télécharger à destination d'un tableur (format CSV)
la liste des factures d'un mois ainsi que la liste des achats à payer d'un mois (par défaut le mois précédent, avec
possibilité de choisir un autre mois).

### Administration du logiciel

Le service informatique de l'entreprise souhaite pouvoir gérer les utilisateurs du logiciel ainsi que leurs droits.
Etant donné que les utilisateurs ne sont pas forcément des utilisateurs du réseau de l'entreprise (stagiaires, intérim)
le logiciel devra avoir sa propre base de données utilisateur. Il faudra en particulier pouvoir préciser quelle partie
du logiciel l'utilisateur à le droit d'utiliser. Un utilisateur pourra utiliser une ou plusieurs parties.


## Conditions de réalisation

### Matériels disponibles

On trouve à l'atelier un serveur fonctionnant sous Microsoft Windows Server avec une base de données
PostgreSQL. La société ne souhaite pas changer ni de système d'exploitation ni de base de données. En effet les
divers logiciels en cours d'utilisation devront pouvoir continuer de fonctionner après l'installation du nouveau
logiciel qui viendra en remplacement pour ne pas avoir à transférer les anciennes données.
Les postes de travail de l'atelier sont équipés de Linux Ubuntu et les postes de travail du service commercial de
Microsoft Windows 10.

### Technologies sélectionnées

Compte tenu des différences entre les postes de travail de l'atelier et ceux du service commercial il a été décidé
d'avoir une application de type web utilisable par le navigateur Mozilla Firefox commun à tous les postes.
La société désirant pouvoir déléguer la maintenance de l'application à des sociétés de service précises le
développement devra obligatoirement être réalisé dans une ou plusieurs technologies parmi les suivantes :

- PHP avec le framework Symfony
- Microsoft .Net
- JavaScript avec ou sans le framework ReactJS
- NodeJS

### Interfaces utilisateurs

L'interface utilisateur devra être claire, logique et facile d'utilisation. Les messages d'erreurs et d'informations
devront être clairs et compréhensibles par un utilisateur non informaticien. Toute action doit avoir un retour quel
que soit le résultat. Les fautes de français dans l'interface ainsi que dans les messages d'erreurs et d'informations
ne sont pas admises.


### Gestion du projet

Une communication sera établie entre le maître d’œuvre et le maître d’ouvrage pendant toute la durée du projet.
Le maître d’œuvre devra tenir au courant régulièrement le maître d’ouvrage de l’avancement du projet lors des
réunions régulières. Ces réunions seront à l'initiative du maître d’œuvre (vous !).
Le logiciel devra être livré en minimum deux parties. En premier la partie atelier puis la partie commerciale.

### Délai de réalisation

La livraison de la partie atelier devra intervenir le jeudi 24 juin au plus tard.
La livraison de la partie commerciale devra intervenir lors de la recette de cette partie le vendredi 2 juillet, le
rendez-vous avec le maître d'ouvrage et le client devant être fixé ultérieurement.

### Recettes

Les jours des recettes une démonstration sera prévue. C'est une recette définitive, toute modification de code et
de configuration y est donc interdite et seules les fonctionnalités terminées seront passées en revue.

### Contraintes scolaires

Travail à réaliser individuellement.
A la différence d'un TP, le résultat attendu ne doit pas être seulement fonctionnel, mais avoir un aspect et un
fonctionnement **professionnel**.


