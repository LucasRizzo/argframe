<?php
session_start (); // start a new session or reconnect to an existing one

require_once "Slim/Slim.php";
require_once "conf/config.inc.php";
include_once "db/DAO/dataSetDAO.php";
include_once "db/DAO/userDAO.php";
include_once "db/pdoDbManager.php";

ini_set('max_post_size', '100M');
ini_set('upload_max_filesize', '100M');
ini_set('php_max_size', '100M');
ini_set('post_max_size', '100M');
ini_set('max_input_vars', '100000');

Slim\Slim::registerAutoloader ();

$app = new \Slim\Slim (); // slim run-time object

// route middleware for simple API authentication
function authenticate(\Slim\Route $route) {
    $app = \Slim\Slim::getInstance();

    $headers = $app->request->headers();

    // Empty session given by slim looks like this
    if (sizeof($_SESSION) == 1 && sizeof($_SESSION["slim.flash"]) == 0 && empty($_POST)) {
        $app->response->redirect($app->urlFor('login'));
//     } else if (empty($headers["User"])) {
//         $app->response->redirect($app->urlFor('login'));
    } else if (validateUserKey() === false) {
        $app->response->redirect($app->urlFor('errlogin'));
    } /*else if (validateUserHeaders($headers) === false) {
        $app->halt(401);
    }*/
}

// function validateUserHeaders($headers) {
// 
//     // Check if it is a guest first
// 
//     if (("Guest" == $headers["User"]) && ("guest" == $headers["Password"])) {
//         $_SESSION["username"] = $headers["User"];
//         $_SESSION["password"] = $headers["Password"];
//         return  true;
//     }
// 
//     $dBManager = new pdoDbManager ();
//     $dbLink = $dBManager->openConnection ();
//     $userDAO = new UserDAO ($dBManager);
// 
//     $userList = $userDAO->getUsers();
// 
//     foreach ( $userList as $key => $value ) {
// 
//         if (($value["email"] == $headers["User"]) && ($value["password"] == md5($headers["Password"]))) {
//             $_SESSION["username"] = $headers["User"];
//             $_SESSION["password"] = md5($headers["Password"]);
//             return  true;
//         }
//     }
// 
//     return false;
// }

function validateUserKey() {

    // Check if it is a guest first
    if (! empty ( $_POST ["username"] ) && ! empty ( $_POST ["password"] )) {
        if (("Guest" == $_POST ["username"]) && ("guest" == $_POST ["password"])) {
                $_SESSION["username"] = $_POST ["username"];
                $_SESSION["password"] = $_POST ["password"];
                return  true;
        }
    }

    if (! empty ( $_SESSION ["username"] ) && ! empty ( $_SESSION ["password"] )) {
        if (("Guest" == $_SESSION ["username"]) && ("guest" == $_SESSION ["password"])) {
            return  true;
        }
    }

    $dBManager = new pdoDbManager ();
    $dbLink = $dBManager->openConnection ();
    $userDAO = new UserDAO ($dBManager);

    $userList = $userDAO->getUsers();
    
    
    if (! empty ( $_POST ["username"] ) && ! empty ( $_POST ["password"] )) {
        foreach ( $userList as $key => $value ) {
            if (($value["email"] == $_POST ["username"]) && ($value["password"] == md5($_POST ["password"]))) {
                $_SESSION["username"] = $_POST ["username"];
                $_SESSION["password"] = md5($_POST["password"]);
                
                if(! empty($_POST["remember"])) {
                    setcookie ("member_login", $_POST["username"] ,time()+ (10 * 365 * 24 * 60 * 60));
                    setcookie ("member_pass", $_POST["password"] ,time()+ (10 * 365 * 24 * 60 * 60));
                } else {
                    if(isset($_COOKIE["member_login"])) {
                        setcookie ("member_login","");
                    }
                    
                    if(isset($_COOKIE["member_pass"])) {
                        setcookie ("member_pass","");
                    }
                }
                
                return  true;
            }
        }
    }
    
    if (! empty($_SESSION ["password"]) && ! empty($_SESSION ["username"])) {
        foreach ( $userList as $key => $value ) {
            if (($value["email"] == $_SESSION ["username"]) && ($value["password"] == $_SESSION ["password"])) {
                return  true;
            }
        }
    }

    return false;
}

