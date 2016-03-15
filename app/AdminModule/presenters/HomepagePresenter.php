<?php

/**
 * Created by PhpStorm.
 * User: Lizardor
 * Date: 29. 10. 2015
 * Time: 21:11
 */
namespace App\AdminModule\Presenters;
class HomepagePresenter extends BasePresenter {

	public function startup() {
		parent::startup();
		if(!$this->getUser()->isLoggedIn()){
			$this->redirect('Login:');
		}
	}

	public function renderDefault() {

	}
	
	public function actionOut(){
		$this->user->logout();
		$this->redirect('Homepage:');
	}

}