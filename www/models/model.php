<?php

class Model {
	
    public $str;
    public $login;
    public $logged = true;
    public $loggedErr;
    public $error;
    public $errorType;
    public $success;
    public $successType;

    public $featureset;
    public $featuresetHeader;

    public $currentGraph;
    public $currentFeatureset;

    public $indexes = array(array());
    public $attributes = array(array());
    public $reasoningsForecast = array();
    public $reasoningsNotForecast = array();
    public $reasoningsHeuristic = array(array());

    protected $DBManager = null;
    protected $dbLink = null;
    protected $dataSetDAO = null;

    public $apiResponse = "NULL";
    public $apiVisualization = "NULL";
    public $apiResponseGraphs = "NULL";
    public $apiResponseConclusions = "NULL";

    public function __construct() {
        $this->DBManager = new pdoDbManager ();
        $this->dbLink = $this->DBManager->openConnection ();
        $this->dataSetDAO = new DataSetDAO ($this->DBManager);
        $this->error = "NULL";
        $this->errorType = "NULL";
        $this->success = "NULL";
        $this->successType = "NULL";
    }

    public function __destruct() {
        $this->DBManager->closeConnection ();
    }

    public function updateData() {
        $this->str = "Hello World! Click here updated";
    }

    public function featuresets() {
        return ($this->dataSetDAO->featuresets());
    }

    public function featuresetsWithGraphs() {
        return ($this->dataSetDAO->featuresetsWithGraphs());
    }

    public function attributes() {
        return ($this->dataSetDAO->attributes());
    }

    public function attributesByFeatureset($featureset) {
        return ($this->dataSetDAO->attributesByFeatureset($featureset));
    }

    public function conclusionsByFeatureset($featureset) {
        return ($this->dataSetDAO->conclusionsByFeatureset($featureset));
    }

    public function levels() {
        return ($this->dataSetDAO->levels());
    }

    public function deleteGraph($idGraph) {
        $answer = $this->dataSetDAO->deleteGraph($idGraph);
        if ($answer == 0) {
            $this->success = "Graph not found!";
            $this->successType = "deleteGraph";
        } else {
            $this->success = "Graph deleted with success!";
            $this->successType = "deleteGraph";
        }
        return ($answer);
    }

    public function featuresetGraphs() {
        return ($this->dataSetDAO->featuresetGraphs());
    }

    public function featuresetArguments() {
        return ($this->dataSetDAO->featuresetArguments());
    }

    public function getGraph($graphname) {
        return ($this->dataSetDAO->getGraph($graphname));
    }

    public function getFeaturesetAllGraphs($featureset) {
        return ($this->dataSetDAO->getFeaturesetAllGraphs($featureset));
    }

    public function getAllGraphs() {
        return ($this->dataSetDAO->getAllGraphs());
    }
    
     public function getFeatureset($featureset) {
        return ($this->dataSetDAO->getFeatureset($featureset));
    }

    public function getAllFeaturesets() {
        return ($this->dataSetDAO->getAllFeaturesets());
    }
    
    public function allUserFeaturesets() {
        return ($this->dataSetDAO->allUserFeaturesets());
    }
    
    public function createNewGraph($newGraph) {
        $this->dataSetDAO->createNewGraph($newGraph);
        $this->success = "Graph created with success! Now working on graph <b>" . $newGraph["newGraph"] . ".</b>";
        $this->successType = "createGraph";
        $this->currentGraph = $newGraph["newGraph"];
        $this->currentFeatureset = $newGraph["featuresetNewGraph"];
    }

    public function saveFeaturesetGraph($jsonGraph) {
        //TODO: check graphs on the client side
        $this->dataSetDAO->insertFeaturesetGraph($jsonGraph);
        $this->success = "Graph created with success!";
        $this->successType = "createGraph";
    }

    public function updateFeaturesetGraph($jsonGraph) {
        //TODO: check Graphs on the client side
        $this->dataSetDAO->updateFeaturesetGraph($jsonGraph);
        $this->success = "Graph updated with success!";
        $this->successType = "createGraph";
        $this->currentGraph = $_POST["editGraphName"];
        $this->currentFeatureset = $_POST["editFeaturesetName"];
    }

    public function updateFeaturesetGraphEdges($jsonGraph) {
        //TODO: check Graphs on the client side
        $log = $this->dataSetDAO->updateFeaturesetGraphEdges($jsonGraph);
        $this->success = "Graph updated with success!";
        $this->successType = "createGraph";
        $this->currentGraph = $_POST["editGraphName"];
        $this->currentFeatureset = $_POST["editFeaturesetName"];

        return $log;
    }
    
