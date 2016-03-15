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
		return $this->database->query("SELECT datum_zapasu, domaci.nazev_klubu, hoste.nazev_klubu, skore_domaci, skore_hoste FROM zapas
		JOIN klub as hoste ON zapas.id_hoste = hoste.id_klub
		JOIN klub as domaci ON zapas.id_klub = domaci.id_klub
		WHERE domaci.id_klub = ? OR hoste.id_klub = ? ORDER BY datum_zapasu DESC", $idClub, $idClub)->fetchAll();
	}
	
	

}