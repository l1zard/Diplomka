<?php
/**
 * Created by PhpStorm.
 * User: Lizardor
 * Date: 10. 2. 2016
 * Time: 17:22
 */

namespace App\AdminModule\Presenters;

use App\Model\KlubModel;
use App\Model\LeagueModel;
use Nette;
use Nette\Application\UI;

class KlubPresenter extends BasePresenter {

	/** @var KlubModel @inject */
	public $clubModel;

	/** @var LeagueModel @inject */
	public $leagueModel;
	
	private $klubId;

	public function renderDefault() {
		$this->template->clubs = $this->clubModel->getClubs();
	}

	public function actionAddClub() {

	}

	protected function createComponentFindClub() {
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
		$values = $form->getValues();
		//$this->template->findUsers = $this->userModel->findUser($values->searchText);

	}

	protected function createComponentAddClub() {
		$form = new UI\Form();
		$form->addText("nazev_klubu")
			->setAttribute("class", "form-control");
		$form->addText("stadion")
			->setAttribute("class", "form-control");
		$form->addText("rok_zalozeni")
			->setAttribute("class", "form-control");
		$form->addText("webova_stranka")
			->setAttribute("class", "form-control");
		$form->addTextArea("informace")
			->setAttribute("class", "form-control");
		$form->addUpload("logo")
			->addCondition(UI\Form::FILLED)
			->addRule(Nette\Forms\Form::IMAGE, 'Fotka musí být ve formátu JPEG nebo PNG');
		$form->addSubmit("pridat_klub");
		$form->onSuccess[] = array($this, 'addClubSucceeded');

		return $form;
	}

	public function addClubSucceeded(UI\Form $form) {
		$values = $form->getValues();
		$logo   = $values->logo;
		$result = false;
		if($logo != "") {
			$img     = Nette\Utils\Image::fromFile($logo);
			$imgPath = $this->context->parameters['wwwDir'] . '/images/loga_klubu/' . $logo->getName();
			$logo->move($imgPath);
			$img = Nette\Utils\Image::fromFile($imgPath);
			$img->resize(16, 16, Nette\Utils\Image::STRETCH);
			$img->save($imgPath);
			$result = $this->clubModel->addClub($values->nazev_klubu, $values->stadion, $values->rok_zalozeni, $values->webova_stranka, $values->informace, $logo->getName());
		} else {
			$result = $this->clubModel->addClub($values->nazev_klubu, $values->stadion, $values->rok_zalozeni, $values->webova_stranka, $values->informace, "");
		}
		if($result) {
			$this->flashMessage('Klub byl úspěšně přidán', 'alert-success');
			$this->redirect('Klub:');
		}

	}

	protected function createComponentAddClubToLeague() {
		$form  = new UI\Form();
		$array = $this->leagueModel->getLeagues();
		$form->addSelect('league', 'Liga', $array)
			->setPrompt('Zvolte ligu')
			->setAttribute('class', 'form-control');
		$form->addSubmit('addToLeague', 'Přidat tým do ligy')
			->setAttribute('class', 'btn btn-sm btn-success add-to-league');
		$form->onSuccess[] = array($this, 'addClubToLeagueSucceeded');
		return $form;

	}
	
	public function addClubToLeagueSucceeded(UI\Form $form){
		$values = $form->getValues();
		$this->clubModel->addClubToLeague($this->klubId, $values->league);
		$this->flashMessage('Klub byl úspěšně přidán do ligy', 'alert-success');
		$this->redirect('this');
	}
	
	public function actionDetail($id) {
		if(is_numeric($id)) {
			$this->template->club = $this->clubModel->getClubById($id);
			$this->klubId = $id;
		} else {
			throw new Nette\Application\ForbiddenRequestException();
		}
	}

	public function actionLigaClub($id) {
		if(is_numeric($id)) {
			$this->template->club   = $this->clubModel->getClubById($id);
			$this->template->league = $this->clubModel->getLeaguesOfClub($id);
			$this->klubId = $id;

		} else {
			throw new Nette\Application\ForbiddenRequestException();
		}
	}

}