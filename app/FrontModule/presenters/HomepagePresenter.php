<?php

namespace App\FrontModule\Presenters;

use Nette;
use App\Model;
use App\Model\MatchModel;
use App\Model\TicketModel;
use App\Model\LeagueModel;
use Nette\Application\UI;

class HomepagePresenter extends BasePresenter {

	/** @var MatchModel @inject */
	public $matchModel;

	/** @var TicketModel @inject */
	public $ticketModel;

	/** @var LeagueModel @inject */
	public $leagueModel;
	
	
	public function startup(){
		parent::startup();
		$this->template->matches     = $this->leagueModel->getListedMatchesByLeague(1);
	}
	public function renderDefault() {
		$this->template->anyVariable = 'any value';
		$this->template->tiket       = $this->session->getSection('tiket');
		$this->template->leagues     = $this->leagueModel->getAllLeagues();
	}

	public function handleAddToTicket($idMatch, $idOppurtiny) {
		$sekce   = $this->session->getSection('tiket');
		$matches = $this->matchModel->getIncommingMatchs();
		foreach($matches as $match) {
			if($match->id_zapas == $idMatch) {
				$zapas = $match;
			}
		}
		foreach($zapas->prilezitosti as $oppurtunity) {
			if($oppurtunity->id_prilezitost == $idOppurtiny) {
				$prilezitost = $oppurtunity;
			}
		}

		$sekce->polozka[$idMatch] = array(
			"zapas"       => $zapas->klubdomaci . '-' . $zapas->klubhoste,
			"kurz"        => $prilezitost['kurz'],
			"prilezitost" => $this->matchModel->getTypPrilezitostString($prilezitost->id_typ_prilezitosti),
			"id"          => $idOppurtiny,
			"idMatch"     => $idMatch
		);

		$sekce->celkovyKurz = 1;
		$sekce->pocetZapasu = count($sekce->polozka);
		foreach($sekce->polozka as $item) {
			$sekce->celkovyKurz = $sekce->celkovyKurz * $item['kurz'];

		}

		$this->redrawControl('ticketSnippet');
	}

	public function handleDeleteMatchFromTicket($idMatch) {
		$sekce = $this->session->getSection('tiket');
		if(count($sekce->polozka) == 1) {
			unset($sekce->polozka[$idMatch]);
			$sekce->setExpiration(-1);
			$this->session->close();
			$this->session->start();
		} else {
			unset($sekce->polozka[$idMatch]);
			$sekce->pocetZapasu = $sekce->pocetZapasu - 1;
		}

		$this->redrawControl('ticketSnippet');
	}

	public function handleDeleteTicket() {
		$sekce = $this->session->getSection('tiket');
		$sekce->setExpiration(-1);
		$this->session->close();
		$this->session->start();
		$this->redrawControl('ticketSnippet');
	}

	protected function createComponentBetTicket() {
		$form = new UI\Form();
		$form->getElementPrototype()->class('ajax');
		$form->addText("vklad")
			->setAttribute("class", "textinput betbutton")
			->addRule(UI\Form::INTEGER, 'Hodnota musí být celočíselná!');
		$form->addSubmit("send", 'Vsadit')
			->setAttribute('class', 'button-blue accept');
		$form->onSuccess[] = array($this, 'betTicketSucceed');

		return $form;
	}

	public function betTicketSucceed(UI\Form $form) {
		$values = $form->getValues();
		if(!$this->user->isLoggedIn()) {
			$this->template->betStatus = "Uživatel není přihlášen! Pro vsazení tiketu se přihlašte";
		} else {
			$money = $this->userModel->getUserMoney($this->user->getId());
			if($money->zustatek >= $values->vklad) {
				$sekce = $this->session->getSection('tiket');
				$this->ticketModel->betTicket($this->user->getId(), $values->vklad, $sekce->polozka);
				$this->userModel->minusUserMoney($this->user->getId(), $values->vklad);
				$sekce->setExpiration(-1);
				$this->session->close();
				$this->session->start();
			} else {
				$this->template->betStatus = "Nedostatečný zůstatek! Pro vsazení tiketu prosím vložte další finanční prostředky";
			}

		}

		$this->redrawControl('ticketSnippet');

	}

	public function handleChangeLeague($idLeague) {
		$this->template->matches     = $this->leagueModel->getListedMatchesByLeague($idLeague);
		$this->redrawControl('leagueMatches');
	}
}
