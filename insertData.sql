USE projet2Bdd;

-- Insére un utilisateur "admin" dans la table Utilisateurs
INSERT INTO Utilisateurs (NomUtilisateur, MotDePasse, AdresseEmail, DateInscription)
VALUES ('admin', 'motdepasse', 'admin@example.com', NOW());

-- Récupére l'ID de l'utilisateur "admin" 
SET @adminUserID = LAST_INSERT_ID();

-- Insére 4 articles écrits par l'utilisateur "admin" dans la table Articles
INSERT INTO Articles (TitreArticle, ContenuArticle, IDAuteur, DatePublication, Image)
VALUES
    ('Titre de l\'article', 'Contenu de l\'article', @adminUserID, NOW(), 'image1.jpg'),
    ('Titre de l\'article', 'Contenu de l\'article', @adminUserID, NOW(), 'image2.jpg'),
    ('Titre de l\'article', 'Contenu de l\'article', @adminUserID, NOW(), 'image3.jpg'),
    ('Titre de l\'article', 'Contenu de l\'article', @adminUserID, NOW(), 'image4.jpg');
