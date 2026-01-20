
CREATE DATABASE newdb;

use newdb;

CREATE TABLE newdb.articole (
	doi VARCHAR(30) PRIMARY KEY, 
	titlu_articol VARCHAR(30) NOT NULL,
	tara VARCHAR(30) NOT NULL,
	jurnal VARCHAR (30),
	anpub INT(4)
)ENGINE=InnoDB;

CREATE TABLE newdb.autori (
    id INT NOT NULL AUTO_INCREMENT,
    nume VARCHAR(30) NOT NULL,
    PRIMARY KEY (id)
) ENGINE=INNODB;

CREATE TABLE newdb.coresp1(
    id_autor INT,
    doi_coresp VARCHAR(30),
    FOREIGN KEY (id_autor)
	REFERENCES autori(id)
	ON DELETE CASCADE
	ON UPDATE CASCADE,
    FOREIGN KEY (doi_coresp)
	REFERENCES articole(doi)
	ON DELETE CASCADE
	ON UPDATE CASCADE
) ENGINE=INNODB;


CREATE TABLE newdb.domenii(
	id INT NOT NULL AUTO_INCREMENT,
	denumire VARCHAR(30) NOT NULL,
	PRIMARY KEY (id)
)ENGINE=INNODB;

CREATE TABLE newdb.coresp2(
	id_domeniu INT,
	doi_coresp VARCHAR(30),
	FOREIGN KEY (id_domeniu)
		REFERENCES domenii(id)
		ON DELETE CASCADE
		ON UPDATE CASCADE,
	FOREIGN KEY (doi_coresp)
		REFERENCES articole(doi)
		ON DELETE CASCADE
		ON UPDATE CASCADE
)
ENGINE=INNODB;


CREATE TABLE newdb.draft(
	doi VARCHAR(30),
	titlu VARCHAR(30),
	tara VARCHAR (30),
	jurnal VARCHAR (30),
	anpub INT(4),
	autori VARCHAR (30),
	PRIMARY KEY (doi)
)ENGINE=INNODB;
