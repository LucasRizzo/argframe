<?php

class DataSetDAO {
    private $dbManager;

    function DataSetDAO($DBMngr) {
        $this->dbManager = $DBMngr;
    }

    public function deleteComputations($user) {
         // Remove previous computations
        $sql = "DELETE FROM computations WHERE user = ?";
        $stmt = $this->dbManager->prepareQuery ($sql);
        $this->dbManager->bindValue ($stmt, 1, $user, $this->dbManager->STRING_TYPE);
        $this->dbManager->executeQuery ($stmt);
    }

    public function saveComputations($data, $user) {

        $sql = " INSERT INTO computations (extensions, user) VALUES ";
        $sql .= "(?, ?);";

        $stmt = $this->dbManager->prepareQuery ($sql);

        $this->dbManager->bindValue($stmt, 1, $data, $this->dbManager->STRING_TYPE);
        $this->dbManager->bindValue($stmt, 2, $user, $this->dbManager->STRING_TYPE);

        $this->dbManager->executeQuery ($stmt);
    }

    public function getComputations($user) {

        $sql = "SELECT extensions ";
        $sql .= "FROM computations ";
        $sql .= "WHERE user = ?";

        $stmt = $this->dbManager->prepareQuery ($sql);
        $this->dbManager->bindValue ($stmt, 1, $user, $this->dbManager->STRING_TYPE);
        $this->dbManager->executeQuery ($stmt);
        $rows = $this->dbManager->fetchResults ($stmt);

        return ($rows);
    }

    public function getGraph($graphname) {

        $graphname = "%" . $graphname . "%";

        $sql = "SELECT * ";
        $sql .= "FROM graphs ";
        $sql .= "WHERE name LIKE ?";

        $stmt = $this->dbManager->prepareQuery ( $sql );
        $this->dbManager->bindValue ( $stmt, 1, $graphname, $this->dbManager->STRING_TYPE );
        $this->dbManager->executeQuery ( $stmt );
        $rows = $this->dbManager->fetchResults ( $stmt );

        return ($rows);
    }

    public function getFeaturesetAllGraphs($featureset) {

        $featureset = "%" . $featureset . "%";

        $sql = "SELECT * ";
        $sql .= "FROM graphs ";
        $sql .= "WHERE featureset LIKE ? ";
        $sql .= "ORDER BY name";

        $stmt = $this->dbManager->prepareQuery ($sql);
        $this->dbManager->bindValue ($stmt, 1, $featureset, $this->dbManager->STRING_TYPE);
        $this->dbManager->executeQuery ($stmt);
        $rows = $this->dbManager->fetchResults ($stmt);

        return ($rows);
    }

    public function getFeatureset($featureset) {

        $featureset = "%" . $featureset . "%";

        $sql = "SELECT DISTINCT * ";
        $sql .= "FROM attributes ";
        $sql .= "WHERE featureset LIKE ? ";
        $sql .= "ORDER BY featureset, attribute";

        $stmt = $this->dbManager->prepareQuery ($sql);
        $this->dbManager->bindValue ($stmt, 1, $featureset, $this->dbManager->STRING_TYPE);
        $this->dbManager->executeQuery ($stmt);
        $rows = $this->dbManager->fetchResults ($stmt);

        return ($rows);
    }
    
    public function getFeaturesetsNames() {
        $sql = "SELECT DISTINCT featureset ";
        $sql .= "FROM user_featureset ";
        $sql .= "WHERE email = ?";

        $stmt = $this->dbManager->prepareQuery ($sql);
        $this->dbManager->bindValue ($stmt, 1, $_SESSION["username"], $this->dbManager->STRING_TYPE);
        $this->dbManager->executeQuery ($stmt);
        $rows = $this->dbManager->fetchResults ($stmt);
        
        $featuresets = "";
        foreach ($rows as $key => $value) {
            $featuresets .= $value["featureset"] . ",";
        }
        
        $featuresets = rtrim($featuresets, ",");

        return ($featuresets);
    }
    
    public function newFeatureset($featuresetName) {
        
        $sql = " INSERT INTO user_featureset (email, featureset) VALUES ";
        $sql .= "(?, ?);";

        $stmt = $this->dbManager->prepareQuery ($sql);
        $this->dbManager->bindValue ($stmt, 1, $_SESSION["username"], $this->dbManager->STRING_TYPE);
        $this->dbManager->bindValue ($stmt, 2, $featuresetName, $this->dbManager->STRING_TYPE);
        $this->dbManager->executeQuery ($stmt);

        return ("ok");
    }
    
    
    public function copyFeatureset($featuresetNameNew, $featuresetNameOld) {
        
        // Create new name in the user_featureset table
        $sql = " INSERT INTO user_featureset (email, featureset) VALUES ";
        $sql .= "(?, ?);";

        $stmt = $this->dbManager->prepareQuery ($sql);
        $this->dbManager->bindValue ($stmt, 1, $_SESSION["username"], $this->dbManager->STRING_TYPE);
        $this->dbManager->bindValue ($stmt, 2, $featuresetNameNew, $this->dbManager->STRING_TYPE);
        $this->dbManager->executeQuery ($stmt);
        
        // Copy attributes   
        $sql = "INSERT INTO attributes(attribute, featureset, a_level, a_from, a_to) ";
        $sql .= "SELECT attribute, ?, a_level, a_from, a_to FROM attributes WHERE featureset = ?"; 
        $stmt = $this->dbManager->prepareQuery ($sql);
        $this->dbManager->bindValue ($stmt, 1, $featuresetNameNew, $this->dbManager->STRING_TYPE);
        $this->dbManager->bindValue ($stmt, 2, $featuresetNameOld, $this->dbManager->STRING_TYPE);
        $this->dbManager->executeQuery ($stmt);

        // Copy conclusions        
        $sql = "INSERT INTO conclusions(featureset, conclusion, c_from, c_to) ";
        $sql .= "SELECT ?, conclusion, c_from, c_to FROM conclusions WHERE featureset = ?"; 
        $stmt = $this->dbManager->prepareQuery ($sql);
        $this->dbManager->bindValue ($stmt, 1, $featuresetNameNew, $this->dbManager->STRING_TYPE);
        $this->dbManager->bindValue ($stmt, 2, $featuresetNameOld, $this->dbManager->STRING_TYPE);
        $this->dbManager->executeQuery ($stmt);
        return ("ok");
    }
    
    
    public function renameFeatureset($featuresetNameNew, $featuresetNameOld) {
        
        // Update user feature set
        $sql = " UPDATE user_featureset SET featureset = ? WHERE featureset = ? AND email = ?";
        $stmt = $this->dbManager->prepareQuery ($sql);
        $this->dbManager->bindValue ($stmt, 1, $featuresetNameNew, $this->dbManager->STRING_TYPE);
        $this->dbManager->bindValue ($stmt, 2, $featuresetNameOld, $this->dbManager->STRING_TYPE);
        $this->dbManager->bindValue ($stmt, 3, $_SESSION["username"], $this->dbManager->STRING_TYPE);
        $this->dbManager->executeQuery ($stmt);
        
        // Copy attributes   
        $sql = "UPDATE attributes SET featureset = ? WHERE featureset = ?"; 
        $stmt = $this->dbManager->prepareQuery ($sql);
        $this->dbManager->bindValue ($stmt, 1, $featuresetNameNew, $this->dbManager->STRING_TYPE);
        $this->dbManager->bindValue ($stmt, 2, $featuresetNameOld, $this->dbManager->STRING_TYPE);
        $this->dbManager->executeQuery ($stmt);
        
        // Copy arguments   
        $sql = "UPDATE arguments SET featureset = ? WHERE featureset = ?"; 
        $stmt = $this->dbManager->prepareQuery ($sql);
        $this->dbManager->bindValue ($stmt, 1, $featuresetNameNew, $this->dbManager->STRING_TYPE);
        $this->dbManager->bindValue ($stmt, 2, $featuresetNameOld, $this->dbManager->STRING_TYPE);
        $this->dbManager->executeQuery ($stmt);

        // Copy conclusions        
        $sql = "UPDATE conclusions SET featureset = ? WHERE featureset = ?"; 
        $stmt = $this->dbManager->prepareQuery ($sql);
        $this->dbManager->bindValue ($stmt, 1, $featuresetNameNew, $this->dbManager->STRING_TYPE);
        $this->dbManager->bindValue ($stmt, 2, $featuresetNameOld, $this->dbManager->STRING_TYPE);
        $this->dbManager->executeQuery ($stmt);
        
        // Copy graphs  
        $sql = "UPDATE graphs SET featureset = ? WHERE featureset = ?"; 
        $stmt = $this->dbManager->prepareQuery ($sql);
        $this->dbManager->bindValue ($stmt, 1, $featuresetNameNew, $this->dbManager->STRING_TYPE);
        $this->dbManager->bindValue ($stmt, 2, $featuresetNameOld, $this->dbManager->STRING_TYPE);
        $this->dbManager->executeQuery ($stmt);
        return ("ok");
    }
    