    public function copyFeaturesetGraph($jsonGraph) {
        //TODO: check Graphs on the client side
        $this->dataSetDAO->createGraphCopy($jsonGraph);
        $this->success = "Graph copied with success!";
        $this->successType = "createGraph";
        $this->currentGraph = $_POST["copyNameGraph"];
        $this->currentFeatureset = $_POST["editFeaturesetName"];
    }

    public function insertTeste($newFeatureset) {

        var_dump($newFeatureset);

        //$this->dataSetDAO->insertDataSet($newDataSet);
        $this->success = "Feature set created with success!";
        $this->successType = "create";

        // Check if dataset name already existis
        /*$datasets = $this->dataSetDAO->getDatasets();
        foreach ($datasets as $d) {
            if ($newDataSet["dataset"] == $d) {
                $this->error = "Dataset " . $newDataSet["dataset"] . " already exists. Please try again.";
                $this->errorType = "create";
                return;
            }
        }

        // Check if there are no conclusions with the same name
        if (count(array_unique($newDataSet["conclusionname"])) < count($newDataSet["conclusionname"])) {
            $this->error = "Conclusions can not have the same category.";
            $this->errorType = "create";
            return;
        }

        // Check if there are no attributes with the same name
        if (count(array_unique($newDataSet["attributename"])) < count($newDataSet["attributename"])) {
            $this->error = "Two or more attributes with the same name. Please try again.";
            $this->errorType = "create";
            return;
        }

        // Check if there is no attribute with the same level or overlaping ranges
        $rangeStart = 0;
        foreach ($newDataSet["attributename"] as $key => $value) {

            for ($i = 0; $i < $newDataSet["nrange"][$key]; $i++) {
                for ($j = $i + 1; $j < $newDataSet["nrange"][$key]; $j++) {

                    if ($newDataSet["attributelevel"][$rangeStart + $j] == $newDataSet["attributelevel"][$rangeStart + $i]) {
                        $this->error = "Attribute " . $value . " has different ranges with same level. Please try again.";
                        $this->errorType = "create";
                        return;
                    }

                    /*if (floatval($newDataSet["attributefrom"][$rangeStart + $j]) <= floatval($newDataSet["attributeto"][$rangeStart + $i]) &&
                        floatval($newDataSet["attributefrom"][$rangeStart + $j]) >= floatval($newDataSet["attributefrom"][$rangeStart + $i])) {
                        $this->error = "Attribute " . $value . " has overlaping ranges. Please try again.";
                        $this->errorType = "create";
                        return;
                    }

                    if (floatval($newDataSet["attributeto"][$rangeStart + $j]) <= floatval($newDataSet["attributeto"][$rangeStart + $i]) &&
                        floatval($newDataSet["attributeto"][$rangeStart + $j]) >= floatval($newDataSet["attributefrom"][$rangeStart + $i])) {
                        $this->error = "Attribute " . $value . " has overlaping ranges. Please try again.";
                        $this->errorType = "create";
                        return;
                    }

                    if (floatval($newDataSet["attributefrom"][$rangeStart + $i]) <= floatval($newDataSet["attributeto"][$rangeStart + $j]) &&
                        floatval($newDataSet["attributefrom"][$rangeStart + $i]) >= floatval($newDataSet["attributefrom"][$rangeStart + $j])) {
                        $this->error = "Attribute " . $value . " has overlaping ranges. Please try again.";
                        $this->errorType = "create";
                        return;
                    }

                    if (floatval($newDataSet["attributeto"][$rangeStart + $i]) <= floatval($newDataSet["attributeto"][$rangeStart + $j]) &&
                        floatval($newDataSet["attributeto"][$rangeStart + $i]) >= floatval($newDataSet["attributefrom"][$rangeStart + $j])) {
                        $this->error = "Attribute " . $value . " has overlaping ranges. Please try again.";
                        $this->errorType = "create";
                        return;
                    }*/
              /*  }
            }

            $rangeStart += $newDataSet["nrange"][$key];
        }

        if ($this->error == "NULL") {
            $this->dataSetDAO->insertDataSet($newDataSet);
            $this->success = "Dataset created with success!";
            $this->successType = "create";
        }*/
    }

