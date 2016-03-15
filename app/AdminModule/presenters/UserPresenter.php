<?php
/**
 * Created by PhpStorm.
 * User: Lizardor
 * Date: 3. 2. 2016
 * Time: 18:23
 */

namespace App\AdminModule\Presenters;

use App\Model\UserModel;
use Nette;
use Nette\Application\UI;

class UserPresenter extends BasePresenter {

	/** @var UserModel @inject */
	public $userModel;

	public function renderDefault() {
		$this->template->users = $this->userModel->getLastUsers(10);
	}

	public function actionDetail($id) {
		if(is_numeric($id)) {
			$this->template->user = $this->userModel->getUserById($id);
		}
		else{
			throw new Nette\Application\ForbiddenRequestException();
		}
	}
	
	
	public function actionEditUser($id){
		if(is_numeric($id)) {
			$this->template->user = $this->userModel->getUserById($id);
		}
		else{
			throw new Nette\Application\ForbiddenRequestException();
		}
	}

	protected function createComponentFindUser() {
		$form = new UI\Form();
		$form->addText("searchText")
			->setAttribute('placeholder', 'Vyhledat...')
			->setAttribute("class", "form-control");
		$form->addSubmit("search");
		$button = $form['search']->getControlPrototype();
		$button->setName('button');
		$button->setHtml("<i class='fa fa-search'></i>");
		$button->class[]   = "btn btn-default";
		$form->onSuccess[] = array($this, 'findUserSucceeded');

		return $form;
	}

	public function findUserSucceeded(UI\Form $form) {
		$values                    = $form->getValues();
		$this->template->findUsers = $this->userModel->findUser($values->searchText);

	}

}