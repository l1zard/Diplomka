<?php

namespace App\FrontModule\Presenters;

use Nette;
use App\Model;
use App\Model\MatchModel;
use Nette\Application\UI;

class HomepagePresenter extends BasePresenter {

	/** @var MatchModel @inject */
	public $matchModel;

	public function renderDefault() {
		$this->template->anyVariable = 'any value';
		$this->template->matches     = $this->matchModel->getIncommingMatchs();
		$this->template->tiket       = $this->session->getSection('tiket');
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
		}

		$this->redrawControl('ticketSnippet');
	}

	protected function createComponentBetTicket() {
		$form = new UI\Form();
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

	}
}
