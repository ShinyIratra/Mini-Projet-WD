-- Données de test pour script.sql
-- Ordre d'insertion: Utilisateur -> Categorie -> Article -> Article_photo -> Auteur

INSERT INTO Utilisateur (Id_Utilisateur, nom, prenom, identifiant, password) VALUES
(1, 'Rakoto', 'Lina', 'lina.rakoto', 'test123'),
(2, 'Andriam', 'Tiana', 'tiana.andriam', 'test123'),
(3, 'Rasoanaivo', 'Mickael', 'mickael.raso', 'test123'),
(4, 'Rabe', 'Fara', 'fara.rabe', 'test123');

INSERT INTO Categorie (Id_Categorie, rubrique) VALUES
(1, 'Politique'),
(2, 'Economie'),
(3, 'Societe'),
(4, 'Sport'),
(5, 'Culture');

INSERT INTO Article (Id_Article, titre, contenu, date_publication, Id_Categorie) VALUES
(1, 'Elections locales 2026', 'Les elections locales ont mobilise de nombreux citoyens a travers le pays.', '2026-03-20 09:15:00', 1),
(2, 'Hausse des exportations agricoles', 'Le ministere de l''economie annonce une progression de 12% des exportations.', '2026-03-21 14:30:00', 2),
(3, 'Nouvelle campagne de sante publique', 'Une campagne nationale vise a renforcer la prevention dans les zones rurales.', '2026-03-22 08:00:00', 3),
(4, 'Championnat national: finale', 'La finale du championnat national se jouera ce dimanche au stade municipal.', '2026-03-23 18:45:00', 4),
(5, 'Festival des arts urbains', 'Le festival met en avant de jeunes talents locaux en musique et en danse.', '2026-03-24 11:10:00', 5);

INSERT INTO Article_photo (Id_Article_photo, chemin, alt, Id_Article) VALUES
(1, '/assets/img/articles/elections-2026.jpg', 'Affiche des elections locales 2026', 1),
(2, '/assets/img/articles/exportations-agricoles.jpg', 'Produits agricoles en preparation', 2),
(3, '/assets/img/articles/sante-publique.jpg', 'Equipe medicale en sensibilisation', 3),
(4, '/assets/img/articles/finale-championnat.jpg', 'Stade avant la finale', 4),
(5, '/assets/img/articles/festival-arts.jpg', 'Scene du festival des arts urbains', 5),
(6, '/assets/img/articles/finale-supporters.jpg', 'Supporters celebrant avant le match', 4);

INSERT INTO Auteur (Id_Utilisateur, Id_Article) VALUES
(1, 1),
(2, 2),
(3, 3),
(4, 4),
(1, 5),
(2, 4);        

-- Si besoin, remettre les sequences en phase apres insertion manuelle des IDs
SELECT setval('utilisateur_id_utilisateur_seq', (SELECT MAX(Id_Utilisateur) FROM Utilisateur));
SELECT setval('categorie_id_categorie_seq', (SELECT MAX(Id_Categorie) FROM Categorie));
SELECT setval('article_id_article_seq', (SELECT MAX(Id_Article) FROM Article));
SELECT setval('article_photo_id_article_photo_seq', (SELECT MAX(Id_Article_photo) FROM Article_photo));
