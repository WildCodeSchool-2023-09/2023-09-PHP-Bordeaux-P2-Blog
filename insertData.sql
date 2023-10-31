USE bdd_blog;

-- Insére un utilisateur "admin" dans la table Utilisateurs
INSERT INTO blog_user (name, password, email, date)
VALUES ('admin', 'motdepasse', 'admin@example.com', NOW());

-- Récupére l'ID de l'utilisateur "admin" 
SET @adminUserID = LAST_INSERT_ID();

-- Insére 4 articles écrits par l'utilisateur "admin" dans la table Articles
INSERT INTO article (title, content, blog_user_id, date, image)
VALUES
    ('Titre de l\'article', 'Contenu de l\'article', @adminUserID, NOW(), 'image1.jpg'),
    ('Titre de l\'article', 'Contenu de l\'article', @adminUserID, NOW(), 'image2.jpg'),
    ('Titre de l\'article', 'Contenu de l\'article', @adminUserID, NOW(), 'image3.jpg'),
    ('Titre de l\'article', 'Contenu de l\'article', @adminUserID, NOW(), 'image4.jpg');

UPDATE article SET image = 'https://image.noelshack.com/fichiers/2023/44/2/1698756889-logo-bump.png
' WHERE id = 1;
