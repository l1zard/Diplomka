<?php
/**
 * Created by PhpStorm.
 * User: Lizardor
 * Date: 29. 10. 2015
 * Time: 22:09
 */

namespace App\AdminModule\Presenters;

use App\Forms\SignFormFactory;

class LoginPresenter extends BasePresenter {

	/** @var SignFormFactory @inject */
	public $factory;

	public function renderDefault() {

	}

	protected function createComponentSignInForm() {
		$form              = $this->factory->create();
		$form->onSuccess[] = function ($form) {
			$form->getPresenter()->redirect('Homepage:');
		};

		return $form;
	}

}