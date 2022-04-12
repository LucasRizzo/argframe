<?php

class Controller {
    private $model;
    private $action;
    private $slimApp;
    private $requestBody;

    public function __construct($model, $action = null, $slimApp, $parameters = null) {
    //public function __construct($model, $action) {

        $this->model = $model;
        $this->action = $action;
        $this->slimApp = $slimApp;

        switch ($action) {
            case "click" :
                $this->handleClick ();
                break;
            case "logout" :
                $this->handleLogout ();
                break;
            case "clean" :
                $this->cleanSession ();
                break;
            case "export" :
                $this->exportResults ();
                break;
            //case "database" :
            //    $this->handleDataBase ();
            //    break;
            case "insert" :
                $this->insertFeatureset ($_POST);
                break;
            case "teste" :
                $this->insertTeste ($_POST);
                break;
            case "save" :
                $this->saveFeaturesetGraph ();
                break;
            case "update" :
                $this->updateFeaturesetGraph ();
                break;
            //case "compute":
            //    $this->handleDataBase ();
            //    break;
            case "newGraph":
                $this->createNewGraph($_POST);
                break;
            case "deleteGraph":
                $this->deleteGraph($_POST);
                break;
            case ACTION_GET_GRAPH : 
                $this->getGraph ($parameters);
                break;
            case ACTION_GET_ALL_GRAPHS :
                $this->getAllGraphs ($parameters);
                break;
            case ACTION_LOGIN :
                $this->handleLogin ();
                break;
            case ACTION_LOGIN_ERR :
                $this->handleLoginErr ();
                break;
            case ACTION_GET_ALL_FEATURESETS :
                $this->getAllFeaturesets ();
                break;
            case ACTION_GET_FEATURESET :
                $this->getFeatureset ($parameters["featureset"]);
                break;
            case ACTION_CREATE_FEATURESET :
                $this->requestBody = json_decode($this->slimApp->request->getBody(), true);
                $this->createFeatureset ($this->requestBody);
                break;
            case "insertjson" :
                $this->requestBody = json_decode($_POST["jsonfile"], true);
                $postForm = $this->postForm($this->requestBody);
                $this->model->insertFeatureset($postForm);
        }
    }

    private function deleteGraph($idGraph) {
        $this->model->deleteGraph($idGraph);
    }

    private function saveApiResponse($answer, $visualization) {
        if ($answer != null) {
            $this->slimApp->response()->setStatus ( HTTPSTATUS_OK );
            $this->model->apiResponse = $answer;
            $this->model->apiVisualization = $visualization;
        } else {
            $this->slimApp->response ()->setStatus ( HTTPSTATUS_NOTFOUND );
            $Message = array (
                    GENERAL_MESSAGE_LABEL => GENERAL_NOCONTENT_MESSAGE 
            );
            $this->model->apiResponse = $Message;
        }
    }

    private function getAllFeaturesets() {
        $answer = $this->model->getAllFeaturesets();
        $this->saveApiResponse($answer, VIEW_FEATURESET);
        if ($answer != null) {
            $answer = $this->model->getAllGraphs();
            if ($answer != null) {
                $this->model->apiResponseGraphs = $answer;
            }
        }
    }

    private function getFeatureset($featureset) {
        $answer = $this->model->getFeatureset($featureset);
        $this->saveApiResponse($answer, VIEW_FEATURESET);

        // Since only one feature set has been requested also its graphs will be
        // displayed/
        if ($answer != null) {
            $answer = $this->model->getFeaturesetAllGraphs ($featureset);
            if ($answer != null) {
                $this->model->apiResponseGraphs = $answer;
            }
        }
    }

    private function getAllGraphs() {
        $answer = $this->model->getAllGraphs();
        $this->saveApiResponse($answer, VIEW_GRAPH);
    }

    private function getFeaturesetAllGraphs($parameters) {
        $answer = $this->model->getFeaturesetAllGraphs ($parameters["featureset"]);
        $this->saveApiResponse($answer, VIEW_GRAPH);
    }

    private function getgraph($parameters) {
        $answer = $this->model->getGraph ($parameters["graphname"]);
        $this->saveApiResponse($answer, VIEW_GRAPH);
    }

