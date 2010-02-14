<?php
/**
 * Подключаем необходимые файлы
 */
require_once CORE_PATH .'Open/Auth'. EXT;
require_once CORE_PATH .'Open/Acl'. EXT;
require_once CORE_PATH .'Open/Cache'. EXT;
require_once CORE_PATH .'Open/Color'. EXT;
require_once CORE_PATH .'Open/Controller'. EXT;
require_once CORE_PATH .'Open/Session'. EXT;
require_once CORE_PATH .'Open/Validator'. EXT;

/**
 * Контроллер пример
 */
class Example extends Open_Controller
{
	const INDEX_DEFAULT_PAGE = 1;
	const INDEX_DEFAULT_SPAN = 3;

	/**
	 * Время жизни кеша страницы демонстрации цветов
	 */
	const COLORS_LIFETIME = 86400;

	public function index($page=self::INDEX_DEFAULT_PAGE, $span=self::INDEX_DEFAULT_SPAN)
	{
		/*---------------- Open_Benchmark ----------------*/

		/**
		 * Ссылка на объект Open_Benchmark не находится ни в одном из свойств контроллера
		 * Поэтому надо получать её самостоятельно в случае необходимости
		 * Можно использовать флаг дебага, тогда при релизе информация о времени работы нигде фигурировать не будет
		 */
		if(DEBUG) $B = Open_Benchmark::getInstance();

		/**
		 * Сохраняем метку начала выполнения этого метода
		 * В конце выполнения метода сохраним метку окончания и выведем
		 */
		if(DEBUG) $B->mark('example_index_start');

		/**
		 * Существует метод timetest класса Open_Benchmark для испытания производительности
		 * Первый аргумент - массив испытуемых функций с аргументами или без
		 * Функция представляет собой либо строку с названием, либо массив с именем класса и именем метода, например:
		 * 'function' или array('class', 'method')
		 * За функцией может следовать массив аргументов, которые ей необходимо передать при вызове, но его можно не указывать, если функция может быть вызвана без аргументов
		 * Пример первого аргумента:
		 * array(
		 * 		'function1',
		 * 		array('arg1', 'arg2'),
		 * 		'function2',
		 * 		'function3',
		 * 		array(__CLASS__, 'method'),
		 * 		array('arg1', 'arg2', 'arg3'),
		 * )
		 * Второй аргумент - количество проводимых тестов
		 * Третий аргумент - количество итераций вызова функции в одном тесте
		 * Метод возвращает массив с результатами, а также выводит результат на экран в виде таблицы
		 * Вывод на экран можно запретить передав четвёртым аргументом ложное значение
		 *
		 * Результат выводится в виде таблицы следующим образом:
		 * <div class="timetest">
		 * 		<table>...</table>
		 * </div>
		 * Соответственно вы можете изменить внешний вид таблицы
		 */

		/**
		 * Две функции делающие по своей сути одно и то же, но разными способами
		 * Разделение строки на массив строк через слеши с исключением пустых строк из результата
		 * Интересно, какая из них будет работать быстрее?
		 */
		$function1 = create_function('$str=FALSE', '
			return preg_split(\'#/#\', $str, 0, PREG_SPLIT_NO_EMPTY);
		');

		$function2 = create_function('$str=FALSE', '
			$temp = explode(\'/\', $str);
			foreach($temp as $key => &$value)
			{	if(empty($value))
				{	unset($temp[$key]);
				}
			}
			return $temp;
		');

		$str = $this->input->path();

		if(DEBUG) $B->timetest(
			array(
				$function1,
				array($str),
				$function2,
				array($str),
			),
			4,
			64,
			TRUE // Выводить результат на экран? Необязательный аргумент
		);

		/*---------------- Open_Controller ----------------*/

		/**
		 * Для удобства сохраняем ссылки на необходимые объекты в переменные с короткими именами
		 * Если необходимо в каждом экземпляре контроллера иметь другие объекты такие как например Open_Auth, Open_Acl, Open_Session
		 * Имеет смысл создать свой класс контроллера унаследованный от Open_Controller и совершить все необходимые действия
		 */
		$C = $this->config;
		$I = $this->input;
		$R = $this->router;
		$T = $this->text;
		$V = $this->view;

		/**
		 * Если можно пренебречь общим временем выполнения скрипта обращайтесь к глобальной переменной $time или константой TIME
		 * Она хранит время начала выполнения скрипта и предназначена для избежания множественных вызовов функции time()
		 * Так как обратиться к переменной быстрее, чем вызвать функцию
		 * Можно сделать алиас на глобальную переменную двумя способами:
		 * global $time;
		 * $time = &$GLOBALS['time'];
		 */
		//$time = time();
		//global $time;
		$time = TIME;
		$time = &$GLOBALS['time'];
		$V->smarty->assign('globalTime', $time);

		/**
		 * Если объявить метод контроллера с аргументами, то при его вызове будут автоматически переданы аргументы переданные в URL
		 * Обратите внимание как объявлен этот метод
		 * Желательно присвоить аргументам значение по умолчанию, иначе если они не переданы в URL возникнет ошибка
		 * Значения передаются такими какие они есть, соответсвенно их надо проверять и чистить
		 */
		$V->smarty->assign('argumentPage', $page);
		$V->smarty->assign('argumentSpan', $span);

		/**
		 * Проверка значений
		 */
		$page = ( is_numeric($page) ? $page : self::INDEX_DEFAULT_PAGE );
		$span = ( is_numeric($span) ? $span : self::INDEX_DEFAULT_SPAN );

		/**
		 * Вот так можно получить доступ ко всем переданным аргументам
		 */
		$args = $this->getArguments();

		/**
		 * Получение тех же аргументов page и span, но через метод обращения к массиву аргументов
		 */
		$V->smarty->assign('argumentPageAnother', $this->getArguments(0));
		$V->smarty->assign('argumentSpanAnother', $this->getArguments(1));

		/*---------------- Open_Config ----------------*/

		/**
		 * Конфиги лежат в application/configs/
		 * В конфигах помимо массива можно задавать константы
		 * Для использования констант до использования конфига, его необходимо явно загрузить
		 * Во всех остальных случаях конфиг подгрузится автоматически
		 */
		$C->load('example');
		//echo EXAMPLE_ANYTHING;

		/**
		 * В папке с конфигами лежит файл example.php к которому мы обращаемся
		 * Единственное требование к файлу конфига - он должен возвращать массив
		 * Можно выполнять любые действия в файле
		 * Подгружать ничего не надо, просто обращаемся к свойствам внутри
		 * Всё подгрузится автоматически
		 * Обращаться к конфигу можно прямо из Smarty, посмотрите шаблон example.tpl для примера
		 */
		$something = $C->get('example', 'something');
		$V->smarty->assign('configSomething', $something);

		/**
		 * Также можно обращаться сразу к нескольким значениям в одном конфиге
		 * Размер входного массива не ограничен
		 * На выходе будет соответсвующий ассоциативный массив
		 */
		$C->get('example', array('something', 'something', 'something'));

		/**
		 * При обращении к стандартному конфигу указывать его название не обязательно
		 * Т.е.
		 * $application_name = $C->get('config', 'application_name');
		 * Даст такой же результат
		 */
		$applicationName = $C->get('application_name');

		/**
		 * Можно сохранять значения в конфиг
		 * Но только для текущего скрипта
		 * Используйте, если есть необходимость
		 */
		$C->set('config', 'example', 'Was it set? Yes, it was!');
		$V->smarty->assign('configSetExample', $C->get('config', 'example'));

		/**
		 * Метод getAll() возволяет получить всю секцию целиком
		 * Принимает имя секции, параметры из которой необходимо получить
		 * Если имя секции не передано, возвращает секцию по умолчанию
		 */
		//$C->getAll();
		$C->getAll('example');

		/**
		 * С помощью метода exists можно проверить существует ли параметр в конфиге
		 * Принимает два аргумента: имя секции и имя параметра
		 * Если передан один аргумент он считается именем параметра и ищется в секции по умолчанию
		 * Если переданная секция не загружена, метод пытается её загрузить
		 * Конфиги создаются вручную программистом, и в процессе разработки должно быть известно, существует ли тот или иной параметр
		 * Но возможно кому-то пригодится этот метод
		 */
		$C->exists('example', 'something');

		/*---------------- Open_Exception ----------------*/

		/**
		 * Шаблоны ошибок лежат в application/errors/
		 */

		/**
		 * Вот так генерируются стандартные ошибки E_USER_NOTICE, E_USER_WARNING, E_USER_ERROR
		 * При E_USER_ERROR выполнение скрипта завершается
		 * Ошибки создаются с обратным отслеживанием, но оно закомментировано в шаблоне
		 */
		//trigger_error('Example of User Notice', E_USER_NOTICE);

		/**
		 * Вот так генерируется 404-я, 403-я
		 * Выполнение скрипта прекращается
		 * Эти ошибки в лог не записываются
		 * При отключенном режиме дебага они всё равно отображаются
		 */
		//trigger404('Example of 404');
		//trigger403('Example of 403');

		/**
		 * Ошибки можно генерировать напрямую кидая и обрабатывая их в конструкции try...catch
		 */
		try
		{	throw new Open_Exception('Example of direct Open_Exception throw and handling', E_USER_WARNING);
		}
		catch(Open_Exception $E)
		{	$E->handle();
		}

		/*---------------- Open_Input ----------------*/

		/**
		 * Обращайтесь к переменным массивов _GET, _POST, _COOKIE желательно делать только через объект Open_Input
		 * Содержимое автоматически проходит проверку и очистку
		 * В методах обращения к массивам из объекта предусмотрена проверка на существование запрошенного ключа и в случае его отсутствия возврат значения FALSE
		 */
		$V->smarty->assign('exampleGet', $I->get('exampleGet'));
		$V->smarty->assign('examplePost', $I->post('examplePost'));
		$V->smarty->assign('exampleCookie', $I->cookie('exampleCookie'));

		/**
		 * Некоторые полезные функции класса Open_Input
		 */

		/**
		 * IP клиента
		 * Пример: 127.0.0.1
		 */
		$V->smarty->assign('ip', $I->ip());

		/**
		 * База URL
		 * Пример: http://www.example.com/
		 */
		$V->smarty->assign('base', $I->base());

		/**
		 * ULR
		 * Пример: http://www.example.com/ru/hello/world/
		 */
		$V->smarty->assign('url', $I->url());

		/**
		 * URI
		 * Пример: http://www.example.com/ru/hello/world/?foo=bar
		 */
		$V->smarty->assign('uri', $I->uri());

		/**
		 * Путь с локалью
		 * Пример: /ru/hello/world/
		 */
		$V->smarty->assign('path', $I->path());

		/**
		 * Путь без локали
		 * Пример: /hello/world/
		 */
		$V->smarty->assign('path_no_locale', $I->pathNoLocale());

		/**
		 * Локаль переданная в запросе с предшествующим слешем, либо пустая строка, если не передана
		 * Пример: /ru
		 */
		$V->smarty->assign('input_locale', $I->locale());

		/*---------------- Open_Router ----------------*/

		/**
		 * В конфиге роутера можно задавать регулярные выражения по которым осуществлять маршрутизацию
		 * Маршруты задаются здесь - application/configs/config.php
		 */

		/**
		 * Так можно обращаться к секциям пути по их порядку начиная с ноля
		 * До маршрутизации и после
		 */
		$V->smarty->assign('section0', $R->getSection(0));
		$V->smarty->assign('sectionRouted0', $R->getSectionR(0));

		/**
		 * Рабочая локаль
		 * Если в URL не передана локаль, то здесь будет локаль используемая по умолчанию
		 */
		$V->smarty->assign('locale', $R->getLocale());

		/**
		 * Контроллер
		 */
		$V->smarty->assign('controller', $R->getController());

		/**
		 * Метод контроллера
		 */
		$V->smarty->assign('method', $R->getMethod());

		/**
		 * Для создания ссылок предлагается вот такая удобная функция класса Open_Router
		 * Которую можно также использовать из Smarty
		 * Если какая-либо из переменных пропущена или ей присвоено значение FALSE, то на это место ничего не будет добавлено
		 * Если переменная имеет значение TRUE, то на это место будет добавлено значение переданное при обращении БЕЗ маршрутизации
		 * Будте аккуратны с этой функцией, т.к. можно сгенерировать ссылку, которая приведёт к 404
		 * Особенно если оставляете FALSE контроллер и метод, но аргументы переданы, убедитесь, что маршрутизация пройдёт успешно
		 * Используя метод из Smarty-шаблонов булево значение необходимо указывать маленькими буквами - особенность Smarty
		 */
		$V->smarty->assign('link', $R->link(TRUE, 'home', TRUE, TRUE, FALSE));

		/**
		 * Так можно вызвать выполнение метода другого контроллера, или текущего
		 * Но метод текущего контроллера можно вызвать и обычным способом
		 * Если необходимо, чтобы выполнение текущего метода прекратилось, надо вернуть значение возвращаемое методом call()
		 */
		//return $R->call('Home', 'index');

		/**
		 * Так осуществляется редирект на URL
		 * Если передан полный URL с хостом, то перенаправление чётко на него
	 	 * Если передан URL без хоста, то к нему добавляется текущая локаль (если её нет в переданном URL) и текущий хост
	 	 * Проверка наличия хоста сделана через функцию parse_url(), учитывайте её поведение
	 	 * Редирект сделан через вызов header('Location: http://some.url/'); exit(0);
	 	 * Соответственно никакого вывода не должно быть до вызова редиректа
		 */
		//$R->redirect('/example/123/');

		/*---------------- Captcha ----------------*/

		/**
		 * Подключаем JS-скрипт необходимый для перерисовки
		 */
		$V->addJs('captcha');

		/**
		 * Для проверки введённого значения с хранящимся значением используйте метод verify() модели Captcha
		 */
		getModel('CaptchaModel')->verify('inputValue');

		/*---------------- Open_Model ----------------*/

		/**
		 * Подключение к БД происходит автоматически при первом обращении
		 * Об этом беспокоиться не стоит
		 * Необходимо только задать в конфиге параметры подключения и профиль по умолчанию
		 * В дальнейшем текущий рабочий профиль можно поменять обратясь к методу switchProfile() объекта Open_Db
		 */

		/**
		 * Вот так загружается модель
		 * Модель находится здесь - application/models/ExampleModel.php
		 */
		$M = getModel('ExampleModel');

		/**
		 * Обращаемся к модели, получаем данные и передаём Smarty
		 */
		$result = $M->get($page, $span);
		$V->smarty->assign_by_ref('result', $result);
		$foundRows = $M->getFoundRows();
		$V->smarty->assign('foundRows', $foundRows);
		//$V->smarty->assign('insert_id', $M->set());

		/*---------------- Open_Pagination ----------------*/

		/**
		 * Создание массива данных для постраничной навигации
		 */
		$temp = $this->getArguments();
		$temp[0] = '[:nav:]';
		$pagination['link'] = $R->link(TRUE, TRUE, FALSE, $temp, TRUE);
		$pagination['amount'] = $foundRows;
		$pagination['span'] = $span;
		$pagination['current'] = $page;

		/**
		 * Построение постраничной навигации происходит через обращение к функции Smarty pagination
		 *
		 * Пример обращения из Smarty-шаблона:
		 * {pagination pattern="first" link="/example/[:nav:]/" amount=$foundRows span=$span current=$page}
		 *
		 * Параметры:
		 *
		 * 1. Обязательные
		 *
		 * 1.1. Для всех шаблонов
		 * 1.1.1. pattern - шаблон постраничной навигации
		 * 1.1.2. link - ссылка для навигации, где подстрока [:nav:] заменяется на параметр навигации (e.g. страницу, номер объекта)
		 * 1.1.3. amount - количество объектов по которым производится навигация
		 * 1.1.4. span - количество объектов выводимых на одной странице
		 * 1.1.5. current - текущий параметр навигации (e.g. текущая страница, номер объекта)
		 *
		 * 1.2. Шаблон first
		 *
		 * 2. Необязательные
		 *
		 * 2.1. Для всех шаблонов
		 * 2.1.1. class - имя класса для <div> навигации. По умолчанию будет присвоен класс 'pagination-'. $params['pattern']
		 *
		 * 2.2. Шаблон first
		 * 2.2.1. around - страниц рядом с текущей
		 * 2.2.2. gaps - максимальное количество промежутков
		 * 2.2.3. threshold - порог промежутка
		 * 2.2.4. gap - обозначение промежутка, по умолчанию строка '&#8230;', что есть многоточие
		 *
		 * Для каждого шаблона навигации существует smarty-щаблон в папке application/views/pagination/
		 */
		$V->smarty->assign('pagination', $pagination);

		/*---------------- Open_Text ----------------*/

		/**
		 * Для работы с текстом создан класс Open_Text как замена gettext расширению
		 * Файлы с тектом лежат в папке application/text/
		 * Например домен messages для ru локали должен находиться здесь: application/text/ru/messages.php
		 * К файлам доменов предъявляется одно требование: должен быть возвращён массив
		 * Массив представляет собой пары ключ-значение, где значение может быть:
		 * - Строка для сообщений не нуждающихся в вычислении множественной формы
		 * - Массив с нумерацией ключей соответствующей функции вычисления множественной формы, и значениями строками
		 * В стандартном конфиге настраивается домен по умолчанию и функции вычисления множественной формы для различных локалей
		 *
		 * Предусмотрено 4 метода объекта Open_Text по примеру расширения gettext:
		 * get(string $message) (метод-алиас gettext) - замена функции gettext. Возвращает сообщение по ключу $message из домена по умолчанию
		 * nget(string $message, int) (метод-алиас ngettext) - замена функции ngettext. Возвращает сообщение по ключу $message с учётом множественной формы от $n из домена по умолчанию
		 * dget(string $domain, string $message) (метод-алиас dgettext) - замена функции dgettext. Возвращает сообщение по ключу $message из домена $domain
		 * dnget(string $domain, string $message, int) (метод-алиас dngettext) - замена функции dngettext. Возвращает сообщение по ключу $message с учётом множественной формы от $n из домена $domain
		 *
		 * В классе Open_Text существует 2 метода для получения/задания значения рабочей локали и домена по умолчанию
		 * Open_Text::locale(string $locale=FALSE) - если значение передано, устанавливается рабочая локаль. Возвращает текущую рабочую локаль
		 * Open_Text::domain(string $domain=FALSE) - если значение передано, устанавливается домен по умолчанию. Возвращает текущую домен по умолчанию
		 */

		$V->smarty->assign('textN', ($n = mt_rand(-100, 100)));
		$V->smarty->assign('textGet', $T->get('Text message'));
		$V->smarty->assign('textDget', $T->dget('messages', 'Text message'));
		$V->smarty->assign('textNget', sprintf($T->nget('%d Messages', $n), $n));
		$V->smarty->assign('textDnget', sprintf($T->dnget('messages', '%d Messages', $n), $n));

		/*---------------- Open_Session ----------------*/

		/**
		 * Получение объекта для работы с сессией
		 */
		$session = Open_Session::getInstance();

		/**
		 * Примеры работы с данными в сессии
		 * Первый пример - использование метода объекта
		 * Второй пример - использование перегруженных для удобства методов
		 */

		/**
		 * Получение переменной из сессии
		 */
		$numberOfVisits = $session->get('numberOfVisits');
		$numberOfVisits = $session->numberOfVisits;

		$numberOfVisits = ( empty($numberOfVisits) ? 1 : $numberOfVisits );

		$V->smarty->assign('sessionNumberOfVisits', $numberOfVisits++);

		/**
		 * Проверка существования переменной в сессии
		 */
		$session->exists('numberOfVisits');
		isset($session->numberOfVisits);

		/**
		 * Удаление переменной из сессии
		 */
		$session->delete('numberOfVisits');
		unset($session->numberOfVisits);

		/**
		 * Установка значения в сессию
		 */
		$session->set('numberOfVisits', $numberOfVisits);
		$session->numberOfVisits = $numberOfVisits;

		/*---------------- Open_Cache ----------------*/

		/**
		 * Получение объекта для работы с кешем в оперативной памяти
		 * Кеш реализован через xcache
		 */
		$cache = Open_Cache::getInstance();

		/**
		 * Существует возможность эксклюзивной блокировки доступа к данным в кеше
		 * С помощью методов lock(), unlock() объекта Open_Cache
		 * Блокируем доступ к переменной над которой собираемся производить действия
		 */
		$cache->lock('cachedValue');

		/**
		 * Примеры работы с данными в кеше
		 * Первый пример - использование метода объекта
		 * Второй пример - использование перегруженных для удобства методов
		 */

		/**
		 * Получение переменной из кеша
		 */
		$cachedValue = $cache->get('cachedValue');
		$cachedValue = $cache->cachedValue;

		$cachedValue = ( empty($cachedValue) ? 1 : $cachedValue );

		$V->smarty->assign('cachedValue', $cachedValue++);

		/**
		 * Проверка существования переменной в кеше
		 */
		$cache->exists('cachedValue');
		isset($cache->cachedValue);

		/**
		 * Удаление переменной из кеша
		 */
		$cache->delete('cachedValue');
		unset($cache->cachedValue);

		/**
		 * Установка значения в кеш
		 */
		$cache->set('cachedValue', $cachedValue);
		$cache->cachedValue = $cachedValue;

		/**
		 * Возможна также установка значения со временем жизни в секундах
		 */
		$cache->set('cachedValue', $cachedValue, 3600);

		/**
		 * Снимаем блокировку доступа
		 */
		$cache->unlock('cachedValue');

		/*---------------- Open_Convert ----------------*/

		/**
		 * Некоторые методы конвертации данных
		 */
		$convert = Open_Convert::getInstance();

		/**
		 * base64 преобразование
		 * Отличается от стандартного тем, что безопасно для использования в именах файлов, URL и т.д.
		 */
		$string = 'Something to be converted...';
		$V->smarty->assign('convertedToBase64', $temp = $convert->toBase64($string));

		/**
		 * Обратное base64 преобразование
		 */
		$V->smarty->assign('convertedFromBase64', $convert->fromBase64($temp));

		/**
		 * Преобразовать строку вотТакогоВида или ВотТакогоВида или ВотТАКогоВИДА в вот_такого_вида
		 */
		$string = 'stringLikeThisStringLikeThisOrSTRingLIkeTHIS';
		$V->smarty->assign('convertedCamelToUnderscore', $temp = $convert->camelToUnderscore($string));

		/**
		 * Преобразовать строку вот_такого_вида в вотТакойВот
		 */
		$V->smarty->assign('convertedUnderscoreToCamel', $convert->underscoreToCamel($temp));

		/*---------------- Open_Color ----------------*/

		/**
		 * Получение цвета
		 * В качестве параметра конструктору должно быть передано число (0xFFEEDD), массив (array(0xFF, 0xEE, 0xDD)), либо строка ('#FFEEDD' или название web-цвета)
		 */
		$V->smarty->assign('color', $color = 'RoyalBlue');
		$color = new Open_Color($color);
		$V->smarty->assign('colorHex', $color->toString());

		/**
		 * Инвертирование цвета
		 */
		$temp = clone $color;
		$V->smarty->assign('invertedColorHex', $temp->invert()->toString());

		/**
		 * Сделать чёрно-белым
		 */
		$temp = clone $color;
		$V->smarty->assign('grayscaledColorHex', $color->grayscale()->toString());

		/**
		 * Преобразование в массив из трёх компонент
		 */
		$V->smarty->assign('rgbColor', $color->toArray());

		/*---------------- Open_Security ----------------*/

		/**
		 * Методы для безопасности данных
		 */
		$security = Open_Security::getInstance();

		/**
		 * XSS-очистка данных
		 */
		$V->smarty->assign('stringBeforeXssClean', $string = '<script>document.write("Hello World!");</script>');
		$V->smarty->assign('stringAfterXssClean', $security->xssClean('<script>document.write("Hello World!");</script>'));

		/**
		 * XSS-очистка массива рекурсивно
		 * Массив должен быть передан по ссылке
		 */
		$temp = array('<script>document.write("Hello World!");</script>', 'qwer', 'asdf');
		$security->xssCleanArray($temp);

		/**
		 * Простое шифрование строки со стандартным ключом
		 * Вторым параметром методу можно передать ключ для шифрования
		 */
		$V->smarty->assign('easyEncryptedString', $temp = $security->easyEncrypt('Hello world!'/*, $key*/));

		/**
		 * Дефишрация простого шифрования со стандартным ключом
		 * Вторым параметром методу можно передать ключ для дешифрации
		 */
		$V->smarty->assign('easyDecryptedString', $security->easyDecrypt($temp/*, $key*/));

		/*---------------- Open_Sync ----------------*/

		/**
		 * Методы для обеспечения синхронизации доступа к ресурсам
		 */
		$sync = Open_Sync::getInstance();

		/**
		 * Методы для создание эксклюзивной блокировки основанной на файлах
		 * Передаётся строка идентифицирующая блокировку
		 */
		$sync->fileLock('Resource name');
		$sync->fileUnlock('Resource name');

		/**
		 * Методы для создания эксклюзивной блокировки основанной на семафорах
		 * Должен быть передан ключ семафора от 0x00 до 0xff
		 * Метод позволяет захватывать до 256 семафоров
		 * PHP должен быть собран с поддержкой соответствующих System V IPC функций
		 * А также должны быть установлены правильные ограничения на общую память и количество семафоров в системе
		 */
		//$sync->semLock(0x01);
		//$sync->semUnlock(0x01);

		/*---------------- Open_Auth ----------------*/

		/**
		 * Получение объекта для работы с авторизацией
		 * Для авторизации используется БД
		 */
		$auth = Open_Auth::getInstance();

		/**
		 * Существует возможность задать свою сессию для аутентификации
		 * Объект должен соответствовать интерфейсу Open_Storage_Interface
		 * По умолчанию устанавливается сессия Open_Session
		 */
		//$auth->session(Open_Session::getInstance());

		/**
		 * Также существует возможность задать свой объект идентификатор
		 * Объект должен соответствовать интерфейсу Open_Auth_Identifier_Interface
		 * По умолчанию используется идентификатор с БД Open_Auth_Identifier_Db
		 * В конфиге auth указывается:
		 * - Имя таблицы
		 * - Имя поля уникального идентификатора пользователя (логин)
		 * - Имя поля проверки личности пользователя (пароль)
		 * - Функция шифрования пароля перед сверкой со значением в БД
		 */
		//$auth->identifier(Open_Auth_Identifier_Db::getInstance());

		/**
		 * Аутентификация без параметров
		 * Попытка будет успешной, если ранее уже была осуществлена авторизация с параметрами и запись хранится в сессии
		 */
		$auth->authenticate();

		/**
		 * Попытка аутентификации по имени и паролю
		 * Если в сессии запись уже хранится возвращается TRUE
		 * Иначе если происходит обращение к идентификатору, возвращается значение, которое вернул идентификатор
		 */
		switch( $auth->authenticate('login', 'password') )
		{
			case Open_Auth_Identifier_Db::SUCCESS:
				/**
				 * @todo Действия в случае успеха
				 */
				break;

			case Open_Auth_Identifier_Db::FAILURE_IDENTITY_NOT_FOUND:
				/**
				 * @todo Действия в случае если такого пользователя не существует
				 */
				break;

			case Open_Auth_Identifier_Db::FAILURE_CREDENTIAL_INVALID:
				/**
				 * @todo Действия в случае если неверный пароль
				 */
				break;

			default: case Open_Auth_Identifier_Db::FAILURE:
				/**
				 * @todo Общие действия в случае провала
				 */
				break;
		}

		/**
		 * Получить сущность прошедшую аутентификацию в виде объекта
		 * Однажды пройдя аутентификацию сущность сохраняется в сессии
		 * Если аутентификия не пройдена возвращается значение FALSE
		 * Полученную сущность можно преобразовать как необходимо и сохранить обратно
		 * Она будет сохранена в свойство объекта, но НЕ в сессию
		 */
		$identity = $auth->identity();
		$auth->identity($identity);

		/**
		 * Отмена аутентификации
		 * Очищается запись в сессии и удаляется текущая прошедшая аутентификацию сущность
		 */
		$auth->inauthenticate();

		/*---------------- Open_Acl ----------------*/

		/**
		 * Получение объекта для работы со списками доступа
		 * Роли, ресурсы, и списки прав доступа указываются в конфиге acl.php
		 * Самое интересное, если хранить в БД в таблице аутентификации роль пользователя, и использовать потом её для проверки возможностей пользователя
		 */
		$acl = Open_Acl::getInstance();

		/**
		 * Так проверяется возмножность роли ACL_ROLE_GUEST производить действие ACL_ACTION_SHOW над ресурсом ACL_RESOURCE_CAPTCHA
		 */
		$V->smarty->assign('aclIsAllowed', ($acl->isAllowed(ACL_ROLE_GUEST, ACL_RESOURCE_CAPTCHA, ACL_ACTION_SHOW) ? $T->dget('example', 'allowed') : $T->dget('example', 'NOT allowed')));

		/**
		 * Соответственно так проверяется запрещение возможности
		 */
		$V->smarty->assign('aclIsDenied', ($acl->isDenied(ACL_ROLE_GUEST, ACL_RESOURCE_CAPTCHA, ACL_ACTION_SHOW) ? $T->dget('example', 'denied') : $T->dget('example', 'NOT denied')));

		/*---------------- Open_Validator ----------------*/

		/**
		 * Массив данных для проверки, допустим мы получили его из GET или POST данных
		 */
		$data = array(
			'example' => array(
				'first' => 'qwer',
				'second' => 'qwer@asdfq.vt',
			),
			'hello' => '1q2',
		);

		/**
		 * Лямбда-функция для проверки
		 * Ничего не проверяет, исключительно для примера
		 */
		$func = create_function('$var, $args', 'return TRUE;');

		/**
		 * Массив правил, которым должны соответствовать данные
		 *
		 * Правила:
		 * - required - Проверка заданности значения, пропускается через функцию empty(), соответственно значения 0 и '0' также считаются пустыми
		 * - minLength - Проверка минимальной длины строки или массива, принимает числовое значение
		 * - maxLength - Проверка максимальной длины строки или массива, принимает числовое значение
		 * - length - Проверка точной длины строки или массива, принимает числовое значение
		 * - email - Проверка строки на соответствие шаблону email
		 * - regexp - Проверка на соответствие строки регулярному выражению, принимает строку с регулярным выражением
		 * - callback - Проверка при помощи функции обратного вызова, принимает имя функции или класс и метод через '::' ('function' или 'Class::method')
		 * - numeric - Является ли поле числом
		 * - match - Совпадает ли поле с переданным значением, принимает сравниваемое значение
		 *
		 * Требования к функции обратного вызова:
		 * - Принимает по ссылке 2 аргумента - проверяемое значение и массив аргументов
	 	 * - Возвращает TRUE в случае успеха, другое значение в случае неудачи
	 	 *
	 	 * Дополнительным аргументом всем правилам может быть передано значение, которое будет возвращено в случае провала проверки, т.е. для замещения стандартного текста ошибки
		 */
		$rules = array(
			'example' => array(
				'first' => 'required|callback:"'. $func .'":"#^(.*)$#i"|email|regexp:"#^(.*)$#i"|minLength:0x04',
				'second' => 'required',
			),
			'hello' => 'required:"Oops..."|numeric:qwer',
		);

		/**
		 * Получаем объект валидации
		 */
		$va = Open_Validator::getInstance();

		/**
		 * Запускаем проверку и получаем результат
		 * Если всё в порядке, возвращается TRUE, иначе массив c ошибками
		 */
		$validation_result = $va->validate($data, $rules);

		$V->smarty->assign('validationResult', preg_replace('#(\r\n|\n\r|\r|\n)#', '<br />', print_r($validation_result, TRUE)));

		/*---------------- Open_View ----------------*/

		/**
		 * Получение времени прошедшего с момента начала example_index_start до текущего момента
		 */
		if(DEBUG) $V->smarty->assign('benchmarkExampleIndexElapsed', $B->elapsed('example_index'));

		/**
		 * Отображаем шаблон
		 * Посмотрите структуру шаблонов начиная с index.tpl
		 *
		 * Реализован стандартный механизм кеширования
		 * Метод show() принимает кроме имени шаблона, который необходимо отобразить в качестве тела документа, ещё 2 аргумента:
		 * - id для идентификации кеша по правилам Smarty с поддержкой группового кеширования
		 * - Время жизни кеша
		 * В конечном итоге кешируется index.tpl с id основанным на переданном имени шаблона тела и переданном id
		 *
		 * Не забудте проверить, что кеш Smarty включен со значение 2. По умолчанию в процессе отладки кеш выключен
		 */
		$id = NULL;
		$lifetime = 3600;
		$V->show('Example/index', $id, $lifetime);

		/**
		 * Проверка существования кеша
		 */
		$V->isCached('Example/index', $id);

		/**
		 * Очистка кеша
		 */
		//$V->clearCache('Example/index', $id);

		/*---------------- Open_Benchmark ----------------*/

		/**
		 * Сохраняем метку конца выполнения метода и выводим результат
		 * Результат записывается как html-комментарий
		 */
		if(DEBUG) $B->mark('example_index_end');
		if(DEBUG) $B->display('example_index');
	}

	/**
	 * Демонстрация цветов
	 */
	public function colors()
	{
		$V = $this->view;
		$template = 'Example/colors';

		/**
		 * Проверяем закеширована ли страница
		 */
		if( !$V->isCached($template) )
		{
			$webColors = array(
				'Reds' => array('IndianRed', 'LightCoral', 'Salmon', 'DarkSalmon', 'LightSalmon', 'Crimson', 'Red', 'FireBrick', 'DarkRed'),
				'Pinks' => array('Pink', 'LightPink', 'HotPink', 'DeepPink', 'MediumVioletRed', 'PaleVioletRed'),
				'Oranges' => array('Coral', 'Tomato', 'OrangeRed', 'DarkOrange', 'Orange'),
				'Yellows' => array('Gold', 'Yellow', 'LightYellow', 'LemonChiffon', 'LightGoldenrodYellow', 'PapayaWhip', 'Moccasin', 'PeachPuff', 'PaleGoldenrod', 'Khaki', 'DarkKhaki'),
				'Purples' => array('Lavender', 'Thistle', 'Plum', 'Violet', 'Orchid', 'Fuchsia', 'Magenta', 'MediumOrchid', 'MediumPurple', 'BlueViolet', 'DarkViolet', 'DarkOrchid', 'DarkMagenta', 'Purple', 'Indigo', 'SlateBlue', 'DarkSlateBlue'),
				'Greens' => array('GreenYellow', 'Chartreuse', 'LawnGreen', 'Lime', 'LimeGreen', 'PaleGreen', 'LightGreen', 'MediumSpringGreen', 'SpringGreen', 'MediumSeaGreen', 'SeaGreen', 'ForestGreen', 'Green', 'DarkGreen', 'YellowGreen', 'OliveDrab', 'Olive', 'DarkOliveGreen', 'MediumAquamarine', 'DarkSeaGreen', 'LightSeaGreen', 'DarkCyan', 'Teal'),
				'Blues' => array('Aqua', 'Cyan', 'LightCyan', 'PaleTurquoise', 'Aquamarine', 'Turquoise', 'MediumTurquoise', 'DarkTurquoise', 'CadetBlue', 'SteelBlue', 'LightSteelBlue', 'PowderBlue', 'LightBlue', 'SkyBlue', 'LightSkyBlue', 'DeepSkyBlue', 'DodgerBlue', 'CornflowerBlue', 'MediumSlateBlue', 'RoyalBlue', 'Blue', 'MediumBlue', 'DarkBlue', 'Navy', 'MidnightBlue'),
				'Browns' => array('Cornsilk', 'BlanchedAlmond', 'Bisque', 'NavajoWhite', 'Wheat', 'BurlyWood', 'Tan', 'RosyBrown', 'SandyBrown', 'Goldenrod', 'DarkGoldenrod', 'Peru', 'Chocolate', 'SaddleBrown', 'Sienna', 'Brown', 'Maroon'),
				'Whites' => array('White', 'Snow', 'Honeydew', 'MintCream', 'Azure', 'AliceBlue', 'GhostWhite', 'WhiteSmoke', 'Seashell', 'Beige', 'OldLace', 'FloralWhite', 'Ivory', 'AntiqueWhite', 'Linen', 'LavenderBlush', 'MistyRose'),
				'Grays' => array('Gainsboro', 'LightGray', 'Silver', 'DarkGray', 'Gray', 'DimGray', 'LightSlateGray', 'SlateGray', 'DarkSlateGray', 'Black'),
			);

			$webColorsValues = array();
			$webColorsInverted = array();
			foreach($webColors as $categoryName => &$category)
			{
				foreach($category as $key => $name)
				{
					$temp = new Open_Color($name);
					$webColorsValues[$categoryName][$key] = $temp->toString();
					$webColorsInverted[$categoryName][$key] = $temp->invert()->toString();
				}
			}

			$V->smarty->assign_by_ref('webColors', $webColors);
			$V->smarty->assign_by_ref('webColorsValues', $webColorsValues);
			$V->smarty->assign_by_ref('webColorsInverted', $webColorsInverted);
		}

		$V->show($template, NULL, self::COLORS_LIFETIME);
	}
}