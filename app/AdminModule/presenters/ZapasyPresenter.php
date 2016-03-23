<?php
/**
 * Created by PhpStorm.
 * User: Lizardor
 * Date: 9. 3. 2016
 * Time: 23:44
 */

namespace App\AdminModule\Presenters;

use App\Model\LeagueModel;
use App\Model\MatchModel;
use Nette;
use Nette\Application\UI;
use Nette\Utils\Validators;

class ZapasyPresenter extends BasePresenter {

	/** @var LeagueModel @inject */
	public $leagueModel;

	/** @var MatchModel @inject */
	public $matchModel;
	private $idMatch;
	
	public function renderDefault() {
		$this->template->_form = $this['ligaZapasy'];
	}

	public function handleLigaChange($value) {
		if($value) {
			$array = $this->leagueModel->getKlubByLeagueIdArray($value);

			$this['ligaZapasy']['klub']->setPrompt('Vyberte klub')
				->setItems($array);

		} else {
			$this['ligaZapasy']['klub']->setPrompt('Zvolte nejdříve ligu')
				->setItems(array());
		}

		$this->redrawControl('secondSnippet');
	}
	// HANDLES
	
	public function handleAjaxResultChange($domaci, $hoste, $id) {
		if(Validators::isNumericInt($domaci) && Validators::isNumericInt($hoste)) {
			$this->matchModel->addResultByMatchID($id, $domaci, $hoste);
			$this->template->match    = $this->matchModel->getMatchByID($id);
			$this->template->isFinish = true;
			$this->getFlashSession()->remove();
			$this->flashMessage('Zápas již byl ukončen!', 'alert-success');
			$this->redrawControl('finishArea');
			$this->redrawControl('flashMessages');
		} else {
			$this->template->error = "Zadejte výsledek v číselném formátu!";
		}

	}
	
	
	public function handleAjaxDomaciKurz($kurz, $id){
		if(Validators::isNumeric($kurz)){
			$this->matchModel->addOpportunity($id, 2, $kurz);
			$this['addKurzDomaci']['domaci']->setValue($kurz);
			$this['addKurzDomaci']['domaci']->getControlPrototype()->class('form-control text-center width-50 done');
			$this->template->isDomaciKurz = true;
		}
		else{
			$this->template->error = "Zadejte kurz v číselném formátu!";
		}
		$this->redrawControl('domaciSnippet');
	}
	
	
	
	
	
	// FORMS
	protected function createComponentAddKurzDomaci(){
		$form = new UI\Form();
		$form->addText('domaci')
			->setAttribute('class', 'form-control text-center width-50 error');
		$form->addSubmit('send');
		$form->onSuccess[] = $this->addKurzDomaciSucceeded;

		return $form;
	}
	
	public function addKurzDomaciSucceeded(UI\Form $form) {
		

	}
	
	protected function createComponentAddKurzRemiza(){
		$form = new UI\Form();
		$form->getElementPrototype()->class('ajax');
		$form->addText('remiza')
			->setAttribute('class', 'form-control text-center width-50 error');
		$form->addSubmit('send');
		$form->onSuccess[] = $this->addKurzRemizaSucceeded;
		return $form;
	}
	
	public function addKurzRemizaSucceeded(UI\Form $form) {
		
		$values = $form->getValues();
		if(Validators::isNumeric($values->remiza)){
			$this->matchModel->addOpportunity($this->idMatch, 1, $values->remiza);
			$form['remiza']->getControlPrototype()->class('form-control text-center width-50 done');
		}
		else{
			
		}
		if(!$this->isAjax()){
			$this->redirect('this');
		}
		else{
			$this->redrawControl('remizaSnippet');
		}
	
	}
	
	protected function createComponentLigaZapasy() {
		$array = $this->leagueModel->getLeagues();
		$form  = new UI\Form();
		$form->addSelect('liga', 'Vyber ligu', $array)
			->setPrompt("Vyber ligu")
			->setAttribute('class', 'form-control');
		$form->addSelect('klub', "Vyber klub")
			->setPrompt('Zvolte nejdříve ligu')
			->setAttribute('class', 'form-control');
		$form->addSubmit('send', 'Vyhledat')
			->setAttribute('class', 'btn btn-sm btn-success');
		$form->onSuccess[] = $this->ligaZapasySucceeded;

		return $form;
	}

	public function ligaZapasySucceeded(UI\Form $form) {
		$values = $form->getHttpData();
		$form->setDefaults($values);
		$this->template->matches = $this->matchModel->getMatches($values['klub']);

	}

	protected function createComponentAddResult() {
		$form = new UI\Form();
		$form->addText('domaci')
			->setAttribute('class', 'form-control bfh-number text-center');
		$form->addText('hoste')
			->setAttribute('class', 'form-control bfh-number text-center');
		$form->addSubmit('send')
			->setAttribute('id', 'frm-addResult-send');
		$form->onSuccess[] = $this->addResultSucceeded;

		return $form;

	}

	public function addResultSucceeded(UI\Form $form) {
		$values = $form->getValues();
		if($this->isAjax()) {
			$this->redrawControl('aaa');
		} else {
			$this->redirect('this');
		}
	}
	
	
	
	
	
	
	

	public function actionDetail($id) {
		$this->template->match = $this->matchModel->getMatchByID($id);
		$this->template->_form = $this['addResult'];
		$this->idMatch = $id;
		if(!$this->matchModel->getMatchStatus($id)) {
			$this->flashMessage('Zápas nebyl ukončen! Výsledek není vyplňen', 'alert-danger');
			$this->template->isFinish = false;
		} else {
			$this->flashMessage('Zápas již byl ukončen!', 'alert-success');
			$this->template->isFinish = true;
		}
	}

}