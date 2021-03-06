<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


///Updater.php

class Updater extends CI_Model {
	//Variabile globare
	var $info = array();
	
	
	
	//###private function decode_utf8 ( string )###
	//- Realizeaza decodare UTF 8 a diacriticelor
	//- Returneaza : string 
	
	private function decode_utf8($string) {

		$search = array("\u00c3", "\u00c4\u0083", "\u00c2", "\u00e2", "\u00ce", "\u00ee", "\u00aa", "\u00ba", "\u00c8\u009a", "\u00c8\u009b");
	
		$replace = array("A", "a", "A", "a", "I", "i", "S", "s", "T", "t");
	
		return str_replace($search, $replace, $string);
	}
	
	
	//###private function seems_utf8( string ) ###
	//- Verifica daca string-ul contine caractere ilegale
	//@return : string
	
	private function seems_utf8($str){
			$length = strlen($str);
			for ($i=0; $i < $length; $i++) {
				$c = ord($str[$i]);
				if ($c < 0x80) $n = 0; # 0bbbbbbb
				elseif (($c & 0xE0) == 0xC0) $n=1; # 110bbbbb
				elseif (($c & 0xF0) == 0xE0) $n=2; # 1110bbbb
				elseif (($c & 0xF8) == 0xF0) $n=3; # 11110bbb
				elseif (($c & 0xFC) == 0xF8) $n=4; # 111110bb
				elseif (($c & 0xFE) == 0xFC) $n=5; # 1111110b
				else return false; # Does not match any model
				for ($j=0; $j<$n; $j++) { # n bytes matching 10bbbbbb follow ?
					if ((++$i == $length) || ((ord($str[$i]) & 0xC0) != 0x80))
						return false;
				}
			}
			return true;
	}
	
	//###private function remove_accents ( string ) ###
	//@param string $string Text that might have accent characters
	//@return string Filtered string with replaced "nice" characters.
	
