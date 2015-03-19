CREATE TABLE IF NOT EXISTS species (family VARCHAR(5000) , scientificNameWithoutAuthorship VARCHAR(5000) , scientificNameAuthorship VARCHAR(5000));
INSERT INTO species VALUES ('Boraginaceae','Cordia latiloba','I.M.Johnst.');
INSERT INTO species VALUES ('Boraginaceae','Myriopus volubilis','Small');
CREATE TABLE IF NOT EXISTS occurrences (occurrenceID VARCHAR(5000) , bibliographicCitation VARCHAR(5000) , institutionCode VARCHAR(5000) , collectionCode VARCHAR(5000) , catalogNumber VARCHAR(5000) , recordNumber VARCHAR(5000) , recordedBy VARCHAR(5000) , occurrenceRemarks VARCHAR(5000) , year VARCHAR(5000) , month VARCHAR(5000) , day VARCHAR(5000) , identifiedBy VARCHAR(5000) , yearIdentified VARCHAR(5000) , monthIdentified VARCHAR(5000) , dayIdentified VARCHAR(5000) , stateProvince VARCHAR(5000) , municipality VARCHAR(5000) , locality VARCHAR(5000) , decimalLatitude VARCHAR(5000) , decimalLongitude VARCHAR(5000) , family VARCHAR(5000) , genus VARCHAR(5000) , specificEpithet VARCHAR(5000) , infraspecificEpithet VARCHAR(5000) , scientificName VARCHAR(5000) , georeferenceRemarks VARCHAR(5000) , georeferenceProtocol VARCHAR(5000) , georeferenceVerificationStatus VARCHAR(5000) , georeferencedBy VARCHAR(5000) , georeferencedDate VARCHAR(5000) , georeferencePrecision VARCHAR(5000) , acceptedNameUsage VARCHAR(5000) , valid VARCHAR(5000) , validation_taxonomy VARCHAR(5000) , validation_cultivated VARCHAR(5000) , validation_duplicated VARCHAR(5000) , validation_native VARCHAR(5000) , validation_georeference VARCHAR(5000) , remarks VARCHAR(5000) , comments VARCHAR(5000));
CREATE TABLE IF NOT EXISTS threats (family VARCHAR(5000) , scientificName VARCHAR(5000) , threat VARCHAR(5000) , incidence VARCHAR(5000) , timing VARCHAR(5000) , decline VARCHAR(5000));
CREATE TABLE IF NOT EXISTS actions (family VARCHAR(5000) , scientificName VARCHAR(5000) , action VARCHAR(5000) , situation VARCHAR(5000));
CREATE TABLE IF NOT EXISTS synonyms (family VARCHAR(5000) , scientificNameWithoutAuthorship VARCHAR(5000) , scientificNameAuthorship VARCHAR(5000) , acceptedNameUsage VARCHAR(5000));
INSERT INTO synonyms VALUES ('Boraginaceae','Cordia latiloba','I.M.Johnst.','Cordia latiloba I.M.Johnst.');
INSERT INTO synonyms VALUES ('Boraginaceae','Myriopus volubilis','Small','Myriopus volubilis Small');
CREATE TABLE IF NOT EXISTS ecology (family VARCHAR(5000) , scientificName VARCHAR(5000) , lifeForm VARCHAR(5000) , fenology VARCHAR(5000) , luminosity VARCHAR(5000) , substratum VARCHAR(5000) , longevity VARCHAR(5000) , resprout VARCHAR(5000));
CREATE TABLE IF NOT EXISTS uses (family VARCHAR(5000) , scientificName VARCHAR(5000) , use VARCHAR(5000));
CREATE TABLE IF NOT EXISTS habitats (family VARCHAR(5000) , scientificName VARCHAR(5000) , habitat VARCHAR(5000));
CREATE TABLE IF NOT EXISTS fitofisionomias (family VARCHAR(5000) , scientificName VARCHAR(5000) , fitofisionomie VARCHAR(5000));
CREATE TABLE IF NOT EXISTS biomas (family VARCHAR(5000) , scientificName VARCHAR(5000) , bioma VARCHAR(5000));
CREATE TABLE IF NOT EXISTS assessments (family VARCHAR(5000) , scientificName VARCHAR(5000) , analysis VARCHAR(5000) , assessment VARCHAR(5000) , category VARCHAR(5000) , criteria VARCHAR(5000));
INSERT INTO assessments VALUES ('Boraginaceae','Cordia latiloba','','','','');
INSERT INTO assessments VALUES ('Boraginaceae','Myriopus volubilis','','','','');
CREATE TABLE IF NOT EXISTS pollination (family VARCHAR(5000) , scientificName VARCHAR(5000) , pollination VARCHAR(5000));
CREATE TABLE IF NOT EXISTS dispersion (family VARCHAR(5000) , scientificName VARCHAR(5000) , dispersion VARCHAR(5000));
