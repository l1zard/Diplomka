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
		$this->database->query("UPDATE ZAPAS SET skore_domaci = ?, skore_hoste = ? WHERE id_zapas = ?", $domaci, $hoste, $id);
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
		if($row->zobrazit = 1){
			return true;
		}
		else{
			return false;
		}
	}

}