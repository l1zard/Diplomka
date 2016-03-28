<?php

namespace App\FrontModule\Presenters;

use Nette;
use App\Model;
use Nette\Application\UI;


/**
 * Base presenter for all application presenters.
 */
abstract class BasePresenter extends Nette\Application\UI\Presenter
{


    protected function createComponentLoginUser(){
        $form = new UI\Form();
        $form->addText('username');
        $form->addPassword('password');
        $form->addSubmit('send');
        $form->onSuccess[] = $this->loginUserSucceed;

        return $form;
    }

    public function loginUserSucceed(){

    }
}
