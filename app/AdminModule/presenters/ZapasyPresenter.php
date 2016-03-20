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

class ZapasyPresenter extends BasePresenter {

	/** @var LeagueModel @inject */
	public $leagueModel;

	/** @var MatchModel @inject */
	public $matchModel;

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
	
	protected function createComponentEditMatch(){
		
	}
	public function actionDetail($id){
		$this->template->match = $this->matchModel->getMatchByID($id);
		if(!$this->matchModel->getMatchStatus($id)){
			$this->flashMessage('Zápas nebyl ukončen! Výsledek není vyplňen', 'alert-danger');	
			$this->template->isFinish = false;
		}
		else{
			$this->flashMessage('Zápas již byl ukončen!', 'alert-success');	
			$this->template->isFinish = true;
		}
	}

}