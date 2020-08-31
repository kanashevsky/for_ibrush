<?php

//Отправка сообщений при изменнени документа на разных этапах
use \Bitrix\Main\Mail\Event;
class workflowHandler_upd
{
    function notifyUser_upd($id, $iblock_id, $props)
    {
        if ($iblock_id == 109) {

            $prop = self::getWorkflowProperties_upd($id, $iblock_id, $props);
            $fields = self::getWorkflowFields_upd($id, $iblock_id, $props);

            if (($prop['MW_PROC_CURRENT']['VALUE'] == 4500 && $prop['MW_STATUS_CURRENT']['VALUE'] == 4487) || ($prop['MW_PROC_CURRENT']['VALUE'] == 4500 && $prop['MW_STATUS_CURRENT']['VALUE'] == 4490)) {
                // Документ направлен на согласование - Ready
                
                if (count($prop['MW_USERS_PARTICIP']['VALUE']) > 0) {
                    $userEmailsPart = $prop['MW_USERS_PARTICIP']['VALUE'];
                    array_push($userEmailsPart, $fields['CREATED_BY']);
                    $filter = array('ID' => implode('|', $userEmailsPart));
                    $rsUsers = CUser::GetList(($by="id"), ($order="desc"), $filter);
                    $userEmails = array();
                    while ($arUser = $rsUsers->GetNext()) {
                        $userEmails[] = $arUser['EMAIL'];
                    }

                    $userEmails = implode(',', $userEmails);
                    //self::log(["type" => "text", "info" => $userEmails]);

                    $params = array(
                        'TITLE' => "Согласование документа №{$id}",
                        'MESSAGE_TEXT' => 'Поступил документ на согласование',
                        'EMAILS' => $userEmails
                    );

                    self::SendNotification_upd($params);
                }
            }
            if($prop['MW_PROC_CURRENT']['VALUE'] == 4495 && $prop['MW_STATUS_CURRENT']['VALUE'] == 4490){
                // Документ направлен на утверждение (одобрение руководителем) - Ready
                
                if (count($prop['MW_MANAGEMENT']['VALUE']) > 0) {
                    $filter = array('ID' => $prop['MW_MANAGEMENT']['VALUE']);
                    $rsUsers = CUser::GetList(($by="id"), ($order="desc"), $filter);
                    $userEmails = array();
                    while ($arUser = $rsUsers->GetNext()) {
                        $userEmails[] = $arUser['EMAIL'];
                    }

                    $userEmails = implode(',', $userEmails);
                    //self::log(["type" => "text", "info" => $userEmails]);

                    $params = array(
                        'TITLE' => "Утверждение документа №{$id}",
                        'MESSAGE_TEXT' => 'Документ направлен на утверждение',
                        'EMAILS' => $userEmails
                    );

                    self::SendNotification_upd($params);
                }
            }
            if($prop['MW_PROC_CURRENT']['VALUE'] == 4495 && $prop['MW_STATUS_CURRENT']['VALUE'] == 4486){
                // Документ отклонен руководителем - Ready
                
                if (count($prop['MW_MANAGEMENT']['VALUE']) > 0) {
                    $filter = array('ID' => $fields['CREATED_BY']);
                    $rsUsers = CUser::GetList(($by="id"), ($order="desc"), $filter);
                    $userEmails = array();
                    while ($arUser = $rsUsers->GetNext()) {
                        $userEmails[] = $arUser['EMAIL'];
                    }

                    $userEmails = implode(',', $userEmails);
                    //self::log(["type" => "text", "info" => $userEmails]);

                    $params = array(
                        'TITLE' => "Утверждение документа №{$id}",
                        'MESSAGE_TEXT' => 'Документ отклонен руководителем',
                        'EMAILS' => $userEmails
                    );

                    self::SendNotification_upd($params);
                }
            }
           //Находим ID статуса - "Обновлены участники согласования"
            $arFilterStatus = array("IBLOCK_ID"=>112, "CODE" => 'NEW_PARTICIP');
            $resStatus = CIBlockElement::GetList(array(), $arFilterStatus, false, array("nPageSize"=>1), array('ID'));
            $elementStatus = $resStatus->Fetch();
            $newStatus = $elementStatus['ID'];
            if($prop['MW_PROC_CURRENT']['VALUE'] == 4500 && $prop['MW_STATUS_CURRENT']['VALUE'] == $newStatus){
                //Вы добавлены в качестве нового согласующего  - Ready
                
                if (count($prop['MW_USERS_PARTICIP_NEW']['VALUE']) > 0) {
                    $filter = array('ID' => $fields['CREATED_BY']);
                    $rsUsers = CUser::GetList(($by="id"), ($order="desc"), $filter);
                    $userEmails = array();
                    while ($arUser = $rsUsers->GetNext()) {
                        $userEmails[] = $arUser['EMAIL'];
                    }

                    $userEmails = implode(',', $userEmails);
                    //self::log(["type" => "text", "info" => $userEmails]);

                    $params = array(
                        'TITLE' => "Согласование документа №{$id}",
                        'MESSAGE_TEXT' => 'Вы добавлены в качестве нового согласующего',
                        'EMAILS' => $userEmails
                    );

                    self::SendNotification_upd($params);
                }
            }
            if($prop['MW_PROC_CURRENT']['VALUE'] == 4500 && $prop['MW_STATUS_CURRENT']['VALUE'] == 4485){
                // Документ согласован одним из согласующих - Ready
                
                if (count($prop['MW_USERS_PARTICIP']['VALUE']) > 0) {
                    $filter = array('ID' => $fields['CREATED_BY']);
                    $rsUsers = CUser::GetList(($by="id"), ($order="desc"), $filter);
                    $userEmails = array();
                    while ($arUser = $rsUsers->GetNext()) {
                        $userEmails[] = $arUser['EMAIL'];
                    }

                    $userEmails = implode(',', $userEmails);
                    //self::log(["type" => "text", "info" => $userEmails]);

                    $params = array(
                        'TITLE' => "Согласование документа №{$id}",
                        'MESSAGE_TEXT' => 'Документ согласован одним из согласующих',
                        'EMAILS' => $userEmails
                    );

                    self::SendNotification_upd($params);
                }
            }
            if($prop['MW_PROC_CURRENT']['VALUE'] == 4500 && $prop['MW_STATUS_CURRENT']['VALUE'] == 4486){
                // Документ отклонён одним из согласующих -- Ready
                
                if (count($prop['MW_USERS_PARTICIP']['VALUE']) > 0) {
                    $filter = array('ID' => $fields['CREATED_BY']);
                    $rsUsers = CUser::GetList(($by="id"), ($order="desc"), $filter);
                    $userEmails = array();
                    while ($arUser = $rsUsers->GetNext()) {
                        $userEmails[] = $arUser['EMAIL'];
                    }

                    $userEmails = implode(',', $userEmails);
                    //self::log(["type" => "text", "info" => $userEmails]);

                    $params = array(
                        'TITLE' => "Согласование документа №{$id}",
                        'MESSAGE_TEXT' => 'Документ отклонён одним из согласующих',
                        'EMAILS' => $userEmails
                    );

                    self::SendNotification_upd($params);
                }
            }
            if($prop['MW_PROC_CURRENT']['VALUE'] == 4497 && $prop['MW_STATUS_CURRENT']['VALUE'] == 4485){ // Приходит после согласования всеми участниками, либо с ними и с контрагентом
                // Документ согласован всеми участниками согласования - Ready
                
                if (count($prop['MW_USERS_PARTICIP']['VALUE']) > 0) {
                    
                    $arResultDocs = array();
                    $arResultDocs[$arFields['ID']] = $prop['MW_USERS_PARTICIP']['VALUE'];
                    
                    foreach ($arResultDocs as $IdDocs => $IdUsers) {
                        $arResultUserStatus = array();
                        $countUser =  count($IdUsers);
                        foreach ($IdUsers as $IdUser) {
                            $arFilter = array("IBLOCK_ID" => 111, "PROPERTY_MW_COM_DOC" => $IdDocs, "PROPERTY_MW_COM_PROC" => 4500, "PROPERTY_MW_COM_STATUS" => 4485, "PROPERTY_MW_COM_USER" => $IdUser,);
                            $res = CIBlockElement::GetList(array('timestamp_x' => 'desc'), $arFilter);
                
                            while ($ob = $res->GetNextElement()) {
                                $fields = $ob->GetFields();
                                $props = $ob->GetProperties();
                                if (!in_array($props["MW_COM_USER"]["VALUE"], $arResultUserStatus[$IdDocs])) {
                                    $arResultUserStatus[$IdDocs][] = $props["MW_COM_USER"]["VALUE"];
                                }
                            }
                        }
                     }

                    $filename = $_SERVER["DOCUMENT_ROOT"] . 'log.txt';
                    // Запись.
                    $data = serialize($arResultDocs);      // PHP формат сохраняемого значения.
                    file_put_contents($filename, $data);

                        $countUserStatusOk =  count($arResultUserStatus[$IdDocs]);
                        if((int)$countUserStatusOk == (int)$countUser){
                            $filter = array('ID' => $fields['CREATED_BY']);
                            $rsUsers = CUser::GetList(($by="id"), ($order="desc"), $filter);
                            $userEmails = array();
                            while ($arUser = $rsUsers->GetNext()) {
                                $userEmails[]  = $arUser['EMAIL'];
                            }
                            
                            $userEmails = implode(',', $userEmails);
                            //self::log(["type" => "text", "info" => $userEmails]);
        
                            $params = array(
                                'TITLE' => "Согласование документа №{$id}",
                                'MESSAGE_TEXT' => 'Документ согласован всеми участниками согласования',
                                'EMAILS' => $userEmails
                            );
        
                            self::SendNotification_upd($params);
                        }
                        unset($arResultUserStatus);
                   
                }
            }
            if($prop['MW_PROC_CURRENT']['VALUE'] == 4497 && $prop['MW_STATUS_CURRENT']['VALUE'] == 4485){
                // Документ направлен на подписание - Ready
                
                if (count($prop['MW_PODPISANT']['VALUE']) > 0) {
                    $filter = array('ID' => $prop['MW_PODPISANT']['VALUE']);
                    $rsUsers = CUser::GetList(($by="id"), ($order="desc"), $filter);
                    $userEmails = array();
                    while ($arUser = $rsUsers->GetNext()) {
                        $userEmails[] = $arUser['EMAIL'];
                    }

                    $userEmails = implode(',', $userEmails);
                    //self::log(["type" => "text", "info" => $userEmails]);

                    $params = array(
                        'TITLE' => "Согласование документа №{$id}",
                        'MESSAGE_TEXT' => 'Поступил документ на подписание',
                        'EMAILS' => $userEmails
                    );

                    self::SendNotification_upd($params);
                }
            }  
            if(($prop['MW_PROC_CURRENT']['VALUE'] == 4499 && $prop['MW_STATUS_CURRENT']['VALUE'] == 4492) || ($prop['MW_PROC_CURRENT']['VALUE'] == 4498 && $prop['MW_STATUS_CURRENT']['VALUE'] == 4492)){
                // Документ подписан - Ready
                
                if (count($prop['MW_PODPISANT']['VALUE']) > 0) {
                    $filter = array('ID' => $fields['CREATED_BY']);
                    $rsUsers = CUser::GetList(($by="id"), ($order="desc"), $filter);
                    $userEmails = array();
                    while ($arUser = $rsUsers->GetNext()) {
                        $userEmails[] = $arUser['EMAIL'];
                    }

                    $userEmails = implode(',', $userEmails);
                    //self::log(["type" => "text", "info" => $userEmails]);

                    $params = array(
                        'TITLE' => "Согласование документа №{$id}",
                        'MESSAGE_TEXT' => 'Документ подписан',
                        'EMAILS' => $userEmails
                    );

                    self::SendNotification_upd($params);
                }
            }
        }
    }

