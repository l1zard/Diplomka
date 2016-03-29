<?php

namespace App\FrontModule\Presenters;

use Nette;
use App\Model;
use Nette\Application\UI;
use Nette\Application\UI\Form;

/**
 * Base presenter for all application presenters.
 */
abstract class BasePresenter extends Nette\Application\UI\Presenter
{


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
        } catch (Nette\Security\AuthenticationException $e) {
            $form->addError($e->getMessage());
        }
    }

    public function handleUserLogout(){
        if($this->user->isLoggedIn()){
            $this->user->logout();
            $this->redirect('this');
        }
    }
}
