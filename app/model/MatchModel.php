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
	
	public function getMatches($idClub, $limit = null){
		return $this->database->query("SELECT id_zapas, datum_zapasu, domaci.nazev_klubu as klubdomaci, hoste.nazev_klubu as klubhoste, skore_domaci as skoredomaci, skore_hoste as skorehoste FROM zapas
		JOIN klub as hoste ON zapas.id_hoste = hoste.id_klub
		JOIN klub as domaci ON zapas.id_klub = domaci.id_klub
		WHERE domaci.id_klub = ? OR hoste.id_klub = ? ORDER BY datum_zapasu DESC", $idClub, $idClub)->fetchAll();
	}
	
	public function getMatchByID($idMatch){
		return $this->database->query("SELECT id_zapas, datum_zapasu, domaci.nazev_klubu as klubdomaci, hoste.nazev_klubu as klubhoste, skore_domaci as skoredomaci, skore_hoste as skorehoste FROM zapas
		JOIN klub as hoste ON zapas.id_hoste = hoste.id_klub
		JOIN klub as domaci ON zapas.id_klub = domaci.id_klub
		WHERE id_zapas = ?", $idMatch)->fetch();
	}
	
	public function getMatchStatus($idMatch){
		$match = $this->getMatchByID($idMatch);
		if ($match->skoredomaci == null && $match->skorehoste == null){
			return false;
		}
		else{
			return true;
		}
	}
	
	

}