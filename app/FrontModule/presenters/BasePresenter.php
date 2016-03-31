<?php

namespace App\FrontModule\Presenters;

use Nette;
use App\Model;
use Nette\Application\UI;
use Nette\Application\UI\Form;
use App\Model\UserModel;


/**
 * Base presenter for all application presenters.
 */
abstract class BasePresenter extends Nette\Application\UI\Presenter
{
    
    /** @var UserModel @inject */
    public $userModel;
    
    protected $myUser;
    
    public function beforeRender() {
        parent::beforeRender();
        if($this->user->isLoggedIn()){
            $this->myUser = $this->userModel->getUserById($this->user->getId());
            $this->template->myUser = $this->myUser;
        }
    }

    protected function createComponentLoginUser(){

        $form = new UI\Form();
        $form->addText('username')
            ->setAttribute('class', 'textinput')
            ->setAttribute('placeholder', 'Uživatelské jméno')
            ->addRule(Form::FILLED, 'Vyplňte prosím jméno');
        $form->addPassword('password')
            ->setAttribute('class', 'textinput')
            ->setAttribute('placeholder', 'Heslo')
            ->addRule(Form::FILLED, 'Vyplňte prosím heslo');
        $form->addSubmit('send','Přihlásit')
            ->setAttribute('class', 'button-blue');
        $form->onSuccess[] = $this->loginUserSucceed;

        return $form;
    }
    
    public function loginUserSucceed(Form $form){
        $values = $form->values;
        $this->user->setExpiration('20 minutes', TRUE);
        try {
            $this->user->login($values->username, $values->password);
            $this->myUser = $this->userModel->getUserById($this->user->getId());
            $this->template->myUser = $this->myUser;
        } catch (Nette\Security\AuthenticationException $e) {
            $form->addError($e->getMessage());
        }
        
        /*$client = $this->getClient();
        $payment = $client->createPayment(11)
            ->setDescription('Order 123')
            ->addCartItem('Item 1', 10 * 100, 1)
            ->addCartItem('Item 2', 11 * 100, 1)
            ->setReturnUrl('http://localhost.diplomka.cz/?do=getPayment');
        $paymentResponse = $client->paymentInit($payment);
        $this->userModel->savePaymentId($paymentResponse->getPayId());
        // redirect to payment
        header('Location: ' . $client->paymentProcess($paymentResponse->getPayId())->getUrl());*/

    }

    public function handleUserLogout(){
        if($this->user->isLoggedIn()){
            $this->user->logout();
            $this->redirect('this');
        }
    }
    
    public function handleGetPayment(){
        $client = $this->getClient();
        $test = $client->receiveResponse($_POST);
        var_dump($test);
    }
    
   
}