    public function exportResults() {
        $objPHPExcel = $this->model->getXlsObject();

        // Write overall indexes
        $this->model->writeXlsData($objPHPExcel, 0);

        // Get tasks and groups from feature set
        $tasks = $this->model->getTasks();
        $groups = $this->model->getgroups();

        $activeSheet = 1;

        // Write indexes by task and group
        foreach($tasks as $t) {
            foreach ($groups as $g) {
                $objPHPExcel->createSheet();
                $this->model->writeXlsData($objPHPExcel, $activeSheet, $t, $g);
                $activeSheet++;
            }
        }

        // Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="WorkloadIndexes.xls"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');

        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
        header ('Cache-Control: cache, must-revalidate');
        header ('Pragma: public');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        ob_end_clean();
        $objWriter->save('php://output');
    }

    public function saveFeaturesetGraph() {

        // Build the json string of the graph according to the
        // post parameters
        $jsonEdges = $this->buildJasonEdges();
        $this->model->saveFeaturesetGraph($jsonEdges);
    }

    public function updateFeaturesetGraph() {
        // Build the json string of the graph according to the
        // post parameters
        $jsonEdges = $this->buildEditJasonEdges();
        if ($_POST["oldGraphName"] != "") {
            $this->model->updateFeaturesetGraph($jsonEdges);
        } else {
            $this->model->copyFeaturesetGraph($jsonEdges);
        }
    }

    public function buildEditJasonEdges() {

        $json = "[";

        for($i = 0; $i < count($_POST["editSourceLabel"]) - 1; $i++) {

            $json .= "{\"source\":\"" . $_POST["editSourceLabel"][$i] . "\",";
            $json .= "\"target\":\"" . $_POST["editTargetLabel"][$i] . "\",";
            $json .= "\"type\":\"" . $_POST["editTypeLabel"][$i] . "\"}";
            if ($i < count($_POST["editSourceLabel"]) - 2) {
                $json .= ",";
            }
        }

        $json .= "]";

        /* Example how to access the data:
         * $edges = json_decode($json);
         * $edges[0]->target --> target of the first edge
         * $edges[0]->source --> source of the first edge
        */

        return $json;
    }

    public function buildJasonEdges() {

        $json = "[";

        for($i = 0; $i < count($_POST["sourceLabel"]) - 1; $i++) {

            $json .= "{\"source\":\"" . $_POST["sourceLabel"][$i] . "\",";
            $json .= "\"target\":\"" . $_POST["targetLabel"][$i] . "\"}";
            if ($i < count($_POST["sourceLabel"]) - 2) {
                $json .= ",";
            }
        }

        $json .= "]";

        /* Example how to access the data:
         * $edges = json_decode($json);
         * $edges[0]->target --> target of the first edge
         * $edges[0]->source --> source of the first edge
        */

        return $json;
    }

    public function handleClick() {
        $this->model->str = "Data successfully updated";
    }

    public function handleLogin() {
        $this->model->logged = false;
    }

    public function handleLoginErr() {
        $this->model->logged = false;
        $this->model->loggedErr = "Wrong username or password!";
    }

    public function handleLogout() {
        session_unset (); // unset the session (and the $_SESSION)
        header ( "Location: index.php" ); // redirect to the index.php
    }

    public function getAction() {
        return ($this->action);
    }

    public function computeExpertSystem() {

        $ids = $this->model->getIDS();
        $inputs = array();

        foreach ($ids as $value) {
            $inputs[$value] = $this->model->getInputs($value);
        }

        $expertSystem = new ExpertSystem($ids, $inputs);
        foreach ($ids as $value) {
            $expertSystem->compute($value);
        }


        $this->model->indexes = $expertSystem->getIndexes();
        $this->model->reasoningsForecast = $expertSystem->getReasoningsForecast();
        $this->model->reasoningsNotForecast = $expertSystem->getReasoningsNotForecast();
        $this->model->reasoningsHeuristic = $expertSystem->getReasoningsHeuristic();
    }

    public function insertFeatureset($newFeatureset) {
        $this->model->insertFeatureset($newFeatureset);
    }

    public function insertTeste($newFeatureset) {
        $this->model->insertTeste($newFeatureset);
    }

    public function createNewGraph($newGraph) {
        $this->model->createNewGraph($newGraph);
    }

