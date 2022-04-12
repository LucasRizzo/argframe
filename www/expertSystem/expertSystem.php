<?php


/* This is the same file in the online IDE. However it is not working, probably
   cause in the IDE it is exporting everything, and here in some part it is 
   required to compute only one instance of the expert system. */

class ExpertSystem {

    private $atrributes;

    // Task difficult is a derivation of sd, sr, ts, vm, vr, ar, mr and sp
    private $taskDifficult;

    // An associative array which gives for each attribute a value between [0, 100]
    private $inputs;

    // An associative array which sets for each attribute one of possible four
    // values: underload, fittingMinus, fittingPlus and overload
    private $dichotomy;

    private $reasoning;

    // TODO: Implement a description in the xls cell about the expert system
    private $systemDescription;

    public function __construct($inputs) {

        /* List of attributes being used as input for the inferrance of mental
           workload:
        *  md = mental demand
        *  td = temporal demand
        *  pd = physical demand
        *  sd = solving and deciding (central)
        *  sr = selection of response (response)
        *  ts = task and space (spatial proc)
        *  vm = verbal material
        *  vr = visual resource
        *  ar = auditory resources
        *  mr = manual response
        *  sp = speech response
        *  ef = effort
        *  pa = parallelism
        *  cb = context bias
        *  pf = performance
        *  ps = psychological stress (frustration)
        *  pk = past knowledge
        *  sk = skills
        *  mv = motivation (intention)
        *  ao = arousal
        */

        $this->attributes = array("md", "td", "pd", "sd", "sr", "ts", "vm", "vr",
                                  "ar", "mr", "sp", "ef", "pa", "cb", "pf", "ps",
                                  "pk", "sk", "mv");

        $this->inputs = $inputs;
    }

    public function reasoning($solveConflicts = true, $attributes = 1) {


        if ($attributes == 1) {
            $attributes = $this->attributes;
        }

        $this->reasoning = "";

        $this->taskDifficult = $this->getDichotomy("task", 1.0/8.0 * ($this->inputs["sd"] +
                                                                      $this->inputs["sr"] +
                                                                      $this->inputs["ts"] +
                                                                      $this->inputs["vm"] +
                                                                      $this->inputs["vr"] +
                                                                      $this->inputs["ar"] +
                                                                      $this->inputs["mr"] +
                                                                      $this->inputs["sp"]));

        //var_dump($this->inputs);

        // Compute arousal
        $this->dichotomy["ao"] = $this->getDichotomy("ao", $this->inputs["ao"]);

        // Apply rules from group 1
        foreach ($attributes as $att) {

            $this->dichotomy[$att] = $this->getDichotomy($att, $this->inputs[$att]);
        }

        if ($solveConflicts) {
            $this->denyDichotomy();
        }
    }

    public function getAllIndexes() {

        $workloadIndexes = [];
        $workloadIndexes[0] = $this->computeWorkload1();
        //echo "<br><br>";
        $workloadIndexes[1] = $this->computeWorkload2();
        //echo "<br><br>";
        $workloadIndexes[2] = $this->computeWorkload3();
        //echo "<br><br>";
        $workloadIndexes[3] = $this->computeWorkload4();
        $workloadIndexes[4] = $this->computeWorkload5();
        $workloadIndexes[5] = $this->computeWorkload6();
        $workloadIndexes[6] = $this->computeWorkload7();
        $workloadIndexes[7] = $this->computeWorkload8();
        $workloadIndexes[8] = $this->computeWorkload9();
        $workloadIndexes[9] = $this->computeWorkload10();
        $workloadIndexes[10] = $this->computeWorkload11();
        $workloadIndexes[11] = $this->computeWorkload12();
        $workloadIndexes[12] = $this->computeWorkload13();
        $workloadIndexes[13] = $this->computeWorkload14();
        $workloadIndexes[14] = $this->computeWorkload15();
        $workloadIndexes[15] = $this->computeWorkload16();

        return ($workloadIndexes);
    }

    public function getReasoning() {
        return $this->reasoning;
    }

    /*public function compute($id) {
        $this->computeWorkload1($id);
        //$this->computeWorkload2(1);
        //$this->computeWorkload3(2);
        //$this->computeWorkload4(3);
        //$this->computeWorkload1(4, false);
        //$this->computeWorkload2(5, false);
        //$this->computeWorkload3(6, false);
        //$this->computeWorkload4(7, false);
    }*/


