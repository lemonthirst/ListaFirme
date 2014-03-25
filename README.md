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
	
###public function get_firma_fiscal ( int cif ) ###
	- Preia informatii fiscale despre firma
	@param : int cif
	@return : array date