    public function getFeature($featureset, $featureName, $featureLevel) {

        $sql = "SELECT * ";
        $sql .= "FROM attributes ";
        $sql .= "WHERE featureset = ? AND attribute = ? AND a_level = ?;";

        $stmt = $this->dbManager->prepareQuery ($sql);
        $this->dbManager->bindValue ($stmt, 1, $featureset, $this->dbManager->STRING_TYPE);
        $this->dbManager->bindValue ($stmt, 2, $featureName, $this->dbManager->STRING_TYPE);
        $this->dbManager->bindValue ($stmt, 3, $featureLevel, $this->dbManager->STRING_TYPE);
        $this->dbManager->executeQuery ($stmt);
        $rows = $this->dbManager->fetchResults ($stmt);

        return ($rows);
    }
    
    public function getConclusion($featureset, $conclusion) {

        $sql = "SELECT * ";
        $sql .= "FROM conclusions ";
        $sql .= "WHERE featureset = ? AND conclusion = ?;";

        $stmt = $this->dbManager->prepareQuery ($sql);
        $this->dbManager->bindValue ($stmt, 1, $featureset, $this->dbManager->STRING_TYPE);
        $this->dbManager->bindValue ($stmt, 2, $conclusion, $this->dbManager->STRING_TYPE);
        $this->dbManager->executeQuery ($stmt);
        $rows = $this->dbManager->fetchResults ($stmt);

        return ($rows);
    }
    
    public function getArgumentGivenFeature($featureset, $featureName, $featureLevel) {
        
        
        $premise = "%\"" . $featureLevel . " " . $featureName . "\"%";
        
        $sql = "SELECT * ";
        $sql .= "FROM arguments ";
        $sql .= "WHERE featureset = ? AND argument LIKE ?;";

        $stmt = $this->dbManager->prepareQuery ($sql);
        $this->dbManager->bindValue ($stmt, 1, $featureset, $this->dbManager->STRING_TYPE);
        $this->dbManager->bindValue ($stmt, 2, $premise, $this->dbManager->STRING_TYPE);
        $this->dbManager->executeQuery ($stmt);
        $rows = $this->dbManager->fetchResults ($stmt);

        return ($rows);
    }
    
    public function getArgumentGivenConclusion($featureset, $conclusion) {
        
        
        $conclusion = "%" . $conclusion . "%";
        
        $sql = "SELECT * ";
        $sql .= "FROM arguments ";
        $sql .= "WHERE featureset = ? AND conclusion LIKE ?;";

        $stmt = $this->dbManager->prepareQuery ($sql);
        $this->dbManager->bindValue ($stmt, 1, $featureset, $this->dbManager->STRING_TYPE);
        $this->dbManager->bindValue ($stmt, 2, $conclusion, $this->dbManager->STRING_TYPE);
        $this->dbManager->executeQuery ($stmt);
        $rows = $this->dbManager->fetchResults ($stmt);

        return ($rows);
    }

    public function deleteFeatureset($featureset) {
        // Delete from user feature set
        $sql = " DELETE FROM user_featureset WHERE featureset = ? AND email = ?";
        $stmt = $this->dbManager->prepareQuery ($sql);
        $this->dbManager->bindValue ($stmt, 1, $featureset, $this->dbManager->STRING_TYPE);
        $this->dbManager->bindValue ($stmt, 2, $_SESSION["username"], $this->dbManager->STRING_TYPE);
        $this->dbManager->executeQuery ($stmt);
        
        // Delete from attributes   
        $sql = "DELETE FROM attributes WHERE featureset = ?"; 
        $stmt = $this->dbManager->prepareQuery ($sql);
        $this->dbManager->bindValue ($stmt, 1, $featureset, $this->dbManager->STRING_TYPE);
        $this->dbManager->executeQuery ($stmt);
        
        // Delete from arguments   
        $sql = "DELETE FROM arguments WHERE featureset = ?"; 
        $stmt = $this->dbManager->prepareQuery ($sql);
        $this->dbManager->bindValue ($stmt, 1, $featureset, $this->dbManager->STRING_TYPE);
        $this->dbManager->executeQuery ($stmt);

        // Delete from conclusions        
        $sql = "DELETE FROM conclusions WHERE featureset = ?"; 
        $stmt = $this->dbManager->prepareQuery ($sql);
        $this->dbManager->bindValue ($stmt, 1, $featureset, $this->dbManager->STRING_TYPE);
        $this->dbManager->executeQuery ($stmt);
        
        // Delete from graphs  
        $sql = "DELETE FROM graphs WHERE featureset = ?"; 
        $stmt = $this->dbManager->prepareQuery ($sql);
        $this->dbManager->bindValue ($stmt, 1, $featureset, $this->dbManager->STRING_TYPE);
        $this->dbManager->executeQuery ($stmt);
        return ("ok");
    }

