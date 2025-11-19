# 🚀 TaskBoard Pro - Roadmap Produit

---

## ✅ Sprint 1 - TERMINÉ

### Authentification
- [x] Inscription utilisateur
- [x] Confirmation email par OTP
- [x] Login / Logout
- [x] Remember me (7 jours)
- [x] Bloquer login si email non confirmé

### Projets
- [x] **Créer un projet**
    - [x] 3 types: SCRUM (5 colonnes), KANBAN (3 colonnes), BASIC (2 colonnes)
    - [x] Formulaire: nom, description, dates
    - [x] Je deviens créateur et seul membre
    - [x] Colonnes créées automatiquement
    - [x] Notification de création

- [x] **Lister mes projets**
    - [x] Grille de cards
    - [x] Filtres: recherche, type, statut
    - [x] Tri: activité, date, nom
    - [x] Pagination 12 par page
    - [x] Menu contextuel: Voir/Modifier/Archiver

- [x] **Voir un projet**
    - [x] Détails projet
    - [x] Tableau Kanban (colonnes vides)
    - [x] Onglets: Board/Liste/Activité/Sprints
    - [x] Seul le créateur y accède

### Notifications
- [x] Dropdown navbar avec compteur
- [x] Notification automatique (création projet)
- [x] Marquer comme lu
- [x] Marquer tout comme lu

---

## 🔜 Sprint 2 - Compléter la Gestion des Projets

### Modifier un projet
- [x] Formulaire d'édition
- [x] Changer nom, description, dates
- [ ] Empêcher changement de type si tâches existent
- [x] Notification de modification

### Archiver un projet
- [x] Bouton "Archiver"
- [x] Projet masqué par défaut dans la liste
- [x] Badge "ARCHIVED" visible
- [x] Bouton "Restaurer"
- [ ] Impossible de créer des tâches si archivé

### Supprimer un projet
- [ ] Modal de confirmation
- [ ] Saisir le nom du projet pour confirmer
- [ ] Suppression complète (projet + colonnes + tâches futures)

### Dashboard amélioré
- [ ] Statistiques réelles (projets actifs, tâches)
- [ ] Derniers projets consultés
- [ ] Accès rapides

---

## 🔜 Sprint 3 - Les Tâches

### Créer une tâche
- [x] Bouton dans chaque colonne du board
- [x] Modal avec formulaire
- [x] Champs: titre, description, priorité, date échéance
- [x] Numérotation automatique (EX-1, EX-2...)
- [x] Tâche créée dans la bonne colonne

### Afficher les tâches
- [x] Cards dans les colonnes
- [x] Numéro + titre
- [x] Badge priorité (couleur)
- [x] Date échéance (rouge si dépassée)
- [x] Compteur dans header colonne

### Voir une tâche
- [x] Modal au clic sur la card
- [x] Toutes les infos
- [x] Boutons: Éditer, Supprimer

### Modifier une tâche
- [ ] Formulaire dans le modal
- [ ] Modifier titre, description, priorité, date

### Supprimer une tâche
- [ ] Confirmation
- [ ] Suppression + réorganisation des positions

### Déplacer une tâche
- [ ] Boutons "Précédent" / "Suivant" dans le modal
- [ ] Changement de colonne
- [ ] Historique visible dans l'activité

---

## 🔜 Sprint 4 - Collaboration de Base

### Commentaires
- [ ] Ajouter un commentaire sur une tâche
- [ ] Lister les commentaires
- [ ] Modifier mon commentaire
- [ ] Supprimer mon commentaire
- [ ] Compteur sur la card
- [ ] Notification quand commentaire ajouté

### Historique d'activité
- [ ] Onglet "Activité" fonctionnel
- [ ] Timeline des actions:
    - [ ] Projet créé/modifié/archivé
    - [ ] Tâche créée/modifiée/déplacée/supprimée
    - [ ] Commentaire ajouté
- [ ] Affichage: avatar + action + timestamp
- [ ] Filtres: type d'action

---

## 🔜 Sprint 5 - Expérience Utilisateur

### Drag & Drop
- [ ] Glisser-déposer les tâches entre colonnes
- [ ] Réorganiser l'ordre des tâches dans une colonne
- [ ] Animation fluide
- [ ] Mise à jour immédiate

### Filtres du board
- [ ] Barre de filtres au-dessus du board
- [ ] Filtrer par priorité
- [ ] Chercher une tâche
- [ ] Compteur de résultats

### Statistiques projet
- [ ] Graphique de progression
- [ ] Répartition des tâches par colonne
- [ ] Temps moyen de complétion
- [ ] Stats réelles calculées

### Recherche globale
- [ ] Barre de recherche dans la navbar
- [ ] Chercher dans projets et tâches
- [ ] Résultats instantanés
- [ ] Navigation rapide

---

## 🔜 Sprint 6 - Polish & Finitions

### Amélioration Dashboard
- [ ] Widget "Mes tâches du jour"
- [ ] Widget "Tâches en retard"
- [ ] Graphique activité 7 derniers jours
- [ ] Raccourcis clavier

### Notifications enrichies
- [ ] Email de notification (tâche, commentaire)
- [ ] Préférences de notification
- [ ] Digest quotidien optionnel

### Petites améliorations
- [ ] Toast notifications (succès/erreur)
- [ ] États de chargement
- [ ] Messages vides avec illustrations
- [ ] Animations transitions

---

## 💡 Idées Post-MVP

### Partage de projets
- [ ] Inviter d'autres utilisateurs à un projet
- [ ] Rôles: Manager, Développeur, Observateur
- [ ] Assigner une tâche à un membre
- [ ] Mentions dans les commentaires (@user)

### Features SCRUM
- [ ] Créer des sprints
- [ ] Backlog vs Sprint actif
- [ ] Burndown chart
- [ ] Story points

### Features avancées
- [ ] Dépendances entre tâches
- [ ] Labels/tags personnalisés
- [ ] Pièces jointes
- [ ] Tâches récurrentes
- [ ] Templates de projet

### Intégrations
- [ ] GitHub (lier commits)
- [ ] Slack (notifications)
- [ ] Google Calendar
- [ ] Export PDF/CSV

### Personnalisation
- [ ] Dark mode
- [ ] Langue FR complète
- [ ] Avatar personnalisé
- [ ] Colonnes personnalisées

---

## 🎯 User Journey

### Aujourd'hui (Sprint 1) ✅
1. Je crée un compte
2. Je confirme mon email
3. Je me connecte
4. Je crée un projet "Site Web" en SCRUM
5. Je vois mon board avec 5 colonnes vides
6. Je liste mes projets avec filtres

### Sprint 2 🔜
7. Je modifie le nom de mon projet
8. J'archive un vieux projet
9. Je supprime un projet test

### Sprint 3 🔜
10. Je crée ma première tâche "Design homepage"
11. Je la vois dans la colonne "Backlog"
12. Je consulte les détails de la tâche
13. Je déplace la tâche dans "In Progress"

### Sprint 4 🔜
14. Je commente la tâche
15. Je vois l'historique: tâche créée → déplacée → commentaire
16. Je reçois une notification

### Sprint 5 🔜
17. Je drag & drop la tâche dans "Done"
18. Je filtre pour voir seulement les tâches "High priority"
19. Je cherche "homepage" dans la barre de recherche
20. Je vois les stats: 1/1 tâche terminée (100%)

### Sprint 6 🔜
21. Je vois mes tâches du jour sur le dashboard
22. Je reçois un email récapitulatif
23. J'active le dark mode
