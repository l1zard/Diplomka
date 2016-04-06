<?php
/**
 * Created by PhpStorm.
 * User: Lizardor
 * Date: 9. 3. 2016
 * Time: 23:54
 */

namespace App\Model;

use Nette;

class MatchModel extends Nette\Object {

	private $database;

	public function __construct(Nette\Database\Context $database) {
		$this->database = $database;

	}

	public function getMatches($idClub, $limit = null) {
		return $this->database->query("SELECT id_zapas, datum_zapasu, domaci.nazev_klubu as klubdomaci, hoste.nazev_klubu as klubhoste, skore_domaci as skoredomaci, skore_hoste as skorehoste FROM zapas
		JOIN klub as hoste ON zapas.id_hoste = hoste.id_klub
		JOIN klub as domaci ON zapas.id_klub = domaci.id_klub
		WHERE domaci.id_klub = ? OR hoste.id_klub = ? ORDER BY datum_zapasu DESC", $idClub, $idClub)->fetchAll();
	}

	public function getMatchByID($idMatch) {
		return $this->database->query("SELECT id_zapas, datum_zapasu, domaci.nazev_klubu as klubdomaci, hoste.nazev_klubu as klubhoste, skore_domaci as skoredomaci, skore_hoste as skorehoste FROM zapas
		JOIN klub as hoste ON zapas.id_hoste = hoste.id_klub
		JOIN klub as domaci ON zapas.id_klub = domaci.id_klub
		WHERE id_zapas = ?", $idMatch)->fetch();
	}

	public function addResultByMatchID($id, $domaci, $hoste) {
		$row = $this->database->query("SELECT id_liga, id_klub, id_hoste FROM ZAPAS WHERE id_zapas = ?", $id)->fetch();
		$sezona = $this->database->query("SELECT id_sezony FROM sezona WHERE aktivni = 1")->fetch();
		$this->database->query("UPDATE ZAPAS SET skore_domaci = ?, skore_hoste = ? WHERE id_zapas = ?", $domaci, $hoste, $id);
		if($domaci > $hoste){
			$this->database->query("UPDATE tabulka SET vyhry = vyhry + 1, vstrelene_goly = ?, obdrzene_goly = ?, pocet_bodu = pocet_bodu + 3 WHERE id_klub = ? AND id_liga = ? AND id_sezony = ?",
				$domaci, $hoste, $row->id_klub, $row->id_liga, $sezona->id_sezony);
			$this->database->query("UPDATE tabulka SET prohry = prohry + 1, vstrelene_goly = ?, obdrzene_goly = ? WHERE id_klub = ? AND id_liga = ? AND id_sezony = ?",
				$hoste, $domaci, $row->id_hoste, $row->id_liga, $sezona->id_sezony);
		}
		else if($domaci < $hoste){
			$this->database->query("UPDATE tabulka SET prohry = prohry + 1, vstrelene_goly = ?, obdrzene_goly = ? WHERE id_klub = ? AND id_liga = ? AND id_sezony = ?",
				$domaci, $hoste, $row->id_klub, $row->id_liga, $sezona->id_sezony);
			$this->database->query("UPDATE tabulka SET vyhry = vyhry + 1, vstrelene_goly = ?, obdrzene_goly = ?, pocet_bodu = pocet_bodu + 3 WHERE id_klub = ? AND id_liga = ? AND id_sezony = ?",
				$hoste, $domaci, $row->id_hoste, $row->id_liga, $sezona->id_sezony);
		}
		else if($domaci == $hoste){
			$this->database->query("UPDATE tabulka SET remizy = remizy + 1, vstrelene_goly = ?, obdrzene_goly = ?, pocet_bodu = pocet_bodu + 1 WHERE id_klub = ? AND id_liga = ? AND id_sezony = ?",
				$domaci, $hoste, $row->id_klub, $row->id_liga, $sezona->id_sezony);
			$this->database->query("UPDATE tabulka SET remizy = remizy + 1, vstrelene_goly = ?, obdrzene_goly = ?, pocet_bodu = pocet_bodu + 1 WHERE id_klub = ? AND id_liga = ? AND id_sezony = ?",
				$hoste, $domaci, $row->id_hoste, $row->id_liga, $sezona->id_sezony);
		}
	}

	public function getMatchStatus($idMatch) {
		$match = $this->getMatchByID($idMatch);
		if($match->skoredomaci == null && $match->skorehoste == null) {
			return false;
		} else {
			return true;
		}
	}

	// příležitosti

	public function addOpportunity($idZapas, $typ, $kurz) {
		$row = $this->getOpportunityByTyp($idZapas, $typ);
		if($row != null) {
			$this->database->query("UPDATE prilezitost SET kurz = ? WHERE id_zapas = ? AND id_typ_prilezitosti = ?", $kurz, $idZapas, $typ);
		} else {
			$this->database->query("INSERT INTO prilezitost(kurz, id_zapas, id_typ_prilezitosti) VALUES(?,?,?)", $kurz, $idZapas, $typ);
		}
	}

	public function getOpportunityByTyp($idZapas, $typ) {
		$row = $this->database->query("SELECT kurz, id_zapas, id_typ_prilezitosti, id_stav_prilezitost FROM prilezitost WHERE id_zapas = ? AND id_typ_prilezitosti = ?", $idZapas, $typ)->fetch();

		return $row;
	}

	public function getAllOpportunityOfMatch($idZapas) {
		return $row = $this->database->query("SELECT kurz, id_zapas, id_typ_prilezitosti, typ_prilezitost.typ as typ_prilezitost, id_stav_prilezitost FROM prilezitost
		JOIN typ_prilezitost ON prilezitost.id_typ_prilezitosti = typ_prilezitost.id_typ_prilezitost
		WHERE id_zapas = ?", $idZapas)->fetchAll();
	}
	
	public function hasTypeOfKurzInOpportunity($array, $typ){
		foreach($array as $item){
			if($item['typ_prilezitost'] === $typ){
				return array(
					'found' => true,
					'kurz' => $item['kurz']
				);
			}
		}
		return false;
	}
	
	
	public function setMatchShow($idZapas){
		$row = $this->database->query("SELECT COUNT(*) as pocet FROM prilezitost WHERE id_zapas = ?", $idZapas)->fetch();
		if($row->pocet >= 3){
			$this->database->query("UPDATE zapas SET zobrazit = 1 WHERE id_zapas = ?", $idZapas);
			return true;
		}
		else{
			return false;
		}
	}
	
	public function getMatchShow($idZapas){
		$row = $this->database->query("SELECT zobrazit FROM zapas WHERE id_zapas = ?", $idZapas)->fetch();
		if($row->zobrazit == 1){
			return true;
		}
		else{
			return false;
		}
	}
	
	public function addMatch($idLiga, $idDomaci, $idHoste, $datum, $kolo, $informace=null){
		$date = new Nette\Utils\DateTime($datum);
		$this->database->query("INSERT INTO zapas(id_liga, id_klub, id_hoste, datum_zapasu, kolo, informace)
		VALUES(?, ?, ?, ?, ?, ?)", $idLiga, $idDomaci, $idHoste, $date, $kolo, $informace);
	}
	
	public function getIncommingMatchs(){
		$zapasy = $this->database->query("SELECT id_zapas, datum_zapasu, domaci.nazev_klubu as klubdomaci, hoste.nazev_klubu as klubhoste FROM zapas t1
		JOIN klub as hoste ON t1.id_hoste = hoste.id_klub
		JOIN klub as domaci ON t1.id_klub = domaci.id_klub")->fetchAll();
		foreach($zapasy as $zapas){
			$opportunitys = $this->database->query("SELECT id_prilezitost, kurz, id_typ_prilezitosti, typ_prilezitost.typ as typ FROM prilezitost JOIN typ_prilezitost ON prilezitost.id_typ_prilezitosti = typ_prilezitost.id_typ_prilezitost WHERE id_zapas = ?", $zapas->id_zapas)->fetchAll();
			$zapas->prilezitosti = $opportunitys;
		}
		return $zapasy;
	}
	
	public static function getTypPrilezitostString($idTyp){
		if($idTyp == 1){
			return "0 Remíza";
		}
		elseif($idTyp == 2){
			return "1 Výhra domácích";
		}
		elseif($idTyp == 3){
			return "2 Výhra hostí";
		}
	}

}