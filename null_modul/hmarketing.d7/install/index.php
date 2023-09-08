<?
// пространство имен для подключений ланговых файлов
use Bitrix\Main\Localization\Loc;
// пространство имен для управления (регистрации/удалении) модуля в системе/базе
use Bitrix\Main\ModuleManager;
// пространство имен для работы с параметрами модулей хранимых в базе данных
use Bitrix\Main\Config\Option;
// пространство имен с абстрактным классом для любых приложений, любой конкретный класс приложения является наследником этого абстрактного класса
use Bitrix\Main\Application;
// пространство имен для работы с директориями
use Bitrix\Main\IO\Directory;

// подключение ланговых файлов
Loc::loadMessages(__FILE__);

class Hmarketing_D7 extends CModule
{
    // переменные модуля
    public  $MODULE_ID;
    public  $MODULE_VERSION;
    public  $MODULE_VERSION_DATE;
    public  $MODULE_NAME;
    public  $MODULE_DESCRIPTION;
    public  $PARTNER_NAME;
    public  $PARTNER_URI;
    public  $errors;

    // конструктор класса, вызывается автоматически при обращение к классу
    function __construct()
    {
        // создаем пустой массив для файла version.php
        $arModuleVersion = array();
        // подключаем файл version.php
        include_once(__DIR__ . '/version.php');

        // версия модуля
        $this->MODULE_VERSION = $arModuleVersion['VERSION'];
        // дата релиза версии модуля
        $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        // id модуля
        $this->MODULE_ID = "hmarketing.d7";
        // название модуля
        $this->MODULE_NAME = "Пример модуля D7";
        // описание модуля
        $this->MODULE_DESCRIPTION = "Тестовый модуль для разработчиков, можно использовать как основу для разработки новых модулей для Битрикс";
        // имя партнера выпустившего модуль
        $this->PARTNER_NAME = "Эйч Маркетинг";
        // ссылка на рисурс партнера выпустившего модуль
        $this->PARTNER_URI = "https://hmarketing.ru";
    }

    // метод отрабатывает при установке модуля
    function DoInstall()
    {
        // глобальная переменная с обстрактным классом
        global $APPLICATION;
        // создаем таблицы баз данных, необходимые для работы модуля
        $this->InstallDB();
        // регистрируем обработчики событий
        $this->InstallEvents();
        // копируем файлы, необходимые для работы модуля
        $this->InstallFiles();
        // регистрируем модуль в системе
        ModuleManager::RegisterModule("hmarketing.d7");
        // подключаем скрипт с административным прологом и эпилогом
        $APPLICATION->includeAdminFile(
            Loc::getMessage('INSTALL_TITLE'),
            __DIR__ . '/instalInfo.php'
        );
        // для успешного завершения, метод должен вернуть true
        return true;
    }

    // метод отрабатывает при удалении модуля
    function DoUninstall()
    {
        // глобальная переменная с обстрактным классом
        global $APPLICATION;
        // удаляем таблицы баз данных, необходимые для работы модуля
        $this->UnInstallDB();
        // удаляем обработчики событий
        $this->UnInstallEvents();
        // удаляем файлы, необходимые для работы модуля
        $this->UnInstallFiles();
        // удаляем регистрацию модуля в системе
        ModuleManager::UnRegisterModule("hmarketing.d7");
        // подключаем скрипт с административным прологом и эпилогом
        $APPLICATION->includeAdminFile(
            Loc::getMessage('DEINSTALL_TITLE'),
            __DIR__ . '/deInstalInfo.php'
        );
        // для успешного завершения, метод должен вернуть true
        return true;
    }

    // метод для создания таблицы баз данных
    function InstallDB()
    {
        // глобальный объект $DB для работы с базой данных
        global $DB;
        // изначально устанавливаем переменной errors булево значение false
        $this->errors = false;
        // метод выполняет пакет запросов из файла install.sql и возвращает false в случае успеха или массив ошибок
        $this->errors = $DB->RunSQLBatch($_SERVER['DOCUMENT_ROOT'] . "/local/modules/hmarketing.d7/install/db/install.sql");
        // проверяем ответ, если ответ вернул false, значит таблица успешно создана
        if (!$this->errors) {
            // для успешного завершения, метод должен вернуть true
            return true;
        } else
            return $this->errors;
    }

    // метод для удаления таблицы баз данных
    function UnInstallDB()
    {
        // глобальный объект $DB для работы с базой данных
        global $DB;
        // изначально устанавливаем переменной errors булево значение false
        $this->errors = false;
        // метод выполняет пакет запросов из файла uninstall.sql и возвращает false в случае успеха или массив ошибок
        $this->errors = $DB->RunSQLBatch($_SERVER['DOCUMENT_ROOT'] . "/local/modules/hmarketing.d7/install/db/uninstall.sql");
        // проверяем ответ, если ответ вернул false, значит таблица успешно удалена
        if (!$this->errors) {
            // для успешного завершения, метод должен вернуть true
            return true;
        } else
            return $this->errors;
    }

    // метод для создания обработчика событий
    function InstallEvents()
    {
        RegisterModuleDependences("hmarketing.d7", "OnSomeEvent", "hmarketing.d7", "\\Hmarketing\\Main\\Main", "get");
        // для успешного завершения, метод должен вернуть true
        return true;
    }

    // метод для удаления обработчика событий
    function UnInstallEvents()
    {
        UnRegisterModuleDependences("hmarketing.d7", "OnSomeEvent", "hmarketing.d7", "\\Hmarketing\\Main\\Main", "get");
        // для успешного завершения, метод должен вернуть true
        return true;
    }

    // метод для копирования файлов модуля при установке
    function InstallFiles()
    {
        // копируем файлы, которые устанавливаем вместе с модулем, копируем в пространство имен для компонентов которое будет иметь имя модуля hmarketing.7d
        CopyDirFiles(
            __DIR__ . '/copy_files',
            Application::getDocumentRoot() . '/' . $this->MODULE_ID . '/',
            true,
            true
        );
        // для успешного завершения, метод должен вернуть true
        return true;
    }

    // метод для удаления файлов модуля при удалении
    function UnInstallFiles()
    {
        // удаляем директорию по указанному пути до папки
        Directory::deleteDirectory(
            Application::getDocumentRoot() . '/' . $this->MODULE_ID
        );
        // для успешного завершения, метод должен вернуть true
        return true;
    }
}
