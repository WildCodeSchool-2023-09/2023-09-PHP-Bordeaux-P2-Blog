DROP DATABASE IF EXISTS projet2Bdd;
CREATE DATABASE projet2Bdd;

USE projet2Bdd;

-- Utilisateurs
CREATE TABLE Utilisateurs (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    NomUtilisateur VARCHAR(255),
    MotDePasse VARCHAR(255),
    AdresseEmail VARCHAR(255),
    DateInscription DATETIME
);

-- Articles
CREATE TABLE Articles (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    TitreArticle VARCHAR(255),
    ContenuArticle TEXT,
    IDAuteur INT,
    DatePublication DATETIME,
    Image VARCHAR(255),
    FOREIGN KEY (IDAuteur) REFERENCES Utilisateurs(ID)
);

-- Commentaires
CREATE TABLE Commentaires (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    ContenuCommentaire TEXT,
    IDAuteur INT,
    IDArticle INT,
    DateCommentaire DATETIME,
    FOREIGN KEY (IDAuteur) REFERENCES Utilisateurs(ID),
    FOREIGN KEY (IDArticle) REFERENCES Articles(ID)
);

-- Catégories
CREATE TABLE Catégories (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    NomCategorie VARCHAR(255)
);

-- Table de liaison Articles-Catégories
CREATE TABLE ArticlesCategories (
    IDArticle INT,
    IDCategorie INT,
    FOREIGN KEY (IDArticle) REFERENCES Articles(ID),
    FOREIGN KEY (IDCategorie) REFERENCES Catégories(ID)
);



-- DROP DATABASE IF EXISTS bdd_blog;
-- CREATE DATABASE bdd_blog;
-- USE bdd_blog;
-- CREATE TABLE blog_user (
--     id INT NOT NULL AUTO_INCREMENT,
--     name VARCHAR(50),
--     password VARCHAR(255) NOT NULL,
--     email VARCHAR(40) NOT NULL,
--     date DATETIME DEFAULT NOW(),
--     image VARCHAR(255),
--     title VARCHAR(100),
--     description TEXT,
--     UNIQUE KEY (email),
--     PRIMARY KEY (id)
-- );
-- CREATE TABLE article (
--     id INT NOT NULL AUTO_INCREMENT,
--     title VARCHAR(40) NOT NULL,
--     content TEXT,
--     date DATETIME DEFAULT NOW(),
--     image VARCHAR(255),
--     blog_user_id INT,
--     PRIMARY KEY (id),
--     FOREIGN KEY (blog_user_id) REFERENCES blog_user(id)
-- );
-- CREATE TABLE commentary (
--     id INT NOT NULL AUTO_INCREMENT,
--     content TEXT,
--     date DATETIME DEFAULT NOW(),
--     blog_user_id INT,
--     article_id INT,
--     PRIMARY KEY (id),
--     FOREIGN KEY (blog_user_id) REFERENCES blog_user(id),
--     FOREIGN KEY (article_id) REFERENCES article(id)
-- );
-- CREATE TABLE category (
--     id INT NOT NULL AUTO_INCREMENT,
--     name VARCHAR(50),
--     PRIMARY KEY (id),
--     UNIQUE KEY (name)
-- );
-- CREATE TABLE article_category (
--     id INT NOT NULL AUTO_INCREMENT,
--     article_id INT,
--     category_id INT,
--     PRIMARY KEY (id),
--     FOREIGN KEY (article_id) REFERENCES article(id),
--     FOREIGN KEY (category_id) REFERENCES category(id)
-- );