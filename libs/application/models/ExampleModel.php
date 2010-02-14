<?php
/**
 * Подключаем необходимые файлы
 */
require_once CORE_PATH .'Open/Model'. EXT;

if(!defined('DB_TABLES_EXAMPLE')) define('DB_TABLES_EXAMPLE', 'example');

/**
 * Модель пример
 */
class ExampleModel extends Open_Model
{
	public function get($page, $span)
	{
		/**
		 * Создаётся запрос и передаётся функции q(), которая возвращает удобный объект для работы с полученными данными
		 */
		$q = "SELECT SQL_CALC_FOUND_ROWS *, UNIX_TIMESTAMP(date) AS `date` FROM `". DB_TABLES_EXAMPLE ."` WHERE (1) LIMIT ". (($page-1)*$span) .','. $span;
		$result = $this->db->result($q);

		return $result->get();
	}

	public function getFoundRows()
	{
		/**
		 * Получить количество рядов, которое могло бы быть возвращено без конструкции LIMIT в запросе
		 */
		return $this->db->foundRows();
	}

	public function set()
	{
		/**
		 * Не забываем пользоваться функцией escape, чтобы обезопасить данные
		 */
		$q = "INSERT INTO `". DB_TABLES_EXAMPLE ."` (`date`, `name`) VALUES (NOW(), {$this->db->escape('всем привет')})";
		$this->db->q($q);
		return $this->db->insertID();
	}
}