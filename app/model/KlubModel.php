<?php
/**
 * Created by PhpStorm.
 * User: Lizardor
 * Date: 10. 2. 2016
 * Time: 21:23
 */

namespace App\Model;

use Nette;

class KlubModel extends Nette\Object {

	private $database;

	public function __construct(Nette\Database\Context $database) {
		$this->database = $database;

	}

	public function getClubs($pocet = 0) {
		if($pocet > 0) {
			return $rows = $this->database->query("SELECT * FROM klub ORDER by nazev_klubu LIMIT ?", $pocet)->fetchAll();
		} else {
			return $rows = $this->database->query("SELECT * FROM klub ORDER by nazev_klubu LIMIT 10;")->fetchAll();
		}
	}

	public function addClub($nazev, $stadion = null, $rok_zalozeni = null, $web = null, $informace = null, $logo) {
		if($this->database->query("INSERT INTO KLUB(nazev_klubu, stadion, rok_zalozeni, webova_stranka, informace, logo) VALUES(?, ?, ?, ?, ?, ?)", $nazev, $stadion, $rok_zalozeni, $web, $informace, $logo)) {
			return true;
		} else {
			return false;
		}
	}

	public function getClubById($id) {
		return $this->database->query("SELECT id_klub, nazev_klubu, stadion, rok_zalozeni, informace, logo, webova_stranka FROM klub WHERE id_klub = ?", $id)->fetch();
	}

	public function getLeaguesOfClub($id) {
		return $this->database->query("
		SELECT nazev_ligy, sezona.sezona FROM KLUB
		JOIN tabulka ON klub.id_klub = tabulka.id_klub
		JOIN liga ON tabulka.id_liga = liga.id_liga 
		JOIN sezona ON tabulka.id_sezony = sezona.id_sezony
		WHERE klub.id_klub = ?", $id)->fetchAll();
	}
	
	public function addClubToLeague($idClub, $idLeague){
		$row = $this->database->query("SELECT id_sezony FROM sezona WHERE aktivni = 1")->fetch();
		
		$this->database->query("INSERT INTO tabulka(id_klub, id_liga, id_sezony) VALUES(?,?,?)", $idClub, $idLeague, $row->id_sezony);
		
	}
	
}