    // Returns the highest input average of the dichotomy with the highest
    // number of surviving arguments
    public function computeWorkload1($solveConflicts = true) {

        $this->reasoning($solveConflicts);

        $dichotomies = array("underload", "fittingMinus", "fittingPlus", "overload");

        $nDichotomy = array("underload" => 0.0, "fittingMinus" => 0.0,
                            "fittingPlus" => 0.0, "overload" => 0.0);

        $workloadIndex = array("underload" => 0.0, "fittingMinus" => 0.0,
                               "fittingPlus" => 0.0, "overload" => 0.0);

        foreach ($this->attributes as $att) {

            if ($this->dichotomy[$att] != "undefined") {
                $nDichotomy[$this->dichotomy[$att]]++;

                $this->increaseIndex($workloadIndex[$this->dichotomy[$att]], $att);
            }
        }

        foreach ($dichotomies as $d) {
            // Index will be the average of the surviving dichotomies
            if ($nDichotomy[$d] > 0) {
                $workloadIndex[$d] = $workloadIndex[$d] / $nDichotomy[$d];
            }
        }


        // Choose the dichotomy which have the highest number of surviving arguments
        // If there are more than 2 return the average between them
        $bestDichotomy = "underload";

        $i = 0;
        $setBest = [];
        $setBest[$i] = $bestDichotomy;

        foreach ($dichotomies as $d) {

            $this->reasoning .= "<b>" . $d . "</b> has " . $nDichotomy[$d] .
                                " surviding rules. Average inputs: " . $workloadIndex[$d] . " <br>";

            if ($nDichotomy[$d] > $nDichotomy[$bestDichotomy]) {
                $bestDichotomy = $d;
                $i = 0;
                $setBest[$i] = $bestDichotomy;
            }

            if ($nDichotomy[$d] == $nDichotomy[$bestDichotomy]) {
                $i++;
                $setBest[$i] = $d;
            }
        }

        $average = 0;
        foreach($setBest as $s) {
            $average += $workloadIndex[$s] / ($i + 1);
        }

        return $average;
    }

    // Return the highest input average according to each dichotomy
    public function computeWorkload2($solveConflicts = true) {

        $this->reasoning($solveConflicts);

        $dichotomies = array("underload", "fittingMinus", "fittingPlus", "overload");

        $nDichotomy = array("underload" => 0.0, "fittingMinus" => 0.0,
                            "fittingPlus" => 0.0, "overload" => 0.0);

        $workloadIndex = array("underload" => 0.0, "fittingMinus" => 0.0,
                               "fittingPlus" => 0.0, "overload" => 0.0);

        foreach ($this->attributes as $att) {

            if ($this->dichotomy[$att] != "undefined") {
                $nDichotomy[$this->dichotomy[$att]]++;
                $this->increaseIndex($workloadIndex[$this->dichotomy[$att]], $att);
            }
        }

        foreach ($dichotomies as $d) {
            // Index will be the average of the surviving dichotomies
            if ($nDichotomy[$d] > 0) {
                $workloadIndex[$d] = $workloadIndex[$d] / $nDichotomy[$d];
            }
        }

        $bestDichotomy = "underload";
        foreach ($dichotomies as $d) {

            if ($workloadIndex[$d] > $workloadIndex[$bestDichotomy]) {
                $bestDichotomy = $d;
            }
        }

        return $workloadIndex[$bestDichotomy];
    }

    // Average of surviving arguments
    public function computeWorkload3($solveConflicts = true, $attributes = 1) {

        $this->reasoning($solveConflicts, $attributes);
        //echo "<br>Inicio ";

        $workloadIndex = 0;
        $nDichotomy = 0;

        ////echo "(" . $this->dichotomy["ps"] . ")";

        foreach ($this->attributes as $att) {

            if ($this->dichotomy[$att] != "undefined") {
                $this->increaseIndex($workloadIndex, $att);
                $nDichotomy++;
            }
        }

        //echo " Fim<br>";

        if ($nDichotomy > 0) {
            return max($workloadIndex / $nDichotomy, 0);
        } else {
            return 0;
        }
    }

