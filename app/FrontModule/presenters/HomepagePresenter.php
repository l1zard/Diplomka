<?php

namespace App\FrontModule\Presenters;

use Nette;
use App\Model;
use App\Model\MatchModel;

class HomepagePresenter extends BasePresenter
{
	
	/** @var MatchModel @inject */
    public $matchModel;
	public function renderDefault()
	{
		$this->template->anyVariable = 'any value';
		$this->template->matches = $this->matchModel->getIncommingMatchs();
		$this->template->tiket  = $this->session->getSection('tiket');
		
	}
	public function handleAddToTicket($idMatch, $idOppurtiny){
		$sekce   = $this->session->getSection('tiket');
		$matches = $this->matchModel->getIncommingMatchs();
		foreach($matches as $match){
			if($match->id_zapas == $idMatch){
				$zapas = $match;
			}
		}
		foreach($zapas->prilezitosti as $oppurtunity){
			if($oppurtunity->id_prilezitost == $idOppurtiny){
				$prilezitost = $oppurtunity;
			}
		}
		$sekce->polozka[$idMatch] = array(
			"zapas" => $zapas->klubdomaci . '-' . $zapas->klubhoste, 
			"kurz"  => $prilezitost['kurz'],
			"prilezitost"  => $this->matchModel->getTypPrilezitostString($prilezitost->id_typ_prilezitosti),
			"id"    => $idOppurtiny,
			"idMatch"    => $idMatch
		);
		$this->redrawControl('ticketSnippet');
	}
	
	public function handleDeleteMatchFromTicket($idMatch){
		$sekce   = $this->session->getSection('tiket');
		unset($sekce->polozka[$idMatch]);
		$this->redrawControl('ticketSnippet');
	}
}