function printAspartix($edges, $isolatedNodes) {

    $args = array();
    foreach ($isolatedNodes as $nextIsolated) {
        echo "arg(" . strtolower($nextIsolated) . ").<br>";
    }

    $atts = array();
    for ($i = 0; $i < sizeof($edges) - 1; $i = $i + 2) {
        if(! in_array(strtolower($edges[$i]), $args, true)){
            array_push($args, strtolower($edges[$i]));
        }

        if(! in_array(strtolower($edges[$i + 1]), $args, true)){
            array_push($args, strtolower($edges[$i + 1]));
        }
    }

    foreach ($args as $nextArg) {
        echo "arg(" . $nextArg . ").<br>";
    }

    for ($i = 0; $i < sizeof($edges) - 1; $i = $i + 2) {
        echo "att(" . strtolower($edges[$i]) . "," . strtolower($edges[$i + 1]) . ").<br>";
    }
}

// Given an string graph return the DungAf object
function getDungAf($graph) {

    if (strpos($graph,":") !== false) {

        $graph = explode(":", $graph);

        $edges = $graph[0];
        $edges = explode(",", $edges);

        $isolatedNodes = $graph[1];
        $isolatedNodes = explode(",", $isolatedNodes);

        if (sizeof($edges) > 1 && sizeof($edges) % 2 != 0) {
            throw new Exception("Wrong argumentation graph for computing preferred extension");
        }

        $atts = array();
        for ($i = 0; $i < sizeof($edges) - 1; $i = $i + 2) {
            array_push($atts, array($edges[$i] => $edges[$i + 1]));
        }

        $af = DungAF::PopulatedDungAF($atts, $isolatedNodes);
        //$af->addArgs($isolatedNodes);

        //printAspartix($edges, $isolatedNodes);
        //exit;

        return $af;
    } else {
        $edges = $graph;
        $edges = explode(",", $edges);

        if (sizeof($edges) > 1 && sizeof($edges) % 2 != 0) {
            throw new Exception("Wrong argumentation graph for computing preferred extension");
        }

        $atts = array();
        for ($i = 0; $i < sizeof($edges) - 1; $i = $i + 2) {
            array_push($atts, array($edges[$i] => $edges[$i + 1]));
        }

        $af = DungAF::PopulatedDungAF($atts, "");
        return $af;
    }
}

$app->get ( "/login", function () use($app) {

    session_unset (); // unset the session (and the $_SESSION)

    $action = ACTION_LOGIN;
    $parameters = null;

    return new loadRunMVCComponents ( "Model", "Controller", "View", $action, $app, $parameters);
} )->name("login");

$app->get ( "/errlogin", function () use($app) {

    session_unset (); // unset the session (and the $_SESSION)

    $action = ACTION_LOGIN_ERR;
    $parameters = null;

    return new loadRunMVCComponents ( "Model", "Controller", "View", $action, $app, $parameters);
} )->name("errlogin");


$app->map ( "/model(/:modelName)", "authenticate", function ($modelname = null) use($app) {

    $httpMethod = $app->request->getMethod ();
    $action = null;

    $parameters ["modelname"] = $modelname;

    if ($modelname != null) {
        switch ($httpMethod) {
            case "GET" :
            $action = ACTION_GET_MODEL;
            break;
            default :
        }
    }

    if ($modelname == null) {
        switch ($httpMethod) {
            case "GET" :
            $action = ACTION_GET_ALL_MODELS;
            break;
            default :
        }
    }

    /*
    if (($userID == null) or is_numeric ( $userID )) {
        switch ($httpMethod) {
            case "GET" :
                if ($userID != null)
                    $action = ACTION_GET_USER;
                else
                    $action = ACTION_GET_USERS;
                break;
            case "POST" :
                $action = ACTION_CREATE_USER;
                break;
            case "PUT" :
                $action = ACTION_UPDATE_USER;
                break;
            case "DELETE" :
                $action = ACTION_DELETE_USER;
                break;
            default :
        }
    }*/

    return new loadRunMVCComponents ( "Model", "Controller", "View", $action, $app, $parameters);
} )->via ( "GET", "POST", "PUT", "DELETE" );

