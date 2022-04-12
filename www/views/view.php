<?php

class View {
    private $model;
    private $controller;

    public function __construct($controller, $model, $slimApp) {
        $this->controller = $controller;
        $this->model = $model;
        $this->slimApp = $slimApp;
    }

    public function getHTMLOutput() {

        $view_slimStatus = $this->slimApp->response()->status();
        $view_loggedErr = $this->model->loggedErr;

        if ($view_slimStatus != HTTPSTATUS_OK) {
            $this->apiResponse();
        } else if (! $this->model->logged) {
            include ("template_login.php");
            // In case it is not logged no need to access further data.
            return;
        }

        $view_bootstrap_min_js = BOOTSTRAP_MIN_JS;
        $view_modals_js = MODALS_JS;

        $view_footer = "Lucas Rizzo d15123771";

        $view_userLogin = $this->model->getLoginUser();

        $view_error = $this->model->login;

        $view_featureset = $this->model->featureset;

        $view_featuresetHeader = $this->model->featuresetHeader;

        $view_featuresets = $this->model->featuresets();
        
        $view_allUserFeaturesets = $this->model->allUserFeaturesets();

        $view_featuresetsWithGraphs = $this->model->featuresetsWithGraphs(); 

        $view_attributes = $this->model->attributes();

        $view_attributesByFeatureset = array();
        foreach($view_featuresets as $key => $featureset) {
            $view_attributesByFeatureset[$featureset] = $this->model->attributesByFeatureset($featureset);
        }

        $view_conclusionsByFeatureset = array();
        foreach($view_featuresets as $key => $featureset) {
            $view_conclusionsByFeatureset[$featureset] = $this->model->conclusionsByFeatureset($featureset);
        }

        $view_allFeaturesets = $this->model->getAllFeaturesets();

        $view_featuresetGraphs = $this->model->featuresetGraphs();
        $view_featuresetArguments = $this->model->featuresetArguments();
        $view_levels = $this->model->levels();
        $view_action = $this->controller->getAction();
        $view_error = $this->model->error;
        $view_errorType = $this->model->errorType;
        $view_success = $this->model->success;
        $view_successType = $this->model->successType;
        $view_currentGraph = $this->model->currentGraph;
        $view_currentFeatureset = $this->model->currentFeatureset;

        $view_indexes = $this->model->indexes;
        $view_reasoningsForecast = $this->model->reasoningsForecast;
        $view_reasoningsNotForecast = $this->model->reasoningsNotForecast;
        $view_reasoningsHeuristic = $this->model->reasoningsHeuristic;

        $view_apiResponse = $this->model->apiResponse;
        $view_apiResponseGraphs = $this->model->apiResponseGraphs;

        $view_apiVisualization = $this->model->apiVisualization;

        include ("template_html.php");
    }

    public function apiResponse(){
        //prepare json response
        $jsonResponse = json_encode($this->model->apiResponse);
        $this->slimApp->response->write($jsonResponse);
    }
}

?>