	private function remove_accents($string) {
		if ( !preg_match('/[\x80-\xff]/', $string) )
			return $string;
	
		if ($this->seems_utf8($string)) {
			$chars = array(
			// Decompositions for Latin-1 Supplement
			chr(195).chr(128) => 'A', chr(195).chr(129) => 'A',
			chr(195).chr(130) => 'A', chr(195).chr(131) => 'A',
			chr(195).chr(132) => 'A', chr(195).chr(133) => 'A',
			chr(195).chr(135) => 'C', chr(195).chr(136) => 'E',
			chr(195).chr(137) => 'E', chr(195).chr(138) => 'E',
			chr(195).chr(139) => 'E', chr(195).chr(140) => 'I',
			chr(195).chr(141) => 'I', chr(195).chr(142) => 'I',
			chr(195).chr(143) => 'I', chr(195).chr(145) => 'N',
			chr(195).chr(146) => 'O', chr(195).chr(147) => 'O',
			chr(195).chr(148) => 'O', chr(195).chr(149) => 'O',
			chr(195).chr(150) => 'O', chr(195).chr(153) => 'U',
			chr(195).chr(154) => 'U', chr(195).chr(155) => 'U',
			chr(195).chr(156) => 'U', chr(195).chr(157) => 'Y',
			chr(195).chr(159) => 's', chr(195).chr(160) => 'a',
			chr(195).chr(161) => 'a', chr(195).chr(162) => 'a',
			chr(195).chr(163) => 'a', chr(195).chr(164) => 'a',
			chr(195).chr(165) => 'a', chr(195).chr(167) => 'c',
			chr(195).chr(168) => 'e', chr(195).chr(169) => 'e',
			chr(195).chr(170) => 'e', chr(195).chr(171) => 'e',
			chr(195).chr(172) => 'i', chr(195).chr(173) => 'i',
			chr(195).chr(174) => 'i', chr(195).chr(175) => 'i',
			chr(195).chr(177) => 'n', chr(195).chr(178) => 'o',
			chr(195).chr(179) => 'o', chr(195).chr(180) => 'o',
			chr(195).chr(181) => 'o', chr(195).chr(182) => 'o',
			chr(195).chr(182) => 'o', chr(195).chr(185) => 'u',
			chr(195).chr(186) => 'u', chr(195).chr(187) => 'u',
			chr(195).chr(188) => 'u', chr(195).chr(189) => 'y',
			chr(195).chr(191) => 'y',
			// Decompositions for Latin Extended-A
			chr(196).chr(128) => 'A', chr(196).chr(129) => 'a',
			chr(196).chr(130) => 'A', chr(196).chr(131) => 'a',
			chr(196).chr(132) => 'A', chr(196).chr(133) => 'a',
			chr(196).chr(134) => 'C', chr(196).chr(135) => 'c',
			chr(196).chr(136) => 'C', chr(196).chr(137) => 'c',
			chr(196).chr(138) => 'C', chr(196).chr(139) => 'c',
			chr(196).chr(140) => 'C', chr(196).chr(141) => 'c',
			chr(196).chr(142) => 'D', chr(196).chr(143) => 'd',
			chr(196).chr(144) => 'D', chr(196).chr(145) => 'd',
			chr(196).chr(146) => 'E', chr(196).chr(147) => 'e',
			chr(196).chr(148) => 'E', chr(196).chr(149) => 'e',
			chr(196).chr(150) => 'E', chr(196).chr(151) => 'e',
			chr(196).chr(152) => 'E', chr(196).chr(153) => 'e',
			chr(196).chr(154) => 'E', chr(196).chr(155) => 'e',
			chr(196).chr(156) => 'G', chr(196).chr(157) => 'g',
			chr(196).chr(158) => 'G', chr(196).chr(159) => 'g',
			chr(196).chr(160) => 'G', chr(196).chr(161) => 'g',
			chr(196).chr(162) => 'G', chr(196).chr(163) => 'g',
			chr(196).chr(164) => 'H', chr(196).chr(165) => 'h',
			chr(196).chr(166) => 'H', chr(196).chr(167) => 'h',
			chr(196).chr(168) => 'I', chr(196).chr(169) => 'i',
			chr(196).chr(170) => 'I', chr(196).chr(171) => 'i',
			chr(196).chr(172) => 'I', chr(196).chr(173) => 'i',
			chr(196).chr(174) => 'I', chr(196).chr(175) => 'i',
			chr(196).chr(176) => 'I', chr(196).chr(177) => 'i',
			chr(196).chr(178) => 'IJ',chr(196).chr(179) => 'ij',
			chr(196).chr(180) => 'J', chr(196).chr(181) => 'j',
			chr(196).chr(182) => 'K', chr(196).chr(183) => 'k',
			chr(196).chr(184) => 'k', chr(196).chr(185) => 'L',
			chr(196).chr(186) => 'l', chr(196).chr(187) => 'L',
			chr(196).chr(188) => 'l', chr(196).chr(189) => 'L',
			chr(196).chr(190) => 'l', chr(196).chr(191) => 'L',
			chr(197).chr(128) => 'l', chr(197).chr(129) => 'L',
			chr(197).chr(130) => 'l', chr(197).chr(131) => 'N',
			chr(197).chr(132) => 'n', chr(197).chr(133) => 'N',
			chr(197).chr(134) => 'n', chr(197).chr(135) => 'N',
			chr(197).chr(136) => 'n', chr(197).chr(137) => 'N',
			chr(197).chr(138) => 'n', chr(197).chr(139) => 'N',
			chr(197).chr(140) => 'O', chr(197).chr(141) => 'o',
			chr(197).chr(142) => 'O', chr(197).chr(143) => 'o',
			chr(197).chr(144) => 'O', chr(197).chr(145) => 'o',
			chr(197).chr(146) => 'OE',chr(197).chr(147) => 'oe',
			chr(197).chr(148) => 'R',chr(197).chr(149) => 'r',
			chr(197).chr(150) => 'R',chr(197).chr(151) => 'r',
			chr(197).chr(152) => 'R',chr(197).chr(153) => 'r',
			chr(197).chr(154) => 'S',chr(197).chr(155) => 's',
			chr(197).chr(156) => 'S',chr(197).chr(157) => 's',
			chr(197).chr(158) => 'S',chr(197).chr(159) => 's',
			chr(197).chr(160) => 'S', chr(197).chr(161) => 's',
			chr(197).chr(162) => 'T', chr(197).chr(163) => 't',
			chr(197).chr(164) => 'T', chr(197).chr(165) => 't',
			chr(197).chr(166) => 'T', chr(197).chr(167) => 't',
			chr(197).chr(168) => 'U', chr(197).chr(169) => 'u',
			chr(197).chr(170) => 'U', chr(197).chr(171) => 'u',
			chr(197).chr(172) => 'U', chr(197).chr(173) => 'u',
			chr(197).chr(174) => 'U', chr(197).chr(175) => 'u',
			chr(197).chr(176) => 'U', chr(197).chr(177) => 'u',
			chr(197).chr(178) => 'U', chr(197).chr(179) => 'u',
			chr(197).chr(180) => 'W', chr(197).chr(181) => 'w',
			chr(197).chr(182) => 'Y', chr(197).chr(183) => 'y',
			chr(197).chr(184) => 'Y', chr(197).chr(185) => 'Z',
			chr(197).chr(186) => 'z', chr(197).chr(187) => 'Z',
			chr(197).chr(188) => 'z', chr(197).chr(189) => 'Z',
			chr(197).chr(190) => 'z', chr(197).chr(191) => 's',
			// Euro Sign
			chr(226).chr(130).chr(172) => 'E',
			// GBP (Pound) Sign
			chr(194).chr(163) => '');
	
			$string = strtr($string, $chars);
		} else {
			// Assume ISO-8859-1 if not UTF-8
			$chars['in'] = chr(128).chr(131).chr(138).chr(142).chr(154).chr(158)
				.chr(159).chr(162).chr(165).chr(181).chr(192).chr(193).chr(194)
				.chr(195).chr(196).chr(197).chr(199).chr(200).chr(201).chr(202)
				.chr(203).chr(204).chr(205).chr(206).chr(207).chr(209).chr(210)
				.chr(211).chr(212).chr(213).chr(214).chr(216).chr(217).chr(218)
				.chr(219).chr(220).chr(221).chr(224).chr(225).chr(226).chr(227)
				.chr(228).chr(229).chr(231).chr(232).chr(233).chr(234).chr(235)
				.chr(236).chr(237).chr(238).chr(239).chr(241).chr(242).chr(243)
				.chr(244).chr(245).chr(246).chr(248).chr(249).chr(250).chr(251)
				.chr(252).chr(253).chr(255);
	
			$chars['out'] = "EfSZszYcYuAAAAAACEEEEIIIINOOOOOOUUUUYaaaaaaceeeeiiiinoooooouuuuyy";
	
			$string = strtr($string, $chars['in'], $chars['out']);
			$double_chars['in'] = array(chr(140), chr(156), chr(198), chr(208), chr(222), chr(223), chr(230), chr(240), chr(254));
			$double_chars['out'] = array('OE', 'oe', 'AE', 'DH', 'TH', 'ss', 'ae', 'dh', 'th');
			$string = str_replace($double_chars['in'], $double_chars['out'], $string);
		}
	
		return $string;
	}
	private function sanitize_key($key){
		$characters = array(" ","-",",");
		return 	str_replace($characters,"",$key);
		
	}
	//###private function curl_get(string adresa , array data) ###
	//@param string adresa - url ce trebuie preluat
	//@param array data - array cu post-ul transmis
	//@return : string - textul preluat
	
