<?php
/**
 * Created by PhpStorm.
 * User: Lizardor
 * Date: 5. 4. 2016
 * Time: 16:23
 */

namespace App\FrontModule\Presenters;
use Nette;
use App\Model;
use Nette\Application\UI;
use App\Model\TicketModel;

class TiketPresenter extends BasePresenter {


	/** @var TicketModel @inject */
	public $ticketModel;

	public function renderDefault($id){
		//$this->template->zapasy = $this->ticketModel->getTicketMatches($id);
	}

	public function actionId($id){
		$this->template->zapasy = $this->ticketModel->getTicketMatches($id);
		$this->template->username = $this->ticketModel->getUserByTicketID($id);
		$this->template->tiket = $this->ticketModel->getTicketMoneyAndKurz($id);
	}

}