<?php

class LucaModel extends Model {
	
    public function getXlsObject () {

        $objPHPExcel = new PHPExcel();

        // Set document properties
        $objPHPExcel->getProperties()->setCreator("Lucas Rizzo")
                                     ->setLastModifiedBy("Lucas Rizzo")
                                     ->setTitle("Workload Indexes")
                                     ->setSubject("Workload Indexes")
                                     ->setDescription("Computed workload indexes for different measures");

        return ($objPHPExcel);
    }

    public function writeXlsData($objPHPExcel, $activeSheet, $task = null, $group = null) {

        $objPHPExcel->setActiveSheetIndex($activeSheet);

        // Get workload indexed that already saved in the dataset
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
            $expertSystem = new oldExpertSystem($this->getInputs($row["id"]));

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

        if ($activeSheet == 0) {
            $objCommentRichText = $objPHPExcel->getActiveSheet()->getComment('A1')->getText()->createTextRun('PHPExcel:');
            $objCommentRichText->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->getComment('A1')->getText()->createTextRun("\r\n");
            $objPHPExcel->getActiveSheet()->getComment('A1')->getText()->createTextRun('Total amount on the current invoice, excluding VAT.');
        }
    }

    public function prepareDataSet() {
        $this->dataSet = $this->dataSetDAO->get();
        $this->dataSetHeader = array('id',
                                     'task',
                                     'NEW_TASK_WI',
                                     'Objective time (seconds)',
                                     'experimentPart',
                                     'userId',
                                     'mental',
                                     'temporal',
                                     'psychological',
                                     'effort',
                                     'performance',
                                     'bias',
                                     'knowledge',
                                     'skill',
                                     'intention',
                                     'parallelism',
                                     'arousal',
                                     'central',
                                     'response',
                                     'spatial proc',
                                     'verbal',
                                     'visual',
                                     'auditory',
                                     'manual',
                                     'speech',
                                     'physical',
                                     'Nasa Computation',
                                     'WP computation(norm)',
                                     'Row-Nasa');
    }

    public function getTasks() {
        $tasks =  $this->dataSetDAO->getTasks();

        $taskArray;

        $i = 0;
        foreach ($tasks as $useless) {
            $taskArray[$i] = $tasks[$i]["task"];
            $i++;
        }

        return $taskArray;
    }

    public function getGroups() {
        $groups = $this->dataSetDAO->getGroups();


        $groupArray;

        $i = 0;
        foreach ($groups as $useless) {
            $groupArray[$i] = $groups[$i]["experimentPart"];
            $i++;
        }

        return $groupArray;
    }

    // Given a specific id return the necessary fields for computing the
    // expert system
    public function getInputs($id) {

        return ($this->dataSetDAO->getInputs($id));
    }

  public function getIDS() {

        return ($this->dataSetDAO->getIDS());
    }

    public function getUser($id) {
        $query = 'SELECT * FROM luca_thesis WHERE id = $id';

        $result = mysql_query($query) or die('Query failed: ' . mysql_error());

        return $result;
    }
}