    // Compute the average of all four surviving dichotomies, and return
    // the average of these four
    public function computeWorkload4($solveConflicts = true) {

        $this->reasoning();

        $dichotomies = array("underload", "fittingMinus", "fittingPlus", "overload");

        $nDichotomy = array("underload" => 0.0, "fittingMinus" => 0.0,
                            "fittingPlus" => 0.0, "overload" => 0.0);

        $workloadIndex = array("underload" => 0.0, "fittingMinus" => 0.0,
                               "fittingPlus" => 0.0, "overload" => 0.0);

        foreach ($this->attributes as $att) {

            if ($this->dichotomy[$att] != "undefined") {
                $nDichotomy[$this->dichotomy[$att]]++;
                $this->increaseIndex($workloadIndex[$this->dichotomy[$att]], $att);
            }
        }

        $average = 0.0;
        $surviving = 0.0;
        foreach ($dichotomies as $d) {
            // Index will be the average of the surviving dichotomies
            if ($nDichotomy[$d] > 0) {
                $workloadIndex[$d] = $workloadIndex[$d] / $nDichotomy[$d];
                $surviving++;
                $average += max($workloadIndex[$d], 0);
            }
        }

        $average /= $surviving;

        return max($average, 0);
    }

    // Reasoining without solving conflicts and return the average of the four
    // dichotomies
    public function computeWorkload5() {

        return $this->computeWorkload1(false);
    }

    // Reasoning without solving conflicts and return the average of all inputs
    public function computeWorkload6() {

        return $this->computeWorkload2(false);
    }

    public function computeWorkload7() {

        return $this->computeWorkload3(false);
    }

    public function computeWorkload8() {

        return $this->computeWorkload2(false);
    }

    public function computeWorkload9() {

        foreach ($this->attributes as $att) {
            $this->dichotomy[$att] = "undefined";
        }

        // NASA-TLX attributes
        $attributes = array("md", "td", "pd", "ef", "pf", "ps");

        return $this->computeWorkload3(true, $attributes);
    }

    public function computeWorkload10() {

        foreach ($this->attributes as $att) {
            $this->dichotomy[$att] = "undefined";
        }

        // WP attributes
        // Group 2 rules makes no different. Result is the same as WP alone
        $attributes = array("sd", "sr", "ts", "vm", "vr", "ar", "mr", "sp");

        return $this->computeWorkload3(true, $attributes);
    }

    public function computeWorkload11() {

        foreach ($this->attributes as $att) {
            $this->dichotomy[$att] = "undefined";
        }

        // NASA + ES
        $attributes = array("pa", "cb", "pk", "sk", "mv", "md", "td", "pd", "ef", "pf", "ps");

        return $this->computeWorkload3(true, $attributes);
    }

    public function computeWorkload12() {

        foreach ($this->attributes as $att) {
            $this->dichotomy[$att] = "undefined";
        }

        // WP + ES
        $attributes = array("pa", "cb", "pk", "sk", "mv", "sd", "sr", "ts", "vm", "vr", "ar", "mr", "sp");

        return $this->computeWorkload3(true, $attributes);
    }

    public function computeWorkload13() {

        foreach ($this->attributes as $att) {
            $this->dichotomy[$att] = "undefined";
        }

        // NASA + WP
        $attributes = array("md", "td", "pd", "ef", "pf", "ps", "sd", "sr", "ts", "vm", "vr", "ar", "mr", "sp");

        return $this->computeWorkload3(true, $attributes);
    }

    public function computeWorkload14() {

        foreach ($this->attributes as $att) {
            $this->dichotomy[$att] = "undefined";
        }

        // My knowledge bases (intuition)
        $attributes = array("md", "td", "pf", "sk", "mv");

        return $this->computeWorkload3(true, $attributes);
    }

    public function computeWorkload15() {

        foreach ($this->attributes as $att) {
            $this->dichotomy[$att] = "undefined";
        }

        // Best diagnosticity attributes
        $attributes = array("ar", "td", "cb", "vm", "sr", "pf");

        return $this->computeWorkload3(true, $attributes);
    }

    public function computeWorkload16() {

        foreach ($this->attributes as $att) {
            $this->dichotomy[$att] = "undefined";
        }

        // My knowledge + best diagnosticity
        $attributes = array("ar", "td", "cb", "vm", "sr", "pf", "md", "sk", "mv");

        return $this->computeWorkload3(true, $attributes);
    }