    public function getAllFeaturesets() {
        $sql = "SELECT * ";
        $sql .= "FROM attributes ";
        $sql .= "ORDER BY featureset, attribute";

        $stmt = $this->dbManager->prepareQuery ($sql);
        $this->dbManager->executeQuery ($stmt);
        $rows = $this->dbManager->fetchResults ($stmt);

        return ($rows);
    }

    public function getAllGraphs() {
        $sql = "SELECT * ";
        $sql .= "FROM graphs ";
        $sql .= "ORDER BY featureset, name";

        $stmt = $this->dbManager->prepareQuery ($sql);
        $this->dbManager->executeQuery ($stmt);
        $rows = $this->dbManager->fetchResults ($stmt);

        return ($rows);
    }


    public function insertFeatureset($newFeatureset) {

        $sql = "";

        // Define initial position of range array for each attribute
        $rangeStart = 0;
        foreach ($newFeatureset["attributename"] as $key => $value) {

            for ($i = 0; $i < $newFeatureset["nrange"][$key]; $i++) {
                //$sql .= " INSERT INTO ? (attribute, from, to, level) VALUES ";
                $sql .= " INSERT INTO attributes (attribute, featureset, a_level, a_from, a_to) VALUES ";
                $sql .= "(?, ?, ?, ?, ?);";
            }

            $rangeStart += $newFeatureset["nrange"][$key];
        }

        $stmt = $this->dbManager->prepareQuery ($sql);

        $bindPosition = 1;
        $rangeStart = 0;
        foreach ($newFeatureset["attributename"] as $key => $value) {
            for ($i = 0; $i < $newFeatureset["nrange"][$key]; $i++) {
                //$this->dbManager->bindValue ( $stmt, $bindPosition, $_POST["featureset"] . "_ranges", $this->dbManager->STRING_TYPE );
                //$bindPosition++;
                $this->dbManager->bindValue($stmt, $bindPosition, $value, $this->dbManager->STRING_TYPE);
                $bindPosition++;
                $this->dbManager->bindValue($stmt, $bindPosition, $newFeatureset["featureset"], $this->dbManager->STRING_TYPE);
                $bindPosition++;
                $this->dbManager->bindValue($stmt, $bindPosition, $newFeatureset["attributelevel"][$rangeStart + $i], $this->dbManager->STRING_TYPE);
                $bindPosition++;
                $this->dbManager->bindValue($stmt, $bindPosition, $newFeatureset["attributefrom"][$rangeStart + $i], $this->dbManager->STRING_TYPE);
                $bindPosition++;
                $this->dbManager->bindValue($stmt, $bindPosition, $newFeatureset["attributeto"][$rangeStart + $i], $this->dbManager->STRING_TYPE);
                $bindPosition++;
            }

            $rangeStart += $newFeatureset["nrange"][$key];
        }

        $this->dbManager->executeQuery ($stmt);

        $sql = "";

        // Define initial position of range array for each attribute
        foreach ($newFeatureset["conclusionname"] as $key => $value) {
            //$sql .= " INSERT INTO ? (attribute, from, to, level) VALUES ";
            $sql .= " INSERT INTO conclusions (featureset, conclusion, c_from, c_to) VALUES ";
            $sql .= "(?, ?, ?, ?);";
        }

        $stmt = $this->dbManager->prepareQuery ($sql);

        $bindPosition = 1;
        foreach ($newFeatureset["conclusionname"] as $key => $value) {
            $this->dbManager->bindValue($stmt, $bindPosition, $newFeatureset["featureset"], $this->dbManager->STRING_TYPE);
            $bindPosition++;
            $this->dbManager->bindValue($stmt, $bindPosition, $value, $this->dbManager->STRING_TYPE);
            $bindPosition++;
            $this->dbManager->bindValue($stmt, $bindPosition, $newFeatureset["conclusionfrom"][$key], $this->dbManager->STRING_TYPE);
            $bindPosition++;
            $this->dbManager->bindValue($stmt, $bindPosition, $newFeatureset["conclusionto"][$key], $this->dbManager->STRING_TYPE);
            $bindPosition++;
        }

        $this->dbManager->executeQuery ($stmt);

        $sql = " INSERT INTO user_featureset (email, featureset) VALUES ";
        $sql .= "(?, ?);";

        $stmt = $this->dbManager->prepareQuery ($sql);

        $this->dbManager->bindValue($stmt, 1, $_SESSION["username"], $this->dbManager->STRING_TYPE);
        $this->dbManager->bindValue($stmt, 2, $newFeatureset["featureset"], $this->dbManager->STRING_TYPE);

        $this->dbManager->executeQuery ($stmt);
    }

    public function featuresetGraphs() {
        $sql = 'SELECT featureset, name, edges, font_size FROM graphs WHERE featureset ' .
               'IN (SELECT DISTINCT featureset FROM user_featureset WHERE email = "' . $_SESSION["username"] . '") ORDER BY name;';
        $stmt = $this->dbManager->prepareQuery ($sql);
        $this->dbManager->executeQuery ($stmt);
        $featuresetGraphs = $this->dbManager->fetchResults($stmt);
        return $featuresetGraphs;
    }

    public function featuresetArguments() {
        $sql = 'SELECT argument, conclusion, x, y, label, graph, featureset, weight FROM arguments WHERE featureset '  .
               'IN (SELECT DISTINCT featureset FROM user_featureset WHERE email = "' . $_SESSION["username"] . '");';
        $stmt = $this->dbManager->prepareQuery ($sql);
        $this->dbManager->executeQuery ($stmt);
        $featuresetArguments = $this->dbManager->fetchResults($stmt);
        return $featuresetArguments;
    }

    public function createNewGraph($newGraph) {

        $sql = "";
        $sql .= " INSERT INTO graphs (featureset, name, edges, font_size) VALUES ";
        $sql .= "(?, ?, ?, ?);";

        $stmt = $this->dbManager->prepareQuery ($sql);
        $this->dbManager->bindValue($stmt, 1, $_POST["featuresetNewGraph"], $this->dbManager->STRING_TYPE);
        $this->dbManager->bindValue($stmt, 2, $_POST["newGraph"], $this->dbManager->STRING_TYPE);
        // Empty edges
        $this->dbManager->bindValue($stmt, 3, "[]", $this->dbManager->STRING_TYPE);
        // Font size 30 initially
        $this->dbManager->bindValue($stmt, 4, "30", $this->dbManager->STRING_TYPE);

        $this->dbManager->executeQuery ($stmt);
    }