$app->map ( "/featureset(/:featureset)", "authenticate", function ($featureset = null) use($app) {

    $httpMethod = $app->request->getMethod ();
    $action = null;

    $parameters ["featureset"] = $featureset; // prepare parameters to be passed to the controller (example: ID)

    if ($featureset != null) {
        switch ($httpMethod) {
            case "GET" :
                $action = ACTION_GET_FEATURESET;
                break;
            case "DELETE" :
                $action = ACTION_DELETE_FEATURESET;
                break;
            default :
        }
    }

    if ($featureset == null) {
        switch ($httpMethod) {
            case "GET" :
                $action = ACTION_GET_ALL_FEATURESETS;
                break;
            case "POST" :
                $action = ACTION_CREATE_FEATURESET;
                break;
            default :
        }
    }

    return new loadRunMVCComponents ( "Model", "Controller", "View", $action, $app, $parameters);
} )->via ( "GET", "POST", "PUT", "DELETE" );

$app->map ( "/", "authenticate", function ($featureset = null, $modelname = null) use($app) {

    $httpMethod = $app->request->getMethod ();
    $action = "";
    if (! empty ( $_GET ['action'] ))
        $action = $_GET ['action'];
    $parameters = null;

    return new loadRunMVCComponents ( "Model", "Controller", "View", $action, $app, $parameters);
} )->via ( "GET", "POST", "PUT", "DELETE" );

$app->map ( "/updateFeature(/:featureInfo)", "authenticate", function ($featureInfo = null) use($app) {
    
    // Pass featureInfo in the format:
    // featureSet;featureNameNew:featureLevelName:featureFromNew:featureToNew;featureNameOld:featureLevelOld:featureFromOld:featureToOld
    $featureInfo = explode(";", $featureInfo);
    $featureSet = $featureInfo[0];
    $featureOld = explode(":", $featureInfo[1]);
    $featureNew = explode(":", $featureInfo[2]);
    
    $dBManager = new pdoDbManager ();
    $dbLink = $dBManager->openConnection ();
    $dataSetDAO = new DataSetDAO ($dBManager);
    // Pass new and old feature info in an array
    // feature[0] = name
    // feature[1] = level
    // feature[2] = from
    // feature[3] = to
    $app->response->write($dataSetDAO->updateFeature($featureSet, $featureOld, $featureNew));

} )->via ( "GET", "POST", "PUT", "DELETE" );


$app->map ( "/createFeature(/:featureInfo)", "authenticate", function ($featureInfo = null) use($app) {
    
    // Pass featureInfo in the format:
    // featureSet;featureNameNew:featureLevelName:featureFromNew:featureToNew
    $featureInfo = explode(";", $featureInfo);
    $featureSet = $featureInfo[0];
    $feature = explode(":", $featureInfo[1]);
    
    $dBManager = new pdoDbManager ();
    $dbLink = $dBManager->openConnection ();
    $dataSetDAO = new DataSetDAO ($dBManager);
    // Pass new and old feature info in an array
    // feature[0] = name
    // feature[1] = level
    // feature[2] = from
    // feature[3] = to
    $app->response->write($dataSetDAO->createFeature($featureSet, $feature));

} )->via ( "GET", "POST", "PUT", "DELETE" );

$app->map ( "/deleteFeature(/:featureInfo)", "authenticate", function ($featureInfo = null) use($app) {
    
    // Pass featureInfo in the format:
    // featureSet;featureNameNew:featureLevelName:featureFromNew:featureToNew
    $featureInfo = explode(";", $featureInfo);
    $featureSet = $featureInfo[0];
    $feature = explode(":", $featureInfo[1]);
    
    $dBManager = new pdoDbManager ();
    $dbLink = $dBManager->openConnection ();
    $dataSetDAO = new DataSetDAO ($dBManager);
    // Pass new and old feature info in an array
    // feature[0] = name
    // feature[1] = level
    // feature[2] = from
    // feature[3] = to
    $app->response->write($dataSetDAO->deleteFeature($featureSet, $feature));

} )->via ( "GET", "POST", "PUT", "DELETE" );

$app->map ( "/deleteFeatureset(/:featureset)", "authenticate", function ($featureset = null) use($app) {

    $dBManager = new pdoDbManager ();
    $dbLink = $dBManager->openConnection ();
    $dataSetDAO = new DataSetDAO ($dBManager);
    $app->response->write($dataSetDAO->deleteFeatureset($featureset));

} )->via ( "GET", "POST", "PUT", "DELETE" );

