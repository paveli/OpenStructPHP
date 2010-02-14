<?php
/**
 * Подключаем необходимые файлы
 */
require_once CORE_PATH .'Open/Model'. EXT;
require_once CORE_PATH .'Open/Session'. EXT;

/**
 * Модель captcha
 */
class CaptchaModel extends Open_Model
{
	/************
	 * Свойства *
	 ************/

	/**
	 * Ключ к данным в сессии
	 *
	 * @var string
	 */
	private $sessionKey = FALSE;

	/**
	 * Объект для работы с сессией
	 *
	 * @var object
	 */
	private $session = FALSE;

	/**********
	 * Методы *
	 **********/

	/**
	 * Получить ключ к данным в сессии
	 *
	 * @return string
	 */
	private function sessionKey()
	{
		if( $this->sessionKey === FALSE )
		{
			$this->sessionKey = $this->config->get('captcha', 'sessionKey');
		}

		return $this->sessionKey;
	}

	/**
	 * Получить объект для работы с сессией
	 *
	 * @return object
	 */
	private function session()
	{
		if( $this->session === FALSE )
		{
			$this->session = Open_Session::getInstance();
		}

		return $this->session;
	}

	/**
	 * Получить текущее сохранённое значение captcha в сессии
	 * Если значения нет, оно будет сгенерировано и положено в сессию
	 * Либо сгенерировано заново и сохранено, если $doRegenerate истинно
	 *
	 * @param bool $doRegenerate
	 * @return string
	 */
	public function get($doRegenerate)
	{
		$S = $this->session();
		$key = $this->sessionKey();

		if( $doRegenerate || ($string = $S->get($key)) === FALSE )
		{
			$S->set($key, $string = $this->generate());
		}

		return $string;
	}

	/**
	 * Положить значение captcha в сессию
	 *
	 * @param string $value
	 */
	private function set($value)
	{
		$this->session()->set($this->sessionKey(), $value);
	}

	/**
	 * Удалить текущее значение captcha из сессии
	 */
	public function reset()
	{
		$this->session()->delete($this->sessionKey());
	}

	/**
	 * Сгенерировать строку captcha
	 *
	 * @return string
	 */
	private function generate()
	{
		$length = $this->config->get('captcha', 'length');
		$characters = $this->config->get('captcha', 'characters');
		$charactersLength = mb_strlen($characters);

		$string = '';
		for($i=0; $i<$length; $i++)
		{	$string .= mb_substr($characters, mt_rand(0, $charactersLength-1), 1);
		}

		return $string;
	}

	/**
	 * Проверить, совпадает ли значение captcha в сессии со значением $value
	 *
	 * @param string $value
	 * @return bool
	 */
	public function verify($value)
	{
		return ($this->session()->get($this->sessionKey()) === $value);
	}
}