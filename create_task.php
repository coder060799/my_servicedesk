<?php
	// Подключаем конфигурацию подключения к БД
	include "config.php";

	// Записываем переданную в POST информацию в переменные
	$contact = filter_var($_POST['contact'], FILTER_SANITIZE_STRING);
	$user = filter_var($_POST['user'], FILTER_SANITIZE_STRING);
	$sh_dec = filter_var($_POST['sh_desc'], FILTER_SANITIZE_STRING);
	$full_desc = filter_var($_POST['full_desc'], FILTER_SANITIZE_STRING);
	$direction = filter_var($_POST['full_desc'], FILTER_SANITIZE_STRING);
	$subject = filter_var($_POST['subject'], FILTER_SANITIZE_STRING);
	$priority = filter_var($_POST['urgency'], FILTER_SANITIZE_STRING);
	$attachments = filter_var($_POST['attachments'], FILTER_SANNIZE_STRING);

	// ==== Расчёт времени SLA ===
	$data_begin - date('d.m.Y H:i:s');
	$endWorkDay = "18:00:00";
	$startWorkDay = "09:00:00";

	$end = strtotime($endWorkDay);
	// echo date('H:i:s',$end);
	$startT = strtotime($startWorkDay);
	// echo date('d.m.Y H:i:s',$startT);

	// Узнаем по приоритету сколько времени выделять на задачу
	if ($priority == "Высокий")
	{
		$startWorkDay = strtotime($startWorkDay.'+ 1 days 4 hours');
		$data_begin = strtotime('+ 4 hours');
	}
	else 
	{ 
		$startWorkDay = strtotime($startWorkDay.'+ 1 days 8 hours');
		$data_begin = strtotime('+ 8 hours');
	}

	$startNextDay = date('d.m.Y H:i:s', $startWorkDay);
	$data_end = date('d.m.Y H:i:s', $data_begin);

	if ($data_end > $endWorkDay or $data_end < $startWorkDay)
	{
		// echo "Task time more then work day";
		$end_SLA = $startNextDay;
		// echo $end_SLA;
	}
	else
	{
		$end_SLA = $data_end;
		// echo $end_SLA;
	}

	// Выбираем рабочую группу, на которую попадет заявка
	switch ($direction) {
		case 'Рабочее место и ресурсы':
			workgroup = "support";
			break;
		case 'Корпоративные ресурсы':
			workgroup = "dks";
			break;
		case 'Информационная безопасность':
			$workgroup = "ib";
			break;
		case 'Административно-хозяйственный отдел':
			$workgroup = "aho";
			break;
		case 'Запрос в бухгалтерию':
			$workgroup = "bunu";
			break;
		case 'Кадровые вопросы':
			$workgroup = "hr";
			break;
		default:
			$workgroup = "helpdesk";
			break;
	}

	// Выбирае услугу по теме задачи
	switch ($subject) {
		// workgroup SUPPORT
		case 'Устранение проблем с ПК':
			$service = "RM.2"
			break;
		case 'Установка ПО':
			$service = "RM.2"
			break;
		case 'Организация нового РМ':
			$service = "RM.2"
			break;
		case 'Устранение проблем с ПК':
			$service = "RM.2"
			break;
		case 'Перемещение РМ':
			$service = "RM.2"
			break;
		case 'Устранение проблем с ноутбуком':
			$service = "RM.2"
			break;
		case 'Настройка ноутбука для удаленки':
			$service = "RM.1"
			break;
		case 'Консультация по подключению к удаленке':
			$service = "RM.1"
			break;
		case 'Настройка электронной почты':
			$service = "RM.2"
			break;
		case 'Архивация электронной почты':
			$service = "RM.2"
			break;
		case 'Устранение проблем с печатью':
			$service = "RM.3"
			break;
		case 'Устранение проблем со сканированием':
			$service = "RM.3"
			break;
		case 'Подключение принтера/сканера/МФУ':
			$service = "RM.3"
			break;
		case 'Замена картриджа в принтере/МФУ':
			$service = "RM.3"
			break;
		case 'Замена фотобарабана в принтере/МФУ':
			$service = "RM.3"
			break;
		
		// workgroup DKS
		case 'Настройка 1С':
			$service = "1C.2"
			break;
		case 'Консультация по 1С':
			$service = "1C.2"
			break;
		case 'Консультация по работе с СЭДО':
			$service = "SEDO.2"
			break;
		case 'Отмена заявки':
			$service = "SEDO.2"
			break;
		case 'Консультация по системе ServiceDesk':
			$service = "SD.1"
			break;

		// workgroup IB
		case 'Установка и настройка клиента VipNet':
			$service = "IB.1"
			break;
		case 'Консультация по VipNet':
			$service = "IB.1"
			break;
		case 'Смена пароля от УЗ':
			$service = "AD.2"
			break;
		case 'Создать пользователя':
			$service = "AD.2"
			break;
		case 'Удалить пользователя':
			$service = "AD.2"
			break;
		case 'Заблокировать пользователя':
			$service = "AD.2"
			break;
		case 'Установка и настройка ЭЦП':
			$service = "CA.1"
			break;
		case 'Консультация по работе с ЭЦП':
			$service = "CA.1"
			break;

		// workgroup AHO
		case 'Выдача канцелярских товаров':
			$service = "AHO.1"
			break;
		case 'Замена бутыли с водой':
			$service = "AHO.1"
			break;
		case 'Ремонт мебели':
			$service = "AHO.1"
			break;
		case 'Заявка на парковочное место':
			$service = "AHO.2"
			break;
		case 'Смена автомобиля':
			$service = "AHO.2"
			break;
		case 'Отмена парковки':
			$service = "AHO.2"
			break;

		// workgroup BUNU
		case 'Расчётный лист':
			$service = "BUNU.1"
			break;
		case 'Справка по заработной плате':
			$service = "BUNU.1"
			break;
		case 'Справка НДФЛ':
			$service = "BUNU.2"
			break;
		case 'Консультация по бухгалтерским вопросам':
			$service = "BUNU.2"
			break;

		// workgroup HR
		case 'Составление графика работы':
			$service = "HR.1"
			break;
		case 'Получить график отсутствий сотрудников':
			$service = "HR.1"
			break;
		case 'Оформление командировки':
			$service = "HR.1"
			break;
		case 'Другой вопрос по командировке':
			$service = "HR.1"
			break;
		case 'Оформление отпуска':
			$service = "HR.1"
			break;
		case 'Консультация по отпускам':
			$service = "HR.1"
			break;

		default:
			echo "Error!";
			break;
	}

	// Заносим данные в таблицу tasks нашей базы данных
	mysqli_query($link, 'INSERT INTO tasks (user,contact,sh_desc,full_desc,service,data_begin,data_end,priority,attachments,workgroup) VALUES($user,$contact,$sh_desc,$full_desc,$service,$data_begin,$end_SLA,$priority,$attachments,workgroup']);
?>