	private function curl_get($adr,$data = array()) {
		
		$ch = curl_init();
		$fields_string = '';
		foreach($data as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
		
		rtrim($fields_string, '&');	
		
		curl_setopt($ch, CURLOPT_URL, $adr);
		if($data != array()) {
		curl_setopt($ch,CURLOPT_POST, count($data));
		curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
		
		}
		curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);  // Follow the redirects (needed for mod_rewrite)
		curl_setopt($ch, CURLOPT_HEADER, false);         // Don't retrieve headers   // Don't retrieve the body
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  // Return from curl_exec rather than echoing
	//	curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);   // Always ensure the connection is fresh
		
		
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
	
	}
	
	//###public function genereaza_cif ( int cif )###
	//- Cenereaza cifra de control a cif-ului 
	//@param int cif 
	//@return : int cif - cif ce contine cifra de control
	
	public function genereaza_cif($cif){
	   $original = $cif;
        
       static $validare="753217532";
		
       static $lungimeValidare=9; // strlen($validare)

        // Verifică dacă e întreg
        if ((int) $cif != $cif)
                return false;

        $cif = (int) $cif;

        if (strlen($cif) > 10)
                return false;

        // Extrage cifra de control
       $cifra_control = substr($cif, -1);
      //  $cif = substr($cif, 0, -1);

        // Adaugă zerouri în prefix pâna când CIF are 9 cifre
        while (strlen($cif)<9)
                $cif = '0' . $cif;

        // Calculează suma de control;
        // nu mai este nevoie de inversare (avem zerouri în primele poziții)
        $suma = 0;
        for($i=0; $i<$lungimeValidare; $i++)
			     $suma += $cif[$i] * $validare[$i];
		
        $rest = ( $suma*10  )% 11;

        if ($rest == 10)
                $rest=0;
		return $original.$rest;
	  }
	  