    // Heuristic 3 but only with NASA-TLX attributes
    public function computeWorkload17() {

        $this->reasoning = "";

        $this->taskDifficult = $this->getDichotomy("task", 1.0/8.0 * ($this->inputs["sd"] +
                                                                      $this->inputs["sr"] +
                                                                      $this->inputs["ts"] +
                                                                      $this->inputs["vm"] +
                                                                      $this->inputs["vr"] +
                                                                      $this->inputs["ar"] +
                                                                      $this->inputs["mr"] +
                                                                      $this->inputs["sp"]));

        // Compute arousal
        $this->dichotomy["ao"] = $this->getDichotomy("ao", $this->inputs["ao"]);

        // Apply rules from group 1
        foreach ($this->attributes as $att) {

            $this->dichotomy[$att] = "undefined";
        }

        // Nasa sensitivity 12 group 1, 6 group 2
        // WP sensitivity 6 group 1, 3 group 2

        // NASA-TLX attributes
        //$attributes = array("md", "td", "pd", "ef", "pf", "ps");

        // WP attributes
        // Group 2 rules makes no different. Result is the same as WP alone
        $attributes = array("sd", "sr", "ts", "vm", "vr", "ar", "mr", "sp");

        // 12 sensitivity, 31% diagnosticity, normal, 0.8 correlation NASA,
        // 0.5 correlation WP
        // NASA + ES
        //$attributes = array("pa", "cb", "pk", "sk", "mv", "md", "td", "pd", "ef", "pf", "ps");

        // WP + ES
        // 9 sensitivity, normal, 0.489 correlation NASA, 0.805 correlation WP,
        // diagnosticity 37.4%
        //$attributes = array("pa", "cb", "pk", "sk", "mv", "sd", "sr", "ts", "vm", "vr", "ar", "mr", "sp");

        // NASA + WP
        // Normal, NASA 0.696, WP 0.834, 11 sensitivity group 1, 1 sensitivity group 2
        // diagnosticity 43.3%
        //$attributes = array("md", "td", "pd", "ef", "pf", "ps", "sd", "sr", "ts", "vm", "vr", "ar", "mr", "sp");

        // My knowledge bases (intuition)
        // normal, sensitivity 13 group 1, 7 group 2, 0.638 Nasa, 0.329 WP
        // diagnosticity 20%
        //$attributes = array("md", "td", "pf", "sk", "mv");

        // Best diagnosticity attributes
        // Sensitivity 14 group 1, 5 group 2, nasa 0.553, wp 0.648,
        // diagnosticity 30.5%
        //$attributes = array("ar", "td", "cb", "vm", "sr", "pf");

        // My knowledge + best diagnosticity
        // normal, nasa 0.605, wp 0.538, 14 group 1, 6 group 2
        // diagnosticity 34.2%
        //$attributes = array("ar", "td", "cb", "vm", "sr", "pf", "md", "sk", "mv");

        // ES, WP and NASA-TLX -> not good

        // Apply rules from group 1
        foreach ($attributes as $att) {

            $this->dichotomy[$att] = $this->getDichotomy($att, $this->inputs[$att]);
        }

        //if ($solveConflicts) {
        $this->denyDichotomy();
        //}

        // Average of surviving rules is exactly the same as WP because there
        // is no denied rule.
        foreach ($attributes as $att) {

            if ($this->dichotomy[$att] != "undefined") {
                $this->increaseIndex($workloadIndex, $att);
                $nDichotomy++;
            }
        }

        if ($nDichotomy > 0) {
            return max($workloadIndex / $nDichotomy, 0);
        } else {
            return 0;
        }
    }

