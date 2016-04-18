<?php
/**
 * Created by PhpStorm.
 * User: Lizardor
 * Date: 9. 3. 2016
 * Time: 13:03
 */

namespace App\Model;

use Nette;

class LeagueModel extends Nette\Object{
	
	private $database;

	public function __construct(Nette\Database\Context $database) {
		$this->database = $database;

	}
	public function getLeagueArray(){
		return $row = $this->database->query("SELECT id_liga, nazev_ligy FROM liga")->fetchAll();
	}
	
	public function getLeagueById($id){
		return $this->database->query("SELECT * FROM liga WHERE id_liga = ?", $id)->fetch();
	}
	public function getLeagues(){
		$row = $this->getLeagueArray();
		$array = array();
		foreach($row as $item){
			$array[$item->id_liga] =  $item->nazev_ligy;
		}
		return $array;
	}
	
	public function getKlubByLeagueId($idLeague){
		return $this->database->query("SELECT klub.id_klub, nazev_klubu FROM KLUB
		JOIN tabulka ON klub.id_klub = tabulka.id_klub
		JOIN sezona ON tabulka.id_sezony = sezona.id_sezony
		WHERE sezona.aktivni = 1 AND id_liga = ?", $idLeague)->fetchAll();
	}
	
	public function getKlubByLeagueIdArray($idLeague){
		$row = $this->getKlubByLeagueId($idLeague);
		$array = array();
		foreach($row as $item){
			$array[$item->id_klub] =  $item->nazev_klubu;
		}
		return $array;
		
	}
	public function getAllLeagues(){
		return $this->database->query("SELECT id_liga, nazev_ligy, logo, narodnost, zkratka, flag_image FROM LIGA
		JOIN narodnost ON liga.id_narodnost = narodnost.id_narodnost")->fetchAll();
	}
	
	public function getNationality(){
		$rows = $this->database->query("SELECT * FROM narodnost")->fetchAll();
		$array = array();
		foreach($rows as $row){
			$array[$row->id_narodnost] = $row->narodnost;
		}
		return $array;
	}
	
	public function addLeague($nazev_ligy, $narodnost, $logo = null){
		if($this->database->query("INSERT INTO LIGA(nazev_ligy, id_narodnost, logo) VALUES(?, ?, ?)", $nazev_ligy, $narodnost, $logo)){
			return true;
		}
		else{
			return false;
		}
	}
	
	public function getListedMatchesByLeague($idLeague){
		$zapasy = $this->database->query("SELECT id_zapas, datum_zapasu, domaci.nazev_klubu as klubdomaci, hoste.nazev_klubu as klubhoste FROM zapas t1
		JOIN klub as hoste ON t1.id_hoste = hoste.id_klub
		JOIN klub as domaci ON t1.id_klub = domaci.id_klub
		WHERE t1.zobrazit = 1 AND datum_zapasu > (NOW() - INTERVAL 5 MINUTE) AND t1.id_liga = ?", $idLeague)->fetchAll();
		foreach($zapasy as $zapas){
			$opportunitys = $this->database->query("SELECT id_prilezitost, kurz, id_typ_prilezitosti, typ_prilezitost.typ as typ FROM prilezitost JOIN typ_prilezitost ON prilezitost.id_typ_prilezitosti = typ_prilezitost.id_typ_prilezitost WHERE id_zapas = ? ORDER BY FIELD(typ_prilezitost.typ,\"domaci\",\"remiza\",\"hoste\");", $zapas->id_zapas)->fetchAll();
			$zapas->prilezitosti = $opportunitys;
		}
		return $zapasy;
	}

}