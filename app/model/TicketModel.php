<?php
/**
 * Created by PhpStorm.
 * User: Lizardor
 * Date: 5. 4. 2016
 * Time: 11:45
 */

namespace App\Model;

use Nette;
class TicketModel extends Nette\Object {


	private $database;

	public function __construct(Nette\Database\Context $database) {
		$this->database = $database;

	}

	public function betTicket($idUzivatel, $money, $arrayMatches) {
		$idTiket = $this->addNewTicket($money, $idUzivatel);
		foreach($arrayMatches as $match) {
			$this->addOpportunityToTicket($idTiket, $match['id']);
		}


	}

	private function addNewTicket($money, $idUzivatel) {
		$this->database->query("INSERT INTO tiket(castka, datum_vytvoreni, id_uzivatel) VALUES(?, NOW(), ?)", $money, $idUzivatel);

		return $this->database->getInsertId();
	}

	private function addOpportunityToTicket($idTicket, $idOpprtunity) {
		$this->database->query("INSERT INTO prilezitost_tiketu(id_prilezitost, id_tiket) VALUES(?,?)", $idOpprtunity, $idTicket);
	}

	public function getAllTicketByUser($id) {
		$rows = $this->database->query("SELECT id_tiket, datum_vytvoreni, castka, id_stav FROM tiket WHERE id_uzivatel = ?", $id)->fetchAll();
		foreach($rows as $row) {
			if($row->id_stav == 1) {
				$row->stav  = "question";
				$row->color = "1AADFF";
			} elseif($row->id_stav == 2) {
				$row->stav  = "check";
				$row->color = "green";
			} else if($row->id_stav == 3) {
				$row->stav  = "remove";
				$row->color = "red";
			}
		}

		return $rows;
	}

	public function getTicketMatches($idTicket) {
		$rows = $this->database->query("SELECT domaci.nazev_klubu as domaciklub, hoste.nazev_klubu as hosteklub, prilezitost.id_typ_prilezitosti as id_typ_prilezitosti, prilezitost.kurz as kurz, zapas.datum_zapasu as datum, prilezitost.id_stav_prilezitost as stav_prilezitost FROM tiket
		JOIN prilezitost_tiketu ON tiket.id_tiket = prilezitost_tiketu.id_tiket
		JOIN prilezitost ON prilezitost_tiketu.id_prilezitost = prilezitost.id_prilezitost
		JOIN zapas ON prilezitost.id_zapas = zapas.id_zapas
		JOIN klub as domaci ON zapas.id_klub = domaci.id_klub
		JOIN klub as hoste ON zapas.id_hoste = hoste.id_klub
		WHERE tiket.id_tiket = ?", $idTicket)->fetchAll();
		foreach($rows as $row) {
			$row->typ_prilezitost = MatchModel::getTypPrilezitostString(1);
			if($row->stav_prilezitost == 1) {
				$row->stav  = "question";
				$row->color = "1AADFF";
			} elseif($row->stav_prilezitost == 2) {
				$row->stav  = "check";
				$row->color = "green";
			} else if($row->stav_prilezitost == 3) {
				$row->stav  = "remove";
				$row->color = "red";
			}
		}

		return $rows;

	}

}