$app->map ( "/deleteConclusion(/:conclusionInfo)", "authenticate", function ($conclusionInfo = null) use($app) {
    
    // Pass conclusionInfo in the format:
    // featureSet;conclusion
    $conclusionInfo = explode(";", $conclusionInfo);
    $featureSet = $conclusionInfo[0];
    $conclusion = $conclusionInfo[1];
    
    $dBManager = new pdoDbManager ();
    $dbLink = $dBManager->openConnection ();
    $dataSetDAO = new DataSetDAO ($dBManager);
    $app->response->write($dataSetDAO->deleteConclusion($featureSet, $conclusion));

} )->via ( "GET", "POST", "PUT", "DELETE" );

$app->map ( "/getFeaturesetsNames", "authenticate", function () use($app) {
    

    $dBManager = new pdoDbManager ();
    $dbLink = $dBManager->openConnection ();
    $dataSetDAO = new DataSetDAO ($dBManager);
    $app->response->write($dataSetDAO->getFeaturesetsNames());

} )->via ( "GET", "POST", "PUT", "DELETE" );


$app->map ( "/newFeatureset(/:featuresetName)", "authenticate", function ($featuresetName = null) use($app) {
    
    $dBManager = new pdoDbManager ();
    $dbLink = $dBManager->openConnection ();
    $dataSetDAO = new DataSetDAO ($dBManager);
    $app->response->write($dataSetDAO->newFeatureset($featuresetName));

} )->via ( "GET", "POST", "PUT", "DELETE" );


$app->map ( "/copyFeatureset(/:featuresetName)", "authenticate", function ($featuresetName = null) use($app) {
    
    // Pass conclusionInfo in the format:
    // featureSet;conclusion
    $featuresetName = explode(";", $featuresetName);
    $featuresetNameNew = $featuresetName[0];
    $featuresetNameOld = $featuresetName[1];
    
    
    $dBManager = new pdoDbManager ();
    $dbLink = $dBManager->openConnection ();
    $dataSetDAO = new DataSetDAO ($dBManager);
    $app->response->write($dataSetDAO->copyFeatureset($featuresetNameNew, $featuresetNameOld));

} )->via ( "GET", "POST", "PUT", "DELETE" );


$app->map ( "/renameFeatureset(/:featuresetName)", "authenticate", function ($featuresetName = null) use($app) {
    
    // Pass conclusionInfo in the format:
    // featureSet;conclusion
    $featuresetName = explode(";", $featuresetName);
    $featuresetNameNew = $featuresetName[0];
    $featuresetNameOld = $featuresetName[1];
    
    $dBManager = new pdoDbManager ();
    $dbLink = $dBManager->openConnection ();
    $dataSetDAO = new DataSetDAO ($dBManager);
    $app->response->write($dataSetDAO->renameFeatureset($featuresetNameNew, $featuresetNameOld));

} )->via ( "GET", "POST", "PUT", "DELETE" );

$app->map ( "/newFeaturesetJSON", "authenticate", function () use($app) {

    
    include_once "models/model.php";
    $model = new Model(); // common model
    
    $request = $app->request();
    $jsonCode = $request->getBody();
    
    $jsonCode = json_decode($jsonCode, true);
    
    $postForm["featureset"] = $jsonCode["featureset"];
    $joinArray = 0;

    for ($i = 0; $i < count($jsonCode["attributes"]); $i++) {
        $postForm["attributename"][$i] = $jsonCode["attributes"][$i][0]["name"];
        $postForm["nrange"][$i] = $jsonCode["attributes"][$i][1]["range"];

        for ($j = 0; $j < count($jsonCode["attributes"][$i][2]["from"]); $j++) {
            $postForm["attributefrom"][$joinArray] = $jsonCode["attributes"][$i][2]["from"][$j]["value"];
            $postForm["attributeto"][$joinArray] = $jsonCode["attributes"][$i][3]["to"][$j]["value"];
            $postForm["attributelevel"][$joinArray] = $jsonCode["attributes"][$i][4]["level"][$j]["value"];
            $joinArray++;
        }
    }

    for ($i = 0; $i < count($jsonCode["conclusions"]); $i++) {
        $postForm["conclusionname"][$i] = $jsonCode["conclusions"][$i][0]["category"];
        $postForm["conclusionfrom"][$i] = $jsonCode["conclusions"][$i][1]["from"];
        $postForm["conclusionto"][$i] = $jsonCode["conclusions"][$i][2]["to"];
    }
    
    $model->insertFeatureset($postForm);
    
    if ($model->error == "NULL") {
        $app->response->write("ok");
    } else {
        $app->response->write($model->error);
    }

} )->via ( "GET", "POST", "PUT", "DELETE" );