	 public function next_cif($cif){
		 
		$base = substr($cif,0,-1) + 1;
		
		return $this->genereaza_cif($base); 
		 
	 }
	 
	//###public function get_firma_mfinante ( int cif ) ###
	//- Preia si proceseaza datele de la Ministerul de Finante
	//@param : int cif
	//@return : array date 
	  
	public function get_firma_mfinante($cif){

		$data['pagina'] = 'domenii';
		$data['cod'] = $cif; 
		$data['captcha'] = 'null';
		$data['B1'] = 'VIZUALIZARE';

		$url = "http://www.mfinante.ro/infocodfiscal.html";
		
		$content = $this->curl_get($url,$data);
		$content = str_replace('&nbsp;','',$content);
		
		libxml_use_internal_errors(true);
	
	   $DOM = new DOMDocument;
	   if(!$DOM->loadHTML($content))
	   	foreach (libxml_get_errors() as $error) {
        // handle errors here
    	}

   		 libxml_clear_errors();
	   
	   $items = $DOM->getElementsByTagName('td');
	   $k = 0;
		  for ($i = 20; $i < 74; $i++){
			  $itm = preg_replace( '/\s+/', ' ',str_replace(array("\r", "\n"), '', trim($this->remove_accents($items->item($i)->nodeValue))));
			  if($i%2 == 0)
				$struct[$k]['key'] = $itm;
			  else {
				  $struct[$k]['value'] = $itm;
				  $k++;
			  }
		
		  }
		  
//Select 
		$option_tag =  $DOM->getElementsByTagName('option');
			foreach($option_tag as $tag){
				$an_fiscal = $tag->getAttribute('value');
				
				$fiscal[$an_fiscal] = $this->get_firma_fiscal($cif,$an_fiscal);
				
			}
		
		  array_shift ($struct);
		  
		  $return['date'] = $struct;
		  $return['fiscal'] = (isset($fiscal)) ? $fiscal : array();
		  
	  return $return;
	
	}
	
	//###public function get_firma_fiscal ( int cif ) ###
	//- Preia informatii fiscale despre firma
	//@param : int cif
	//@return : array date
	