    public function insertFeaturesetGraph($jsonEdges) {

        // Insert arguments
        $sql = "";
        foreach ($_POST["argument"] as $key => $value) {
            // The last position of argument is empty because it is the template filled by
            // the javascript
            if($value != "") {
                $sql .= " INSERT INTO arguments (argument, conclusion, x, y, label, graph, featureset) VALUES ";
                $sql .= "(?, ?, ?, ?, ?, ?, ?);";
            }
        }

        $stmt = $this->dbManager->prepareQuery ($sql);
        $bindPosition = 1;
        foreach ($_POST["argument"] as $key => $value) {
            // The last position of argument is empty because it is the template filled by
            // the javascript
            if($value != "") {
                $this->dbManager->bindValue($stmt, $bindPosition, $value, $this->dbManager->STRING_TYPE);
                $bindPosition++;
                $this->dbManager->bindValue($stmt, $bindPosition, $_POST["conclusion"][$key], $this->dbManager->STRING_TYPE);
                $bindPosition++;
                $this->dbManager->bindValue($stmt, $bindPosition, $_POST["x"][$key], $this->dbManager->STRING_TYPE);
                $bindPosition++;
                $this->dbManager->bindValue($stmt, $bindPosition, $_POST["y"][$key], $this->dbManager->STRING_TYPE);
                $bindPosition++;
                $this->dbManager->bindValue($stmt, $bindPosition, $_POST["label"][$key], $this->dbManager->STRING_TYPE);
                $bindPosition++;
                $this->dbManager->bindValue($stmt, $bindPosition, $_POST["graphName"], $this->dbManager->STRING_TYPE);
                $bindPosition++;
                $this->dbManager->bindValue($stmt, $bindPosition, $_POST["featuresetName"], $this->dbManager->STRING_TYPE);
                $bindPosition++;
            }
        }

        $this->dbManager->executeQuery ($stmt);

        $sql = "";
        $sql .= " INSERT INTO graphs (featureset, name, edges) VALUES ";
        $sql .= "(?, ?, ?);";

        $stmt = $this->dbManager->prepareQuery ($sql);

        $this->dbManager->bindValue($stmt, 1, $_POST["featuresetName"], $this->dbManager->STRING_TYPE);
        $this->dbManager->bindValue($stmt, 2, $_POST["graphName"], $this->dbManager->STRING_TYPE);
        $this->dbManager->bindValue($stmt, 3, $jsonEdges, $this->dbManager->STRING_TYPE);

        $this->dbManager->executeQuery ($stmt);
    }
    
    
    public function updateFeature($featureset, $featureOld, $featureNew) {
    
        // $featureset is a string
        // $featureOld and $featureNew structure
        // feature[0] = name
        // feature[1] = level
        // feature[2] = from
        // feature[3] = to
        
        // Changing name and keeping level
        if ($featureOld[0] != $featureNew[0] && $featureOld[1] == $featureNew[1]){
            $db_feature = $this->getFeature($featureset, $featureNew[0], $featureNew[1]);
            // Feature name already exisit
            if (sizeof($db_feature) > 0) {
                return ("Cannot update! Feature name already defined with this level in this feature set.");
            }
        }
        
        // Changing level and keeping name
        if ($featureOld[0] == $featureNew[0] && $featureOld[1] != $featureNew[1]){
            $db_feature = $this->getFeature($featureset, $featureNew[0], $featureNew[1]);
            // Feature name already exisit
            if (sizeof($db_feature) > 0) {
                return ("Cannot update! Level already defined for this feature in this feature set.");
            }
        }
    
        $sql =  "UPDATE attributes SET attribute = ?, featureset = ?, a_level = ?, a_from = ?, a_to = ? ";
        $sql .= "WHERE attribute = ? AND featureset = ? AND a_level = ?;";
        $stmt = $this->dbManager->prepareQuery ($sql);
        $this->dbManager->bindValue($stmt, 1, $featureNew[0], $this->dbManager->STRING_TYPE);
        $this->dbManager->bindValue($stmt, 2, $featureset, $this->dbManager->STRING_TYPE);
        $this->dbManager->bindValue($stmt, 3, $featureNew[1], $this->dbManager->STRING_TYPE);
        $this->dbManager->bindValue($stmt, 4, $featureNew[2], $this->dbManager->STRING_TYPE);
        $this->dbManager->bindValue($stmt, 5, $featureNew[3], $this->dbManager->STRING_TYPE);
        $this->dbManager->bindValue($stmt, 6, $featureOld[0], $this->dbManager->STRING_TYPE);
        $this->dbManager->bindValue($stmt, 7, $featureset, $this->dbManager->STRING_TYPE);
        $this->dbManager->bindValue($stmt, 8, $featureOld[1], $this->dbManager->STRING_TYPE);
        $this->dbManager->executeQuery ($stmt);
        
        
        // If changing names or levels it is necessary to update the graphs
        if ($featureOld[0] != $featureNew[0] || $featureOld[1] != $featureNew[1]){
            $arguments = $this->getArgumentGivenFeature($featureset, $featureOld[0], $featureOld[1]);
            foreach ($arguments as $key => $value) {
                
                $newArgument = str_replace("\"$featureOld[1] $featureOld[0]\"", "\"$featureNew[1] $featureNew[0]\"", $value["argument"]);
                //var_dump($newArgument);
                
                $sql = "UPDATE arguments SET argument = ?";
                $sql .= "WHERE id = ?;";
                
                $stmt = $this->dbManager->prepareQuery ($sql);
                $this->dbManager->bindValue($stmt, 1, $newArgument, $this->dbManager->STRING_TYPE);
                $this->dbManager->bindValue($stmt, 2, $value["id"], $this->dbManager->STRING_TYPE);
                $this->dbManager->executeQuery ($stmt);
            }
        }
        
        return ("ok");  
    }
    
    public function createFeature($featureset, $feature) {
    
        // $featureset is a string
        // $feature structure
        // feature[0] = name
        // feature[1] = level
        // feature[2] = from
        // feature[3] = to
        
        $db_feature = $this->getFeature($featureset, $feature[0], $feature[1]);
        // Feature name already exisit
        if (sizeof($db_feature) > 0) {
            return ("Cannot create feature! Feature name and level already defined in this feature set.");
        }
        
        $sql = " INSERT INTO attributes (attribute, featureset, a_level, a_from, a_to) VALUES ";
        $sql .= "(?, ?, ?, ?, ?);";
    
        $stmt = $this->dbManager->prepareQuery ($sql);
        $this->dbManager->bindValue($stmt, 1, $feature[0], $this->dbManager->STRING_TYPE);
        $this->dbManager->bindValue($stmt, 2, $featureset, $this->dbManager->STRING_TYPE);
        $this->dbManager->bindValue($stmt, 3, $feature[1], $this->dbManager->STRING_TYPE);
        $this->dbManager->bindValue($stmt, 4, $feature[2], $this->dbManager->STRING_TYPE);
        $this->dbManager->bindValue($stmt, 5, $feature[3], $this->dbManager->STRING_TYPE);
        $this->dbManager->executeQuery ($stmt);
        
        return ("ok");  
    }
    