$app->map ( "/updateConclusion(/:conclusionInfo)", "authenticate", function ($conclusionInfo = null) use($app) {
    
    // Pass $conclusionInfo in the format:
    // featureSet;conclusionNameNew:conclusionLevelName:conclusionFromNew:conclusionToNew;
    // conclusionNameOld:conclusionLevelOld:conclusionFromOld:conclusionToOld
    $conclusionInfo = explode(";", $conclusionInfo);
    $featureSet = $conclusionInfo[0];
    $conclusionOld = explode(":", $conclusionInfo[1]);
    $conclusionNew = explode(":", $conclusionInfo[2]);
    
    $dBManager = new pdoDbManager ();
    $dbLink = $dBManager->openConnection ();
    $dataSetDAO = new DataSetDAO ($dBManager);
    // Pass new and old feature info in an array
    // conclusion[0] = name
    // conclusion[1] = level
    // conclusion[2] = from
    // conclusion[3] = to
    $app->response->write($dataSetDAO->updateConclusion($featureSet, $conclusionOld, $conclusionNew));

} )->via ( "GET", "POST", "PUT", "DELETE" );

$app->map ( "/createConclusion(/:conclusionInfo)", "authenticate", function ($conclusionInfo = null) use($app) {
    
    // Pass $conclusionInfo in the format:
    // featureSet;conclusionName:conclusionLevelName:conclusionFromNew:conclusionToNew
    $conclusionInfo = explode(";", $conclusionInfo);
    $featureSet = $conclusionInfo[0];
    $conclusion = explode(":", $conclusionInfo[1]);
    
    $dBManager = new pdoDbManager ();
    $dbLink = $dBManager->openConnection ();
    $dataSetDAO = new DataSetDAO ($dBManager);
    // Pass new and old feature info in an array
    // conclusion[0] = name
    // conclusion[1] = level
    // conclusion[2] = from
    // conclusion[3] = to
    $app->response->write($dataSetDAO->createConclusion($featureSet, $conclusion));

} )->via ( "GET", "POST", "PUT", "DELETE" );

$app->map ( "/preferred(/:graph)", "authenticate", function ($graph = null) use($app) {

    require_once "semantics/dungAF.php"; 

    $af = getDungAf($graph);

    $preferred =  json_encode($af->getPreferredExts());
    $app->response->write($preferred);

} )->via ( "GET", "POST", "PUT", "DELETE" );

$app->map ( "/grounded(/:graph)", "authenticate", function ($graph = null) use($app) {

    require_once "semantics/dungAF.php"; 

    $af = getDungAf($graph);

    $grounded =  json_encode($af->getGroundedExt());
    $app->response->write($grounded);

} )->via ( "GET", "POST", "PUT", "DELETE" );

$app->map ( "/expert(/:graph)", "authenticate", function ($graph = null) use($app) {

    require_once "semantics/dungAF.php"; 

    $af = getDungAf($graph);

    $expert =  json_encode($af->getExpertSystem());
    $app->response->write($expert);

} )->via ( "GET", "POST", "PUT", "DELETE" );

$app->map ( "/admissible(/:graph)", "authenticate", function ($graph = null) use($app) {

    require_once "semantics/dungAF.php"; 

    $af = getDungAf($graph);

    $admissible=  json_encode($af->getAdmissibleSets());
    $app->response->write($admissible);

} )->via ( "GET", "POST", "PUT", "DELETE" );

$app->map ( "/stable(/:graph)", "authenticate", function ($graph = null) use($app) {

    require_once "semantics/dungAF.php"; 

    $af = getDungAf($graph);

    $stable =  json_encode($af->getStableExts());
    $app->response->write($stable);

} )->via ( "GET", "POST", "PUT", "DELETE" );

