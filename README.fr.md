Inokufu Search - Plugin Repository Moodle
=================================

Le plugin Inokufu Search - Plugin Dépôt Moodle fait partie de l'ensemble des plugins Moodle Inokufu Search, qui inclut également le plugin Inokufu Search - Plugin Atto Moodle. Ce plugin Dépôt Moodle vous permet d'accéder à la technologie Inokufu Search et d'ajouter des Objets d'Apprentissage à vos formations sur Moodle.
Ce document vous guidera à travers l'installation et l'utilisation du plugin.

## Installation

### Installation à partir d'un ZIP
1. Téléchargez le fichier zip du plugin à partir de ce dépôt GitHub.
2. Connectez-vous à votre site Moodle en tant qu'administrateur.
3. Accédez à `Administration du site > Plugins > Installer des plugins`.
4. Téléversez le fichier zip que vous avez téléchargé à partir de ce dépôt GitHub et suivez les instructions à l'écran.
5. Remplissez et confirmez les formulaires pour terminer l'installation du plugin.

### Installation à partir de la source
1. Établissez une connexion SSH à votre instance Moodle.
2. Clonez les fichiers source à partir de ce dépôt GitHub directement dans vos fichiers source Moodle.
3. Renommez le dossier cloné en `inokufu`.
4. Déplacez le dossier `inokufu` dans le dossier `repository` de votre installation Moodle. Assurez-vous que le dossier du plugin est nommé `inokufu`.
5. Connectez-vous à votre site Moodle en tant qu'administrateur.
6. Accédez à `Administration du site > Notifications` pour finaliser l'installation du plugin.

## Configuration
1. Après une installation réussie, accédez à `Administration du site > Plugins > Dépôts > Gérer les dépôts` pour configurer les paramètres du plugin.
2. Activez le plugin de dépôt `Inokufu Search` en le basculant sur `Activé et visible`.
3. Accédez aux paramètres du plugin en cliquant sur `Paramètres` (à côté de `Inokufu Search` et `Activé et visible`), ou en accédant à `Administration du site > Plugins > Dépôts > Inokufu Search`.
4. Entrez le Nom du Plugin (facultatif) et votre Clé API.
5. Enregistrez les modifications et commencez à utiliser le plugin de dépôt.

**Note :** Une Clé API est requise pour voir ce plugin dans les Dépôts Moodle et l'utiliser dans le Sélecteur de Fichiers Moodle. Pour obtenir une Clé API, veuillez vous référer à la section [Inokufu APIs Gateway](https://gateway.inokufu.com/) ou contacter le [Support Inokufu](https://support.inokufu.com/).

## Résolution des problèmes
Si vous rencontrez des problèmes avec le plugin, veuillez vérifier les points suivants :
1. Assurez-vous que votre site Moodle répond aux exigences minimales pour le plugin.
2. Vérifiez que votre Clé API est correctement remplie et valide.
3. Consultez le journal Moodle (`Administration du site > Rapports > Journaux`) pour voir s'il y a des messages d'erreur liés au plugin.
4. Si aucune de ces étapes ne vous a aidé, n'hésitez pas à contacter notre [Support Inokufu](https://support.inokufu.com/).

## Support
Pour obtenir une assistance supplémentaire ou signaler un problème, veuillez visiter le dépôt GitHub du plugin et ouvrir une `issue`. Veillez à inclure tous les détails pertinents, tels que la version de votre Moodle, la version du plugin et une description détaillée du problème.