    public function deleteFeature($featureset, $feature) {
    
        // $featureset is a string
        // $feature structure
        // feature[0] = name
        // feature[1] = level
        
        // Arguments that also need to be deleted
        $arguments = $this->getArgumentGivenFeature($featureset, $feature[0], $feature[1]);
        
        $labels = array();
        $graphs = array();
        $ids = "";
        foreach ($arguments as $key => $value) {
            array_push($labels, $value["label"]);
            array_push($graphs, $value["graph"]);
            $ids .= $value["id"] . ",";
        }
        
        // Remove last comma
        $ids = rtrim($ids, ",");
        
        // Delete arguments
        if (strlen($ids) > 0) {
            $sql = "DELETE FROM arguments WHERE id IN (" . $ids . ")";
            $stmt = $this->dbManager->prepareQuery ( $sql );
            $this->dbManager->executeQuery ( $stmt );
        }
        
        // Delete edges from graphs that contain arguments deleted
        foreach ($labels as $key => $value) {
            
            $graphInfo = $this->getGraph($graphs[$key]);
            
            // Not beautiful regex but works. There is problem some way to do it in one line =)
            $patternSourceUndercut = '/{"source":"' . $value . '","target":"[^,{}]+","type":"rebuttal"}/i';
            $graphInfo[0]["edges"] = preg_replace($patternSourceUndercut, '', $graphInfo[0]["edges"]);
            
            $patternSourceRebuttal = '/{"source":"' . $value . '","target":"[^,{}]+","type":"undercut"}/i';
            $graphInfo[0]["edges"] = preg_replace($patternSourceRebuttal, '', $graphInfo[0]["edges"]);
            
            $patternSourceUndermine = '/{"source":"' . $value . '","target":"[^,{}]+","type":"undermine"}/i';
            $graphInfo[0]["edges"] = preg_replace($patternSourceUndermine, '', $graphInfo[0]["edges"]);
            
            $patternSourceNone = '/{"source":"' . $value . '","target":"[^,{}]+","type":"none"}/i';
            $graphInfo[0]["edges"] = preg_replace($patternSourceNone, '', $graphInfo[0]["edges"]);

            $patternSourceEmpty = '/{"source":"' . $value . '","target":"[^,{}]+"}/i';
            $graphInfo[0]["edges"] = preg_replace($patternSourceEmpty, '', $graphInfo[0]["edges"]);
            
            $patternTargetUndercut = '/{"source":"[^,{}]+","target":"' . $value . '","type":"rebuttal"}/i';
            $graphInfo[0]["edges"] = preg_replace($patternTargetUndercut, '', $graphInfo[0]["edges"]);
            
            $patternTargetRebuttal = '/{"source":"[^,{}]+","target":"' . $value . '","type":"undercut"}/i';
            $graphInfo[0]["edges"] = preg_replace($patternTargetRebuttal, '', $graphInfo[0]["edges"]);
            
            $patternTargetUndermine = '/{"source":"[^,{}]+","target":"' . $value . '","type":"undermine"}/i';
            $graphInfo[0]["edges"] = preg_replace($patternTargetUndermine, '', $graphInfo[0]["edges"]);
            
            $patternTargetNone = '/{"source":"[^,{}]+","target":"' . $value . '","type":"none"}/i';
            $graphInfo[0]["edges"] = preg_replace($patternTargetNone, '', $graphInfo[0]["edges"]);
            
            $patternTargetEmpty = '/{"source":"[^,{}]+","target":"' . $value . '"}/i';
            $graphInfo[0]["edges"] = preg_replace($patternTargetEmpty, '', $graphInfo[0]["edges"]);
            
            // Remove possible wrong commas            
            do {
                $beforeReplace = $graphInfo[0]["edges"];
                $graphInfo[0]["edges"] = str_replace(",,", ",", $graphInfo[0]["edges"]);
                
                if ($beforeReplace == $graphInfo[0]["edges"]) {
                    break;
                }
            } while (True);
            
            $graphInfo[0]["edges"] = str_replace("[,", "[", $graphInfo[0]["edges"]);
            $graphInfo[0]["edges"] = str_replace(",]", "]", $graphInfo[0]["edges"]);
            
            // Update graph edges
            $sql =  "UPDATE graphs SET edges = ? ";
            $sql .= "WHERE featureset = ? AND name = ?;";
            
            $stmt = $this->dbManager->prepareQuery ($sql);
            $this->dbManager->bindValue($stmt, 1, $graphInfo[0]["edges"], $this->dbManager->STRING_TYPE);
            $this->dbManager->bindValue($stmt, 2, $graphInfo[0]["featureset"], $this->dbManager->STRING_TYPE);
            $this->dbManager->bindValue($stmt, 3, $graphInfo[0]["name"], $this->dbManager->STRING_TYPE);
            $this->dbManager->executeQuery ($stmt);
        }
        
        $sql = "DELETE FROM attributes WHERE featureset = ? AND attribute = ? AND a_level = ?";
        $stmt = $this->dbManager->prepareQuery ($sql);
        $this->dbManager->bindValue ($stmt, 1, $featureset, $this->dbManager->STRING_TYPE);
        $this->dbManager->bindValue ($stmt, 2, $feature[0], $this->dbManager->STRING_TYPE);
        $this->dbManager->bindValue ($stmt, 3, $feature[1], $this->dbManager->STRING_TYPE);
        $this->dbManager->executeQuery ($stmt);
        
        return ("ok");  
    }
    
    
    public function deleteConclusion($featureset, $conclusion) {
    
        // $featureset and $conclusion are strings
        
        // Arguments that also need to be deleted
        $arguments = $this->getArgumentGivenConclusion($featureset, $conclusion);
        
        $labels = array();
        $graphs = array();
        $ids = "";
        foreach ($arguments as $key => $value) {
            array_push($labels, $value["label"]);
            array_push($graphs, $value["graph"]);
            $ids .= $value["id"] . ",";
        }
        
        // Remove last comma
        $ids = rtrim($ids, ",");
        
        // Delete arguments
        if (strlen($ids) > 0) {
            $sql = "DELETE FROM arguments WHERE id IN (" . $ids . ")";
            $stmt = $this->dbManager->prepareQuery ( $sql );
            $this->dbManager->executeQuery ( $stmt );
        }
        
        // Delete edges from graphs that contain arguments deleted
        foreach ($labels as $key => $value) {
            
            $graphInfo = $this->getGraph($graphs[$key]);
            
            // Not beautiful regex but works. There is problem some way to do it in one line =)
            $patternSourceUndercut = '/{"source":"' . $value . '","target":"[^,{}]+","type":"rebuttal"}/i';
            $graphInfo[0]["edges"] = preg_replace($patternSourceUndercut, '', $graphInfo[0]["edges"]);
            
            $patternSourceRebuttal = '/{"source":"' . $value . '","target":"[^,{}]+","type":"undercut"}/i';
            $graphInfo[0]["edges"] = preg_replace($patternSourceRebuttal, '', $graphInfo[0]["edges"]);
            
            $patternSourceUndermine = '/{"source":"' . $value . '","target":"[^,{}]+","type":"undermine"}/i';
            $graphInfo[0]["edges"] = preg_replace($patternSourceUndermine, '', $graphInfo[0]["edges"]);
            
            $patternSourceNone = '/{"source":"' . $value . '","target":"[^,{}]+","type":"none"}/i';
            $graphInfo[0]["edges"] = preg_replace($patternSourceNone, '', $graphInfo[0]["edges"]);

            $patternSourceEmpty = '/{"source":"' . $value . '","target":"[^,{}]+"}/i';
            $graphInfo[0]["edges"] = preg_replace($patternSourceEmpty, '', $graphInfo[0]["edges"]);
            
            $patternTargetUndercut = '/{"source":"[^,{}]+","target":"' . $value . '","type":"rebuttal"}/i';
            $graphInfo[0]["edges"] = preg_replace($patternTargetUndercut, '', $graphInfo[0]["edges"]);
            
            $patternTargetRebuttal = '/{"source":"[^,{}]+","target":"' . $value . '","type":"undercut"}/i';
            $graphInfo[0]["edges"] = preg_replace($patternTargetRebuttal, '', $graphInfo[0]["edges"]);
            
            $patternTargetUndermine = '/{"source":"[^,{}]+","target":"' . $value . '","type":"undermine"}/i';
            $graphInfo[0]["edges"] = preg_replace($patternTargetUndermine, '', $graphInfo[0]["edges"]);
            
            $patternTargetNone = '/{"source":"[^,{}]+","target":"' . $value . '","type":"none"}/i';
            $graphInfo[0]["edges"] = preg_replace($patternTargetNone, '', $graphInfo[0]["edges"]);
            
            $patternTargetEmpty = '/{"source":"[^,{}]+","target":"' . $value . '"}/i';
            $graphInfo[0]["edges"] = preg_replace($patternTargetEmpty, '', $graphInfo[0]["edges"]);
            
            // Remove possible wrong commas            
            do {
                $beforeReplace = $graphInfo[0]["edges"];
                $graphInfo[0]["edges"] = str_replace(",,", ",", $graphInfo[0]["edges"]);
                
                if ($beforeReplace == $graphInfo[0]["edges"]) {
                    break;
                }
            } while (True);
            
            $graphInfo[0]["edges"] = str_replace("[,", "[", $graphInfo[0]["edges"]);
            $graphInfo[0]["edges"] = str_replace(",]", "]", $graphInfo[0]["edges"]);
            
            // Update graph edges
            $sql =  "UPDATE graphs SET edges = ? ";
            $sql .= "WHERE featureset = ? AND name = ?;";
            
            $stmt = $this->dbManager->prepareQuery ($sql);
            $this->dbManager->bindValue($stmt, 1, $graphInfo[0]["edges"], $this->dbManager->STRING_TYPE);
            $this->dbManager->bindValue($stmt, 2, $graphInfo[0]["featureset"], $this->dbManager->STRING_TYPE);
            $this->dbManager->bindValue($stmt, 3, $graphInfo[0]["name"], $this->dbManager->STRING_TYPE);
            $this->dbManager->executeQuery ($stmt);
        }
        
        $sql = "DELETE FROM conclusions WHERE featureset = ? AND conclusion = ?";
        $stmt = $this->dbManager->prepareQuery ($sql);
        $this->dbManager->bindValue ($stmt, 1, $featureset, $this->dbManager->STRING_TYPE);
        $this->dbManager->bindValue ($stmt, 2, $conclusion, $this->dbManager->STRING_TYPE);
        $this->dbManager->executeQuery ($stmt);
        
        return ("ok");  
    }