$app->map ( "/semistable(/:graph)", "authenticate", function ($graph = null) use($app) {

    require_once "semantics/dungAF.php"; 

    $af = getDungAf($graph);

    $semistable =  json_encode($af->getSemiStableExts());
    $app->response->write($semistable);

} )->via ( "GET", "POST", "PUT", "DELETE" );

$app->map ( "/eager(/:graph)", "authenticate", function ($graph = null) use($app) {

    require_once "semantics/dungAF.php"; 

    $af = getDungAf($graph);

    $eager =  json_encode($af->getEagerExt());
    $app->response->write($eager);

} )->via ( "GET", "POST", "PUT", "DELETE" );

$app->map ( "/ideal(/:graph)", "authenticate", function ($graph = null) use($app) {

    require_once "semantics/dungAF.php"; 

    $af = getDungAf($graph);

    $ideal =  json_encode($af->getIdealExt());
    $app->response->write($ideal);

} )->via ( "GET", "POST", "PUT", "DELETE" );

$app->map ( "/categoriser(/:graph)", "authenticate", function ($graph = null) use($app) {

    require_once "semantics/dungAF.php"; 

    $af = getDungAf($graph);

    $categoriser =  json_encode($af->getCategoriser());
    $app->response->write($categoriser);

} )->via ( "GET", "POST", "PUT", "DELETE" );

$app->map ( "/allSemantics(/:graph)", "authenticate", function ($graph = null) use($app) {

    require_once "semantics/dungAF.php"; 

    $httpMethod = $app->request->getMethod ();

    $graphAndSemantics = explode(";", $graph);

    //$file = 'graph.txt';

    $graph = $graphAndSemantics[0];

    //$current = file_get_contents($file);
    // Append a new person to the file
    //$current .= $graph . "\n";
    // Write the contents back to the file
    //file_put_contents($file, $current);

    $semantics = explode(",", $graphAndSemantics[1]);

    $af = getDungAf($graph);

    $allSemantics = array();

    foreach ($semantics as $nextSemantic) {

        if ($nextSemantic == "expert") {
            array_push($allSemantics, $af->getExpertSystem());
        } else  if ($nextSemantic == "grounded") {
            array_push($allSemantics, $af->getGroundedExt());
        } else if ($nextSemantic == "eager") {
            array_push($allSemantics, $af->getEagerExt());
        } else if ($nextSemantic == "ideal") {
            array_push($allSemantics, $af->getIdealExt());
        } else if ($nextSemantic == "preferred") {
            array_push($allSemantics, $af->getPreferredExts());
        } else if ($nextSemantic == "stable") {
            array_push($allSemantics, $af->getStableExts());
        } else if ($nextSemantic == "semistable") {
            array_push($allSemantics, $af->getSemiStableExts());
        } else if ($nextSemantic == "admissible") {
            array_push($allSemantics, $af->getAdmissibleSets());
        } else if ($nextSemantic == "categoriser") {
            array_push($allSemantics, $af->getCategoriser());
        }
    }
    $app->response->write(json_encode($allSemantics));

} )->via ( "GET", "POST", "PUT", "DELETE" );

if(!empty($_POST['data'])){
    $data = $_POST['data'];
    $fname = "graphs.txt";
    // Clean file
    file_put_contents($fname, "");

    $file = fopen($fname, 'w');

    fwrite($file, $data);

    /*$requests = explode(";;",  $data);

    $file = 'error.txt';
    $current = file_get_contents($file);
    // Append a new person to the file
    $current .= var_dump(count($requests));
    // Write the contents back to the file
    file_put_contents($file, $current);*/

    fclose($file);
}

$app->map ( "/deleteComputations", "authenticate", function ($graph = null) use($app) {

    $dBManager = new pdoDbManager ();
    $dbLink = $dBManager->openConnection ();
    $dataSetDAO = new DataSetDAO ($dBManager);
    // Pass username so multiple users can use computation table
    $dataSetDAO->deleteComputations($_SESSION ["username"]);

} )->via ( "GET", "POST", "PUT", "DELETE" );