    function SendNotification_upd($params)
    {

        $eventName = "WORKFLOW_NOTIFY";

        $arFields = array(
            'FROM_EMAIL' => 'noreply@new.niioz.ru',
            //'EMAIL_TO' => 'ga@a-daru.ru, ga@no-ri.ru',
            'EMAIL_TO' => $params['EMAILS'],
            'TITLE' => $params['TITLE'],
            'MESSAGE_TEXT' => $params['MESSAGE_TEXT'],
        );

        $arrSite = 's1';

        $event = new CEvent;
        $event->SendImmediate($eventName, $arrSite, $arFields, "N", 95);

    }

    function getWorkflowProperties_upd($id, $iblock_id, $props)
    {
        $arSort = array("ID" => "ASC");

        $arFilter = array(
            "IBLOCK_ID" => $iblock_id,
            "ID" => $id,
        );

        $arSelected = array('*', 'PROPERTY_*');

        $mwElement = CIBlockElement::GetList(
            $arSort,
            $arFilter,
            false,
            false,
            $arSelected
        );

        $mwResult = [];
        while($mwElementFields = $mwElement->GetNextElement()){
            $props = $mwElementFields->GetProperties();
            return $props;

        }

        return false;
    }
    
    function getWorkflowFields_upd($id, $iblock_id, $props)
    {
        $arSort = array("ID" => "ASC");

        $arFilter = array(
            "IBLOCK_ID" => $iblock_id,
            "ID" => $id,
        );

        $arSelected = array('*', 'PROPERTY_*');

        $mwElement = CIBlockElement::GetList(
            $arSort,
            $arFilter,
            false,
            false,
            $arSelected
        );

        $mwResult = [];
        while($mwElementFields = $mwElement->GetNextElement()){
            $fields = $mwElementFields->GetFields();
            return $fields;

        }

        return false;
    }

    function log_upd($data)
    {
        $logPath = $_SERVER['DOCUMENT_ROOT'] . "/local/class/workflow.log";
        if ($data['type'] == 'text') {
            file_put_contents($logPath, date('Y-m-d H:i:s').": ".$data['info'].PHP_EOL, FILE_APPEND);
        }else{
            file_put_contents($logPath, var_export($data['info'], true) . PHP_EOL, FILE_APPEND);
        }
    }
}