    public function updateConclusion($featureset, $conclusionOld, $conclusionNew) {
    
        // $featureset is a string
        // $conclusionOld and $conclusionNew structure
        // conclusion[0] = name
        // conclusion[1] = from
        // conclusion[2] = to
        
        if ($conclusionOld[0] != $conclusionNew[0]){
            // Feature name already exisit
            $db_conclusion = $this->getConclusion($featureset, $conclusionNew[0]);
            if (sizeof($db_conclusion) > 0) {
                return ("Cannot update! Conclusion name already defined in this feature set.");
            }
        }
        
        // Keep old conclusion to check if it is reversed in some argument later
        $db_conclusion = $this->getConclusion($featureset, $conclusionOld[0]);

        $sql =  "UPDATE conclusions SET conclusion = ?, c_from = ?, c_to = ? ";
        $sql .= "WHERE featureset = ? AND conclusion = ?;";
        $stmt = $this->dbManager->prepareQuery ($sql);
        $this->dbManager->bindValue($stmt, 1, $conclusionNew[0], $this->dbManager->STRING_TYPE);
        $this->dbManager->bindValue($stmt, 2, $conclusionNew[1], $this->dbManager->STRING_TYPE);
        $this->dbManager->bindValue($stmt, 3, $conclusionNew[2], $this->dbManager->STRING_TYPE);
        $this->dbManager->bindValue($stmt, 4, $featureset, $this->dbManager->STRING_TYPE);
        $this->dbManager->bindValue($stmt, 5, $conclusionOld[0], $this->dbManager->STRING_TYPE);
        $this->dbManager->executeQuery ($stmt);
        
        // Update arguments which used the old conclusion
        $arguments = $this->getArgumentGivenConclusion($featureset, $conclusionOld[0]);
        
        //var_dump($db_conclusion);
        foreach ($arguments as $key => $value) {
            
            $full_conclusion = $value["conclusion"];
            // full conclusion is something like "name [from, to]"
            $conclusion_name = explode(" ", $full_conclusion)[0];
            $conclusion_from = explode(" ", $full_conclusion)[1];
            $conclusion_to = explode(" ", $full_conclusion)[2];
            
            // remove brackets and commas
            $conclusion_from = str_replace("[", "", $conclusion_from);
            $conclusion_from = str_replace(",", "", $conclusion_from);
            
            $conclusion_to = str_replace("]", "", $conclusion_to);
            $conclusion_to = str_replace(",", "", $conclusion_to);
            
            // Check if conclusion in the argument is reversed. Reversed conclusion are kept like regardless of new values;
            $reverse = false;
            if ($db_conclusion[0]["c_from"] == $conclusion_to && $db_conclusion[0]["c_to"] == $conclusion_from) {
                $reverse = true;
            }
            
            $newConclusion = "";
            if (! $reverse) {
                $newConclusion = $conclusionNew[0] . " [" . $conclusionNew[1] . ", " . $conclusionNew[2] . "]";
            } else {
                $newConclusion = $conclusionNew[0] . " [" . $conclusionNew[2] . ", " . $conclusionNew[1] . "]";
            }
            
            $sql = "UPDATE arguments SET conclusion = ?";
            $sql .= "WHERE id = ?;";
            
            $stmt = $this->dbManager->prepareQuery ($sql);
            $this->dbManager->bindValue($stmt, 1, $newConclusion, $this->dbManager->STRING_TYPE);
            $this->dbManager->bindValue($stmt, 2, $value["id"], $this->dbManager->STRING_TYPE);
            $this->dbManager->executeQuery ($stmt);
        }
        
        return ("ok");  
    }
    
    
    public function createConclusion($featureset, $conclusion) {
    
        // $featureset is a string
        // $conclusion
        // conclusion[0] = name
        // conclusion[1] = from
        // conclusion[2] = to
        
        // Feature name already exisit
        $db_conclusion = $this->getConclusion($featureset, $conclusion[0]);
        if (sizeof($db_conclusion) > 0) {
            return ("Cannot update! Conclusion name already defined in this feature set.");
        }

        $sql = " INSERT INTO conclusions (featureset, conclusion, c_from, c_to) VALUES ";
        $sql .= "(?, ?, ?, ?);";

        $stmt = $this->dbManager->prepareQuery ($sql);
        $this->dbManager->bindValue($stmt, 1, $featureset, $this->dbManager->STRING_TYPE);
        $this->dbManager->bindValue($stmt, 2, $conclusion[0], $this->dbManager->STRING_TYPE);
        $this->dbManager->bindValue($stmt, 3, $conclusion[1], $this->dbManager->STRING_TYPE);
        $this->dbManager->bindValue($stmt, 4, $conclusion[2], $this->dbManager->STRING_TYPE);
        
        $this->dbManager->executeQuery ($stmt);
        
        
        return ("ok");  
    }