$app->map ( "/getComputations", "authenticate", function ($graph = null) use($app) {

    require_once "semantics/dungAF.php"; 

    $httpMethod = $app->request->getMethod ();

    $dBManager = new pdoDbManager ();
    $dbLink = $dBManager->openConnection ();
    $dataSetDAO = new DataSetDAO ($dBManager);
    // Pass username so multiple users can use computation table
    $app->response->write(json_encode($dataSetDAO->getComputations($_SESSION ["username"]), JSON_PRETTY_PRINT));

} )->via ( "GET", "POST", "PUT", "DELETE" );


$app->map ( "/saveComputations", "authenticate", function ($graph = null) use($app) {

    require_once "semantics/dungAF.php"; 

    $httpMethod = $app->request->getMethod ();

    $data = $_POST['data'];
    $requests = explode(";;", $data);

    //var_dump(count($requests));

    $response = "";

    foreach ($requests as $r) {
        //echo($r);
        $graphAndSemantics = explode(";", $r);
        $graph = $graphAndSemantics[0];
        $semantics = explode(",", $graphAndSemantics[1]);

        $af = getDungAf($graph);

        $allSemantics = array();

        foreach ($semantics as $nextSemantic) {

            if ($nextSemantic == "expert") {
                array_push($allSemantics, $af->getExpertSystem());
            } else  if ($nextSemantic == "grounded") {
                array_push($allSemantics, $af->getGroundedExt());
            } else if ($nextSemantic == "eager") {
                array_push($allSemantics, $af->getEagerExt());
            } else if ($nextSemantic == "ideal") {
                array_push($allSemantics, $af->getIdealExt());
            } else if ($nextSemantic == "preferred") {
                array_push($allSemantics, $af->getPreferredExts());
            } else if ($nextSemantic == "stable") {
                array_push($allSemantics, $af->getStableExts());
            } else if ($nextSemantic == "semistable") {
                array_push($allSemantics, $af->getSemiStableExts());
            } else if ($nextSemantic == "admissible") {
                array_push($allSemantics, $af->getAdmissibleSets());
            } else if ($nextSemantic == "categoriser") {
                array_push($allSemantics, $af->getCategoriser());
            }
        }

        $response .= json_encode($allSemantics) . ";;";
    }
    //var_dump($response);

    $response = substr($response, 0, -2);

    $dBManager = new pdoDbManager ();
    $dbLink = $dBManager->openConnection ();
    $dataSetDAO = new DataSetDAO ($dBManager);
    // Pass username so multiple users can use computation table
    $dataSetDAO->saveComputations($response, $_SESSION["username"]);

} )->via ( "GET", "POST", "PUT", "DELETE" );

$app->map ( "/fileSemantics", "authenticate", function ($graph = null) use($app) {

    require_once "semantics/dungAF.php"; 

    $httpMethod = $app->request->getMethod ();
    $myfile = fopen("graphs.txt", "r") or die("Unable to open file!");
    $requests = explode(";;", fread($myfile,filesize("graphs.txt")));

    //var_dump(count($requests));

    $response = "";

    foreach ($requests as $r) {
        //echo($r);
        $graphAndSemantics = explode(";", $r);
        $graph = $graphAndSemantics[0];
        $semantics = explode(",", $graphAndSemantics[1]);

        $af = getDungAf($graph);

        $allSemantics = array();

        foreach ($semantics as $nextSemantic) {

            if ($nextSemantic == "expert") {
                array_push($allSemantics, $af->getExpertSystem());
            } else  if ($nextSemantic == "grounded") {
                array_push($allSemantics, $af->getGroundedExt());
            } else if ($nextSemantic == "eager") {
                array_push($allSemantics, $af->getEagerExt());
            } else if ($nextSemantic == "ideal") {
                array_push($allSemantics, $af->getIdealExt());
            } else if ($nextSemantic == "preferred") {
                array_push($allSemantics, $af->getPreferredExts());
            } else if ($nextSemantic == "stable") {
                array_push($allSemantics, $af->getStableExts());
            } else if ($nextSemantic == "semistable") {
                array_push($allSemantics, $af->getSemiStableExts());
            } else if ($nextSemantic == "admissible") {
                array_push($allSemantics, $af->getAdmissibleSets());
            } else if ($nextSemantic == "categoriser") {
                array_push($allSemantics, $af->getCategoriser());
            }
        }

        $response .= json_encode($allSemantics) . ";;";
    }
    //var_dump($response);

    // Save response in a file
    $fname = "extensions.txt";
    // Clean file
    file_put_contents($fname, "");
    $file = fopen($fname, 'w');
    // Remove last two ;;
    fwrite($file, substr($response, 0, -2));
    fclose($file);

    //$app->response->write(substr($response, 0, -2));

} )->via ( "GET", "POST", "PUT", "DELETE" );



