<?php
/**
 * Подключаем необходимые файлы
 */
require_once CORE_PATH .'Open/Controller'. EXT;

/**
 * Контроллер по умолчанию
 * Главная страница
 */
class Home extends Open_Controller
{
	/************
	 * Свойства *
	 ************/

	/**********
	 * Методы *
	 **********/

	/**
	 * Главная страница
	 */
	public function index()
	{
		$this->view->show('Home/index');
	}
}