    public function computeWorkload18() {

        $this->reasoning();

        $dichotomies = array("underload", "fittingMinus", "fittingPlus", "overload");

        $nDichotomy = array("underload" => 0.0, "fittingMinus" => 0.0,
                            "fittingPlus" => 0.0, "overload" => 0.0);

        $workloadIndex = array("underload" => 0.0, "fittingMinus" => 0.0,
                               "fittingPlus" => 0.0, "overload" => 0.0);

        foreach ($this->attributes as $att) {

            if ($this->dichotomy[$att] != "undefined") {
                $nDichotomy[$this->dichotomy[$att]]++;

                $this->increaseIndex($workloadIndex[$this->dichotomy[$att]], $att);
            }
        }

        foreach ($dichotomies as $d) {
            // Index will be the average of the surviving dichotomies
            if ($nDichotomy[$d] > 0) {
                $workloadIndex[$d] = $workloadIndex[$d] / $nDichotomy[$d];
            }
        }

        if ($nDichotomy["underload"] > $nDichotomy["fittingMinus"] + $nDichotomy["fittingPlus"] +
                                       $nDichotomy["overload"]) {

           return $workloadIndex["underload"];
        }

        if ($nDichotomy["fittingMinus"] > $nDichotomy["underload"] + $nDichotomy["fittingPlus"] +
                                       $nDichotomy["overload"]) {

           return $workloadIndex["fittingMinus"];
        }

        if ($nDichotomy["fittingPlus"] > $nDichotomy["fittingMinus"] + $nDichotomy["underload"] +
                                       $nDichotomy["overload"]) {

           return $workloadIndex["fittingPlus"];
        }

        if ($nDichotomy["overload"] > $nDichotomy["fittingMinus"] + $nDichotomy["fittingPlus"] +
                                       $nDichotomy["underload"]) {

           return $workloadIndex["overload"];
        }

        if ($nDichotomy["overload"] + $nDichotomy["fittingPlus"] >
            $nDichotomy["underload"] + $nDichotomy["fittingMinus"] ) {

            return ($workloadIndex["overload"] + $workloadIndex["fittingPlus"]) / 2.0;
        }

        if ($nDichotomy["overload"] + $nDichotomy["fittingPlus"] <
            $nDichotomy["underload"] + $nDichotomy["fittingMinus"] ) {

            return ($workloadIndex["underload"] + $workloadIndex["fittingMinus"]) / 2.0;
        }

        $average = 0.0;
        $n = 0.0;
        foreach ($dichotomies as $d) {
            if ($nDichotomy[$d] > 0) {
                $average += $workloadIndex[$d];
                $n++;
            }
        }

        return $average / $n;
    }

    private function increaseIndex(&$workloadIndex, $attribute) {
        // Past knowledge, skills and performance have an inverted
        // relationship with mental workload. Because of that, they
        // will contribute with the supplement of the overall mental
        // while the other attributes will contribue positively.
        if ($attribute == "pk" ||
            $attribute == "sk" ||
            $attribute == "pf") {

            $v = 0;

            if ($this->inputs[$attribute] == 0) {
                $workloadIndex += 100;
                $v = 100;

            } else if ($this->inputs[$attribute] < 100) {
                $workloadIndex += (99 - $this->inputs[$attribute]);
                $v = (99 - $this->inputs[$attribute]);
            }

            //echo $attribute . " (" . $v . ") ";
        } else {
            $workloadIndex += $this->inputs[$attribute];
            //echo $attribute . " (" . $this->inputs[$attribute] . ") ";
        }
    }