	public function get_firma_fiscal($cif,$an){
		
		
		$data['an'] = $an;
		$data['cod'] = $cif; 
		$data['captcha'] = 'null';
		$data['method.bilant'] = 'VIZUALIZARE';

		$url = "http://www.mfinante.ro/infocodfiscal.html";
		
		$content = $this->curl_get($url,$data);
		$content = str_replace('&nbsp;','',$content);
		
		//echo $content;
		
		libxml_use_internal_errors(true);
	
	   $DOM = new DOMDocument;
	   if(!$DOM->loadHTML($content))
	   	foreach (libxml_get_errors() as $error) {
        // handle errors here
    	}

   		 libxml_clear_errors();
	   
	   $items = $DOM->getElementsByTagName('td');
	   
	   	   $k = 0;
		   $kt = 0;
		  for ($i = 26; $i < $items->length; $i++){
			  $itm = preg_replace( '/\s+/', ' ',str_replace(array("\r", "\n"), '', trim($this->remove_accents($items->item($i)->nodeValue))));
			  if($items->item($i)->getAttribute('colspan') != 2 ){
				  if($kt%2 == 0) 
					$struct[$k]['key'] = $itm;
				  else {
					$struct[$k]['value'] = $itm;
					if($struct[$k]['key'] != '' && $struct[$k]['key'] != 'Indicatori din CONTUL DE PROFIT SI PIERDERE')
						$k++;
				  }
				 $kt++;
			  }
		
		  }
	   
	   array_pop($struct);
		
		return $struct;
	
	}
	private function get_from_array($an_fiscal,$key,$delete=true){
		
		
		foreach($this->info['fiscal'][$an_fiscal] as $crt => $line)
			if($this->sanitize_key(strtolower($line['key']))== $this->sanitize_key(strtolower($key))){
				$value = str_replace("-","0",$line['value']);
				if($delete)
					unset($this->info['fiscal'][$an_fiscal][$crt]);
				return $value;
			}
					
		return 0;
	}
	