    public function insertFeatureset($newFeatureset) {

        // Check if Featureset name already existis
        $featuresets = $this->dataSetDAO->getFeaturesets();
        foreach ($featuresets as $d) {
            if ($newFeatureset["featureset"] == $d) {
                $this->error = "Feature set " . $newFeatureset["featureset"] . " already exists. Please try again.";
                $this->errorType = "create";
                return;
            }
        }

        // Check if there are no conclusions with the same name
        if (count(array_unique($newFeatureset["conclusionname"])) < count($newFeatureset["conclusionname"])) {
            $this->error = "Conclusions can not have the same category.";
            $this->errorType = "create";
            return;
        }

        // Check if there are no attributes with the same name
        if (count(array_unique($newFeatureset["attributename"])) < count($newFeatureset["attributename"])) {
            $this->error = "Two or more attributes with the same name. Please try again.";
            $this->errorType = "create";
            return;
        }
        
//         // Check if range match number of to, from and levels
//         foreach ($newFeatureset["attributename"] as $key => $value) {
//             if ($newFeatureset["nrange"][$key] != sizeof($newFeatureset["attributelevel"]) {
//                 $this->error = "Range value does not match number of levels";
//                 $this->errorType = "create";
//             }
//             
//             if ($newFeatureset["nrange"][$key] != sizeof($newFeatureset["attributefrom"]) {
//                 $this->error = "Range value does not match number of from";
//                 $this->errorType = "create";
//             }
//             
//             if ($newFeatureset["nrange"][$key] != sizeof($newFeatureset["attributeto"]) {
//                 $this->error = "Range value does not match number of from";
//                 $this->errorType = "create";
//             }
//         }

        // Check if there is no attribute with the same level or overlaping ranges
        $rangeStart = 0;
        foreach ($newFeatureset["attributename"] as $key => $value) {

            for ($i = 0; $i < $newFeatureset["nrange"][$key]; $i++) {
                for ($j = $i + 1; $j < $newFeatureset["nrange"][$key]; $j++) {

                    if ($newFeatureset["attributelevel"][$rangeStart + $j] == $newFeatureset["attributelevel"][$rangeStart + $i]) {
                        $this->error = "Attribute " . $value . " has different ranges with same level. Please try again.";
                        $this->errorType = "create";
                        return;
                    }

                    /*if (floatval($newFeatureset["attributefrom"][$rangeStart + $j]) <= floatval($newFeatureset["attributeto"][$rangeStart + $i]) &&
                        floatval($newFeatureset["attributefrom"][$rangeStart + $j]) >= floatval($newFeatureset["attributefrom"][$rangeStart + $i])) {
                        $this->error = "Attribute " . $value . " has overlaping ranges. Please try again.";
                        $this->errorType = "create";
                        return;
                    }

                    if (floatval($newFeatureset["attributeto"][$rangeStart + $j]) <= floatval($newFeatureset["attributeto"][$rangeStart + $i]) &&
                        floatval($newFeatureset["attributeto"][$rangeStart + $j]) >= floatval($newFeatureset["attributefrom"][$rangeStart + $i])) {
                        $this->error = "Attribute " . $value . " has overlaping ranges. Please try again.";
                        $this->errorType = "create";
                        return;
                    }

                    if (floatval($newFeatureset["attributefrom"][$rangeStart + $i]) <= floatval($newFeatureset["attributeto"][$rangeStart + $j]) &&
                        floatval($newFeatureset["attributefrom"][$rangeStart + $i]) >= floatval($newFeatureset["attributefrom"][$rangeStart + $j])) {
                        $this->error = "Attribute " . $value . " has overlaping ranges. Please try again.";
                        $this->errorType = "create";
                        return;
                    }

                    if (floatval($newFeatureset["attributeto"][$rangeStart + $i]) <= floatval($newFeatureset["attributeto"][$rangeStart + $j]) &&
                        floatval($newFeatureset["attributeto"][$rangeStart + $i]) >= floatval($newFeatureset["attributefrom"][$rangeStart + $j])) {
                        $this->error = "Attribute " . $value . " has overlaping ranges. Please try again.";
                        $this->errorType = "create";
                        return;
                    }*/
                }
            }

            $rangeStart += $newFeatureset["nrange"][$key];
        }

        if ($this->error == "NULL") {
            $this->dataSetDAO->insertFeatureset($newFeatureset);
            $this->success = "Feature set created with success!";
            $this->successType = "create";
        }
    }

    // FIXME: Implement a proper user system in the database and use a DAO
    // to manage it.
    public function getLoginUser() {

        //session_start (); // start a new session or reconnect to an existing one

        if (! empty ( $_SESSION ["username"] ) && ! empty ( $_SESSION ["password"] )) {
            return true;
        } else {
            return false;
        }
    }

