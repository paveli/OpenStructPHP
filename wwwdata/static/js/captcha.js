/**
 * Класс для работы с captcha
 */
Captcha =
{
	/**
	 * Можно перерисовать? Изменяется после перерисовки
	 */
	enabled: true,

	/**
	 * Предыдущий цвет
	 */
	color: false,

	/**
	 * Перерисовать captcha
	 * Зависит от разметки
	 *
	 * @param element Элемент вызвавший метод
	 * @param delay Время в милисекундах на которое отменить возможность перерисовки
	 */
	Redraw: function(element, delay)
	{
		if(this.enabled)
		{
			element.previousSibling.src = '/captcha/draw/true/#' + (new Date()).getTime();

			this.color = element.firstChild.style.color;
			element.firstChild.style.color = 'LightGrey';

			this.enabled = false;
			setTimeout(
				function()
				{
					element.firstChild.style.color = Captcha.color;
					Captcha.enabled = true;
				},
				delay
			);
		}
	}
}