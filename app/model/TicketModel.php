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

	public function getUserByTicketID($idTicket){
		return $this->database->query("SELECT uzivatelske_jmeno, tiket.id_uzivatel FROM tiket
		JOIN uzivatel ON tiket.id_uzivatel = uzivatel.id_uzivatel
		WHERE id_tiket = ?", $idTicket)->fetch();
	}
	
	public function getTicketMoneyAndKurz($idTicket){
		$row = $this->database->query("SELECT kurz  FROM tiket
		JOIN prilezitost_tiketu ON tiket.id_tiket = prilezitost_tiketu.id_tiket
		JOIN prilezitost ON prilezitost_tiketu.id_prilezitost = prilezitost.id_prilezitost
		WHERE tiket.id_tiket = ?", $idTicket)->fetchAll();
		$summary = 1;
		foreach($row as $item){
			$summary = $summary * $item->kurz;
		}
		$row = $this->database->query("SELECT castka, datum_vytvoreni, id_stav FROM tiket WHERE id_tiket = ?", $idTicket)->fetch();
		$array = array(
			"kurz" => $summary,
			"castka" => $row->castka,
			"datum" => $row->datum_vytvoreni,
			"stav" => $row->id_stav,
			"vyhra" => $summary * $row->castka
			
		);
		return Nette\Database\Row::from($array);
	}

	public function updateTicketsByOpportunities($idOpportunity){
		$rows = $this->database->query("SELECT prilezitost.id_prilezitost as id_prilezitost, prilezitost.id_typ_prilezitosti as typ_prilezitosti, prilezitost.id_stav_prilezitost as stav_prilezitost, tiket.id_tiket as id_tiket,
		tiket.id_uzivatel, tiket.id_stav, tiket.castka
		FROM prilezitost
		JOIN prilezitost_tiketu ON prilezitost_tiketu.id_prilezitost = prilezitost.id_prilezitost
		JOIN tiket ON prilezitost_tiketu.id_tiket = tiket.id_tiket
		WHERE prilezitost.id_prilezitost = ? AND tiket.id_stav = 1", $idOpportunity)->fetchAll();
		foreach($rows as $row){
			if($row->stav_prilezitost == 3){
				$this->setTicketLost($row->id_tiket);
			}
			elseif($row->stav_prilezitost == 2){
				if($this->checkIfTicketIsDone($row->id_tiket)){
					$this->setTicketWin($row->id_tiket);
					$user = $this->getUserByTicketID($row->id_tiket);
					$tiket = $this->getTicketMoneyAndKurz($row->id_tiket);
					$this->updateUserMoney($user->id_uzivatel, $tiket->vyhra);
				}
			}
		}
	}

	public function setTicketLost($idTicket){
		$ticket = $this->getTicketMoneyAndKurz($idTicket);
		$this->database->query("UPDATE tiket SET id_stav = 3, vyhra = 0, datum_vyhodnoceni = NOW(), celkovy_kurz = ?",$ticket->kurz);
	}
	public function setTicketWin($idTicket){
		$ticket = $this->getTicketMoneyAndKurz($idTicket);
		$this->database->query("UPDATE tiket SET id_stav = 2, vyhra = ?, datum_vyhodnoceni = NOW(), celkovy_kurz = ?", $ticket->vyhra, $ticket->kurz);
	}
	public function checkIfTicketIsDone($idTicket){
		$ticket = $this->getTicketMatches($idTicket);
		foreach($ticket as $match){
			if($match->stav_prilezitost != 2){
				return false;
			}
		}
		return true;
	}

	public function updateUserMoney($idUser, $money){
		$this->database->query("UPDATE uzivatel SET zustatek = zustatek + ? WHERE id_uzivatel = ?", $money, $idUser);
	}
}