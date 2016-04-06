<?php
/**
 * Created by PhpStorm.
 * User: Lizardor
 * Date: 5. 4. 2016
 * Time: 16:23
 */

namespace App\FrontModule\Presenters;

class TiketPresenter extends BasePresenter {
	
	public function renderDefault($id){
		$this->template->id = $id;
	}

}