    private function denyDichotomy() {

        $newDichotomy = $this->dichotomy;

        ////echo "*** arousal " . $this->dichotomy["ao"] . " task " . $this->taskDifficult . " pf " . $this->dichotomy["pf"];

        // Deny performance according to task difficult and arousal
        if ($this->dichotomy["ao"] == "low") {
            if ($this->taskDifficult == "easy") {

                if ($this->dichotomy["pf"] == "underload") {
                    //echo "<br>$$ pf 1 $$";
                    $newDichotomy["pf"] = "undefined";
                } else if ($this->dichotomy["pf"] == "fittingPlus") {
                    //echo "<br>$$ pf 2 $$";
                    $newDichotomy["pf"] = "undefined";
                } else if ($this->dichotomy["pf"] == "fittingMinus") {
                    //echo "<br>$$ pf 3 $$";
                    $newDichotomy["pf"] = "undefined";
                }

            } else if ($this->taskDifficult == "difficult") {
                if ($this->dichotomy["pf"] == "underload") {
                    //echo "<br>$$ pf 4 $$";
                    $newDichotomy["pf"] = "undefined";
                } else if ($this->dichotomy["pf"] == "fittingPlus") {
                    //echo "<br>$$ pf 5 $$";
                    $newDichotomy["pf"] = "undefined";
                } else if ($this->dichotomy["pf"] == "fittingMinus") {
                    //echo "<br>$$ pf 6 $$";
                    $newDichotomy["pf"] = "undefined";
                }
            }
        } else if ($this->dichotomy["ao"] == "medium lower") {
            if ($this->taskDifficult == "easy") {

                if ($this->dichotomy["pf"] == "overload") {
                    //echo "<br>$$ pf 7 $$";
                    $newDichotomy["pf"] = "undefined";
                } else if ($this->dichotomy["pf"] == "underload") {
                    //echo "<br>$$ pf 8 $$";
                    $newDichotomy["pf"] = "undefined";
                }

            } else if ($this->taskDifficult == "difficult") {
                if ($this->dichotomy["pf"] == "overload") {
                    $newDichotomy["pf"] = "undefined";
                    //echo "<br>$$ pf 9 $$";
                } else if ($this->dichotomy["pf"] == "underload") {
                    //echo "<br>$$ pf 10 $$";
                    $newDichotomy["pf"] = "undefined";
                } else if ($this->dichotomy["pf"] == "fittingMinus") {
                    //echo "<br>$$ pf 11 $$";
                    $newDichotomy["pf"] = "undefined";
                }
            }
        } else if ($this->dichotomy["ao"] == "medium upper") {
            if ($this->taskDifficult == "easy") {
                if ($this->dichotomy["pf"] == "overload") {
                    //echo "<br>$$ pf 12 $$";
                    $newDichotomy["pf"] = "undefined";
                } else if ($this->dichotomy["pf"] == "fittingPlus") {
                    //echo "<br>$$ pf 13 $$";
                    $newDichotomy["pf"] = "undefined";
                } else if ($this->dichotomy["pf"] == "fittingMinus") {
                    //echo "<br>$$ pf 14 $$";
                    $newDichotomy["pf"] = "undefined";
                }

            } else if ($this->taskDifficult == "difficult") {
                if ($this->dichotomy["pf"] == "overload") {
                    //echo "<br>$$ pf 15 $$";
                    $newDichotomy["pf"] = "undefined";
                } else if ($this->dichotomy["pf"] == "fittingMinus") {
                    //echo "<br>$$ pf 16 $$";
                    $newDichotomy["pf"] = "undefined";
                } else if ($this->dichotomy["pf"] == "underload") {
                    //echo "<br>$$ pf 17 $$";
                    $newDichotomy["pf"] = "undefined";
                }
            }
        } else if ($this->dichotomy["ao"] == "high") {
            if ($this->taskDifficult == "easy") {
                if ($this->dichotomy["pf"] == "fittingMinus") {
                    //echo "<br>$$ pf 18 $$";
                    $newDichotomy["pf"] = "undefined";
                } else if ($this->dichotomy["pf"] == "fittingPlus") {
                    //echo "<br>$$ pf 19 $$";
                    $newDichotomy["pf"] = "undefined";
                } else if ($this->dichotomy["pf"] == "overload") {
                    //echo "<br>$$ pf 20 $$";
                    $newDichotomy["pf"] = "undefined";
                }

            } else if ($this->taskDifficult == "difficult") {
                if ($this->dichotomy["pf"] == "underload") {
                    //echo "<br>$$ pf 21 $$";
                    $newDichotomy["pf"] = "undefined";
                } else if ($this->dichotomy["pf"] == "fittingPlus") {
                    //echo "<br>$$ pf 22 $$";
                    $newDichotomy["pf"] = "undefined";
                } else if ($this->dichotomy["pf"] == "fittingMinus") {
                    //echo "<br>$$ pf 23 $$";
                    $newDichotomy["pf"] = "undefined";
                }
            }
        }

        if ($newDichotomy["pf"] != $this->dichotomy["pf"]) {
            $this->reasoning .= "<b>Performance</b> can't infer " . $this->dichotomy["pf"] . " due to "
                                 . $this->dichotomy["ao"] . " <b>arousal</b> and "
                                 . $this->taskDifficult . " <b>task</b> <br>";
        }

        // Deny effort according to motivation
        if ($this->dichotomy["mv"] == "underload" && ($this->dichotomy["ef"] == "fittingPlus" || $this->dichotomy["ef"] == "overload")) {

            $this->reasoning .= "<b>Effort</b> can't infer " . $this->dichotomy["ef"] . " due to "
                                . $this->dichotomy["mv"] . " <b>motivation</b><br>";

            $newDichotomy["ef"] = "undefined";
        }

        // Deny effort according to motivation
        if ($this->inputs["mv"] >= 67 && ($this->dichotomy["ef"] == "fittingMinus" || $this->dichotomy["ef"] == "underload")) {

            $this->reasoning .= "<b>Effort</b> can't infer " . $this->dichotomy["ef"] . " due to "
                                . $this->dichotomy["mv"] . " <b>motivation</b><br>";

            $newDichotomy["ef"] = "undefined";
        }

        // Deny effort or performanace according to task difficult, skills and
        // effort
        if ($this->taskDifficult == "difficult" && $this->inputs["sk"] >= 67) {
            if ($this->dichotomy["ef"] == "overload") {

                $this->reasoning .= "<b>Effort</b> can't infer " . $this->dichotomy["ef"] . " due to "
                                    . "<b>high skills</b> and "
                                    . $this->taskDifficult . " <b>task</b> <br>";

                $newDichotomy["ef"] = "undefined";
            }

            if ($this->inputs["ef"] < 67) {
                if ($this->dichotomy["pf"] == "underload") {

                    $effort = "medium upper";

                    if ($this->inputs["ef"] < 33) {
                        $effort = "low";
                    } else if ($this->inputs["ef"] < 51) {
                        $effort = "medium lower";
                    }

                    $this->reasoning .= "<b>Performance</b> can't infer " . $this->dichotomy["pf"] . " due to "
                                        . "<b>high skills</b>, " . $this->taskDifficult . " task "
                                        . "and " . $effort . " <b>effort</b><br>";

                    //echo "<br>$$ pf 24 $$";
                    $newDichotomy["pf"] = "undefined";
                }
            }
        }



        // Deny motivation and solve and deciding if they have contradictory
        // information
        if ($this->dichotomy["md"] == "underload" && $this->dichotomy["sd"] == "overload") {

            $this->reasoning .= "<b>Mental demand</b> can't infer underload at the same time "
                                . "<b>solve and deciding</b> infers overload<br>";

            $newDichotomy["md"] = "undefined";
            $newDichotomy["sd"] = "undefined";
        } else if ($this->inputs["md"] >= 67 && $this->dichotomy["sd"] == "underload") {

            $this->reasoning .= "<b>Mental demand</b> can't infer overload at the same time "
                                . "<b>solve and deciding</b> infers underload<br>";

            $newDichotomy["md"] = "undefined";
            $newDichotomy["sd"] = "undefined";
        }


        // Deny past knowledge and skills if they have contradictory information
        if ($this->dichotomy["pk"] == "overload" && $this->dichotomy["sk"] == "underload") {

            $this->reasoning .= "<b>Past knowledge</b> can't infer overload at the same time "
                                . "<b>skills</b> infers underload<br>";

            $newDichotomy["pk"] = "undefined";
            $newDichotomy["sk"] = "undefined";
        }

        if ($this->dichotomy["pk"] == "underload" && $this->dichotomy["sk"] == "overload") {

            $this->reasoning .= "<b>Past knowledge</b> can't infer underload at the same time "
                                . "<b>skills</b> infers overload<br>";

            $newDichotomy["pk"] = "undefined";
            $newDichotomy["sk"] = "undefined";
        }

        if ($this->dichotomy["pk"] == "overload" && $this->dichotomy["ef"] == "underload") {

            $this->reasoning .= "<b>Past knowledge</b> can't infer overload at the same time "
                                . "<b>effort</b> infers underload<br>";

            $newDichotomy["pk"] = "undefined";
            $newDichotomy["ef"] = "undefined";
        }

        if ($this->dichotomy["pk"] == "underload" && $this->dichotomy["ef"] == "overload") {

            $this->reasoning .= "<b>Past knowledge</b> can't infer overload at the same time "
                                . "<b>effort</b> infers underload<br>";

            $newDichotomy["pk"] = "undefined";
            $newDichotomy["ef"] = "undefined";
        }

        if ($this->dichotomy["sk"] == "overload" && $this->dichotomy["ef"] == "underload") {

            $this->reasoning .= "<b>Skills</b> can't infer overload at the same time "
                                . "<b>effort</b> infers underload<br>";

            $newDichotomy["sk"] = "undefined";
            $newDichotomy["ef"] = "undefined";
        }

        if ($this->dichotomy["sk"] == "underload" && $this->dichotomy["ef"] == "overload") {

            $this->reasoning .= "<b>Skills</b> can't infer underload at the same time "
                                . "<b>effort</b> infers overload<br>";

            $newDichotomy["sk"] = "undefined";
            $newDichotomy["ef"] = "undefined";
        }

        if ($this->dichotomy["cb"] == "overload" && $this->dichotomy["ps"] == "underload") {

            $this->reasoning .= "<b>Context bias</b> can't infer overload at the same time "
                                . "<b>psychological stress</b> infers underload<br>";

            $newDichotomy["cb"] = "undefined";
            $newDichotomy["ps"] = "undefined";
        }

        // Update new dichotomy
        $this->dichotomy = $newDichotomy;
    }

