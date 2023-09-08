<?
Bitrix\Main\Loader::registerAutoloadClasses(
	// имя модуля
	"hmarketing.d7",
	array(
		// ключ - имя класса с простанством имен, значение - путь относительно корня сайта к файлу
		"hmarketing\\Main\\Main" => "lib/Main.php",
		"hmarketing\\Data\\DataTable" => "lib/data.php",
	)
);
