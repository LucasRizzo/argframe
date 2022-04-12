<?php

/* database constants */
define ( "DB_HOST", "db" ); // set database host
define ( "DB_USER", "admin" ); // set database user
define ( "DB_PASS", "admin" ); // set database password
define ( "DB_PORT", "3306" ); // set database port
define ( "DB_NAME", "arg-db" ); // set database name
define ( "DB_CHARSET", "utf8" ); // set database charset
define ( "DB_DEBUGMODE", true ); // set database charset


define ("TITLE_WEB_APP", "Web dev. & dep.");

/* paths */
define ("BOOTSTRAP_PATH", "/style/bootstrap/css/bootstrap.css");
define ("BOOTSTRAP_MIN_PATH", "/style/bootstrap/css/bootstrap.min.css");
define ("BOOTSTRAP_RESPONSIVE_PATH", "/style/bootstrap/css/bootstrap-responsive.css");
define ("BOOTSTRAP_EDITABLE", "/style/bootstrap3-editable/css/bootstrap-editable.css");
define ("BOOTSTRAP_EDITABLE_JS", "/style/bootstrap3-editable/js/bootstrap-editable.js");
define ("GRAPH_STYLE", "/style/graphCreator.css");
define ("MY_ALTERATIONS", "/style/myAlterations.css");
define ("BOOTSTRAP_JS", "/style/bootstrap/js/bootstrap.js");
define ("BOOTSTRAP_MIN_JS", "/style/bootstrap/js/bootstrap.min.js");
define ("MODALS_JS", "/style/bootstrap/js/modals.js");
define ("D3", "/js/d3.v3.js");
define ("FILE_SAVER", "//cdn.jsdelivr.net/filesaver.js/0.1/FileSaver.min.js");
define ("GRAPH_CREATOR", "/js/graphCreator.js");
define ("GRAPH_VISUALIZATION", "/js/graphVisualization.js");
define ("GRAPH_EDITION", "/js/graphEdition.js");
define ("GRAPH_SMALL_VISUALIZATION", "/js/smallGraphVisualization.js");
define ("ATTRIBUTE_RANGE_VISUALIZATION", "/js/attributesRangeVisualization.js");
define ("VALIDATOR", "/js/validator.min.js");
define ("JQUERY", "https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js");
define ("TABLE", "/js/table.js");
define ("BOOTSTRAP_CONFIRMATION", "/js/bootstrap-confirmation.js");
define ("BOOTSTRAP_TOOLTIP", "/js/bootstrap-tooltip.js");
define ("JQUERY_CSV", "/js/jquery.csv.js");
define ("REQUIRE_JS", "/js/require.js");
define ("BOOLEAN_PARSER", "/js/boolean-parser.js");

/* HTTP status codes 2xx*/
define("HTTPSTATUS_OK", 200);
define("HTTPSTATUS_CREATED", 201);
define("HTTPSTATUS_NOCONTENT", 204);

/* HTTP status codes 3xx (with slim the output is not produced i.e. echo statements are not processed) */
define("HTTPSTATUS_NOTMODIFIED", 304);

/* HTTP status codes 4xx */
define("HTTPSTATUS_BADREQUEST", 400);
define("HTTPSTATUS_UNAUTHORIZED", 401);
define("HTTPSTATUS_FORBIDDEN", 403);
define("HTTPSTATUS_NOTFOUND", 404);
define("HTTPSTATUS_REQUESTTIMEOUT", 408);
define("HTTPSTATUS_GONE", 410);
define("HTTPSTATUS_TOKENREQUIRED", 499);

/* actions for the USERS REST resource */
define("ACTION_GET_GRAPH", 44);
define("ACTION_GET_ALL_GRAPHS", 99);
define("ACTION_LOGIN", 11);
define("ACTION_LOGIN_ERR", 12);
define("ACTION_GET_ALL_FEATURESETS", 101);
define("ACTION_GET_FEATURESET", 102);
define("ACTION_CREATE_FEATURESET", 103);
define("ACTION_DELETE_FEATURESET", 104);

define("VIEW_FEATURESET", 1010);
define("VIEW_GRAPH", 1011);



define("ACTION_CREATE_USER", 55);
define("ACTION_UPDATE_USER", 66);
define("ACTION_DELETE_USER", 77);
define("ACTION_SEARCH_USERS", 88);


/* general message */
define("GENERAL_MESSAGE_LABEL", "message");
define("GENERAL_SUCCESS_MESSAGE", "success");
define("GENERAL_ERROR_MESSAGE", "error");
define("GENERAL_NOCONTENT_MESSAGE", "no-content");
define("GENERAL_NOTMODIFIED_MESSAGE", "not modified");
define("GENERAL_INTERNALAPPERROR_MESSAGE", "internal app error");
define("GENERAL_CLIENT_ERROR", "client error: modify the request");
define("GENERAL_INVALIDTOKEN_ERROR", "Invalid token");
define("GENERAL_APINOTEXISTING_ERROR", "Api is not existing");
define("GENERAL_RESOURCE_CREATED", "Resource has been created");
define("GENERAL_RESOURCE_UPDATED", "Resource has been updated");
define("GENERAL_RESOURCE_DELETED", "Resource has been deleted");
define("GENERAL_INVALIDBODY", "Request is ok but transmitted body is invalid");

define("ADDRESS_PREFIX", "/");
define("ADDRESS_CALL", "http://localhost/index.php/");
define("ABSOLUTE_CALL", "/framework/");

$userList = array("luca"=>"pw1", "lucas@gmail.com" => "pw2", "luiz" => "benga");

define ("N_SYSTEMS", 8);

?>