	public function format_data($cif){
		
	
		$this->info = $this->get_firma_mfinante($cif);
		
		$data = $this->info;
		
		$return = array(
				'cui' => $cif,
				'denumire'=> $data['date'][0]['value'],
				'adresa' => $data['date'][1]['value'],
				'adresa_judet' => $data['date'][2]['value'],
				'regcom' => $data['date'][3]['value'],	
				'autorizatie' => $data['date'][4]['value'],
				'adresa_postala' => $data['date'][5]['value'],
				'telefon' => $data['date'][6]['value'],
				'fax' => $data['date'][7]['value'],
				'stare' => $data['date'][8]['value'],
				'observatii' => $data['date'][9]['value'],
				'data_declaratie' => $data['date'][10]['value'],
				'data_prelucrare' => $data['date'][11]['value'],
				'evidente' => array('cui' => $cif,
									'impozit_profit'=>  $data['date'][12]['value'],
									'impozit_micro' =>  $data['date'][13]['value'],
									'accize' =>  $data['date'][14]['value'],
									'tva' =>  $data['date'][15]['value'],
									'asigurari_sociale' =>  $data['date'][16]['value'],
									'asigurari_accidente' =>  $data['date'][17]['value'],
									'asigurari_somaj' =>  $data['date'][18]['value'],
									'asigurari_garantare' =>  $data['date'][19]['value'],
									'asigurari_sanatate' =>  $data['date'][20]['value'],
									'concedii' =>  $data['date'][21]['value'],
									'jocuri_noroc' =>  $data['date'][22]['value'],
									'impozit_salarii' =>  $data['date'][23]['value'],
									'impozit_titei' =>  $data['date'][24]['value'],
									'redevente_miniere' =>  $data['date'][25]['value'])
						);
		if(isset($data['fiscal']))							
			foreach($data['fiscal'] as $key => $value){
				$return['fiscal'][] = array(
											'cui' => $cif,
											'an' => str_replace('WEB_AN','',$key),
											'active_imobilizate' => $this->get_from_array($key,"ACTIVE IMOBILIZATE - TOTAL"),
											'active_circulante' => $this->get_from_array($key,"ACTIVE CIRCULANTE - TOTAL, din care"),
											'stocuri' =>$this->get_from_array($key,"Stocuri (materiale, productie in curs de executie, semifabricate, produse finite, marfuri etc.)"),
											'creante' => $this->get_from_array($key,"Creante"),
											'casa_conturi' => $this->get_from_array($key,"Casa si conturi la banci"),
											'cheltuieli_avans' => $this->get_from_array($key,"CHELTUIELI IN AVANS"),
											'datorii' => $this->get_from_array($key,"DATORII - TOTAL"),
											'venituri_avans' => $this->get_from_array($key,"VENITURI IN AVANS"),
											'provizioane' => $this->get_from_array($key,"PROVIZIOANE"),
											'capitaluri' => ($this->get_from_array($key,"CAPITALURI - TOTAL, din care:",false) != '') ? $this->get_from_array($key,"CAPITALURI - TOTAL, din care:") :  $this->get_from_array($key,"Capitaluri - Total, din care"),
											'capital_social' => ($this->get_from_array($key,"Capital social subscris varsat",false)!= '') ?$this->get_from_array($key,"Capital social subscris varsat") : $this->get_from_array($key,"Capital social subscris si varsat"),
											'patrimoniu_regie' => $this->get_from_array($key,"Patrimoniul regiei"),
											'patrimoniu_public' =>$this->get_from_array($key,"Patrimoniul public"),
											'cifra_afaceri_neta' => ($this->get_from_array($key,"Cifra de afaceri neta",false) != '') ? $this->get_from_array($key,"Cifra de afaceri neta") : $this->get_from_array($key,"Cifra de afaceri"),
											'venituri_totale' =>$this->get_from_array($key,"VENITURI TOTALE"),
											'cheltuieli_totale' => $this->get_from_array($key,"CHELTUIELI TOTALE"),
											'profit_brut'  => ($this->get_from_array($key,"-Profit",false) != '') ? $this->get_from_array($key,"-Profit") : $this->get_from_array($key,"Profitul brut al exercitiului"),
											'pierdere_brut'  =>($this->get_from_array($key,"-Pierdere",false) != '') ? $this->get_from_array($key,"-Pierdere") : $this->get_from_array($key,"Pierderea bruta a exercitiului"),
											'profit_net' =>  ($this->get_from_array($key,"-Profit",false) != '') ? $this->get_from_array($key,"-Profit") : $this->get_from_array($key,"Profitul net al exercitiului"),
											'pierdere_net' => ($this->get_from_array($key,"-Pierdere",false) != '') ? $this->get_from_array($key,"-Pierdere") : $this->get_from_array($key,"Pierderea neta a exercitiului"),
											'nr_angajati' => $this->get_from_array($key,"Numar mediu de salariati"),
											'activitate' => $this->get_from_array($key,"Tipul de activitate, conform clasificarii CAEN"));		
			}
		return $return;
		
	}
	
	private function save2db($data) {
		
		$this->load->database();
		
		$data_bk = $data;
		
		$evidente = $data['evidente'];
		$fiscal = (isset($data['fiscal'])) ? $data['fiscal'] : array();
		
		unset($data['evidente']);
		unset($data['fiscal']);
		
		$this->db->insert('lista_general',$data);
	
		$this->db->insert('lista_evidente',$evidente);
		if(count($fiscal))
		foreach($fiscal as $fiscal_ac)
			$this->db->insert('lista_fiscal',$fiscal_ac);
	
	}
	
	
	public function firma($cif){
		
		
		$data = $this->format_data($cif);
		$this->save2db($data);	
		
	}
	
	
}