<?php

namespace App;

use Nette;
use Nette\Application\Routers\RouteList;
use Nette\Application\Routers\Route;


class RouterFactory
{

	/**
	 * @return Nette\Application\IRouter
	 */
	public static function createRouter()
	{
		$router = new RouteList;
        $router[] = $admin = new RouteList('Admin');
        $admin[] = new Route('admin/<presenter>/<action>[/<id>]', 'Homepage:default');
        $router[] = $front = new RouteList('Front');
        $front[] = new Route('<presenter>/<action>[/<id>]', 'Homepage:default');
        return $router;
	}



}
