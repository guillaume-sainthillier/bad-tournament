Create table categorie(
		idcat			char(4),
	 	nomcat			varchar(50),
	 	CONSTRAINT pk_categorie PRIMARY KEY (idcat)
)
;

Create table tournoi(
		idtournoi			int UNSIGNED NOT NULL AUTO_INCREMENT,
	 	nomtournoi			varchar(50),
	 	nbterrain		    int(3),
		nbjoueurparpoule    int(3),
		nbpoule		  	    int(3),
		idcat			    char(4),
		estGenere			boolean,
		CONSTRAINT pk_tournoi PRIMARY KEY (idtournoi),
		CONSTRAINT fk_tournoi_categorie FOREIGN KEY(idcat) REFERENCES categorie(idcat)
)
;

Create table poule(
		idpoule				int UNSIGNED NOT NULL AUTO_INCREMENT,
	 	nompoule			varchar(30),
		nbparticipants		int (2),
		nbjoues				int(3),
		idtournoi			int UNSIGNED,
		CONSTRAINT pk_poule PRIMARY KEY (idpoule),
		CONSTRAINT fk_poule_tournoi FOREIGN KEY(idtournoi) REFERENCES tournoi(idtournoi) ON  DELETE CASCADE
)
;

Create table equipe(
		idequipe			int UNSIGNED NOT NULL AUTO_INCREMENT,
	 	nomequipe			varchar(50),
	 	nbvictoire			int(2),
	 	nbdefaite			int(2),
	 	idpoule				int unsigned,
		idtournoi			int unsigned,
		CONSTRAINT pk_equipe PRIMARY KEY (idequipe),
		CONSTRAINT fk_equipe_poule FOREIGN KEY(idpoule) REFERENCES poule(idpoule) ON  DELETE CASCADE,
CONSTRAINT fk_equipe_tournoi FOREIGN KEY(idtournoi) REFERENCES tournoi(idtournoi) ON  DELETE CASCADE
		
)
;




CREATE TABLE participant(


		idparticipant			int UNSIGNED NOT NULL AUTO_INCREMENT,
		nomparticipant			varchar(50),
		prenomparticipant 		varchar(30),
		sexeparticipant			varchar(10),
		idequipe				int unsigned,
		CONSTRAINT pk_participant PRIMARY KEY (idparticipant),
		CONSTRAINT fk_participant_equipe FOREIGN KEY(idequipe) REFERENCES equipe(idequipe) ON  DELETE CASCADE
		
		
)
;




CREATE TABLE typematch(
		
		idtypematch	 			int UNSIGNED NOT NULL AUTO_INCREMENT,
		nomtypematch			varchar(20),
		
CONSTRAINT pk_typematch PRIMARY KEY (idtypematch)
		
)
;



CREATE TABLE terrains(
		
		idterrain	            int UNSIGNED,
		nomterrain     			varchar(20),
		
CONSTRAINT pk_terrains PRIMARY KEY (idterrain)
)
;


CREATE TABLE matchs(
		
		idmatch	            int UNSIGNED  AUTO_INCREMENT, 		
		idequipe1			int UNSIGNED,
		idequipe2			int UNSIGNED ,
		score1				int(2),
		score2				int(2),
		heurematch			DATE,
		estfini				boolean,
		estEnCours			boolean,
		idtournoi			int UNSIGNED,
		idtypematch			int UNSIGNED,
		idterrainjoue		int UNSIGNED,
		
		CONSTRAINT pk_matchs PRIMARY KEY (idmatch),
		CONSTRAINT fk_matchs_tournoi FOREIGN KEY(idtournoi) REFERENCES tournoi(idtournoi) ON  DELETE CASCADE,
		CONSTRAINT fk_match_equipe1 FOREIGN KEY(idequipe1) REFERENCES equipe(idequipe) ,
		CONSTRAINT fk_match_equipe2 FOREIGN KEY(idequipe2) REFERENCES equipe(idequipe),
		CONSTRAINT fk_match_typematch FOREIGN KEY(idtypematch) REFERENCES typematch(idtypematch)
		
)
;

CREATE TABLE deroulementMatch(
		
		idterrain	            int UNSIGNED,
		idmatch	           		int UNSIGNED,
 		ordre					int(2),
		idtournoi				int UNSIGNED,

				
		CONSTRAINT pk_deroulementMatch PRIMARY KEY (idterrain, idmatch),
		CONSTRAINT fk_deroulementMatch1 FOREIGN KEY(idterrain) REFERENCES terrains(idterrain),
		CONSTRAINT fk_deroulement_tournoi FOREIGN KEY(idtournoi) REFERENCES tournoi(idtournoi),
		CONSTRAINT fk_deroulementMatchs2 FOREIGN KEY(idmatch) REFERENCES matchs(idmatch)

)
;