    // Given a certain attribute and value the method will return the respective
    // dichotomy.
    private function getDichotomy($att, $value) {

        // FIXME: it might be possible to improve this
        $underload =  32;  // [ 0,  32]
        $fittingM  =  49;  // [33,  49]
        $fittingP  =  66;  // [50,  66]
        $overload  = 100;  // [67, 100]

        // FIXME: Correct eveything with the correct dichotomy and then make the
        // inference

        // 4 level rules with a direct relatioship with workloal
        if ($att == "md" || $att == "td" || $att == "pd" || $att == "sd" ||
            $att == "sr" || $att == "ts" || $att == "vm" || $att == "vr" ||
            $att == "ar" || $att == "mr" || $att == "sp" || $att == "ef" ||
            $att == "pa" || $att == "cb") {

            if ($value <= $underload) {

                $this->reasoning .= $att . " infers underload (" . $value . ")<br>";

                return ("underload");
            }

            if ($value <= $fittingM) {

                $this->reasoning .= $att . " infers fitting minus (" . $value . ")<br>";

                return ("fittingMinus");
            }

            if ($value <= $fittingP) {

                $this->reasoning .= $att . " infers fitting plus (" . $value . ")<br>";

                return ("fittingPlus");
            }

            $this->reasoning .= $att . " infers overload (" . $value . ")<br>";

            return ("overload");
        }

        // Performance has a 4 level rule and an indirect relationship with workload.
        // It needs to be treated separately.
        if ($att == "pf") {

             if ($value <= $underload) {

                $this->reasoning .= $att . " infers overload (" . $value . ")<br>";

                return ("overload");
            }

             if ($value <= $fittingM) {

                $this->reasoning .= $att . " infers fitting plus (" . $value . ")<br>";

                return ("fittingPlus");
            }

            if ($value <= $fittingP) {

                $this->reasoning .= $att . " infers fitting minus (" . $value . ")<br>";

                return ("fittingMinus");
            }


            $this->reasoning .= $att . " infers underload (" . $value . ")<br>";

            return ("underload");
        }

        // 2 level rules with an indirect relationship with workload
        if ($att == "pk" || $att == "sk") {

            if ($value <= $underload) {

                $this->reasoning .= $att . " infers overload (" . $value . ")<br>";

                return ("overload");
            }

            if ($value > $fittingP) {

                $this->reasoning .= $att . " infers underload (" . $value . ")<br>";

                return ("underload");
            }

            $this->reasoning .= $att . " is undefined (" . $value . ")<br>";

            return "undefined";
        }

        // 2 level rule with a direct relationship with workload
        if ($att == "ps") {

            if ($value <= $underload) {

                $this->reasoning .= $att . " infers underload (" . $value . ")<br>";

                return ("underload");
            }

            if ($value > $fittingP) {

                $this->reasoning .= $att . " infers overload (" . $value . ")<br>";

                return ("overload");
            }

            $this->reasoning .= $att . " is undefined (" . $value . ")<br>";

            return "undefined";
        }


        // 1 level rule with a direct relationship with workload
        if ($att == "mv") {
            if ($value <= $underload) {

                $this->reasoning .= $att . " infers underload (" . $value . ")<br>";

                return ("underload");
            }

            $this->reasoning .= $att . " is undefined (" . $value . ")<br>";

            return ("undefined");
        }

        // Other attributes that are not infering mental workload.
        if ($att == "ao") {

            if ($value <= 32) {

                $this->reasoning .= $att . " is low (" . $value . ")<br>";

                return ("low");
            }

            if ($value < 50) {

                $this->reasoning .= $att . " is medium lower (" . $value . ")<br>";

                return ("medium lower");
            }

            if ($value <= 66) {

                $this->reasoning .= $att . " is medium upper (" . $value . ")<br>";

                return ("medium upper");
            }

            $this->reasoning .= $att . " is high (" . $value . ")<br>";

            return ("high");
        }

        if ($att == "task") {

            if ($value <= 32) {

                $this->reasoning .= $att . " is easy (" . $value . ")<br>";

                return "easy";
            }

            if ($value >= 67) {

                $this->reasoning .= $att . " is difficult (" . $value . ")<br>";

                return "difficult";
            }

            $this->reasoning .= $att . " is undefined (" . $value . ")<br>";

            return "undefined";

        }

        $this->reasoning .= $att . " is undefined (" . $value . ")<br>";

        return ("undefined");
    }
}
?>