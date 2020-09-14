<?php
include_once $_SERVER["DOCUMENT_ROOT"] . "/classes/factory.php";

$controller = Factory::getObject(Factory::TYPE_CONTROLLER);
switch ($_GET["source"]) {
    case "start":
        try {
            $controller->initNewGame($_GET['language']);
            echo json_encode(array("error" => 0, 'message' => "Game initialized"));
        } catch(Exception $e) {
            echo json_encode(array("error" => 1, "message" => $e->getMessage()));
        }
        break;
    case "init" :
        try {
            $currentValues = $controller->getCurrentGameValues();
            echo json_encode(array("error" => 0, 'data' => $currentValues));
        } catch(Exception $e) {
            echo json_encode(array("error" => 1, "message" => $e->getMessage()));
        }
        break;
}