    public function createFeatureset($newFeatureset) {

        $postForm = $this->postForm($newFeatureset);

        $this->model->insertFeatureset($postForm);

        if ($this->model->error == "NULL") {
            $this->slimApp->response()->setStatus (HTTPSTATUS_CREATED);
            $Message = array (
                    GENERAL_MESSAGE_LABEL => GENERAL_RESOURCE_CREATED,
            );
            $this->model->apiResponse = $Message;
        } else {
            $this->slimApp->response ()->setStatus (HTTPSTATUS_BADREQUEST);
            $Message = array (
                    GENERAL_MESSAGE_LABEL => $this->model->error 
            );
            $this->model->apiResponse = $Message;
        }
    }

    public function postForm($newFeatureset) {
        /* Convert json form to post from
        /* JSON FORM
           {"featureset":"aaaqw",
            "attributes":[[{"name":"effort"},
                           {"range":"2"},
                           {"from":[{"value":"0"},
                                    {"value":"11"}]},
                            {"to":[{"value":"10"},
                                   {"value":"20"}]},
                            {"level":[{"value":"level1"},
                                      {"value":"level2"}]}],
                           [{"name":"motivation"},
                            {"range":"2"},
                            {"from":[{"value":"0"},
                                     {"value":"10"}]},
                            {"to":[{"value":"9"},
                                   {"value":"15"}]},
                            {"level":[{"value":"fraco"},
                                      {"value":"medio"}]}
                           ]
                         ],
            "conclusions":[[{"category":"underload"},
                            {"from":"0"},
                            {"to":"30"}],
                           [{"category":"fitting"},
                            {"from":"30"},
                            {"to":"60"}],
                           [{"category":"overload"},
                            {"from":"60"},
                            {"to":"100"}]]
            }

          POST FORM
          array(7) { ["featureset"]=> string(6) "grande"
                     ["attributename"]=> array(2) { [0]=> string(6) "effort"
                                                    [1]=> string(10) "motivation" }
                     ["nrange"]=> array(2) { [0]=> string(1) "2"
                                             [1]=> string(1) "2" }
                     ["attributefrom"]=> array(4) { [0]=> string(1) "0" 
                                                    [1]=> string(2) "11"
                                                    [2]=> string(1) "0"
                                                    [3]=> string(2) "10" }
                     ["attributeto"]=> array(4) { [0]=> string(2) "10"
                                                  [1]=> string(2) "20"
                                                  [2]=> string(1) "9"
                                                  [3]=> string(2) "15" }
                     ["attributelevel"]=> array(4) { [0]=> string(6) "level1"
                                                     [1]=> string(6) "level2"
                                                     [2]=> string(5) "fraco"
                                                     [3]=> string(5) "medio" }
                     ["conclusionname"]=> array(3) { [0]=> string(6) "underload"
                                                     [1]=> string(6) "fitting"
                                                     [2]=> string(5) "overload"}
                     ["conclusionfrom"]=> array(3) { [0]=> string(1) "0"
                                                     [1]=> string(2) "30"
                                                     [2]=> string(2) "60"}
                     ["conclusionto"]=> array(3) { [0]=> string(2) "30"
                                                     [1]=> string(2) "60"
                                                     [2]=> string(3) "100"} }*/

        $postForm["featureset"] = $newFeatureset["featureset"];

        $joinArray = 0;

        for ($i = 0; $i < count($newFeatureset["attributes"]); $i++) {
            $postForm["attributename"][$i] = $newFeatureset["attributes"][$i][0]["name"];
            $postForm["nrange"][$i] = $newFeatureset["attributes"][$i][1]["range"];

            for ($j = 0; $j < count($newFeatureset["attributes"][$i][2]["from"]); $j++) {
                $postForm["attributefrom"][$joinArray] = $newFeatureset["attributes"][$i][2]["from"][$j]["value"];
                $postForm["attributeto"][$joinArray] = $newFeatureset["attributes"][$i][3]["to"][$j]["value"];
                $postForm["attributelevel"][$joinArray] = $newFeatureset["attributes"][$i][4]["level"][$j]["value"];
                $joinArray++;
            }
        }

        for ($i = 0; $i < count($newFeatureset["conclusions"]); $i++) {
            $postForm["conclusionname"][$i] = $newFeatureset["conclusions"][$i][0]["category"];
            $postForm["conclusionfrom"][$i] = $newFeatureset["conclusions"][$i][1]["from"];
            $postForm["conclusionto"][$i] = $newFeatureset["conclusions"][$i][2]["to"];
        }

        return $postForm;
    }

}

?>

