<?php
/**
 * Created by PhpStorm.
 * User: Lizardor
 * Date: 29. 10. 2015
 * Time: 22:13
 */

namespace App\Model;
use Nette;
use Nette\Security\AuthenticationException;
use Nette\Security\IIdentity;

class UserLogin extends Nette\Object implements Nette\Security\IAuthenticator{

	const
		TABLE_NAME = 'uzivatel',
		COLUMN_ID = 'id_uzivatel',
		COLUMN_NAME = 'uzivatelske_jmeno',
		COLUMN_PASSWORD_HASH = 'heslo',
		COLUMN_ROLE = 'nazev_role';
	/**
	 * Performs an authentication against e.g. database.
	 * and returns IIdentity on success or throws AuthenticationException
	 *
	 * @return IIdentity
	 * @throws AuthenticationException
	 */
	private $database;

	public function __construct(Nette\Database\Context $database)
	{
		$this->database = $database;
		
	}
	
	function authenticate(array $credentials) {
		list($username, $password) = $credentials;

		$row = $this->database->table(self::TABLE_NAME)->where(self::COLUMN_NAME, $username)->fetch();
		if (!$row) {
			throw new Nette\Security\AuthenticationException('The username is incorrect.', self::IDENTITY_NOT_FOUND);

		} 
		if(md5($password) != $row->heslo) {
			throw new Nette\Security\AuthenticationException('The password is incorrect.', self::INVALID_CREDENTIAL);
		}
			
		return new Nette\Security\Identity($row->id_uzivatel, $row->role->nazev_role, array('username' => $row[self::COLUMN_NAME]));
	}
}