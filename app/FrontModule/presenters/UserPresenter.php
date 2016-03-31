<?php
/**
 * Created by PhpStorm.
 * User: Lizardor
 * Date: 29. 3. 2016
 * Time: 19:52
 */

namespace App\FrontModule\Presenters;

use Nette;
use App\Model;
use Nette\Application\UI;
use App\Model\PaymentModel;
use Nette\Http\Request;

class UserPresenter extends BasePresenter {
	
	/** @var PaymentModel @inject */
    public $paymentModel;
	
	
	public function startup() {
		parent::startup();
		if(!$this->user->isLoggedIn()) {
			throw new Nette\Application\ForbiddenRequestException();
		}
	}

	public function actionPayment() {

	}

	protected function createComponentCreditCardForm() {
		$form = new UI\Form();
		$form->addText("vklad")
			->setAttribute("class", "textinput")
			->addRule(UI\Form::INTEGER, 'Hodnota musí být celočíselná!');
		$form->addSubmit("send", 'Vložit')
			->setAttribute('class', 'button-blue');
		$form->onSuccess[] = array($this, 'creditCardFormSucceed');

		return $form;
	}

	public function creditCardFormSucceed(UI\Form $form) {
		$values = $form->getValues();
		$httpRequest = $this->getHttpRequest();
		$this->paymentModel->createPayment($values->vklad, $this->user->getId(), $httpRequest->getUrl());

	}
	
	public function handleReturn(){
		$state = $this->paymentModel->returnData($_POST);
		if($state['bool']){
			$this->flashMessage('Vklad se zdařil! ' . $state['castka'] . ' Kč  bylo připsáno na váš účet', 'alert-success');
			$this->redirect('User:payment');
		}
		else{
			$this->flashMessage('Platba se nezdařila, opakujte prosím akci. V případě problémů kontaktujte podporu', 'alert-danger');
			$this->redirect('User:payment');
		}
		
	}
	


}