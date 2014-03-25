#ListaFirme#

##Updater.php (Model)##

###private function decode_utf8 ( string )###
	- Realizeaza decodare UTF 8 a diacriticelor
	- Returneaza : string 

###private function seems_utf8( string ) ###
	- Verifica daca string-ul contine caractere ilegale
	@return : string

###private function remove_accents ( string ) ###
	@param string $string Text that might have accent characters
	@return string Filtered string with replaced "nice" characters.
	
###private function curl_get(string adresa , array data) ###
	@param string adresa - url ce trebuie preluat
	@param array data - array cu post-ul transmis
	@return : string - textul preluat
###public function genereaza_cif ( int cif )###
	- Cenereaza cifra de control a cif-ului 
	@param int cif 
	@return : int cif - cif ce contine cifra de control

###public function get_firma_mfinante ( int cif ) ###
	- Preia si proceseaza datele de la Ministerul de Finante
	@param : int cif
	@return : array date
	```
	Array
	(
    [0] => Array
        (
            [key] => Denumire platitor:
            [value] => IMPRESSION PRINT S.R.L.
        )

    [1] => Array
        (
            [key] => Adresa:
            [value] => Str. AGRICULTORI 93 BUCURESTI
        )

    [2] => Array
        (
            [key] => Judetul:
            [value] => MUNICIPIUL BUCURESTI
        )

    [3] => Array
        (
            [key] => Numar de inmatriculare la Registrul Comertului:
            [value] => J40 /3618 /2011
        )

    [4] => Array
        (
            [key] => Act autorizare:
            [value] => -
        )

    [5] => Array
        (
            [key] => Codul postal:
            [value] => 21484
        )

    [6] => Array
        (
            [key] => Telefon:
            [value] => 0747226777
        )

    [7] => Array
        (
            [key] => Fax:
            [value] => -
        )

    [8] => Array
        (
            [key] => Stare societate:
            [value] => INREGISTRAT din data 25 March 2011
        )

    [9] => Array
        (
            [key] => Observatii privind societatea comerciala:
            [value] => -
        )

    [10] => Array
        (
            [key] => Data inregistrarii ultimei declaratii: (*)
            [value] => 19 March 2013
        )

    [11] => Array
        (
            [key] => Data ultimei prelucrari: (**)
            [value] => 04 July 2013
        )

    [12] => Array
        (
            [key] => Impozit pe profit (data luarii in evidenta):
            [value] => NU
        )

    [13] => Array
        (
            [key] => Impozit pe veniturile microintreprinderilor (data luarii in evidenta):
            [value] => 28-MAR-11
        )

    [14] => Array
        (
            [key] => Accize (data luarii in evidenta):
            [value] => NU
        )

    [15] => Array
        (
            [key] => Taxa pe valoarea adaugata (data luarii in evidenta):
            [value] => NU
        )

    [16] => Array
        (
            [key] => Contributia de asigurari sociale (data luarii in evidenta):
            [value] => NU
        )

    [17] => Array
        (
            [key] => Contributia de asigurare pentru accidente de munca si boli profesionale datorate de angajator (data luarii in evidenta):
            [value] => NU
        )

    [18] => Array
        (
            [key] => Contributia de asigurari pentru somaj (data luarii in evidenta):
            [value] => NU
        )

    [19] => Array
        (
            [key] => Contributia angajatorilor pentru Fondul de garantare pentru plata creantelor sociale (data luarii in evidenta):
            [value] => NU
        )

    [20] => Array
        (
            [key] => Contributia pentru asigurari de sanatate (data luarii in evidenta):
            [value] => NU
        )

    [21] => Array
        (
            [key] => Contributii pentru concedii si indemnizatii de la persoane juridice sau fizice (data luarii in evidenta):
            [value] => NU
        )

    [22] => Array
        (
            [key] => Taxa jocuri de noroc (data luarii in evidenta):
            [value] => NU
        )

    [23] => Array
        (
            [key] => Impozit pe veniturile din salarii si asimilate salariilor (data luarii in evidenta):
            [value] => NU
        )

    [24] => Array
        (
            [key] => Impozit la titeiul si la gazele naturale din productia interna (data luarii in evidenta):
            [value] => NU
        )

    [25] => Array
        (
            [key] => Redevente miniere/Venituri din concesiuni si inchirieri (data luarii in evidenta):
            [value] => NU
        )

	)
	```
###public function get_firma_fiscal ( int cif ) ###
	- Preia informatii fiscale despre firma
	@param : int cif
	@return : array date
