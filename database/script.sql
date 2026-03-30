CREATE TABLE Utilisateur(
   Id_Utilisateur SERIAL,
   nom VARCHAR(100)  NOT NULL,
   prenom VARCHAR(200)  NOT NULL,
   identifiant VARCHAR(100)  NOT NULL,
   mdp VARCHAR(50)  NOT NULL,
   PRIMARY KEY(Id_Utilisateur),
   UNIQUE(identifiant)
);

CREATE TABLE Categorie(
   Id_Categorie SERIAL,
   rubrique VARCHAR(50)  NOT NULL,
   PRIMARY KEY(Id_Categorie)
);

CREATE TABLE Article(
   Id_Article SERIAL,
   titre TEXT NOT NULL,
   contenu TEXT NOT NULL,
   date_publication TIMESTAMP NOT NULL,
   Id_Categorie INTEGER NOT NULL,
   PRIMARY KEY(Id_Article),
   FOREIGN KEY(Id_Categorie) REFERENCES Categorie(Id_Categorie)
);

CREATE TABLE Article_photo(
   Id_Article_photo SERIAL,
   chemin TEXT NOT NULL,
   alt VARCHAR(255)  NOT NULL,
   Id_Article INTEGER NOT NULL,
   PRIMARY KEY(Id_Article_photo),
   FOREIGN KEY(Id_Article) REFERENCES Article(Id_Article)
);

CREATE TABLE Auteur(
   Id_Utilisateur INTEGER,
   Id_Article INTEGER,
   PRIMARY KEY(Id_Utilisateur, Id_Article),
   FOREIGN KEY(Id_Utilisateur) REFERENCES Utilisateur(Id_Utilisateur),
   FOREIGN KEY(Id_Article) REFERENCES Article(Id_Article)
);
