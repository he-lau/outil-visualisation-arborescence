CREATE TABLE DOSSIER (
  id INT NOT NULL AUTO_INCREMENT,
  chemin  VARCHAR(255) NOT NULL UNIQUE,
  nom VARCHAR(255) NOT NULL,
  niveau VARCHAR(255) NOT NULL,
  parent_id INT,

  PRIMARY KEY (id),
  FOREIGN KEY (parent_id) REFERENCES DOSSIER (id)
);

CREATE TABLE DOCUMENT (
  id INT NOT NULL AUTO_INCREMENT,
  nom VARCHAR(255) NOT NULL,
  chemin  VARCHAR(255) NOT NULL UNIQUE,
  dossier_id INT NOT NULL,
  extension  VARCHAR(255) NOT NULL,
  taille FLOAT NOT NULL,

  PRIMARY KEY (id),
  FOREIGN KEY (dossier_id) REFERENCES DOSSIER (id)
);

/*
INSERT INTO DOCUMENT VALUES ("doc1","/home/rep1","rep1",".txt","117.5");
INSERT INTO DOCUMENT VALUES ("img1","/home/rep1","rep1",".png","417.5");
INSERT INTO DOCUMENT VALUES ("pdf1","/home/rep1","rep1",".pdf","223.5");

INSERT INTO DOCUMENT VALUES ("doc2","/home/rep2","rep2",".txt","117.7");
INSERT INTO DOCUMENT VALUES ("img2","/home/rep2","rep2",".png","447.5");
INSERT INTO DOCUMENT VALUES ("pdf2","/home/rep2","rep2",".pdf","83.0");

INSERT INTO DOCUMENT VALUES ("doc3","/home/rep3","rep3",".txt","117.7");
INSERT INTO DOCUMENT VALUES ("img3","/home/rep3","rep3",".png","447.5");
INSERT INTO DOCUMENT VALUES ("pdf3","/home/rep3","rep3",".pdf","83.0");
*/
