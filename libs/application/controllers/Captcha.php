<?php
/**
 * Подключаем необходимые файлы
 */
require_once CORE_PATH .'Open/Controller'. EXT;
require_once CORE_PATH .'Open/Convert'. EXT;
require_once CORE_PATH .'Open/Session'. EXT;
require_once CORE_PATH .'Open/Text'. EXT;

/**
 * Класс для работы с captcha
 *
 */
class Captcha extends Open_Controller
{
	/**
	 * Время жизни кеша страницы с каптчей
	 */
	const LIFETIME = 86400;

	/**
	 * Метод по умолчанию
	 * Подключает js-файл для работы с капчей, вызывает шаблон
	 */
	public function index()
	{
		$C = $this->config;
		$V = $this->view;

		/**
		 * Js-скрипт для перерисовки
		 */
		$V->addJs($C->get('captcha', 'js'));

		$V->setBody('Captcha/index');
		$V->show('', 'Captcha/index'. TPLEXT, self::LIFETIME);
	}

	/**
	 * Рисует картинку и возвращает её в виде image/png
	 *
	 */
	public function draw($isRedraw=false)
	{
		$C = $this->config;
		$I = $this->input;
		$V = $this->view;
		$S = Open_Session::getInstance();
		$M = getModel('CaptchaModel');

		/**
		 * Получаем параметры из конфига
		 */
		$config = $C->get('captcha', array('angle', 'background', 'color', 'distraction', 'font', 'height', 'length', 'size', 'sessionKey', 'width'));
		$angle = &$config['angle'];
		$background = &$config['background'];
		$color = &$config['color'];
		$distraction = &$config['distraction'];
		$font = FONTS_PATH . $config['font'];
		$height = &$config['height'];
		$length = &$config['length'];
		$size = &$config['size'];
		$sessionKey = &$config['sessionKey'];
		$width = &$config['width'];

		/**
		 * Получить строку для прорисовки
		 */
		$string = $M->get($isRedraw);

		/**
		 * Создание картинки
		 */
		if( !($img = imagecreatetruecolor($width, $height)) )
		{	trigger_error(Open_Text::getInstance()->dget('errors', 'New image cannot be initialized for captcha'), E_USER_ERROR);
		}

		/**
		 * Формирование цвета фона
		 */
		$background = $this->getColor($img, $background);
		imagefill($img, 0, 0, $background);

		/**
		 * Создание надписи на картинке
		 */
		for($i=0; $i<$length; $i++)
		{
			$currentSize = $size + mt_rand(-(int)($size/6), (int)($size/6));
			$x = (($width-20)/$length)*$i + mt_rand(11, 13);
			$y = ($height + $currentSize)/2 + mt_rand(-4, 4);

			$char = mb_substr($string, $i, 1);

			/**
			 * Отвлекающий символ
			 */
			imagettftext($img, $currentSize+(int)($size/8), mt_rand(-$angle, $angle), $x+mt_rand(-4, 4), $y+mt_rand(-4, 4), $this->getColor($img, $distraction), $font, $char);

			/**
			 * Основной цвет
			 */
			imagettftext($img, $currentSize, mt_rand(-$angle, $angle), $x, $y, $this->getColor($img, $color), $font, $char);

			/**
			 * Повторение надписи цветом фона для создания пересечений
			 */
			imagettftext($img, $currentSize+(int)($size/12), mt_rand(-$angle, $angle), $x+mt_rand(-2, 2), $y+mt_rand(-2, 2), $background, $font, $char);
		}

		/**
		 * Установка header-заголовков
		 * Отменяем кеширование картинки и задаём тип содержимого
		 */
		$V->setHeaders(array(
			"Expires: Mon, 26 Jul 1997 05:00:00 GMT",
			"Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT",
			"Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0, max-age=0",
			"Pragma: no-cache",
			"Content-type: image/png",
		));
		$V->sendHeaders();

		/**
		 * Выдача картинки
		 */
		imagepng($img);
		imagedestroy($img);
	}

	/**
	 * Получить цвет заданый по образцу в конфиге
	 *
	 * @param resource $img
	 * @param array $color
	 * @return int
	 */
	private function getColor(&$img, $color)
	{
		if( is_array($color) )
		{
			if( count($color) == 2 )
			{
				$temp = mt_rand($color[0], $color[1]);
				$color = imagecolorallocate($img, $temp, $temp, $temp);
			}
			else
			{
				$color = imagecolorallocate($img, mt_rand($color[0][0], $color[0][1]), mt_rand($color[1][0], $color[1][1]), mt_rand($color[2][0], $color[2][1]));
			}
		}
		else
		{
			$color = Open_Convert::getInstance()->fromColor($color);
			$color = imagecolorallocate($img, $color[0], $color[1], $color[2]);
		}

		return $color;
	}
}