<?php
/**
 * Created by PhpStorm.
 * User: Lizardor
 * Date: 9. 2. 2016
 * Time: 17:34
 */

namespace App\Model;

use Nette;

class UserModel extends Nette\Object {

	private $database;

	public function __construct(Nette\Database\Context $database) {
		$this->database = $database;

	}

	public function getLastUsers($pocet = 0) {
		if($pocet > 0) {
			return $rows = $this->database->query("SELECT id_uzivatel, uzivatelske_jmeno, email, datum_narozeni, jmeno, prijmeni, telefon FROM uzivatel JOIN role ON uzivatel.id_role = role.id_role WHERE role.id_role = 2 ORDER BY datum_registrace limit ?", $pocet);
		} else {
			return $rows = $this->database->query("SELECT id_uzivatel, uzivatelske_jmeno, email, datum_narozeni, jmeno, prijmeni, telefon FROM uzivatel JOIN role ON uzivatel.id_role = role.id_role WHERE role.id_role = 2 ORDER BY datum_registrace")->fetchAll();
		}
	}
	
	public function findUser($searchText){
		$searchText = "%".$searchText."%";
		return $rows = $this->database->query("SELECT id_uzivatel, uzivatelske_jmeno, email, datum_narozeni, jmeno, prijmeni, telefon FROM uzivatel JOIN role ON uzivatel.id_role = role.id_role WHERE role.id_role = 2 AND (uzivatelske_jmeno like ? OR id_uzivatel like ? OR email like ? OR prijmeni like ?)", $searchText, $searchText, $searchText, $searchText)->fetchAll();
	}
	
	public function getUserById($id){
		return $row = $this->database->query("SELECT id_uzivatel, uzivatelske_jmeno, email, datum_narozeni, jmeno, prijmeni, telefon, zustatek FROM uzivatel WHERE id_uzivatel = ?", $id)->fetch();
	}

}