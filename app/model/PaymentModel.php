<?php
/**
 * Created by PhpStorm.
 * User: Lizardor
 * Date: 30. 3. 2016
 * Time: 17:39
 */

namespace App\Model;

use Kdyby\CsobPaymentGateway\Payment;
use Kdyby\CsobPaymentGateway\PaymentCanceledException;
use Kdyby\CsobPaymentGateway\PaymentException;
use Nette;
use Kdyby\CsobPaymentGateway\Certificate\PrivateKey;
use Kdyby\CsobPaymentGateway\Certificate\PublicKey;
use Kdyby\CsobPaymentGateway\Client;
use Kdyby\CsobPaymentGateway\Configuration;
use Kdyby\CsobPaymentGateway\Http\GuzzleClient;
use Kdyby\CsobPaymentGateway\Message\Signature;

class PaymentModel extends Nette\Object {

	private $database;

	public function __construct(Nette\Database\Context $database) {
		$this->database = $database;

	}

	private function getClient() {
		$client = new Client(
			new Configuration('A1654WIQja', '4win'),
			new Signature(
				new PrivateKey(Configuration::getCsobSandboxPrivateKey(), null),
				new PublicKey(Configuration::getCsobSandboxCertPath())
			),
			new GuzzleClient()
		);

		return $client;
	}

	public function createPayment($money, $idUzivatel, $url) {
		$idPayment       = $this->addPaymentToDB($money, $idUzivatel);
		$client          = $this->getClient();
		$payment         = $client->createPayment((int) $idPayment)
			->setDescription("Vklad na účet 4WIN")
			->addCartItem('Dobití účtu', 100 * $money, 1)
			->setReturnUrl('http://' . $url->host . '/user/?do=return');
		$paymentResponse = $client->paymentInit($payment);
		$this->updatePayId($idPayment, $paymentResponse->getPayId());
		header('Location: ' . $client->paymentProcess($paymentResponse->getPayId())->getUrl());
	}

	public function returnData($post) {
		$client = $this->getClient();
		try {
			$response = $client->receiveResponse($post);
			if($response->getPaymentStatus() === Payment::STATUS_APPROVED || $response->getPaymentStatus() === Payment::STATUS_TO_CLEARING || $response->getPaymentStatus() === Payment::STATUS_TO_CLEARING) {
				$uzivatel = $this->getUserByPayID($response->getPayId());
				$this->updatePaymentStatus($response->getPayId(), $response->getPaymentStatus());
				$this->updateUserMoney($uzivatel->id_uzivatel, $uzivatel->castka);

				return array(
					"bool"   => true,
					"castka" => $uzivatel->castka
				);
			} else {
				return array(
					"bool" => false
				);
			}
		} catch(PaymentCanceledException $exception) {
			$this->updatePaymentStatus($post['payId'], $post['paymentStatus']);
			return array(
				"bool" => false
			);
		}

	}

	public function addPaymentToDB($money, $idUzivatel) {
		$this->database->query("INSERT INTO platba(vytvoreni_platby, castka, id_uzivatel, id_druh_platby) VALUES(NOW(), ?, ?, 1)", $money, $idUzivatel);

		return $this->database->getInsertId();
	}

	public function updatePayId($idPlatba, $payId) {
		$this->database->query("UPDATE platba SET payId = ? WHERE id_platba = ?", $payId, $idPlatba);
	}

	public function getUserByPayID($payId) {
		return $this->database->query("SELECT id_uzivatel, castka FROM platba WHERE payId = ?", $payId)->fetch();
	}

	public function updateUserMoney($idUzivatel, $money) {
		$this->database->query("UPDATE uzivatel SET zustatek = zustatek + ? WHERE id_uzivatel = ?", $money, $idUzivatel);
	}

	public function updatePaymentStatus($payId, $paymentStatus) {
		$this->database->query("UPDATE platba SET paymentStatus = ?, potvrzeni_platby = NOW() WHERE payId = ?", $paymentStatus, $payId);
	}
}