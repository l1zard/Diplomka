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
	
	

}