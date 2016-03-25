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
	private $opportunityOfMatch;
	private $match;

	public function renderDefault() {
		$this->template->_form = $this['ligaZapasy'];
	}
	// HANDLES
	
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

	
	public function handleShowMatch(){
		if($this->matchModel->setMatchShow($this->idMatch)){
			$this->template->showMatchInfoClass = "success";
			$this->template->showMatchInfo = "Zápas byl zobrazen na webu, uživatelé na něj mohou již vsázet";
		}
		else{
			$this->template->showMatchInfoClass = "danger";
			$this->template->showMatchInfo = "Zápas nelze zobrazit! Nejdřívě vyplňte základní kurzy k zápasu";
		}
		$this->redrawControl('showMatchInfoSnippet');
	}
	
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
	

	// FORMS
	protected function createComponentAddKurzDomaci() {
		$form = new UI\Form();

		$form->getElementPrototype()->class('ajax');
		$form->addText('domaci');
		$form->addSubmit('send', 'Uložit')
			->setAttribute('class', 'btn btn-sm btn-success');
		$form->onSuccess[] = $this->addKurzDomaciSucceeded;
		$pom               = $this->matchModel->hasTypeOfKurzInOpportunity($this->opportunityOfMatch, 'domaci');
		if($pom['found']) {
			$form['domaci']->setAttribute('class', 'form-control text-center width-50 done');
			$form->setDefaults(array('domaci' => $pom['kurz']));

		} else {
			$form['domaci']->setAttribute('class', 'form-control text-center width-50 error');
		}

		return $form;
	}

	public function addKurzDomaciSucceeded(UI\Form $form) {
		$values = $form->getValues();
		if($this->match['datum_zapasu'] > new Nette\Utils\DateTime()) {
			if(Validators::isNumeric($values->domaci)) {
				if($values->domaci > 1) {
					$this->matchModel->addOpportunity($this->idMatch, 2, $values->domaci);
					$form['domaci']->getControlPrototype()->class('form-control text-center width-50 done');
					$this->template->editKurz = array(
						"text"  => "Kurz byl editován!",
						"class" => "alert alert-success"
					);
				} else {
					$this->template->editKurz = array(
						"text"  => "Kurz musí být větší než 1!",
						"class" => "alert alert-danger"
					);
				}
			} else {
				$this->template->editKurz = array(
					"text"  => "Kurz musí být číselného typu!",
					"class" => "alert alert-danger"
				);
			}
		} else {
			$this->template->editKurz = array(
				"text"  => "Nelze editovat zápas který již začal!",
				"class" => "alert alert-danger"
			);
		}
		if(!$this->isAjax()) {
			$this->redirect('this');
		} else {
			$this->redrawControl('kurzEdit');
			$this->redrawControl('domaciSnippet');

		}

	}

	protected function createComponentAddKurzRemiza() {
		$form = new UI\Form();
		$form->getElementPrototype()->class('ajax');
		$form->addText('remiza');
		$form->addSubmit('send', 'Uložit')
			->setAttribute('class', 'btn btn-sm btn-success');
		$form->onSuccess[] = $this->addKurzRemizaSucceeded;
		$pom               = $this->matchModel->hasTypeOfKurzInOpportunity($this->opportunityOfMatch, 'remiza');
		if($pom['found']) {
			$form['remiza']->setAttribute('class', 'form-control text-center width-50 done');
			$form->setDefaults(array('remiza' => $pom['kurz']));

		} else {
			$form['remiza']->setAttribute('class', 'form-control text-center width-50 error');
		}

		return $form;
	}

	public function addKurzRemizaSucceeded(UI\Form $form) {
		$values = $form->getValues();
		if($this->match['datum_zapasu'] > new Nette\Utils\DateTime()) {
			if(Validators::isNumeric($values->remiza)) {
				if($values->remiza > 1) {
					$this->matchModel->addOpportunity($this->idMatch, 1, $values->remiza);
					$form['remiza']->getControlPrototype()->class('form-control text-center width-50 done');
					$this->template->editKurz = array(
						"text"  => "Kurz byl editován!",
						"class" => "alert alert-success"
					);
				} else {
					$this->template->editKurz = array(
						"text"  => "Kurz musí být větší než 1!",
						"class" => "alert alert-danger"
					);
				}
			} else {
				$this->template->editKurz = array(
					"text"  => "Kurz musí být číselného typu!",
					"class" => "alert alert-danger"
				);
			}
		} else {
			$this->template->editKurz = array(
				"text"  => "Nelze editovat zápas který již začal!",
				"class" => "alert alert-danger"
			);
		}
		if(!$this->isAjax()) {
			$this->redirect('this');
		} else {
			$this->redrawControl('remizaSnippet');
			$this->redrawControl('kurzEdit');
		}

	}

	protected function createComponentAddKurzHoste() {
		$form = new UI\Form();
		$form->getElementPrototype()->class('ajax');
		$form->addText('hoste');
		$form->addSubmit('send', 'Uložit')
			->setAttribute('class', 'btn btn-sm btn-success');
		$form->onSuccess[] = $this->addKurzHosteSucceeded;
		$pom               = $this->matchModel->hasTypeOfKurzInOpportunity($this->opportunityOfMatch, 'hoste');
		if($pom['found']) {
			$form['hoste']->setAttribute('class', 'form-control text-center width-50 done');
			$form->setDefaults(array('hoste' => $pom['kurz']));

		} else {
			$form['hoste']->setAttribute('class', 'form-control text-center width-50 error');
		}

		return $form;
	}

	public function addKurzHosteSucceeded(UI\Form $form) {
		$values = $form->getValues();
		if($this->match['datum_zapasu'] > new Nette\Utils\DateTime()) {
			if(Validators::isNumeric($values->hoste)) {
				if($values->hoste > 1) {
					$this->matchModel->addOpportunity($this->idMatch, 3, $values->hoste);
					$form['hoste']->getControlPrototype()->class('form-control text-center width-50 done');
					$this->template->editKurz = array(
						"text"  => "Kurz byl editován!",
						"class" => "alert alert-success"
					);
				} else {
					$this->template->editKurz = array(
						"text"  => "Kurz musí být větší než 1!",
						"class" => "alert alert-danger"
					);
				}
			} else {
				$this->template->editKurz = array(
					"text"  => "Kurz musí být číselného typu!",
					"class" => "alert alert-danger"
				);
			}
		} else {
			$this->template->editKurz = array(
				"text"  => "Nelze editovat zápas který již začal!",
				"class" => "alert alert-danger"
			);
		}
		if(!$this->isAjax()) {
			$this->redirect('this');
		} else {
			$this->redrawControl('hosteSnippet');
			$this->redrawControl('kurzEdit');
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
		$this->template->match    = $this->matchModel->getMatchByID($id);
		$this->match              = $this->template->match;
		$this->template->_form    = $this['addResult'];
		$this->idMatch            = $id;
		$this->opportunityOfMatch = $this->matchModel->getAllOpportunityOfMatch($this->idMatch);
		$this->template->showMatchModalText = "Opravdu chcete zobrazit zápas na webu? Tato akce již nepude vrátit zpět";
		
		if(!$this->matchModel->getMatchStatus($id)) {
			$this->flashMessage('Zápas nebyl ukončen! Výsledek není vyplňen', 'alert-danger');
			$this->template->isFinish = false;
		} else {
			$this->flashMessage('Zápas již byl ukončen!', 'alert-success');
			$this->template->isFinish = true;
		}
	}

}