<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

use Bitrix\Main\Context,
    Bitrix\Main\Request,
    Bitrix\Main,
    Bitrix\Catalog;
use Bitrix\Catalog\SubscribeTable;
use Bitrix\Catalog\Product\SubscribeManager;

global $USER;
$request = Context::getCurrent()->getRequest();
$postArray = $request->getPostList()->toArray();

if($postArray){
    
    $userId = $postArray['userid']; // id юзера

	// Проверка - есть ли подписчик на данный товар
	$tableName = \Bitrix\Catalog\SubscribeTable::getTableName();
	$results = $DB->Query("SELECT `USER_ID` FROM `" . $tableName . "` WHERE `ITEM_ID`='".$postArray['idProduct']."'");
	while ($row = $results->Fetch()) {
		$arMail[] = $row["USER_ID"];
	}

    // собираем массив id всех подписок данного юзера
    $results = $DB->Query("SELECT `ID`, `USER_ID` FROM `" . $tableName . "` WHERE `ITEM_ID`='".$postArray['idProduct']."' and `USER_ID`='".$userId."'");
	while ($row = $results->Fetch()) {
		$ListSubscribe['ID'][] = $row["ID"];
	}

    if ($arMail){
        if (in_array($userId, $arMail))
        {
            $arResult['subscr'] = 1; // уже есть подписка
        } else{
            $arResult['subscr'] = 0; //не подписан
        }
    }


    // Подписываем на товар, соберем массив данных
    $subscribeManager = new \Bitrix\Catalog\Product\SubscribeManager;
    $contactTypes = $subscribeManager->contactTypes;

    $subscribeData = array(
        'USER_CONTACT' => $postArray['mail'],
        'ITEM_ID' => $postArray['idProduct'], //ID товара
        'SITE_ID' => 's1',
        'CONTACT_TYPE' => \Bitrix\Catalog\SubscribeTable::CONTACT_TYPE_EMAIL,
        'USER_ID' => $postArray['userid'],
    );

    // Добавляем подписку в БД
    $subscribeId = $subscribeManager->addSubscribe($subscribeData);

    if($subscribeId){

        $res = [
            'status' => 'succes', 
            'text' => 'Вы подписались',
            'error' => '',
            'subscr' => $arResult['subscr']
        ];

    }else{
        //если юзер уже подписан, то Отменим подписку на данный товар
        if($arResult['subscr'] == 1){

            $subscribeManager = new \Bitrix\Catalog\Product\SubscribeManager;
            if(!$subscribeManager->deleteManySubscriptions($ListSubscribe['ID'], $subscribeData['ITEM_ID']))
            {
                $errorObject = current($subscribeManager->getErrors());
                if($errorObject) {
                    $errors = $errorObject->getMessage();
                }
            }

            $res = [
                'status' => 'succes', 
                'text' => 'Подписка отменена',
                'error' => $errors
            ];

        }else{

            //если другая ошибка
            $errorObject = current($subscribeManager->getErrors());
            $errors = array('error' => true);
            if($errorObject)
            {
                $errors['message'] = $errorObject->getMessage();
                if($errorObject->getCode() == $subscribeManager::ERROR_ADD_SUBSCRIBE_ALREADY_EXISTS)
                {
                    $errors['setButton'] = true;
                }
            }

            $res = [
                'status' => 'error', 
                'text' => 'Подписаться',
                'error' => '<b>Ошибка!</b> Что-то пошло не так. Попробуйте еще раз',
                'subscr' => $arResult['subscr']
            ];
        }

    }

}

echo json_encode($res);
return;
?>