    public function writeXlsData($objPHPExcel, $activeSheet, $task = null, $group = null) {

        $objPHPExcel->setActiveSheetIndex($activeSheet);

        // Get workload indexed that already saved in the Featureset
        $dataSetIndexes = $this->dataSetDAO->getDataSetIndexes($task, $group);

        // Dataset id column
        $columnId = "A";
        // Id column width
        $idWidth = 7;

        // Dataset task column
        $columnTask = "B";
        // Task column width
        $taskWidth = 7;

        // Dataset group column
        $columnGroup = "C";
        // Group column width
        $groupWidth = 7;

        // Dataset Nasa-TLX column
        $columnNasa = "D";
        // Nasa-TLX column width
        $nasaWidth = 15;

        // Dataset Workload Profile column
        $columnWP = "E";
        // Workload Profile column width
        $wpWidth = 15;

        // Columns for a maximum of 16 expert systems
        $columnES = array ("F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U");
        // Expert systems column width
        $esWidht = 15;

        // Freezes header
        $objPHPExcel->getActiveSheet()->freezePane("A2");

        // Spreadsheet will be written line by line
        $line = 1;

        // Style of the spreadsheet header
        $styleHeader = array('font' => array('bold' => true, 'size' => 9),
                             'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                                                  'vertical' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,),
                             'borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN,),));

        // Style of the spreadsheet body
        $styleBody = array('font' => array('size' => 9),
                            'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT),
                            'borders' => array('outline' => array('style' => PHPExcel_Style_Border::BORDER_THIN,),));

        // Apply header style
        $objPHPExcel->getActiveSheet()->getStyle($columnId . $line . ":" . $columnES[N_SYSTEMS - 1] . $line)
                                      ->applyFromArray($styleHeader);

        // Set header width for each cell of the header
        $objPHPExcel->getActiveSheet()->getColumnDimension($columnId)->setWidth($idWidth);
        $objPHPExcel->getActiveSheet()->getColumnDimension($columnTask)->setWidth($taskWidth);
        $objPHPExcel->getActiveSheet()->getColumnDimension($columnGroup)->setWidth($groupWidth);
        $objPHPExcel->getActiveSheet()->getColumnDimension($columnNasa)->setWidth($nasaWidth);
        $objPHPExcel->getActiveSheet()->getColumnDimension($columnWP)->setWidth($wpWidth);

        $es = 0;
        while ($es < N_SYSTEMS) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($columnES[$es])->setWidth($esWidht);
            $es++;
        }

        // Set header content
        $objPHPExcel->getActiveSheet()->setCellValue($columnId . $line, "ID");
        $objPHPExcel->getActiveSheet()->setCellValue($columnTask . $line, "Task");
        $objPHPExcel->getActiveSheet()->setCellValue($columnGroup . $line, "Group");
        $objPHPExcel->getActiveSheet()->setCellValue($columnNasa . $line, "Nasa-TLX");
        $objPHPExcel->getActiveSheet()->setCellValue($columnWP . $line, "Workload Profile");

        $nEs = 1;
        while ($nEs <= N_SYSTEMS) {
            $objPHPExcel->getActiveSheet()->setCellValue($columnES[$nEs - 1] . $line, "Expert System " . $nEs);
            $nEs ++;
        }

        $line++;

        // Fill spreadsheet body
        foreach ($dataSetIndexes as $recordNumber => $row) {

            $objPHPExcel->getActiveSheet()->setCellValue($columnId . $line, $row["id"]);
            $objPHPExcel->getActiveSheet()->setCellValue($columnTask . $line, $row["task"]);
            $objPHPExcel->getActiveSheet()->setCellValue($columnGroup . $line, $row["experimentPart"]);
            $objPHPExcel->getActiveSheet()->setCellValue($columnNasa . $line, $row["Nasa_Computation"]);
            $objPHPExcel->getActiveSheet()->setCellValue($columnWP . $line, $row["WP_computationnorm"]);

            // Calculate expert system for the current dataset id
            //echo "<br>$$$ " . $row["id"] . " $$$<br>";
            $expertSystem = new ExpertSystem($this->getInputs($row["id"]));

            $expertSystemWorkload = $expertSystem->getAllIndexes();

            $es = 0;

            while ($es < N_SYSTEMS) {

                $objPHPExcel->getActiveSheet()->setCellValue($columnES[$es] . $line, $expertSystemWorkload[$es]);
                $es++;
            }

            // Go to the next line
            $line++;
        }

        // Save body last line
        $line--;

        // Apply body style
        $objPHPExcel->getActiveSheet()->getStyle($columnId .  "2:" . $columnES[N_SYSTEMS - 1] . $line)
                                      ->applyFromArray($styleBody);


        // Rename worksheet
        if($task == null && $group == null) {
            $objPHPExcel->getActiveSheet()->setTitle('Overall workload');
        } else {
            $objPHPExcel->getActiveSheet()->setTitle("Task " . $task . " Group " . $group);
        }

        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);
    }

}

?>