    public function updateFeaturesetGraph($jsonEdges) {

        // First remove old graph. Since it is too complicated to update
        // all the arguments it is better to remove everything and add as a
        // new graph.
        $sql = "DELETE FROM arguments ";
        $sql .= "WHERE featureset=? AND graph=? ";

        $stmt = $this->dbManager->prepareQuery ( $sql );
        $this->dbManager->bindValue ( $stmt, 1, $_POST["editFeaturesetName"], $this->dbManager->STRING_TYPE);
        $this->dbManager->bindValue ( $stmt, 2, $_POST["oldGraphName"], $this->dbManager->STRING_TYPE);
        $this->dbManager->executeQuery ( $stmt );

        $sql = "DELETE FROM graphs ";
        $sql .= "WHERE featureset=? AND name=? ";

        $stmt = $this->dbManager->prepareQuery ( $sql );
        $this->dbManager->bindValue ( $stmt, 1, $_POST["editFeaturesetName"], $this->dbManager->STRING_TYPE);
        $this->dbManager->bindValue ( $stmt, 2, $_POST["oldGraphName"], $this->dbManager->STRING_TYPE);
        $this->dbManager->executeQuery ( $stmt );

        // Insert arguments
        $sql = "";
        foreach ($_POST["editArgument"] as $key => $value) {
            // The last position of argument is empty because it is the template filled by
            // the javascript
            if($value != "") {
                $sql .= " INSERT INTO arguments (argument, conclusion, x, y, label, graph, featureset, weight) VALUES ";
                $sql .= "(?, ?, ?, ?, ?, ?, ?, ?);";
            }
        }

        $stmt = $this->dbManager->prepareQuery ($sql);
        $bindPosition = 1;
        foreach ($_POST["editArgument"] as $key => $value) {
            // The last position of argument is empty because it is the template filled by
            // the javascript
            if($value != "") {
                $this->dbManager->bindValue($stmt, $bindPosition, $value, $this->dbManager->STRING_TYPE);
                $bindPosition++;
                $this->dbManager->bindValue($stmt, $bindPosition, $_POST["editConclusion"][$key], $this->dbManager->STRING_TYPE);
                $bindPosition++;
                $this->dbManager->bindValue($stmt, $bindPosition, $_POST["editX"][$key], $this->dbManager->STRING_TYPE);
                $bindPosition++;
                $this->dbManager->bindValue($stmt, $bindPosition, $_POST["editY"][$key], $this->dbManager->STRING_TYPE);
                $bindPosition++;
                $this->dbManager->bindValue($stmt, $bindPosition, $_POST["editLabel"][$key], $this->dbManager->STRING_TYPE);
                $bindPosition++;
                $this->dbManager->bindValue($stmt, $bindPosition, $_POST["editGraphName"], $this->dbManager->STRING_TYPE);
                $bindPosition++;
                $this->dbManager->bindValue($stmt, $bindPosition, $_POST["editFeaturesetName"], $this->dbManager->STRING_TYPE);
                $bindPosition++;
                if ($_POST["editWeight"][$key] == "NULL") {
                    $this->dbManager->bindValue($stmt, $bindPosition, null, $this->dbManager->STRING_TYPE);
                } else {
                    $this->dbManager->bindValue($stmt, $bindPosition, $_POST["editWeight"][$key], $this->dbManager->STRING_TYPE);
                }
                $bindPosition++;
            }
        }

        $this->dbManager->executeQuery ($stmt);

        $sql = "";
        $sql .= " INSERT INTO graphs (featureset, name, edges, font_size) VALUES ";
        $sql .= "(?, ?, ?, ?);";

        $stmt = $this->dbManager->prepareQuery ($sql);

        $this->dbManager->bindValue($stmt, 1, $_POST["editFeaturesetName"], $this->dbManager->STRING_TYPE);
        $this->dbManager->bindValue($stmt, 2, $_POST["editGraphName"], $this->dbManager->STRING_TYPE);
        $this->dbManager->bindValue($stmt, 3, $jsonEdges, $this->dbManager->STRING_TYPE);
        $this->dbManager->bindValue($stmt, 4, $_POST["fontsize"], $this->dbManager->STRING_TYPE);

        $this->dbManager->executeQuery ($stmt);
    }

    public function createGraphCopy($jsonEdges) {
        // Insert arguments
        $sql = "";
        foreach ($_POST["editArgument"] as $key => $value) {
            // The last position of argument is empty because it is the template filled by
            // the javascript
            if($value != "") {
                $sql .= " INSERT INTO arguments (argument, conclusion, x, y, label, graph, featureset, weight) VALUES ";
                $sql .= "(?, ?, ?, ?, ?, ?, ?, ?);";
            }
        }

        $stmt = $this->dbManager->prepareQuery ($sql);
        $bindPosition = 1;
        foreach ($_POST["editArgument"] as $key => $value) {
            // The last position of argument is empty because it is the template filled by
            // the javascript
            if($value != "") {
                $this->dbManager->bindValue($stmt, $bindPosition, $value, $this->dbManager->STRING_TYPE);
                $bindPosition++;
                $this->dbManager->bindValue($stmt, $bindPosition, $_POST["editConclusion"][$key], $this->dbManager->STRING_TYPE);
                $bindPosition++;
                $this->dbManager->bindValue($stmt, $bindPosition, $_POST["editX"][$key], $this->dbManager->STRING_TYPE);
                $bindPosition++;
                $this->dbManager->bindValue($stmt, $bindPosition, $_POST["editY"][$key], $this->dbManager->STRING_TYPE);
                $bindPosition++;
                $this->dbManager->bindValue($stmt, $bindPosition, $_POST["editLabel"][$key], $this->dbManager->STRING_TYPE);
                $bindPosition++;
                $this->dbManager->bindValue($stmt, $bindPosition, $_POST["copyNameGraph"], $this->dbManager->STRING_TYPE);
                $bindPosition++;
                $this->dbManager->bindValue($stmt, $bindPosition, $_POST["editFeaturesetName"], $this->dbManager->STRING_TYPE);
                $bindPosition++;
                if ($_POST["editWeight"][$key] == "NULL") {
                    $this->dbManager->bindValue($stmt, $bindPosition, null, $this->dbManager->STRING_TYPE);
                } else {
                    $this->dbManager->bindValue($stmt, $bindPosition, $_POST["editWeight"][$key], $this->dbManager->STRING_TYPE);
                }
            }
        }

        $this->dbManager->executeQuery ($stmt);

        $sql = "";
        $sql .= " INSERT INTO graphs (featureset, name, edges) VALUES ";
        $sql .= "(?, ?, ?);";

        $stmt = $this->dbManager->prepareQuery ($sql);

        $this->dbManager->bindValue($stmt, 1, $_POST["editFeaturesetName"], $this->dbManager->STRING_TYPE);
        $this->dbManager->bindValue($stmt, 2, $_POST["copyNameGraph"], $this->dbManager->STRING_TYPE);
        $this->dbManager->bindValue($stmt, 3, $jsonEdges, $this->dbManager->STRING_TYPE);

        $this->dbManager->executeQuery ($stmt);
    }