$app->map ( "/levels", "authenticate", function ($featureset = null) use($app) {


    $action = null;

    include_once "models/model.php";
    include_once "controllers/controller.php";
    include_once "views/view.php";
    include_once "expertSystem/expertSystem.php";
    include_once "expertSystem/oldExpert.php";
    include_once "PHPExcel/PHPExcel.php";

    $model = new Model(); // common model

    $app->response->write(json_encode($model->levels(), JSON_PRETTY_PRINT));
} )->via ( "GET");

$app->map ( "/featuresetGraphs", "authenticate", function ($featureset = null) use($app) {


    $action = null;

    include_once "models/model.php";
    include_once "controllers/controller.php";
    include_once "views/view.php";
    include_once "expertSystem/expertSystem.php";
    include_once "expertSystem/oldExpert.php";
    include_once "PHPExcel/PHPExcel.php";

    $model = new Model(); // common model

    $app->response->write(json_encode($model->featuresetGraphs(), JSON_PRETTY_PRINT));
} )->via ( "GET");

$app->map ( "/featuresetArguments", "authenticate", function ($featureset = null) use($app) {


    $action = null;

    include_once "models/model.php";
    include_once "controllers/controller.php";
    include_once "views/view.php";
    include_once "expertSystem/expertSystem.php";
    include_once "expertSystem/oldExpert.php";
    include_once "PHPExcel/PHPExcel.php";

    $model = new Model(); // common model

    $app->response->write(json_encode($model->featuresetArguments(), JSON_PRETTY_PRINT));
} )->via ( "GET");

$app->map ( "/conclusionsByFeatureset", "authenticate", function ($featureset = null) use($app) {


    $action = null;

    include_once "models/model.php";
    include_once "controllers/controller.php";
    include_once "views/view.php";
    include_once "expertSystem/expertSystem.php";
    include_once "expertSystem/oldExpert.php";
    include_once "PHPExcel/PHPExcel.php";

    $model = new Model(); // common model

    $featuresets = $model->featuresets();
    $conclusionsByFeatureset = array();
    foreach($featuresets as $key => $value) {
        $conclusionsByFeatureset[$value] = $model->conclusionsByFeatureset($value);
    }

    $app->response->write(json_encode($conclusionsByFeatureset, JSON_PRETTY_PRINT));
} )->via ( "GET");

$app->map ( "/attributesByFeatureset", "authenticate", function ($featureset = null) use($app) {


    $action = null;

    include_once "models/model.php";
    include_once "controllers/controller.php";
    include_once "views/view.php";
    include_once "expertSystem/expertSystem.php";
    include_once "expertSystem/oldExpert.php";
    include_once "PHPExcel/PHPExcel.php";

    $model = new Model(); // common model

    $featuresets = $model->featuresets();

    $attributesByFeatureset = array();
    foreach($featuresets as $key => $value) {
        $attributesByFeatureset[$value] = $model->attributesByFeatureset($value);
    }

    $app->response->write(json_encode($attributesByFeatureset, JSON_PRETTY_PRINT));
} )->via ( "GET");

$app->run ();

class loadRunMVCComponents {

    public $model, $controller, $view;
    public function __construct($modelName, $controllerName, $viewName, $action, $app, $parameters = null) {
        include_once "models/model.php";
        include_once "controllers/controller.php";
        include_once "views/view.php";
        include_once "expertSystem/expertSystem.php";
        include_once "expertSystem/oldExpert.php";
        include_once "PHPExcel/PHPExcel.php";
        //include ("DB/DAO/UsersDAO.php");

        $model = new $modelName (); // common model
        $controller = new $controllerName ( $model, $action, $app, $parameters );
        $view = new $viewName ( $controller, $model, $app, $app->headers ); // common view
        $view->getHTMLOutput (); // this returns the response to the requesting client
    }
}

// *** finish tutorial with an example
// *** fix tooltips

?>
