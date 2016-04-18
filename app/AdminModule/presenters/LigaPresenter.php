<?php
/**
 * Created by PhpStorm.
 * User: Michal
 * Date: 18.4.2016
 * Time: 10:28
 */

namespace App\AdminModule\Presenters;
use Nette;
use Nette\Application\UI;
use App\Model\LeagueModel;

class LigaPresenter extends BasePresenter {
	
	
	/** @var LeagueModel @inject */
    public $leagueModel;
	
	public function renderDefault()
    {
        $this->template->leagues = $this->leagueModel->getAllLeagues();
    }
	
	 protected function createComponentAddLeague()
    {
        $form = new UI\Form();
		$form->addText("nazev_ligy")
			->setAttribute("class", "form-control");
		$form->addUpload("logo")
			->setAttribute("id", "imageInput")
			->addCondition(UI\Form::FILLED)
			->addRule(Nette\Forms\Form::IMAGE, 'Fotka musí být ve formátu JPEG nebo PNG');
	    $form->addSelect('narodnost', "Vyberte národnost")
            ->setPrompt($this->leagueModel->getNationality())
            ->setAttribute('class', 'form-control');
		$form->addSubmit("pridat_ligu");
		$form->onSuccess[] = array($this, 'addLeagueSucceeded');

		return $form;
    }
	
	public function addLeagueSucceeded(UI\Form $form){
		$values = $form->getHttpData();
		$logo   = $values['logo'];
		$result = false;
		if($logo != "") {
			$img     = Nette\Utils\Image::fromFile($logo);
			$imgPath = $this->context->parameters['wwwDir'] . '/images/loga_lig/' . $logo->getName();
			$logo->move($imgPath);
			$img = Nette\Utils\Image::fromFile($imgPath);
			$img->resize(32, 32, Nette\Utils\Image::STRETCH);
			$img->save($imgPath);
			$result = $this->leagueModel->addLeague($values['nazev_ligy'], $values['narodnost'], $logo->getName());
		} else {
			$result = $this->leagueModel->addLeague($values['nazev_ligy'], $values['narodnost'], "");
		}
		if($result) {
			$this->flashMessage('Liga byla úspěšně přidán', 'alert-success');
			$this->redirect('Liga:');
		}
		
	}
}