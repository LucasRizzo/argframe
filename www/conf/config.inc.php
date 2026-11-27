<?php

/* Deployment configuration: environment variables override an optional local file. */
$localConfigPath = __DIR__ . '/config.local.php';
$localConfig = is_file($localConfigPath) ? require $localConfigPath : [];
if (!is_array($localConfig)) {
    throw new RuntimeException('Invalid local configuration.');
}
function argframeConfig(string $name, $default = null) {
    global $localConfig;
    $value = getenv($name);
    return $value !== false ? $value : ($localConfig[$name] ?? $default);
}

define("DB_HOST", argframeConfig("ARGFRAME_DB_HOST", "db"));
define("DB_USER", argframeConfig("ARGFRAME_DB_USER", "admin"));
define("DB_PASS", argframeConfig("ARGFRAME_DB_PASSWORD", ""));
define("DB_PORT", argframeConfig("ARGFRAME_DB_PORT", "3306"));
define("DB_NAME", argframeConfig("ARGFRAME_DB_NAME", "arg-db"));
define("DB_CHARSET", "utf8");
define("DB_DEBUGMODE", false);

define ("TITLE_WEB_APP", "Web dev. & dep.");

// Resolve the application directory from the executing script, not the request host.
// Use same-origin URLs so cookies and CSRF headers remain on the deployment origin.
$scriptDirectory = dirname($_SERVER['SCRIPT_NAME'] ?? '/index.php');
$basePath = trim((string) argframeConfig('ARGFRAME_BASE_PATH', $scriptDirectory), '/');
if ($basePath === '.') $basePath = '';
if (!preg_match('~^(?:[a-zA-Z0-9._-]+/)*[a-zA-Z0-9._-]*$~D', $basePath)
    || in_array('..', explode('/', $basePath), true)) {
    throw new RuntimeException('Invalid application base path.');
}
define("ADDRESS_PREFIX", $basePath === '' ? '/' : '/' . $basePath . '/');
define("ADDRESS_CALL", ADDRESS_PREFIX . "index.php/");
define("ABSOLUTE_CALL", ADDRESS_PREFIX);

/* paths */
define ("BOOTSTRAP_PATH", ADDRESS_PREFIX . "style/bootstrap/css/bootstrap.css");
define ("BOOTSTRAP_MIN_PATH", ADDRESS_PREFIX . "style/bootstrap/css/bootstrap.min.css");
define ("BOOTSTRAP_RESPONSIVE_PATH", ADDRESS_PREFIX . "style/bootstrap/css/bootstrap-responsive.css");
define ("BOOTSTRAP_EDITABLE", ADDRESS_PREFIX . "style/bootstrap3-editable/css/bootstrap-editable.css");
define ("BOOTSTRAP_EDITABLE_JS", ADDRESS_PREFIX . "style/bootstrap3-editable/js/bootstrap-editable.js");
define ("GRAPH_STYLE", ADDRESS_PREFIX . "style/graphCreator.css");
define ("MY_ALTERATIONS", ADDRESS_PREFIX . "style/myAlterations.css");
define ("BOOTSTRAP_JS", ADDRESS_PREFIX . "style/bootstrap/js/bootstrap.js");
define ("BOOTSTRAP_MIN_JS", ADDRESS_PREFIX . "style/bootstrap/js/bootstrap.min.js");
define ("MODALS_JS", ADDRESS_PREFIX . "style/bootstrap/js/modals.js");
define ("D3", ADDRESS_PREFIX . "js/d3.v3.js");
define ("FILE_SAVER", "//cdn.jsdelivr.net/filesaver.js/0.1/FileSaver.min.js");
define ("GRAPH_CREATOR", ADDRESS_PREFIX . "js/graphCreator.js");
define ("GRAPH_VISUALIZATION", ADDRESS_PREFIX . "js/graphVisualization.js");
define ("GRAPH_EDITION", ADDRESS_PREFIX . "js/graphEdition.js");
define ("GRAPH_SMALL_VISUALIZATION", ADDRESS_PREFIX . "js/smallGraphVisualization.js");
define ("ATTRIBUTE_RANGE_VISUALIZATION", ADDRESS_PREFIX . "js/attributesRangeVisualization.js");
define ("VALIDATOR", ADDRESS_PREFIX . "js/validator.min.js");
define ("JQUERY", "https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js");
define ("TABLE", ADDRESS_PREFIX . "js/table.js");
define ("BOOTSTRAP_CONFIRMATION", ADDRESS_PREFIX . "js/bootstrap-confirmation.js");
define ("BOOTSTRAP_TOOLTIP", ADDRESS_PREFIX . "js/bootstrap-tooltip.js");
define ("JQUERY_CSV", ADDRESS_PREFIX . "js/jquery.csv.js");
define ("REQUIRE_JS", ADDRESS_PREFIX . "js/require.js");
define ("BOOLEAN_PARSER", ADDRESS_PREFIX . "js/boolean-parser.js");

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




define ("N_SYSTEMS", 8);

?>
