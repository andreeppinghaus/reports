PRAGMA synchronous = OFF;
PRAGMA journal_mode = MEMORY;
CREATE TABLE IF NOT EXISTS species (family VARCHAR(5000) , scientificNameWithoutAuthorship VARCHAR(5000) , scientificNameAuthorship VARCHAR(5000));
INSERT INTO species VALUES ('MYRTACEAE','Eugenia chlorocarpa','O.Berg');
INSERT INTO species VALUES ('MYRTACEAE','Eugenia martiana','(O.Berg) Mattos');
CREATE TABLE IF NOT EXISTS occurrences (occurrenceID VARCHAR(5000) , bibliographicCitation VARCHAR(5000) , institutionCode VARCHAR(5000) , collectionCode VARCHAR(5000) , catalogNumber VARCHAR(5000) , recordNumber VARCHAR(5000) , recordedBy VARCHAR(5000) , occurrenceRemarks VARCHAR(5000) , year VARCHAR(5000) , month VARCHAR(5000) , day VARCHAR(5000) , identifiedBy VARCHAR(5000) , yearIdentified VARCHAR(5000) , monthIdentified VARCHAR(5000) , dayIdentified VARCHAR(5000) , stateProvince VARCHAR(5000) , municipality VARCHAR(5000) , locality VARCHAR(5000) , decimalLatitude VARCHAR(5000) , decimalLongitude VARCHAR(5000) , family VARCHAR(5000) , genus VARCHAR(5000) , specificEpithet VARCHAR(5000) , infraspecificEpithet VARCHAR(5000) , scientificName VARCHAR(5000) , georeferenceRemarks VARCHAR(5000) , georeferenceProtocol VARCHAR(5000) , georeferenceVerificationStatus VARCHAR(5000) , georeferencedBy VARCHAR(5000) , georeferencedDate VARCHAR(5000) , georeferencePrecision VARCHAR(5000) , acceptedNameUsage VARCHAR(5000) , valid VARCHAR(5000) , validation_taxonomy VARCHAR(5000) , validation_cultivated VARCHAR(5000) , validation_duplicated VARCHAR(5000) , validation_native VARCHAR(5000) , validation_georeference VARCHAR(5000) , contributor VARCHAR(5000) , dateLastModified VARCHAR(5000) , remarks VARCHAR(5000) , comments VARCHAR(5000));
INSERT INTO occurrences VALUES ('occurrence:rj:1399074','','INPA','INPA','130163','2244','Lima, H.C. de','Árvore de 12m alt. Tronco com casca avermelhada e fissurada, soltando pedaços retangulares. Botões verde- pardacentos. Na submata. N.V. \araça\ . Quadrat 2, árvore n 159','1984','10','18','Barroso, G.M.. Martinelli, G.. Lima, H.C. de','','','','Rio de Janeiro','Magé','Paraiso, área do Centro de Primatologia.','-22.486989','-42.911706','Myrtaceae','Calycorectes','martianus','','Calycorectes martianus','','google earth','ok','','','','Eugenia martiana (O.Berg) Mattos','','','','','','','Leonardo Novaes . Tomás Amorim . Lucas Moulton','2015-04-21 12:02:22','','');
INSERT INTO occurrences VALUES ('occurrence:rj:1633713','','F','F_BOTANY_BR','','772','C. Gaudichaud-Beaupré','','1833','1','1','','','','','','','','0','0','Myrtaceae','Eugenia','chlorocarpa','','Eugenia chlorocarpa','','','nok','','','','Eugenia chlorocarpa O.Berg','','','','','','','Victor Menezes . Tomás Amorim . Lucas Moulton','2015-04-15 15:47:34','','');
INSERT INTO occurrences VALUES ('occurrence:rj:2053622','','CEPLAC','CEPEC','130237','4347','H. C. de Lima','Ambiente: Mata. Serra. Habito: Árvore, 12&nf.Árvore, 12m X 14DAP','','','','G. M. Barroso','','','','Rio de Janeiro','','Estação Ecológica Estadual de Paraíso, Serra Queimada, Água Comprida.','-22.478422','-42.88531','Myrtaceae','Calycorectes','martianus','','Calycorectes martianus','','sig','ok','','','','Eugenia martiana (O.Berg) Mattos','','','','','','','Leonardo Novaes . Tomás Amorim . Lucas Moulton','2015-04-21 12:02:22','','');
INSERT INTO occurrences VALUES ('occurrence:rj:2185579','','UEFS','HUEFS','145812','2444','Lima, H.C.de','','1984','10','18','','','','','Rio de Janeiro','Magé','Paraíso, área do Centro de Primatologia.','-22.486989','-42.911706','Myrtaceae','Calycorectes','martianus','','Calycorectes martianus','','google earth','ok','','','','Eugenia martiana (O.Berg) Mattos','','','','','','','Leonardo Novaes . Lucas Moulton','2015-04-21 12:02:22','','');
INSERT INTO occurrences VALUES ('occurrence:rj:2232321','','UEFS','HUEFS','87654','772','Gaudichaud, M.','','','','','','','','','Rio de Janeiro','','','0','0','Myrtaceae','Eugenia','chlorocarpa','','Eugenia chlorocarpa','','','nok','','','','Eugenia chlorocarpa O.Berg','false','unknown','unknown','yes','unknown','unknown','Victor Menezes . Lucas Moulton','2015-04-15 15:47:34','','');
INSERT INTO occurrences VALUES ('occurrence:rj:2801859','','MOBOT','MOBOT_BR','3184508','2244','H. C. Lima, G. Martinelli, G.M. Banoso, H.C. de Lima','','1984','1','1','','','','','Rio de Janeiro','','Brasil, Rj, Mun. De Mage, Paraiso, area do centro de Primatologia do Rj.','-22.486989','-42.911706','Myrtaceae','Calycorectes','martianus','','Calycorectes martianus','','google earth','ok','','','','Eugenia martiana (O.Berg) Mattos','','','','','','','Leonardo Novaes . Lucas Moulton','2015-04-21 12:02:22','','');
INSERT INTO occurrences VALUES ('occurrence:rj:2839753','','MOBOT','MOBOT_BR','3183892','5393','Ynes Mexia','','1930','12','9','Bruce K. Holst,','','','','Minas Gerais','','Fazenda do Engenho. woods on hillcrest, in dense shade.','0','0','Myrtaceae','Eugenia','chlorocarpa','','Eugenia chlorocarpa','','','nok','','','','Eugenia chlorocarpa O.Berg','false','unknown','unknown','yes','unknown','unknown','Victor Menezes . Lucas Moulton','2015-04-15 15:47:34','','');
INSERT INTO occurrences VALUES ('occurrence:rj:3143815','','MBML','MBML-HERBARIO','13976','3276','L. Kollmann','','2000','11','21','M. Sobral, 2001','','','','Espírito Santo','Barra de São Francisco','Parque Municipal Sombra da Tarde','-18.755','-40.890833','Myrtaceae','Calycorectes','martianus','','Calycorectes martianus','','coletor','ok','','','','Eugenia martiana (O.Berg) Mattos','','','','','','','Leonardo Novaes . Lucas Moulton','2015-04-21 12:02:22','','');
INSERT INTO occurrences VALUES ('occurrence:rj:4265870','','JBRJ','RB','316222','4347','Lima, H.C. de','Arvore. Tronco com casca lisa e pardacenta, Flores perfumadas com calice alvo-esverdeado, receptaculo verde e corola e estames alvo-amarelados. Ocasional.  Agua Comprida.','1991','11','21','M. Sobral','','','','Rio de Janeiro','Guapimirim','Estação Ecológica Estadual de Paraíso. Serra Queimada, Água Comprida.','-22.478422','-42.88531','MYRTACEAE','Eugenia','martiana','','Eugenia martiana (O.Berg) Mattos','','sig','ok','','','','Eugenia martiana (O.Berg) Mattos','','','','','','','Leonardo Novaes . Tomás Amorim . Lucas Moulton','2015-04-21 12:02:22','','');
INSERT INTO occurrences VALUES ('occurrence:rj:4288727','','JBRJ','RB','230774','9926','Martinelli, G.','Árvore c/ 10m. de altura, diâmetro de tronco 13cm. diâmetro da copa6m. foljas discolores. estéril. Quadrat II, árvore nº 9.','1984','10','3','G.M.Barroso, H.C. de Lima, .G.Martinelli','','','','Rio de Janeiro','Guapimirim','Estação Ecológica Estadual de Paraíso. Centro de Primatológia do RJ=FEEMA.','-22.486989','-42.911706','MYRTACEAE','Calycorectes','martianus','','Calycorectes martianus O.Berg','','google earth','ok','','','','Eugenia martiana (O.Berg) Mattos','','','','','','','Leonardo Novaes . Lucas Moulton','2015-04-21 12:02:22','','');
INSERT INTO occurrences VALUES ('occurrence:rj:4329074','','JBRJ','RB','230779','9923','Martinelli, G.','Árvore c/ 9m. de altura. diâmetro de tronco c/ 8cm. diâmetro de copa 4m. folhas discolores. estéril. Quadrat ii, árvore nº 12.','1984','10','3','G.M.Barroso, H.C. de lima & G.Martinelli','','','','Rio de Janeiro','Guapimirim','Estação Ecológica Estadual de Paraíso. Centro de Primatológia do RjJFEEMA.','-22.486989','-42.911706','MYRTACEAE','Calycorectes','martianus','','Calycorectes martianus O.Berg','','google earth','ok','','','','Eugenia martiana (O.Berg) Mattos','','','','','','','Leonardo Novaes . Lucas Moulton','2015-04-21 12:02:22','','');
INSERT INTO occurrences VALUES ('occurrence:rj:4364174','','JBRJ','RB','240126','2396','Lima, H.C. de','Árvore com 6m. de altura, DAP 6cm., estéril. Quadrat III, árvore nº 97.','1984','11','12','','','','','Rio de Janeiro','Guapimirim','Estação Ecológica Estadual de Paraíso. Serra dos Porcos.','-22.478422','-42.88531','MYRTACEAE','Calycorectes','martianus','','Calycorectes martianus O.Berg','','sig','ok','','','','Eugenia martiana (O.Berg) Mattos','','','','','','','Leonardo Novaes . Lucas Moulton','2015-04-21 12:02:22','','');
INSERT INTO occurrences VALUES ('occurrence:rj:4368279','','JBRJ','RB','230776','10012','Martinelli, G.','Árvore c/ 10cm diâmetro de tronco, 12m. de altura. folhas discolores. estéril. Quadrat II, árvore nº 159.','1984','10','11','G.M.Barroso, H.C. de Lima & G.Martinelli','','','','Rio de Janeiro','Guapimirim','Estação Ecológica Estadual de Paraíso. Centro de Primatológia dop RJ-FEEMA.','-22.486989','-42.911706','MYRTACEAE','Calycorectes','martianus','','Calycorectes martianus O.Berg','','google earth','ok','','','','Eugenia martiana (O.Berg) Mattos','','','','','','','Leonardo Novaes . Lucas Moulton','2015-04-21 12:02:22','','');
INSERT INTO occurrences VALUES ('occurrence:rj:4393909','','JBRJ','RB','230773','9949','Martinelli, G.','Árvore com 15m. de altura. tronco c/ 10cm diâmetro. copa c/ 3m. diâmetro. folhas discolores. estéril. Quadrat II, árvore nº 43.','1984','10','4','G.M.Barroso, G.MArtinelli & H.C. de Lima','','','','Rio de Janeiro','Guapimirim','Estação Ecológica Estadual de Paraíso. Centro de Primatológia do RJ-FEEMA. .','-22.486989','-42.911706','MYRTACEAE','Calycorectes','martianus','','Calycorectes martianus O.Berg','','google earth','ok','','','','Eugenia martiana (O.Berg) Mattos','','','','','','','Leonardo Novaes . Lucas Moulton','2015-04-21 12:02:22','','');
INSERT INTO occurrences VALUES ('occurrence:rj:4414296','','JBRJ','RB','230778','9928','Martinelli, G.','Árvore com 8m. de altura, tronco com 7cm diâmetro. copa com 3m. diâmetro. folhas discolores. estéril, árvore nº 13.','1984','10','3','G.M.Barroso, G.Martinelli & H.C. de Lima','','','','Rio de Janeiro','Guapimirim','Estação Ecológica Estadual de Paraíso. Centro de Primatológia do RJ-FEEMA.','-22.486989','-42.911706','MYRTACEAE','Calycorectes','martianus','','Calycorectes martianus O.Berg','','google earth','ok','','','','Eugenia martiana (O.Berg) Mattos','','','','','','','Leonardo Novaes . Lucas Moulton','2015-04-21 12:02:22','','');
INSERT INTO occurrences VALUES ('occurrence:rj:4421095','','JBRJ','RB','231836','2273','Lima, H.C. de','Árvore com ca. 12m. de altura. Botões esverdados, estames alvos-pardacentos. Folhas discolores, face dorsal verde claro. ','1984','4','22','M. Sobral','','','','Rio de Janeiro','Magé','Estação Ecológica Estadual de Paraíso. área do centro de primatológia do RJ.','-22.486989','-42.911706','MYRTACEAE','Eugenia','martiana','','Eugenia martiana (O.Berg) Mattos','','google earth','ok','','','','Eugenia martiana (O.Berg) Mattos','','','','','','','Leonardo Novaes . Lucas Moulton','2015-04-21 12:02:22','','');
INSERT INTO occurrences VALUES ('occurrence:rj:4427675','','JBRJ','RB','','5393','Y. Mexia','Dados não transcritos','1930','12','9','Standley','','','','Minas Gerais','','Fazenda de Engenho.','0','0','MYRTACEAE','Eugenia','chlorocarpa','','Eugenia chlorocarpa O.Berg','','','nok','','','','Eugenia chlorocarpa O.Berg','','','','','','','Victor Menezes . Lucas Moulton','2015-04-15 15:47:34','','');
INSERT INTO occurrences VALUES ('occurrence:rj:449400','','UnB','HBVIRTFLBRAS','','s.n.','Riedel, L.. Langsdorff, GH (Baron). Langsdorff, GH (Baron)','','','','','','','','','Rio de Janeiro','','prope Mandiocca','-22.577493','-43.190966','Myrtaceae','Calycorectes','martianus','','Calycorectes martianus','','google earth','ok','','','','Eugenia martiana (O.Berg) Mattos','','','','','','','Leonardo Novaes . Lucas Moulton','2015-04-21 12:02:22','','');
INSERT INTO occurrences VALUES ('occurrence:rj:466287','','UnB','HBVIRTFLBRAS','','772','Gaudichaud Beaupré, C.','Original herbarium: Kunth. Dryas. HABITAT: Dryas','','','','','','','','Rio de Janeiro','','','0','0','Myrtaceae','Eugenia','chlorocarpa','','Eugenia chlorocarpa','','','nok','','','','Eugenia chlorocarpa O.Berg','false','unknown','unknown','yes','unknown','unknown','Victor Menezes . Lucas Moulton','2015-04-15 15:47:34','','');
INSERT INTO occurrences VALUES ('occurrence:rj:576522','','NYBG','NYBG_BR','1279919','2273','H. C. de Lima','more infohttp://sweetgum.nybg.org/vh/specimen.php?irn=1514590>more info>more info','1984','4','22','G. M. Barroso','','','','Rio de Janeiro','Magé','Paraiso, área do Centro de Primatología do RJ','-22.486989','-42.911706','Myrtaceae','Calycorectes','martianus','','Calycorectes martianus','','google earth','ok','','','','Eugenia martiana (O.Berg) Mattos','','','','','','','Leonardo Novaes . Lucas Moulton','2015-04-21 12:02:22','','');
INSERT INTO occurrences VALUES ('occurrence:rj:576523','','NYBG','NYBG_BR','561504','2244','H. C. de Lima','more infohttp://sweetgum.nybg.org/vh/specimen.php?irn=569972>more info>more info','1984','10','18','G. Martinelli. G. Banoso. H. C. de Lima','','','','Rio de Janeiro','Magé','Paraiso, área do Centro de Primatologia do RJ.','-22.486989','-42.911706','Myrtaceae','Calycorectes','martianus','','Calycorectes martianus','','google earth','ok','','','','Eugenia martiana (O.Berg) Mattos','','','','','','','Leonardo Novaes . Lucas Moulton','2015-04-21 12:02:22','','');
CREATE TABLE IF NOT EXISTS threats (family VARCHAR(5000) , scientificName VARCHAR(5000) , threat VARCHAR(5000) , incidence VARCHAR(5000) , timing VARCHAR(5000) , decline VARCHAR(5000));
INSERT INTO threats VALUES ('MYRTACEAE','Eugenia martiana','2 Agriculture & aquaculture','regional','present.past','habitat.occurrence');
CREATE TABLE IF NOT EXISTS actions (family VARCHAR(5000) , scientificName VARCHAR(5000) , action VARCHAR(5000) , situation VARCHAR(5000));
INSERT INTO actions VALUES ('MYRTACEAE','Eugenia martiana','1.1 Site/area protection','on going');
CREATE TABLE IF NOT EXISTS synonyms (family VARCHAR(5000) , scientificNameWithoutAuthorship VARCHAR(5000) , scientificNameAuthorship VARCHAR(5000) , acceptedNameUsage VARCHAR(5000));
INSERT INTO synonyms VALUES ('MYRTACEAE','Eugenia chlorocarpa','O.Berg','Eugenia chlorocarpa O.Berg');
INSERT INTO synonyms VALUES ('MYRTACEAE','Eugenia martiana','(O.Berg) Mattos','Eugenia martiana (O.Berg) Mattos');
CREATE TABLE IF NOT EXISTS ecology (family VARCHAR(5000) , scientificName VARCHAR(5000) , lifeForm VARCHAR(5000) , fenology VARCHAR(5000) , luminosity VARCHAR(5000) , substratum VARCHAR(5000) , longevity VARCHAR(5000) , resprout VARCHAR(5000));
INSERT INTO ecology VALUES ('MYRTACEAE','Eugenia chlorocarpa','Array','','','Array','','');
INSERT INTO ecology VALUES ('MYRTACEAE','Eugenia martiana','Array','','','Array','','');
CREATE TABLE IF NOT EXISTS uses (family VARCHAR(5000) , scientificName VARCHAR(5000) , use VARCHAR(5000) , resource VARCHAR(5000));
CREATE TABLE IF NOT EXISTS habitats (family VARCHAR(5000) , scientificName VARCHAR(5000) , habitat VARCHAR(5000));
INSERT INTO habitats VALUES ('MYRTACEAE','Eugenia chlorocarpa','1 Forest');
INSERT INTO habitats VALUES ('MYRTACEAE','Eugenia martiana','1 Forest');
CREATE TABLE IF NOT EXISTS fitofisionomias (family VARCHAR(5000) , scientificName VARCHAR(5000) , fitofisionomie VARCHAR(5000));
INSERT INTO fitofisionomias VALUES ('MYRTACEAE','Eugenia chlorocarpa','Floresta Ombrófila Densa');
INSERT INTO fitofisionomias VALUES ('MYRTACEAE','Eugenia martiana','Floresta Ombrófila Densa');
CREATE TABLE IF NOT EXISTS biomas (family VARCHAR(5000) , scientificName VARCHAR(5000) , bioma VARCHAR(5000));
INSERT INTO biomas VALUES ('MYRTACEAE','Eugenia chlorocarpa','Mata Atlântica');
INSERT INTO biomas VALUES ('MYRTACEAE','Eugenia martiana','Mata Atlântica');
CREATE TABLE IF NOT EXISTS assessments (family VARCHAR(5000) , scientificNameWithoutAuthorship VARCHAR(5000) , scientificNameAuthorship VARCHAR(5000) , analysis VARCHAR(5000) , assessment VARCHAR(5000) , category VARCHAR(5000) , criteria VARCHAR(5000) , rationale VARCHAR(5000));
INSERT INTO assessments VALUES ('MYRTACEAE','Eugenia chlorocarpa','O.Berg','validation','','','','');
INSERT INTO assessments VALUES ('MYRTACEAE','Eugenia martiana','(O.Berg) Mattos','validation','','','','');
CREATE TABLE IF NOT EXISTS pollination (family VARCHAR(5000) , scientificName VARCHAR(5000) , pollination VARCHAR(5000));
CREATE TABLE IF NOT EXISTS dispersion (family VARCHAR(5000) , scientificName VARCHAR(5000) , dispersion VARCHAR(5000));