    public function getAttributes() {
        $sql = 'SELECT attribute FROM attributes';
        $stmt = $this->dbManager->prepareQuery ($sql);
        $this->dbManager->executeQuery ($stmt);
        $attributes = $stmt->fetchAll (PDO::FETCH_COLUMN, 0);
        return $attributes; 
    }

    public function deleteGraph($idGraph) {

        $sql = "DELETE FROM arguments WHERE featureset=? AND graph=?";
        $stmt = $this->dbManager->prepareQuery ($sql);
        $this->dbManager->bindValue ($stmt, 1, $idGraph["featuresetName"], $this->dbManager->STRING_TYPE);
        $this->dbManager->bindValue ($stmt, 2, $idGraph["graphName"], $this->dbManager->STRING_TYPE);
        $result = $this->dbManager->executeQuery ($stmt);

        $sql = "DELETE FROM graphs WHERE featureset=? AND name=?";
        $stmt = $this->dbManager->prepareQuery ($sql);
        $this->dbManager->bindValue ($stmt, 1, $idGraph["featuresetName"], $this->dbManager->STRING_TYPE);
        $this->dbManager->bindValue ($stmt, 2, $idGraph["graphName"], $this->dbManager->STRING_TYPE);
        $result = $this->dbManager->executeQuery ($stmt);

        return ($stmt->rowCount());
    }

    // Return distinct attributes and featureset. This will avoid of having the same attribute
    // for the same featureset in case the attribute has different levels
    public function attributes() {
        $sql = 'SELECT DISTINCT attribute, featureset FROM attributes';
        $stmt = $this->dbManager->prepareQuery ($sql);
        $this->dbManager->executeQuery ($stmt);
        $attributes = $this->dbManager->fetchResults($stmt);
        return $attributes;
    }

    public function attributesByFeatureset($featureset) {
        $sql = 'SELECT DISTINCT attribute, a_level, a_from, a_to FROM attributes WHERE featureset = \'' . $featureset . '\'';
        $stmt = $this->dbManager->prepareQuery ($sql);
        $this->dbManager->executeQuery ($stmt);
        $attributes = $this->dbManager->fetchResults($stmt);
        return $attributes;
    }

    public function conclusionsByFeatureset($featureset) {
        $sql = 'SELECT conclusion, c_from, c_to FROM conclusions WHERE featureset = \'' . $featureset . '\'';
        $stmt = $this->dbManager->prepareQuery ($sql);
        $this->dbManager->executeQuery ($stmt);
        $conclusions = $this->dbManager->fetchResults($stmt);
        return $conclusions;
    }

    // Return distinct attribute, featureset and level. This will return the attribute multiple
    // time for the same featureset in case the attribute has more than on level.
    public function levels() {
        $sql = 'SELECT DISTINCT attribute, featureset, a_level, a_from, a_to FROM attributes';
        $stmt = $this->dbManager->prepareQuery ($sql);
        $this->dbManager->executeQuery ($stmt);
        $levels = $this->dbManager->fetchResults($stmt);
        return $levels;
    }

    public function featuresets() {
        $sql = 'SELECT DISTINCT featureset FROM attributes WHERE featureset ' .
               'IN (SELECT DISTINCT featureset FROM user_featureset WHERE email = "' . $_SESSION["username"] . '");';
        $stmt = $this->dbManager->prepareQuery ($sql);
        $this->dbManager->executeQuery ($stmt);
        $featuresets = $stmt->fetchAll (PDO::FETCH_COLUMN, 0);
        return $featuresets; 
    }
    
    public function allUserFeaturesets() {
        $sql = 'SELECT featureset FROM user_featureset WHERE email = "' . $_SESSION["username"] . '";';
        $stmt = $this->dbManager->prepareQuery ($sql);
        $this->dbManager->executeQuery ($stmt);
        $featuresets = $stmt->fetchAll (PDO::FETCH_COLUMN, 0);
        return $featuresets; 
    }


    public function featuresetsWithGraphs() {

        $sql = 'SELECT DISTINCT featureset FROM graphs WHERE featureset ' .
               'IN (SELECT DISTINCT featureset FROM user_featureset WHERE email = "' . $_SESSION["username"] . '");';
        $stmt = $this->dbManager->prepareQuery ($sql);
        $this->dbManager->executeQuery ($stmt);
        $featuresets = $stmt->fetchAll (PDO::FETCH_COLUMN, 0);
        return $featuresets; 
    }

    public function getLevels($attribute) {
        $sql = 'SELECT DISTINCT a_level FROM attributes WHERE attribute = ' . $attribute;
        $stmt = $this->dbManager->prepareQuery ($sql);
        $this->dbManager->executeQuery ($stmt);
        $levels = $stmt->fetchAll (PDO::FETCH_COLUMN, 0);
        return $levels; 
    }
    
    public function getFeaturesets() {
        $sql = 'SELECT DISTINCT featureset FROM attributes';
        $stmt = $this->dbManager->prepareQuery ($sql);
        $this->dbManager->executeQuery ($stmt);
        $featuresets = $stmt->fetchAll (PDO::FETCH_COLUMN, 0);
        return $featuresets;
    }

    public function delete($userID) {
        $sql = "DELETE FROM users ";
        $sql .= "WHERE id=? ";

        $stmt = $this->dbManager->prepareQuery ( $sql );
        $this->dbManager->bindValue ( $stmt, 1, $userID, $this->dbManager->INT_TYPE );
        $result = $this->dbManager->executeQuery ( $stmt );

        return ($result);
    }

    public function search($str) {
    }

    public function update($parametersArray, $userID) {
    }
}
?>
