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
