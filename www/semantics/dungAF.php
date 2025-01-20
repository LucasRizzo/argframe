<?php

include_once "cartesian.php";
include_once "CartesianProduct.php";

define("MAX_TIME", 10);
ini_set('memory_limit', '2096M');
ini_set('max_execution_time', -1);

class DungAF {

    //----- FIELDS -----------------------------------------------------------------------------------------------------

    //-------- fundamentals ----------
    /**
     * This AF's arguments. 
     */
    private $args;// = array();

    /**
     * This AF's attacks. 
     */
    private $atts;// = array();

    //-------- maps ----------
    /**
     * A map indicating, for each of this AF's arguments, the arguments attacking it in this AF.  
     */
    private $argsToAttackers;// = array(array());

    /**
     * A map indicating, for each of this AF's arguments, the arguments attacked by it in this AF.
     */
    private $argsToTargets;// = array(array());

    /**
     * A map indicating, for none, some or all of this AF's arguments, the defence-sets around each of those arguments 
     * in this AF.
     *
     * <p> On defence-sets, see {@link #getDefenceSetsAround(String) getDefenceSetsAround(String)}. </p>
     */
    private $argsToDefenceSets;// = array(array(array()));

    //-------- other semantics-related fields ----------
    /**
     * This AF's admissible sets.  
     */
    private $admissibleSets;// = array(array());

    /**
     * This AF's complete extensions. 
     */
    private $completeExts;// = array(array());

    /**
     * This AF's eager extension. 
     */
    private $eagerExt;// = array();

    /**
     * This AF's grounded extension. 
     */
    private $groundedExt;// = array();

    /**
     * This AF's ideal extension. 
     */
    private $idealExt;// = array();

    /**
     * This AF's preferred extensions. 
     */
    private $preferredExts;// = array(array());

    /**
     * The extension prescribed by the <i>sceptical</i> preferred semantics for this AF.  
     */
    private $preferredScepticalExt;// = array();

    /**
     * This AF's semi-stable extensions. 
     */
    private $semiStableExts;// = array(array());

    /**
     * This AF's stable extensions. 
     */
    private $stableExts;// = array(array());

    /**
     * This AF's stable extensions. 
     */
    private $expertSystem;// = array(array());
 
    /**
     * Constructs an empty AF.
     */     
    public function __construct() {

        /*$this->args = array();
        $this->atts = array(); 

        $this->argsToAttackers = array(array());
        $this->argsToTargets = array(array());
        $this->argsToDefenceSets = array(array(array()));*/
    }

    public function returnGrounded() {
        return $this->groundedExt;
    }

    public function returnStable() {
        return $this->stableExts;
    }

    public function returnSemiStable() {
        return $this->semiStableExts;
    }

    /**
     * Constructs a copy of {@code anotherAF}. 
     *
     * <p> The values of <i>all</i> fields in {@code anotherAF} - including semantics-related fields - are 
     * copied into the constructed object. </p>
     *
     * @param anotherAF a {@code DungAF}.
     */     
    public function newCopy($anotherAF) {

        $dungAF = new self();
        $dungAF->args = $anotherAF->getArgs();
        $dungAF->atts = $anotherAF->getAtts();
        $implemSemantics = array("eager", "grounded", "ideal", "preferredSceptical", "admissible",
                                                     "complete", "preferred", "stable", "semiStable");
        $uniqueExtensionSemantics = array("eager", "grounded", "ideal", "preferredSceptical");
        $semanticsIsUniqueExtension;
        $argSet = array();
        $argSets = array(array());
        $fieldName;
        $methodName;

        $dungAF->argsToAttackers = array(array());
        $dungAF->argsToTargets = array(array());

        foreach ($dungAF->args as $nextArg) {
            $dungAF->argsToAttackers[$nextArg] = array();
            $dungAF->argsToTargets[$nextArg] = array();
        }

        foreach ($dungAF->atts as $nextAtt) {
            array_push($dungAF->argsToAttackers[current($nextAtt)], key($nextAtt));
            array_push($dungAF->argsToTargets[key($nextAtt)], current($nextAtt));
        }

        $dungAF->argsToDefenceSets = array(array(array()));
        foreach ($dungAF->args as $nextArg) {
            if ($anotherAF->recordsDefenceSetsAround($nextArg)) {
                $dungAF->argsToDefenceSets[$nextArg] = $anotherAF->getDefenceSetsAround($nextArg);
            }
        }

        /* TODO This copy the semantics from one af to another. Not necessary now
        foreach ($implemSemantics as $nextSemantics) {
            semanticsIsUniqueExtension = uniqueExtensionSemantics.contains(nextSemantics);

            if (nextSemantics.equals("admissible")) {
                fieldName = "admissibleSets";
                methodName = "getAdmissibleSets";
            } else {
                fieldName = nextSemantics + "Ext" + (semanticsIsUniqueExtension ? "" : "s");
                methodName = "get" + nextSemantics.substring(0,1).toUpperCase() + nextSemantics.substring(1);
                methodName += semanticsIsUniqueExtension ? "Ext" : "Exts";
            }

            try {
                 if (anotherAF.recordsExtsOfType(nextSemantics)) {
                     if (semanticsIsUniqueExtension) {
                         argSet = (HashSet<String>) DungAF.class.getMethod(methodName).invoke(anotherAF);
                         DungAF.class.getDeclaredField(fieldName).set(this, argSet);                         
                     } else {
                         argSets = (HashSet<HashSet<String>>) DungAF.class.getMethod(methodName).invoke(anotherAF);
                         DungAF.class.getDeclaredField(fieldName).set(this, argSets);
                     }
                 }
            } catch (Exception e) {
                throw new RuntimeException();   // should never happen.
            }
        }*/

        return $dungAF;
    }


    /**
     * Constructs the AF (<i>args</i>, <i>atts</i>), where <i>args</i> comprises the set-view of {@code argsParam} and 
     * all arguments involved in any attack in {@code attsParam}; and <i>atts</i> comprises the set-view of
     * {@code attsParam}. 
     *
     * @param argsParam a {@code Collection} of {@code String}s, denoting arguments.
     * @param attsParam a {@code Collection} of {@code String}-arrays (which should all denote attacks, and hence have
     * exactly two elements).
     * @throws IllegalArgumentException if {@code attsParam} contains a {@code String}-array which does not have 
     * exactly two elements.
     */ 
    public static function PopulatedDungAF ($attsParam, $isolatedNodes) {

        $dungAF = new self();
        if (! $dungAF->arraysDenoteAttacks($attsParam)) {
            throw new Exception("Attacks included at least one String-array which did not have exactly two elements.");
        } else {
            /* set args, ensuring that every argument involved in any attack in attsParam is added to args. */
            $dungAF->args = array();
            foreach ($attsParam as $nextAtt) {
                if(! in_array(key($nextAtt), $dungAF->args, true)){
                    array_push($dungAF->args, key($nextAtt));
                }

                if(! in_array(current($nextAtt), $dungAF->args, true)){
                    array_push($dungAF->args, current($nextAtt));
                }
            }

            // Copy atts without duplicates
            $dungAF->atts = array_unique($attsParam, SORT_REGULAR);

            $dungAF->argsToAttackers = array(array());
            $dungAF->argsToTargets = array(array());

            foreach ($dungAF->args as $nextArg) {
                $dungAF->argsToAttackers[$nextArg] = array();
                $dungAF->argsToTargets[$nextArg] = array();
            }

            foreach ($dungAF->atts as $nextAtt) {
                array_push($dungAF->argsToAttackers[current($nextAtt)], key($nextAtt));
                array_push($dungAF->argsToTargets[key($nextAtt)], current($nextAtt));
            }

            if ($isolatedNodes != "") {
                foreach ($isolatedNodes as $nextArg) {
                    if (! in_array($nextArg, $dungAF->args, true)){
                        $dungAF->argsToAttackers[$nextArg] = array();
                        $dungAF->argsToTargets[$nextArg] = array();
                        array_push($dungAF->args, $nextArg);
                    }
                }
            }

            $dungAF->argsToDefenceSets = array(array(array()));
            //foreach ($dungAF->args as $nextArg) {
            //    $dungAF->argsToDefenceSets[$nextArg] = $dungAF->getDefenceSetsAround($nextArg);
            //}

            //var_dump($dungAF->argsToDefenceSets);
            return $dungAF;
        }
    }

    private function arraysDenoteAttacks($strArrs) {

        foreach ($strArrs as $nextArr) {
            if (sizeof($nextArr) != 1) {
                return false;
            }
        }

        return true;
    }
    
    //----- MISCELLANEOUS BASIC METHODS --------------------------------------------------------------------------------
    
    /**
     * Returns this AF's arguments.
     *
     * @return a set of {@code String}s, denoting this AF's arguments.
     */ 
    public function getArgs() { 
        return $this->args; 
    }
    
    /**
     * Returns this AF's attacks. 
     *
     * @return a set of {@code String}-arrays, denoting this AF's attacks.
     */
    public function getAtts() { 
        return $this->atts; 
    }

    /**
     * Returns the arguments attacking {@code arg} in this AF.
     *
     * @param arg a {@code String}, denoting an argument.
     * @return a set of {@code String}s, denoting the arguments attacking {@code arg} in this AF.
     */
    public function getAttackersOf($arg) {
        if(in_array($arg, $this->args, true)){
            return $this->argsToAttackers[$arg];
        } else {
            return array();
        }
    }

    /**
     * Returns the arguments attacked by {@code arg} in this AF.
     *
     * @param arg a {@code String}, denoting an argument.
     * @return a set of {@code String}s, denoting the arguments attacked by {@code arg} in this AF.
     */
    public function getTargetsOf($arg) {
    
        if(in_array($arg, $this->args, true)){
            return $this->argsToTargets[$arg];
        } else {
            return array();
        }
    }

     /**
     * Adds the specified arguments to this AF.
     *
     * @param argsToBeAdded one or more {@code String}s, denoting arguments.
     * @return {@code true} if this AF changed as a result of the call.
     */ 
    public function addArgs($argsToBeAdded) {

        $changed = false;
        foreach ($argsToBeAdded as $nextArg) {
            if (! in_array($nextArg, $this->args, true)){
                $this->argsToAttackers[$nextArg] = array();
                $this->argsToTargets[$nextArg] = array();
                array_push($this->args, $nextArg);
                $changed = true;
            }
        }
    }

    /**
    * Removes from this object all information concerning the interpretation of its AF. 
    *
    * <p> Typically called by methods which can change the AF. </p>
    */ 
    private function removeSemanticsInfo() {

        $this->argsToDefenceSets = array();

        $this->admissibleSets = array(array());
        $this->completeExts = array(array());
        $this->eagerExt = array();;
        $this->groundedExt = array(); 
        $this->idealExt = array();
        $this->preferredExts = array();
        $this->preferredScepticalExt = array();
        $this->semiStableExts = array();
        $this->stableExts = array();
    }

    /**
     * Adds the specified attacks to this AF. If {@code attsToBeAdded} do not all denote attacks, this AF remains
     * unchanged.
     *
     * @param attsToBeAdded one or more {@code String}-arrays (which should all denote attacks, and hence have exactly
     * two elements).
     * @return {@code true} if this AF changed as a result of the call.
     * @throws IllegalArgumentException if {@code attsToBeAdded} contains a {@code String}-array which does not have 
     * exactly two elements.
     */ 
    public function addAtts($attsToBeAdded) {

        if (! $this->arraysDenoteAttacks($attsToBeAdded)) {
            throw new Exception("Attacks included at least one String-array which did not have exactly two elements.");
        } else {
            $attsCount = sizeof($this->atts);
            foreach ($attsToBeAdded as $nextArg) {
                array_push($this->atts, $nextArg);
                $this->addArgs(key($nextArg));
                $this->addArgs(current($nextArg));
                if(! in_array(key($nextAtt), $this->argsToAttackers[current($nextArg)], true)){
                    array_push($this->argsToAttackers[current($nextAtt)], key($nextAtt));
                }

                if(! in_array(current($nextAtt), $this->argsToTargets[key($nextArg)], true)){
                    array_push($this->argsToTargets[key($nextAtt)], current($nextAtt));
                }
            }

            // Copy atts without duplicates
            $this->atts = array_unique($this->atts, SORT_REGULAR);

            if ($attsCount < sizeof($this->atts)) {
                $this->removeSemanticsInfo();
                return true;
            } else {
                return false;
            }
        }
    }

    /**
     * Removes the specified arguments from this AF.
     * 
     * @param argsToBeRemoved one or more {@code String}s, denoting arguments.
     * @return {@code true} if this AF changed as a result of the call.
     */ 
    public function removeArgs($argsToBeRemoved) {

        $attsToBeRemoved = array(array());

        $changed = false;
        foreach ($argsToBeRemoved as $nextArg) {
            if(in_array($nextArg, $this->args, true)){
                $key = array_search($nextArg, $this->args);
                unset($this->args[$key]);
                $changed = true;
            }
        }

        if (! $changed) {
            return false;
        }

        foreach ($this->atts as $nextAtt) {
            if (! in_array(key($nextAtt), $this->args, true) || ! in_array(current($nextAtt), $this->args, true)) {
                array_push($attsToBeRemoved[key($nextAtt)], current($nextAtt));
            }
        }

        $this->removeAtts($attsToBeRemoved);

        foreach ($argsToBeRemoved as $nextArg) {
            if(array_key_exists($nextArg, $this->argsToAttackers)) {
                $this->argsToAttackers[$nextArg];
            }

            if(array_key_exists($nextArg, $this->argsToTargets)) {
                $this->argsToTargets[$nextArg];
            }
        }

        $this->removeSemanticsInfo();

        return true;
    }

    /**
     * Removes the specified attacks from this AF. If {@code attsToBeRemoved} do not all denote attacks, this AF
     * remains unchanged.
     *
     * @param attsToBeRemoved one or more {@code String}-arrays (which should all denote attacks, and hence have exactly
     * two elements). 
     * @return {@code true} if this AF changed as a result of the call.
     * @throws IllegalArgumentException if {@code attsToBeRemoved} contains a {@code String}-array which does not have 
     * exactly two elements.
     */ 
    public function removeAtts($attsToBeRemoved) {

        $attsCount = sizeof($this->atts);

        if (! $this->arraysDenoteAttacks($attsToBeRemoved)) {
            throw new Exception("Attacks included at least one String-array which did not have exactly two elements.");
        } else {

            $tempAtts = $this->atts;
            foreach ($tempAtts as $nextAtt) {
                foreach ($attsToBeRemoved as $nextToBeRemoved) {
                    if (key($nextAtt) == key($nextToBeRemoved) && current($nextAtt) == current($nextToBeRemoved)) {
                        unset($this->atts[key($nextAtt)]);
                        break;
                    }
                }
            }

            if ($attsCount > sizeof($this->atts)) {
                foreach ($attsToBeRemoved as $nextToBeRemoved) {
                    $key = array_search(key($nextToBeRemoved), $argsToAttackers[current($nextToBeRemoved)]); 
                    unset($argsToAttackers[current($nextToBeRemoved)][$key]);

                    $key = array_search(current($nextToBeRemoved), $argsToTargets[key($nextToBeRemoved)]); 
                    unset($argsToTargets[key($nextToBeRemoved)][$key]);
                }
            }

            $this->removeSemanticsInfo();

            return true;
        }
    }

    /**
     * Ensures that this AF is a supergraph of {@code anotherAF}.
     *
     * @param anotherAF a {@code DungAF}.
     * @return {@code true} if this AF changed as a result of the call.
     */
    public function ensureSubsumes($anotherAF) {

        $addArgs = $this->addArgs($anotherAF->getArgs());
        $addAtts = $this->addAtts($anotherAF->getAtts());

        if ($addArgs || $addAtts) {
            $this->removeSemanticsInfo();
            return true;
        } else { 
            return false; 
        }
    }

    /**
     * Ensures that this AF contains none of the arguments in {@code anotherAF}. 
     *
     * @param anotherAF a {@code DungAF}.
     * @return {@code true} if this AF changed as a result of the call.
     */
    public function ensureDisjointWith($anotherAF) {
        if ($this->removeArgs($anotherAF->getArgs())) {
            $this->removeSemanticsInfo();
            return true;
        } else { 
            return false; 
        }
    }

    /**
     * Removes all arguments and attacks from this AF.
     */     
    public function clear() {

        $this->args = array();
        $this->atts = array();
        $this->argsToTargets = array();//array(array());
        $this->argsToAttackers = array();//array(array());
    }

    /**
     * Returns {@code true} if this object and {@code anotherAF} record the same AF.
     *
     * <p> Thus two 'equal' objects may differ with respect to the information they record about their AF's 
     * interpretation. </p>
     *
     * @param anotherAF a {@code DungAF}.
     * @return {@code true} if this object and {@code anotherAF} record the same AF.
     */
    public function equals($anotherAF) {
        if ($this->args == $anotherAF->getArgs() && sizeof($this->atts) == sizeof($anotherAF->getAtts())) {
            foreach ($this->args as $nextArg) {
                if ($this->getAttackersOf($nextArg) != $anotherAF->getAttackersOf($nextArg)) {
                    return false;
                }
            }

            return true;
        } else {
            return false;
        }
    }

    /**
     * Returns {@code true} if this AF is a (strict or non-strict) supergraph of {@code anotherAF}.
     *
     * @param anotherAF a {@code DungAF}.
     * @return {@code true} if this AF is a (strict or non-strict) supergraph of {@code anotherAF}.
     */
    public function subsumes($anotherAF) {
        //args.containsAll(anotherAF.getArgs()
        $containsAllArgs = ! array_diff($anotherAF->getArgs(), $this->args);
        $bigger = sizeof($this->atts) >= sizeof($anotherAF->getAtts());
        if ($containsAllArgs && $bigger) {
            foreach ($this->args as $nextArg) {
                $containsAllAttackers = ! array_diff($anotherAF->getAttackersOf($nextArg), $this->getAttackersOf($nextArg));
                if (! $containsAllAttackers) {
                    return false;
                }
            }

            return true;
        } else {
            return false;
        }
    }

    /**
     * Returns {@code true} if this AF contains contains none of the arguments in {@code anotherAF}.
     *
     * @param anotherAF a {@code DungAF}.
     * @return {@code true} if this AF contains none of the arguments in {@code anotherAF}.
     */
    public function isDisjointWith($anotherAF) {

        if (sizeof(array_diff($anotherAF->getAtts(), $this->atts)) == sizeof($anotherAF->getAtts())) {
            return true;
        }

        return false;
    }
    
    /**
     * Returns {@code true} if this object records the extension(s) prescribed by {@code semantics} for its AF, where 
     * {@code semantics} is a semantics implemented by this class.
     *
     * <p> The semantics implemented by this class are recognized by the following names (case-sensitive) - 
     * <ul>
     * <li> admissible </li>  
     * <li> complete </li>  
     * <li> eager </li>  
     * <li> grounded </li>  
     * <li> ideal </li>  
     * <li> preferred </li>  
     * <li> preferredSceptical </li>  
     * <li> semiStable </li>  
     * <li> stable </li>  
     * </ul>
     * </p>
     *
     * @param semantics a {@code String}, being the name of a semantics implemented by this class.
     * @return {@code true} if the relevant field of this object is non-{@code null}.
     * @throws IllegalArgumentException if {@code semantics} is not the name of a semantics implemented by this class. 
     */
    /* public function recordsExtsOfType($semantics) {

        $uniqueExtensionSemantics = array("eager", "grounded", "ideal", "preferredSceptical");
        $otherImplemSemantics = array("admissible", "complete", "preferred", "semiStable", "stable");
        String fieldName;

        if (semantics.equals("admissible")) {
            fieldName = "admissibleSets";
        } else if (uniqueExtensionSemantics.contains(semantics)) {
            fieldName = semantics + "Ext";
        } else if (otherImplemSemantics.contains(semantics)) {
            fieldName = semantics + "Exts";
        } else {
            throw new IllegalArgumentException("by '" + getClass().getName() + ".recordsExtsOfType(String semantics)' "
                                               + "--- \"" + semantics + "\" is not a semantics implemented by " 
                                               + getClass().getName() + ".");
        }
        try {
            return (null != DungAF.class.getDeclaredField(fieldName).get(this));
        } catch (Exception e) {
            throw new RuntimeException();   // should never happen.
        }
    } */

    /**
     * Returns {@code true} if this object records the defence-sets around the specified arguments in its AF.
     *
     * <p> On defence-sets, see {@link #getDefenceSetsAround(String) getDefenceSetsAround(String)}. </p>
     *
     * @param args one or more {@code String}s, denoting arguments.
     * @return {@code true} if this object records the defence-sets around the specified arguments in its AF.
     */
    public function recordsDefenceSetsAround($args) {

        foreach ($args as $nextArg) {
            if (empty($this->argsToDefenceSets[$nextArg][0])) {
                return false;
            }
        }

        return true;
    }

    /**
     * Returns a representation of this AF in the conventional format - 
     * ({<i>arg1</i>, <i>arg2</i>,...}, {(<i>arg1</i>, <i>arg2</i>), (<i>arg2</i>, <i>arg1</i>),...}).
     *
     * @return a {@code String} representation of this AF.
     */
    public function toString() {

        $formattedAttacks = array();
        foreach ($this->atts as $nextAtt) {
            array_push($formattedAttacks, "(" . key($nextAtt) . ", " . current($nextAtt) . ")");
        }

        return "({" . implode(", ", $this->args). "}, {" . implode(", ", $formattedAttacks) . "})";
    }

    //----- MISCELLANEOUS STATIC METHODS -------------------------------------------------------------------------------

    /**
     * Returns an AF which satisfies the constraints passed as parameters, but is otherwise constructed at random.
     *
     * @param minArgs an {@code int}, being the lower bound on the number of arguments. 
     * @param maxArgs an {@code int}, being the upper bound on the number of arguments. 
     * @param minAtts an {@code int}, being the lower bound on the number of attacks.
     * @param maxAtts an {@code int}, being the upper bound on the number of attacks.
     * @param argPool a {@code Collection} of {@code String}s, denoting the arguments which may be used.
     *
     * @return a {@code DungAF} recording an AF which satisfies the constraints passed as parameters.
     *
     * @throws IllegalArgumentException if the specified constraints cannot be satisfied.
     */
    public function getRandomDungAF($minArgs, $maxArgs, $minAtts, $maxAtts, $argPool) {

        $numOfArgs;
        $numOfAtts;
        $uniqueArgs = array();
        $atts = array();
        $attsAsLists = array();
        $tempAtt = array();

        // initialize uniqueArgs from the set-view of argPool.
        $uniqueArgs = $argPool;

        // check upper-bound constraints make sense, and for inconsistency in constraints.
        if ($maxArgs < 0 ||$maxAtts < 0) {
            throw new Exception("by 'DungAF.getRandomDungAF(minArgs, maxArgs, minAtts, maxAtts, argPool)' --- 'maxArgs' or 'maxAtts' is negative.");
        } else if ($minArgs > $maxArgs) {
            throw new Exception("by 'DungAF.getRandomDungAF(minArgs, maxArgs, minAtts, maxAtts, argPool)' --- 'minArgs' is greater than 'maxArgs'.");
        } else if ($minArgs > sizeof($uniqueArgs)) { 
            throw new Exception("by 'DungAF.getRandomDungAF(minArgs, maxArgs, minAtts, maxAtts, argPool)' --- 'argPool' contains fewer than 'minArgs' unique arguments.");
        } else if ($minAtts > $maxAtts) {
            throw new Exception("by 'DungAF.getRandomDungAF(minArgs, maxArgs, minAtts, maxAtts, argPool)' --- 'minAtts' is greater than 'maxAtts'."); 
        } else if ($minAtts > sizeof($uniqueArgs) * sizeof($uniqueArgs)) {
            throw new Exception("by 'DungAF.getRandomDungAF(minArgs, maxArgs, minAtts, maxAtts, argPool)' --- 'argPool' contains too few unique arguments for 'minAtts'.");
        } else if ($minAtts > $maxArgs * $maxArgs) {
            throw new Exception("by 'DungAF.getRandomDungAF(minArgs, maxArgs, minAtts, maxAtts, argPool)' --- 'maxArgs' is too small for 'minAtts'.");
        }

        // adjust minArgs, maxArgs, minAtts and maxAtts, if appropriate.
        if ($minArgs < 0) {
            $minArgs = 0;
        }

        if ($minAtts < 0) {
            $minAtts = 0;
        }

        if ($maxArgs > sizeof($uniqueArgs)) {
            $maxArgs = sizeof($uniqueArgs);
        }

        if ($maxAtts > $maxArgs * $maxArgs) {
            $maxAtts = $maxArgs * $maxArgs;
        }

        // fix the numbers of arguments and attacks at random values within the required ranges.
        $numOfArgs = $minArgs + round($this->random_0_1() * ($maxArgs - $minArgs));
        $numOfAtts = $minAtts + round($this->random_0_1() * ($maxAtts - $minAtts));

        // reduce uniqueArgs by random removal.
        while (sizeof($uniqueArgs) > $numOfArgs) {
            $uniqueArgs = $this->remove($uniqueArgs, $uniqueArgs[round($this->random_0_1() * (sizeof($uniqueArgs) - 1))]);
        }

        // define attacks between remaining arguments at random.
        for ($i = 0; $i < $numOfAtts; $i++) {
            $source = $uniqueArgs[round($this->random_0_1() * (sizeof($uniqueArgs) - 1))];
            $target = $uniqueArgs[round($this->random_0_1() * (sizeof($uniqueArgs) - 1))];

            $newAtt = array($source => $target);

            if (empty($attsAsLists)) {
                array_push($attsAsLists, $newAtt);
            } else {
                $repeated = true;
                foreach ($attsAsLists as $nextAtt) {
                    if (current($nextAtt) == $target && key($nextAtt) == $source) {
                        $i--;
                        $repeated = false;
                        break;
                    }
                }
                if (! $repeated) {
                    array_push($attsAsLists, $newAtt);
                }

            }
        }

        $af = DungAF::PopulatedDungAF($attsAsLists, $uniqueArgs);

        return $af;
    }

    //----- METHODS CONCERNING CONFLICT BETWEEN ARGUMENTS --------------------------------------------------------------

    /**
     * Returns {@code true} if the set-view of the specified collection is a conflict-free set in this AF.
     *
     * @see #containsNoConflictAmong(Collection) containsNoConflictAmong(Collection&ltString&gt) 
     * @param argsParam a {@code Collection} of {@code String}s, denoting arguments.
     * @return {@code true} if this AF contains all of {@code argsParam}; and, in this AF, none of those arguments  
     * attacks itself or any other argument in {@code argsParam}.
     */
    public function hasAsConflictFreeSet($argsParam) {

        foreach ($argsParam as $nextArg) {
            if (! in_array($nextArg, $this->args, true) || sizeof(array_intersect($this->getAttackersOf($nextArg), $argsParam)) > 0) {
                return false;
            }
        }

        return true;
    }

    /**
     * Returns {@code true} if, in this AF, no argument in the specified collection attacks itself or any other 
     * argument in the collection.
     *
     * @see #hasAsConflictFreeSet(Collection) hasAsConflictFreeSet(Collection&ltString&gt)
     * @param argsParam a {@code Collection} of {@code String}s, denoting arguments.
     * @return {@code true} if, in this AF, none of the arguments in {@code argsParam} attacks itself or any other
     * argument in {@code argsParam}.
     */
    public function containsNoConflictAmong($argsParam) {

        foreach ($argsParam as $nextArg) {
            if (sizeof(array_intersect($this->getAttackersOf($nextArg), $argsParam)) > 0) {
                //var_dump(array_intersect($this->getAttackersOf($nextArg), $argsParam));
                return false;
            }
        }

        return true;
    }

    /**
     * Returns {@code true} if the specified collections unite to form a conflict-free set in this AF.
     *
     * @see #containsNoConflictAmongUnionOf(Collection) 
     * containsNoConflictAmongUnionOf(Collection&ltT extends Collection&ltString&gt&gt)
     * @param argColls a {@code Collection} of {@code String}-{@code Collection}s, denoting argument-collections.
     * @return {@code true} if this AF contains all of the arguments in the union of {@code argColls}; contains no 
     * attack by any of those arguments on itself; and contains no attack between any two of those arguments. 
     */
    public function hasUnionOfAsConflictFreeSet($argColls) {

        $union = array();

        foreach ($argColls as $nextArgColl) {
            foreach ($nextArgColl as $nextArg) {
                if (! in_array($nextArg, $union, true)){
                    array_push($union, $nextArg);
                }
            }
        }

        //args.containsAll(union)
        $containsAll = ! array_diff($union, $this->args);

        return ( $containsAll && $this->hasAsConflictFreeSet($union));
    }

    /**
     * Returns {@code true} if, in this AF, none of the arguments in the union of the specified collections 
     * attacks itself or any other argument in the union.
     *
     * @see #hasUnionOfAsConflictFreeSet(Collection) 
     * hasUnionOfAsConflictFreeSet(Collection&ltT extends Collection&ltString&gt&gt)
     * @param argColls a {@code Collection} of {@code String}-{@code Collection}s, denoting argument-collections.
     * @return {@code true} if, in this AF, none of the arguments in the union of {@code argColls} attacks itself or any
     * other argument in the union.
     */
    public function containsNoConflictAmongUnionOf($argColls) {

        $union = array();

        foreach ($argColls as $nextArgColl) {
            foreach ($nextArgColl as $nextArg) {
                if (! in_array($nextArg, $union, true)){
                    array_push($union, $nextArg);
                }
            }
        }

        return $this->containsNoConflictAmong($union);
    }

    /**
     * Returns {@code true} if, in this AF, any of the arguments in the specified collection is in conflict with any 
     * of the specified arguments.
     *
     * @param argsParam0 a {@code Collection} of {@code String}s, denoting arguments.
     * @param argsParam1 one or more {@code String}s, denoting arguments.
     * @return {@code true} if, in this AF, any argument in {@code argsParam0} is attacked by/attacks any argument 
     * in {@code argsParam1}.
     */     
    public function collnIsInConflictWithAnyOf($argsParam0, $argsParam1) {

        foreach ($argsParam1 as $nextArg) {
            if (sizeof(array_intersect($this->getAttackersOf($nextArg), $argsParam0)) > 0 ||
                    sizeof(array_intersect($this->getTargetsOf($nextArg), $argsParam0)) > 0) {
                return true;
            }
        }

        return false;
    }

    //----- METHODS CONCERNING ACCEPTABILITY ---------------------------------------------------------------------------

    /**
     * Returns {@code true} if the specified arguments are all acceptable with respect to the set-view of 
     * {@code acceptingArgColl} in this AF. 
     *
     * @param acceptingArgColl a {@code Collection} of {@code String}s, denoting arguments.
     * @param argsToCheck one or more {@code String}s, denoting arguments.
     * @return {@code true} if all of {@code argsToCheck} are acceptable with respect to the set-view of  
     * {@code acceptingArgColl} in this AF.
     */
    public function argsAccept($acceptingArgColl, $argsToCheck) {

        $targetsOfArgSet = array();

        foreach ($acceptingArgColl as $nextArg) {
            foreach ($this->getTargetsOf($nextArg) as $nextTarget) {
                if (! in_array($nextTarget, $targetsOfArgSet, true)){
                    array_push($targetsOfArgSet, $nextTarget);
                }
            }
        }

        foreach ($argsToCheck as $nextArg) {
            foreach ($this->getAttackersOf($nextArg) as $nextAttacker) {
                if (! in_array($nextAttacker, $targetsOfArgSet, true)){
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Returns the arguments which are acceptable with respect to the set-view of {@code argsParam} in this AF. 
     *
     * @param argsParam a {@code Collection} of {@code String}s, denoting arguments.
     * @return a set of {@code String}s, denoting the arguments which are acceptable with respect to the set-view of
     * {@code argsParam} in this AF.
     */
    public function getArgsAcceptedBy($argsParam) {

        $targets = array();
        $acceptableArgs = array();

        foreach ($argsParam as $nextArg) {
            foreach($this->getTargetsOf($nextArg) as $nextTarget) {
                if (! in_array($nextTarget, $targets, true)){
                    array_push($targets, $nextTarget);
                }
            }
        }

        foreach ($this->getArgs() as $nextArg) {
        
            //targets.containsAll(argsToAttackers.get(nextArg))
            $containsAll = ! array_diff($this->argsToAttackers[$nextArg], $targets);
            if ($containsAll) {
                array_push($acceptableArgs, $nextArg);
            }
        }

        return $acceptableArgs;
    }

    //----- 3. METHODS CONCERNING SEMANTICS ----------------------------------------------------------------------------

    /**
     * Returns the union of the extensions prescribed by {@code semantics} for this AF, where {@code semantics} is a 
     * multiple-extension semantics implemented by this class.
     *
     * <p> The multiple-extension semantics implemented by this class are recognized by the following names 
     * (case-sensitive) - 
     * <ul>
     * <li> admissible </li>  
     * <li> complete </li>  
     * <li> preferred </li>  
     * <li> semiStable </li>  
     * <li> stable </li>  
     * </ul>
     </p>
     *
     * @param semantics a {@code String}, being the name of a multiple-extension semantics implemented by this class.    
     * @return a set of {@code String}s, denoting the union of the extensions prescribed by {@code semantics} for this 
     * AF.
     * @throws IllegalArgumentException if {@code semantics} is not the name of a multiple-extension semantics 
     * implemented by this class.
     */ 
    /*public HashSet<String> getExtsUnion(String semantics) {
        
        List<String> multiExtSemantics = Arrays.asList("admissible", "complete", 
                                                       "preferred", "semiStable", "stable");
        HashSet<String> extsUnion;
        String methodName;
        
        if (multiExtSemantics.contains(semantics)) {
            /* all admissible sets and complete extensions are subsumed by preferred extensions; and the latter are
             more easily calculated. */
            /*if (semantics.equals("preferred") || semantics.equals("admissible") || semantics.equals("complete")) {
                methodName = "getPreferredExts";
            } else {
                methodName = "get" + semantics.substring(0,1).toUpperCase() + semantics.substring(1) + "Exts";
            }
        } else {
            throw new IllegalArgumentException(
                                       "by '" + getClass().getName() + ".getExtsUnion(String semantics)' --- " 
                                       + "\"" + semantics + "\" is not a multiple-extension semantics implemented by " 
                                       + getClass().getName() + ".");
        }
        
        try {       
            extsUnion = new HashSet<String>();
            for (HashSet<String> nextExt : (HashSet<HashSet<String>>) DungAF.class.getMethod(methodName).invoke(this)) {
                extsUnion.addAll(nextExt);
            }
        } catch (Exception e) {
            throw new RuntimeException();   // should never happen.
        }

        return extsUnion;
    }  */ 

    /**
     * Returns this AF's grounded extension. 
     *
     * @return a set of {@code String}s, denoting this AF's grounded extension.
     */ 
    public function getGroundedExt() {

        $finish = time() + MAX_TIME;

        $defeatedArgs = array(); // arguments attacked by groundedExt.
        $candidateArgs = array();  // arguments not in groundedExt or defeatedArgs.        

        //String tempArg;

        /* the grounded extension might already be recorded */
        if (null != $this->groundedExt) {
            return $this->groundedExt; 
        }

        $this->groundedExt = array();

        do {

            foreach ($this->args as $nextArg) {
                if (! in_array($nextArg, $candidateArgs, true)) {
                    array_push($candidateArgs, $nextArg);
                }
            }

            if (time() >= $finish) {
                return "Maximum execution time";
            }

            foreach ($this->groundedExt as $nextArg) {
                if (in_array($nextArg, $candidateArgs, true)) {
                    //$candidateArgs = $this->remove($candidateArgs, $nextArg);
                    $key = array_search($nextArg, $candidateArgs);
                    unset($candidateArgs[$key]);
                }
            }

            if (time() >= $finish) {
                return "Maximum execution time";
            }

            foreach ($defeatedArgs as $nextArg) {
                if (in_array($nextArg, $candidateArgs, true)) {
                    //$candidateArgs = $this->remove($candidateArgs, $nextArg);
                    $key = array_search($nextArg, $candidateArgs);
                    unset($candidateArgs[$key]);
                }
            }

            if (time() >= $finish) {
                return "Maximum execution time";
            }

            /* ensure that candidateArgs contains no argument that's not acceptable wrt groundedExt; 
             add remainder to groundedExt. */
            foreach ($candidateArgs as $nextArg) {
                if (! array_intersect($this->getAttackersOf($nextArg), $candidateArgs)) {
                    if (! in_array($nextArg, $this->groundedExt, true)) {
                       array_push($this->groundedExt, $nextArg);
                    }

                    foreach ($this->getTargetsOf($nextArg) as $nextTarget) {
                        if (! in_array($nextTarget, $defeatedArgs, true)) {
                            array_push($defeatedArgs, $nextTarget);
                        }
                    }
                }
            }

            if (time() >= $finish) {
                return "Maximum execution time";
            }

            foreach ($candidateArgs as $nextArg) {
                if (! in_array($nextArg, $this->groundedExt, true)) {
                    //$candidateArgs = $this->remove($candidateArgs, $nextArg);
                    $key = array_search($nextArg, $candidateArgs);
                    unset($candidateArgs[$key]);
                }
            }

            if (time() >= $finish) {
                return "Maximum execution time";
            }

        } while (sizeof($candidateArgs) > 0);

        return $this->groundedExt;
    }

    /**
     * Returns this AF's expert system extension. 
     *
     * @return a set of {@code String}s, denoting this AF's expert system extension.
     */ 

    public function getAllSemantics() {

        $allSemantics = array();

        array_push($allSemantics, $this->getExpertSystem());
        array_push($allSemantics, $this->getGroundedExt());
        array_push($allSemantics, $this->getPreferredExts());
        array_push($allSemantics, $this->getEagerExt());
        array_push($allSemantics, $this->getIdealExt());
        array_push($allSemantics, $this->getStableExts());
        array_push($allSemantics, $this->getSemiStableExts());
        //array_push($allSemantics, $this->getAdmissibleSets);

        return $allSemantics;
    }

    public function getExpertSystem() {

        $finish = time() + MAX_TIME;

        $defeatedArgs = array(); // arguments attacked by groundedExt.
        $candidateArgs = array();  // arguments not in groundedExt or defeatedArgs.

        //String tempArg;

        /* the grounded extension might already be recorded */
        if (! empty($this->expertSystem)) {
            return $this->expertSystem; 
        }

        $this->expertSystem = array();

        foreach ($this->args as $nextArg) {
            if (time() >= $finish) {
                return "Maximum execution time";
            }
            $attackersOf = $this->getAttackersOf($nextArg); 
            if (empty($attackersOf)) {
                array_push($this->expertSystem, $nextArg);
            }
        }

        return $this->expertSystem;
    }

    public function getCategoriser() {

        $finish = time() + MAX_TIME;

        $this->categoriser = array();

        foreach ($this->args as $nextArg) {
            if (time() >= $finish) {
                return "Maximum execution time";
            }

            $attackersOf = $this->getAttackersOf($nextArg);

            if (empty($attackersOf)) {
                $this->categoriser[$nextArg] = 1;
            } else {

                $sumAttackers = 0;

                foreach ($attackersOf as $nextAttack) {
                    if (array_key_exists($nextAttack, $this->categoriser)) {
                        $sumAttackers += $this->categoriser[$nextAttack];
                    } else {
                        $rootPath = array();
                        array_push($rootPath, $nextArg);
                        $sumAttackers += $this->getCategoriserValue($nextAttack, $rootPath);
                    }
                }

                $this->categoriser[$nextArg] = 1 / (1 + $sumAttackers);
            }
        }

        arsort($this->categoriser);
        $this->categoriser = array($this->categoriser);
        return $this->categoriser;
    }

    public function getCategoriserValue($attr, $rootPath) {

        $attackersOf = $this->getAttackersOf($attr);

        if (empty($attackersOf)) {
            return 1;
        } else {
            $sumAttackers = 0;
            foreach ($attackersOf as $nextAttack) {
                if (in_array($nextAttack, $rootPath)) {
                    $sumAttackers += 0.618033989;
                } else {
                    array_push($rootPath, $nextAttack);
                    $sumAttackers += $this->getCategoriserValue($nextAttack, $rootPath);
                }
            }
        }

        return 1 / (1 + $sumAttackers);
    }

    /**
     * Returns {@code true} if, for each of the specified collections, its set-view is admissible in this AF.
     *
     * <p> If {@code argColls} are all sets, this method returns the same as
     * {@code getAdmissibleSets().containsAll(argColls)}, but without calculating the admissible sets. </p>
     *
     * @param argColls one or more {@code Collection}s of {@code String}s, denoting argument-collections.
     * @return {@code true} if, for each of the specified collections, its set-view is admissible in this AF.
     */ 
    public function admissibleSetsContain($argColls) {

        $attackers = array();
        $targets = array();

        /* NOTE: because getAdmissibleSets() relies on this method, the following approach would cause errors - 

         if (null != admissibleSets) {
            return admissibleSets.containsAll(argColls);         
         } else ...

        */
        foreach ($argColls as $nextArgColl) {
            $attackers = array();
            $targets = array();

            if ($this->hasAsConflictFreeSet($nextArgColl)) {
                foreach ($nextArgColl as $nextArg) {

                    foreach($this->getAttackersOf($nextArg) as $nextAttacker) {
                        if (! in_array($nextAttacker, $attackers, true)) {
                            array_push($attackers, $nextAttacker);
                        }
                    }

                    foreach($this->getTargetsOf($nextArg) as $nextTarget) {
                        if (! in_array($nextTarget, $targets, true)) {
                            array_push($targets, $nextTarget);
                        }
                    }
                }

                //assumedRetainableColl.containsAll(assumedRemovableColl)
                //$containsAllRetainable = ! array_diff(attackers, $targets);

                //if (!targets.containsAll(attackers)) {
                //    return false;   
                //}
                
                //echo "<br> att: <br>";
                //var_dump($attackers);
                //echo "<br> targets: <br>";
                //var_dump($targets);

                //echo "<br> intersect: " . sizeof(array_intersect($attackers, $targets)) . "<br>";

                foreach ($attackers as $nextAtt) {
                    if(! in_array($nextAtt, $targets, true)){
                        return false;
                        //exit;
                    }
                }

                //if (sizeof(array_intersect($attackers, $targets)) != sizeof($attackers)) {
                //if (! $this->in_array_2D(array($targets), $attackers)) {
                //if (! array_diff($attackers, $targets)) {
                //    return false;
                //}
            } else {
                return false;
            }
        }

        return true;
    }

    //getAdmissibleSets
    
    /**
     * Returns the defence-sets around {@code arg} in this AF.
     *
     * <p> Vreeswijk defined <i>defence-sets</i> for arguments in his 2006 paper -
     * <br/>
     * <ul><i>An algorithm to compute minimally grounded and admissible defence sets in argument systems</i></ul> 
     * <ul><u>Proceedings of COMMA'06: pp.109-20</u>.</ul>
     * <br/>
     * Given an AF <i>af</i>, an argument <i>arg</i>, and an argument-set <i>argSet</i>, <i>argSet</i> is 
     * a defence-set around <i>arg</i> in <i>af</i>, if and only if -
     * <br/><br/>
     * <ol>
     * <li> <i>argSet</i> contains <i>arg</i>; and </li>  
     * <li> <i>argSet</i> is admissible in <i>af</i>; and </li>  
     * <li> no strict subset of <i>argSet</i> fulfils (1) and (2). </li>  
     * </ol>
     * </p> 
     *
     * @param  arg a {@code String}, denoting an argument.
     * @return a set of {@code String}-sets, denoting the defence-sets around {@code arg} in this AF.
     */ 
    public function getDefenceSetsAround($arg) {

        $copiesOfDefenceSets = array();

        if(! in_array($arg, $this->args, true)){
            return array(array());
        } else if (! array_key_exists($arg, $this->argsToDefenceSets)) {
            $this->argsToDefenceSets[$arg] = $this->getDefenceSetsAroundHelper($arg, array(), array());
        }

        foreach ($this->argsToDefenceSets[$arg] as $nextSet) {
            array_push($copiesOfDefenceSets, $nextSet);
        }

        return $copiesOfDefenceSets;
    }

    /**
     * Finds the defence-sets around {@code arg} in this AF, if called by another method (and passed {@code arg}, the 
     * empty list and the singleton set containing the empty set). On defence-sets, see
     * {@link #getDefenceSetsAround(String) getDefenceSetsAround(String)}.
     *
     * <p> This method implements a simplified and slightly modified version of Vreeswijk's algorithm for generating 
     * <i>labelled</i> defence-sets (the label denoting, in each case, whether the defence-set is merely an admissible 
     * set, or an admissible set which is also a subset of the grounded extension), as described in his 2006 paper -
     *
     * <br/>
     * <ul><i>An algorithm to compute minimally grounded and admissible defence sets in argument systems</i></ul> 
     * <ul><u>Proceedings of COMMA'06: pp.109-20</u>.</ul>
     * <br/>
     *
     * This method's simplified and slightly modified version generates unlabelled defence-sets. Full details are  
     * provided <a href="../admissibleSemantics.pdf">here</a>. </p> 
     *
     * <p> While an externally-called instance of this method returns the defence-sets around the specified argument, a 
     * recursively-called instance does not generally do so. Instead, it returns a (perhaps empty) set of argument-sets. 
     * Each returned argument-set is conflict-free, but is not necessarily a defence-set of the 
     * argument {@code externInstArg} passed to the externally-called instance. The output of a recursively-called 
     * instance represents a stage in one branch of a search for {@code externInstArg}'s defence-sets. 
     * As such, it might include not just (i) defence-sets of {@code externInstArg}, but also (ii) sets which are 
     * non-admissible (on account of not being acceptable with respect to themselves), and (iii) sets which strictly 
     * subsume defence-sets of {@code externInstArg}. </p> 
     *
     * <p> Following Vreeswijk's usage, the argument-sets passed to and returned by this method are called 
     * <i>candidate-solutions</i>. A candidate-solution is an argument-set which is 'promising'. The algorithm builds 
     * each defence-set by addition, proceeding from the empty set. A candidate-solution <i>cs</i> is (loosely speaking) 
     * such that the algorithm has not yet established that <i>cs</i> is neither (a) a defence-set around the argument 
     * passed to the externally-called instance, nor (b) a subset of such a defence-set. </p>
     *
     * @param arg a {@code String}, denoting an argument.
     * @param path a list of {@code String}s, denoting arguments. 
     * @param canSols a set of {@code String}-sets, denoting candidate-solutions.
     * @return a set of {@code String}-sets, denoting either (a) the defence-sets around {@code arg} in this AF 
     * (if called by another method) or (b) candidate-solutions (if called recursively).
     */
    private function getDefenceSetsAroundHelper($arg, $path, $canSols, $insertArg = true) {

        $finish = time() + MAX_TIME;

        //$accumulatedCanSols = array(array());
        //$filteredCanSols = array(array());
        //$canSolsAttackingNextAttr = array(array());

        /* set onPropArg. onPropArg means that arg is a propArg - i.e. arg is being treated as a potential addition to 
         every candidate-solution in canSols, and hence as a potential member of at least one defence-set (and hence a
         'proponent' of the argument passed to the externally-called instance). Otherwise arg is an oppArg - i.e. arg is
         being treated as an argument which prevents each candidate-solution in canSols from being admissible, by 
         (i) attacking some argument which is in every candidate-solution in canSols, while (ii) not being attacked by 
         any of those candidate-solutions. */
        $onPropArg = sizeof($path) % 2 == 0;

        /* extend path with arg - to ensure that, if this instance recurses, each recursively-called instance correctly 
         determines whether its argument is a propArg or an oppArg. */
        array_push($path, $arg);

        if ($onPropArg) {
            if (in_array($arg, $this->getAttackersOf($arg), true)) {
                /* if arg attacks itself, it cannot be in any admissible set; so as it is a propArg, there can be no 
                 defence-sets this way. So clear canSols. */
                $canSols = array(); //array(array());
            } else {
                /* otherwise arg might be in an admissible set, so there might be defence-sets this way. 
                 So create new, augmented versions of all members of canSols. */
                $tempSetSetStr = array(); //array(array());
                $tempSetStr = array();

                // $insertArg is not in the original code. The reason for doing that
                // is because in java canSols can be an empty multidimensional array
                // or an empty one dimension array. The method "empty" behaves the
                // same for one dimensional and multidimensional in java but 
                // not in php.
                if (empty($canSols) && $insertArg) {
                    array_push($tempSetStr, $arg);
                    array_push($tempSetSetStr, $tempSetStr);
                } else {
                    foreach ($canSols as $nextSet) {
                        $tempSetStr = $nextSet;
                        if(! in_array($arg, $tempSetStr, true)){
                            array_push($tempSetStr, $arg);
                            array_push($tempSetSetStr, $tempSetStr);
                        }
                    }
                }

                $canSols = $tempSetSetStr;
            }
        }

        //echo "<br>can sol meio: <br>";
        //var_dump($canSols);

        /* if there might be defence-sets this way and arg is attacked...  */
        $attackersOf = $this->getAttackersOf($arg);
        if (! empty($canSols) && ! empty($attackersOf)) {
            //echo "<br>attackers of: <br>";
            //var_dump($attackersOf);
            /* ...for each attacker nextAttacker... */
            foreach ($this->getAttackersOf($arg) as $nextAttacker) {

                $arrayNextAttacher = array($nextAttacker);
                if ($onPropArg) { 
                    $canSolsAttackingNextAttr = array();
                }

                /* ...find those candidate-solutions in canSols, such that nextAttacker is relevant to them. */
                $filteredCanSols = array();
                foreach ($canSols as $nextCanSol) { 
                    if ($onPropArg) {
                        /* if arg is a propArg, a candidate-solution is relevant, if it DOES NOT attack nextAttacker, 
                         and hence is rendered non-admissible by nextAttacker. However, we need to record those 
                         candidate-solutions which do defend themselves against nextAttacker. So add nextCanSol to 
                         either filteredCanSols or canSolsAttackingNextAttr. */
                         if (sizeof(array_intersect($this->getAttackersOf($nextAttacker), $nextCanSol)) == 0) {
                            /* it is not necessary to use a *copy* of nextCanSol, because the method nowhere changes 
                             any candidate-solution. */
                            array_push($filteredCanSols, $nextCanSol);
                        } else {
                            array_push($canSolsAttackingNextAttr, $nextCanSol);
                        }
                    } else if (! $this->collnIsInConflictWithAnyOf($nextCanSol, $arrayNextAttacher)) {
                        /* if arg is an oppArg, nextCanSol is relevant, if nextAttacker might 'usefully' defend it 
                         against arg - so nextCanSol is *not* relevant, if it is in conflict with nextAttacker. Even if
                         no such conflict exists, nextAttacker's 'usefulness' as a defender is treated merely as a 
                         *possibility*, because there might be no admissible sets 
                         subsuming ({nextAttacker} U nextCanSol). */
                        array_push($filteredCanSols, $nextCanSol);
                    } 
                }

                if ($onPropArg) {
                    /* if arg is a propArg, attend to those candidate-solutions which do not defend themselves against 
                     nextAttacker - try to expand them into sets which are not deficient in that way (and which subsume 
                     defence-sets of the added arguments, and which are conflict-free), and record such
                     expanded sets in canSols. */

                    $insertArg = true;
                    if (empty($filteredCanSols)) {
                        $insertArg = false;
                    }

                    $canSols = $this->getDefenceSetsAroundHelper($nextAttacker, $path, $filteredCanSols, $insertArg);

                    /* reinstate those canSols which were found to defend themselves against nextAttacker. */
                    foreach($canSolsAttackingNextAttr as $nextCanSolsAtt) {
                        if(! $this->in_array_2D($canSols, $nextCanSolsAtt)){
                            array_push($canSols, $nextCanSolsAtt);
                        }
                    }

                    if (empty($canSols)) {
                        /* if canSols is empty, there are no defence-sets this way, so there is no need to consider any 
                         further attackers of arg... */
                        break; 
                    } else {
                        /* ...otherwise, remove all non-minimal members of canSols, to ensure that the externally-called
                         instance returns no strict superset of a defence-set. */
                        $canSols =  $this->removeNonMinimalMembersOf($canSols);

                        if (time() >= $finish) {
                            return "Maximum execution time";
                        }
                    }
                } else {
                    /* if arg is an oppArg, attend to every candidate-solution, such that nextAttacker might 'usefully' 
                     defend it against arg - try to expand such candidate-solutions into sets which 
                     (i) are conflict-free, (ii) include nextAttacker, and (iii) subsume defence-sets of nextAttacker 
                     and of all subsequently-added arguments. */

                    $insertArg = true;
                    if (empty($filteredCanSols)) {
                        $insertArg = false;
                    }

                    $defenceSet = $this->getDefenceSetsAroundHelper($nextAttacker, $path, $filteredCanSols, $insertArg);

                    //$accumulatedCanSols = array();

                    foreach ($defenceSet as $nextDefenceSet) {
                        if (! isset($accumulatedCanSols)) {
                            $accumulatedCanSols = array();
                            array_push($accumulatedCanSols, $nextDefenceSet);
                        } else if (! $this->in_array_2D($accumulatedCanSols, $nextDefenceSet)){
                            array_push($accumulatedCanSols, $nextDefenceSet);
                        }
                    }

                    /* remove all non-minimal members of canSols, to ensure that the externally-called instance returns 
                     no strict superset of a defence-set. */
                    if (isset($accumulatedCanSols)) {
                        //echo "<br>3<br>";
                        $accumulatedCanSols = $this->removeNonMinimalMembersOf($accumulatedCanSols);

                        if (time() >= $finish) {
                            return "Maximum execution time";
                        }
                    }
                }
            }
        }

        /* remove arg from path - to ensure that, if (i) this instance was recursively-called and (ii) the calling 
         instance proceeds to call another instance, then the other instance will correctly determine whether its 
         argument is a propArg or an oppArg. */ 
        //unset($path[sizeof($path) - 1]);

        if (! $onPropArg) {
            if (isset($accumulatedCanSols)) {
                $canSols = $accumulatedCanSols;
            } else {
                $canSols = array();
            }
        }

        return $canSols;
    }

    // Return true if 2D $array1 contains 1D $array2 
    public function in_array_2D($array1, $array2) {
        foreach($array1 as $arr) {

            if(sizeof($arr) != sizeof($array2)) {
                continue;
            }

            $inArray = true;

            foreach ($array2 as $nextElem) {
                if(! in_array($nextElem, $arr)) {
                    $inArray = false;
                    break;
                }
            }

            if ($inArray) {
                return true;
            }
        }

        return false;
    }

    // Receives 2d $array and removes all elements from $array that are in the
    // 2d $removeContent
    private function removeAll($array, $removeContent) {

        //echo "begin";
         //$array.removeAll($removeContent);
        do {
            $change = false;
            foreach ($removeContent as $nextRemove) {
                if ($this->in_array_2D($array, $nextRemove)) {
                    $key = array_search($nextRemove, $array);
                    unset($array[$key]);
                    $changed = true;
                }
            }
        } while ($change);

        // Fix indexes
        $key = 0;
        $copy = array();
        foreach ($array as $next) {
            $copy[$key] = $next;
            $key++;
        }
        //echo "end";

        return $copy;
    }

    private function remove($array, $element) {
        $key = array_search($element, $array);

        if (! $key) {
            return $array;
        }

        unset($array[$key]);

        // Fix indexes
        $key = 0;
        $copy = array();
        foreach ($array as $next) {
            $copy[$key] = $next;
            $key++;
        }

        return $copy;
    }

    private function remove_2D($array2D, $elem1D) {

        // Example
        // 1 - array(1) { [0]=> array(2) { [0]=> string(1) "a" [1]=> string(1) "b" } }
        // 2 - array(2) { [0]=> string(1) "a" [1]=> string(1) "b" }
        // Remove 2 from 1

        foreach ($array2D as $key => $array1D) {

            /*$keepSearching = true;
            foreach($array1D as $nextElem) {

                if (array_search($nextElem, $array1D) == -1) {
                    $keepSearching = false;
                    break;
                }

                if(array_search($nextElem, $elem1D) != array_search($nextElem, $array1D)) {
                    $keepSearching = false;
                    break;
                }
            }*/

            if (! array_diff($array1D, $elem1D) && sizeof($elem1D) == sizeof($array1D)) {
                unset($array2D[$key]);
                // Fix keys
                $key = 0;
                $copy = array();
                foreach ($array2D as $next) {
                    $copy[$key] = $next;
                    $key++;
                }

                return $copy;
            }
        }
    }

    /**
     * Removes {@code collColl}'s non-minimal/non-maximal members, simply using 
     * {@link java.util.Collection#containsAll(Collection) containsAll(Collection&lt?&gt)} to compare the collections.
     *
     * @param collColl a {@code Collection} of {@code Collection}s.
     * @param removeNonMinimal whether it is the non-minimal or non-maximal members of {@code collColl} 
     * that are to be removed.
     * @return {@code true} if {@code collColl} changed as a result of the call.
     */ 

     // This method was changed. It receives a 2D array and return a new 2D array
     // with all maximal elements
    public function removeNonMaximalMembersOf($collColl) {

        $finish = time() + MAX_TIME;

        $origSize = sizeof($collColl);
        $copyCollColl = $collColl;

        while (! empty($copyCollColl)) {
            $assumedRetainableColl = $copyCollColl[0];

            $copyCollColl = $this->removeAll($copyCollColl, array($assumedRetainableColl));

            if (time() >= $finish) {
                return "Maximum execution time";
            }

            begin:
            foreach ($copyCollColl as $nextColl) {

                if (time() >= $finish) {
                    return "Maximum execution time";
                }

                $assumedRemovableColl = $nextColl;

                //assumedRemovableColl.containsAll(assumedRetainableColl))
                $containsAllRemovable = ! array_diff($assumedRetainableColl, $assumedRemovableColl);

                //assumedRetainableColl.containsAll(assumedRemovableColl)
                $containsAllRetainable = ! array_diff($assumedRemovableColl, $assumedRetainableColl);

                if (sizeof($assumedRemovableColl) > sizeof($assumedRetainableColl) && $containsAllRemovable) {
                    //collColl.removeAll(Arrays.asList(assumedRetainableColl));
                    $collColl = $this->removeAll($collColl, array($assumedRetainableColl));
                    break;
                } else if (sizeof($assumedRetainableColl) > sizeof($assumedRemovableColl) && $containsAllRetainable) {
                    //collColl.removeAll(Arrays.asList(assumedRemovableColl));
                    $collColl = $this->removeAll($collColl, array($assumedRemovableColl));
                    $copyCollColl = $this->remove_2D($copyCollColl, $nextColl);
                    goto begin;
                }
            }
        }

        return $collColl;
    }
    
    
    /**
     * Removes {@code collColl}'s non-minimal/non-maximal members, simply using 
     * {@link java.util.Collection#containsAll(Collection) containsAll(Collection&lt?&gt)} to compare the collections.
     *
     * @param collColl a {@code Collection} of {@code Collection}s.
     * @param removeNonMinimal whether it is the non-minimal or non-maximal members of {@code collColl} 
     * that are to be removed.
     * @return {@code true} if {@code collColl} changed as a result of the call.
     */

     // This method was changed. It receives a 2D array and return a new 2D array
     // with all minimal elements
    public function removeNonMinimalMembersOf($collColl) {

        $finish = time() + MAX_TIME;

        $origSize = sizeof($collColl);
        $copyCollColl = $collColl;

        //var_dump($copyCollColl);
        //echo "<br><br>";

        while (! empty($copyCollColl)) {

            if (time() >= $finish) {
                return "Maximum execution time";
            }

            $assumedRetainableColl = $copyCollColl[0];

            /*if (sizeof($copyCollColl) == 20) {
                $assumedRetainableColl = $copyCollColl[18];
            }*/

            //echo "<br>removed ";
            //var_dump($assumedRetainableColl);
            /*echo "<br>before ";
            var_dump($copyCollColl);
            echo "<br>";*/
            $copyCollColl = $this->removeAll($copyCollColl, array($assumedRetainableColl));
            //echo "<br>after ";
            //var_dump($copyCollColl);
            //echo "<br>";


            begin:
            //$removeFromCopyCollColl = array();
            foreach ($copyCollColl as $nextColl) {

                if (time() >= $finish) {
                    return "Maximum execution time";
                }

                $assumedRemovableColl = $nextColl;

                /*echo "<br>";
                echo "<br>assumedRetainableColl ";
                var_dump($assumedRetainableColl);
                echo "<br>assumedRemovableColl ";
                var_dump($assumedRemovableColl);
                echo "<br>";*/

                //assumedRemovableColl.containsAll(assumedRetainableColl))
                $containsAllRemovable = ! array_diff($assumedRetainableColl, $assumedRemovableColl);

                //assumedRetainableColl.containsAll(assumedRemovableColl)
                $containsAllRetainable = ! array_diff($assumedRemovableColl, $assumedRetainableColl);

                if (sizeof($assumedRemovableColl) > sizeof($assumedRetainableColl) && $containsAllRemovable) {
                    //collColl.removeAll(Arrays.asList(assumedRetainableColl));
                    //echo "oi 1";
                    $collColl = $this->removeAll($collColl, array($assumedRemovableColl));

                    /*echo "<br>remove 1<br>";
                    var_dump($assumedRemovableColl);
                    echo "<br>";*/

                    //array_push($removeFromCopyCollColl, $nextColl);
                    //echo "<br>size antes " . sizeof($copyCollColl) . "<br>";
                    $copyCollColl = $this->remove_2D($copyCollColl, $nextColl);
                    //echo "<br>size depois " . sizeof($copyCollColl) . "<br>";
                    goto begin;
                } else if (sizeof($assumedRetainableColl) > sizeof($assumedRemovableColl) && $containsAllRetainable) {
                    //echo "oi 2";
                    //collColl.removeAll(Arrays.asList(assumedRemovableColl));
                    $collColl = $this->removeAll($collColl, array($assumedRetainableColl));

                    /*echo "<br>remove 2<br>";
                    var_dump($assumedRetainableColl);
                    echo "<br>";*/
                    break;
                }
            }

            /*foreach ($removeFromCopyCollColl as $nextColl) {
                $copyCollColl = $this->remove_2D($copyCollColl, $nextColl);
            }*/

        }

        //echo "end";

        return $collColl;
    }

    /**
     * Returns this AF's preferred extensions. 
     *
     * @return a set of {@code String}-sets, denoting this AF's preferred extensions.
     */ 
    public function getPreferredExts() {

        $finish = time() + MAX_TIME;

        //boolean argIsDefended;
        //boolean disqualifiedByPrefExts;
        $revisedPrefExtCan = array();
        $admiArgs = array();
        $copiesOfPrefExts = array();//array(array());
        $pairsInConflict = array();//array(array());
        $minimalRemovalSets = array();//array(array());
        $prefExtCandidates = array();//array(array());
        $revisedPrefExtCandidates = array();//array(array());

        $tempSetStr = array();
        $tempSetSetStr = array();//array(array());

        /* the preferred extensions might already be recorded. */
        if (! empty($this->preferredExts)) {
            return $this->preferredExts;
        }

        //echo "<br>cu<br>";
        //var_dump($admiArgs);
        //exit;
        
        foreach ($this->args as $nextArg) {
            //echo "<br>arg: " . $nextArg . "<br>";
            $result = $this->getDefenceSetsAround($nextArg);
            if (! empty($result)) {
                array_push($admiArgs, $nextArg);
            }

            if (time() >= $finish) {
                return "Maximum execution time";
            }
        }

        //echo "<br>saiu<br>";
        //var_dump($admiArgs);
        //exit;

        /* for every admissible argument admiArg0, find the set 
         argSet = { admiArg1 | there is an admissible set including admiArg0 and admiArg1 }. argSet is such that for 
         each arg0 in argSet, arg0 is acceptable wrt argSet. Hence argSet is either (a) a preferred extension, or 
         (b) a (non-conflict-free) superset of at least one preferred extension. */
        foreach ($admiArgs  as $nextArg0) {
            $tempSetStr = array();

            foreach ($admiArgs as $nextArg1) {
                if ($this->containsNoConflictAmong(array($nextArg1, $nextArg0))) {

                    if (time() >= $finish) {
                        return "Maximum execution time";
                    }

                    $breakFors = false;
                    foreach ($this->argsToDefenceSets[$nextArg0] as $nextDefSetOfNextArg0) {
                        foreach ($this->argsToDefenceSets[$nextArg1] as $nextDefSetOfNextArg1) {
                            $tempSetSetStr = $nextDefSetOfNextArg0;

                            foreach ($nextDefSetOfNextArg1 as $nextNextDefSetOfNextArg1) {
                                if(! in_array($nextNextDefSetOfNextArg1, $tempSetSetStr, true)) {
                                    array_push($tempSetSetStr, $nextNextDefSetOfNextArg1);
                                }
                            }

                            if ($this->containsNoConflictAmong($tempSetSetStr)) {
                                array_push($tempSetStr, $nextArg1);
                                $breakFors = true;
                            }

                            if ($breakFors) {
                                break;
                            }
                        }

                        if ($breakFors) {
                            break;
                        }
                    }
                }
            }

            if (empty($prefExtCandidates)) {
                array_push($prefExtCandidates, $tempSetStr);
            } else if (! $this->in_array_2D($prefExtCandidates, $tempSetStr)) {
                array_push($prefExtCandidates, $tempSetStr);
            }

            if (time() >= $finish) {
                return "Maximum execution time";
            }
        }

        //echo "<br>preferred candidates<br>";
        //var_dump($prefExtCandidates);
        //exit;

        /* identify preferred extensions in preferredExtCandidates. And for ever other set in 
         preferredExtCandidates, find the preferred extension(s) subsumed by it. */
        foreach ($prefExtCandidates as $nextExtCan) {

            //echo "<br>next can<br>";
            //var_dump($nextExtCan);

            /* find every conflicting pair of arguments in nextExtCan. */
            $pairsInConflict = array();

            foreach ($nextExtCan as $nextArg) {

                $tempSetStr = $this->getAttackersOf($nextArg);

                //tempSetStr.retainAll(nextExtCan);
                do {
                    $change = false;
                    foreach($tempSetStr as $nextSetArg) {
                        if (! in_array($nextSetArg, $nextExtCan, true)){
                            //$tempSetStr = $this->remove($tempSetStr, $nextSetArg);
                            $key = array_search($nextSetArg, $tempSetStr);
                            unset($tempSetStr[$key]);
                            $change = true;
                            break;
                        }
                    }
                } while ($change);

                foreach ($tempSetStr as $nextAttacker) {

                    $samePair = false;
                    foreach ($pairsInConflict as $nextPair) {
                        if ($nextPair[1] == $nextArg && $nextPair[0] == $nextAttacker) {
                            $samePair = true;
                            break;
                        }

                        if ($nextPair[0] == $nextArg && $nextPair[1] == $nextAttacker) {
                            $samePair = true;
                            break;
                        }
                    }

                    //if (! isset($pairsInConflict[$nextArg])) {
                    //    $pairsInConflict[$nextArg] = array();
                    //}

                    if (! $samePair) {
                        array_push($pairsInConflict, array($nextArg, $nextAttacker));
                    }
                }

                if (time() >= $finish) {
                    return "Maximum execution time";
                }
            }

            //echo "<br>Conflicts<br>";
            //var_dump($pairsInConflict);

            /* if there are no pairs-in-conflict, nextExtCan is a preferred extension; for convenience, record it in 
             revisedPrefExtCandidates. Doing this facilitates the identification of the preferred extension(s) 
             subsumed by other members of prefExtCandidates. */
            if (sizeof($pairsInConflict) == 0) {
                if (! $this->in_array_2D($revisedPrefExtCandidates, $nextExtCan)) {
                    array_push($revisedPrefExtCandidates, $nextExtCan);
                }
                //echo "<br>Revised up<br>";
                //var_dump($nextExtCan);
            } else {
                /* otherwise, for every preferred extension prefExt1 subsumed by nextExtCan, prefExt1 is such that, 
                 for some *minimal* member argSet1 of the cartesian product of pairsInConflict, (nextExtCan\argSet1)
                 is a (strict or non-strict) superset of prefExt1. */
                $minimalRemovalSets = array();

                //echo "<br>Revised antes<br>";
                //var_dump($revisedPrefExtCandidates);
                //echo "<br><br>";
                //var_dump($pairs);

                $pairs = $this->cartesianProduct($pairsInConflict);

                //echo "<br>Pairs<br>";
                //var_dump($pairs);
                //exit;

                if (time() >= $finish) {
                    return "Maximum execution time";
                }

                foreach($pairs as $nextList) {
                    if (! $this->in_array_2D($minimalRemovalSets, $nextList)) {
                        array_push($minimalRemovalSets, $nextList);
                    }
                }

                //echo "<br>Minimum antes<br>";
                //var_dump($minimalRemovalSets);
                //exit;

                /* minimalRemovalSets might contain comparable members, because one argument might be in conflict with 
                 multiple arguments. */

                $minimalRemovalSets = $this->removeNonMinimalMembersOf($minimalRemovalSets);

                if (time() >= $finish) {
                    return "Maximum execution time";
                }

                //echo "<br>Minimum<br>";
                //var_dump($minimalRemovalSets);
                //exit;

                /* For each member argSet1 of minimalRemovalSets, (nextExtCan\argSet1) is a maximal conflict-free subset
                 of nextExtCan. Hence argSet1 either is a preferred extension, or it does not adequately defend itself. 
                 So for each argSet1, (i) define revisedPrefExtCan = (nextExtCan\argSet1); then (ii) remove from 
                 revisedPrefExtCan every argument arg1, such that arg1 is not acceptable wrt revisedPrefExtCan. If 
                 revisedPrefExtCan is now admissible, it is a preferred extension. Otherwise, keep repeating (ii), until 
                 revisedPrefExtCan is found to be either (a) a strict subset of a preferred extension which has already 
                 been found; or (b) admissible. If (a), discard revisedPrefExtCan; if (b), revisedPrefExtCan is 
                 *perhaps* a preferred extension.  */
                foreach ($minimalRemovalSets as $nextMinimalRemovalSet) {
                    $revisedPrefExtCan = $nextExtCan;

                    //revisedPrefExtCan.removeAll(nextMinimalRemovalSet);
                    do {
                        $change = false;
                        foreach ($revisedPrefExtCan as $nextRevised) {
                            if (in_array($nextRevised, $nextMinimalRemovalSet)) {
                                $key = array_search($nextRevised, $revisedPrefExtCan);
                                unset($revisedPrefExtCan[$key]);

                                // Fix indexes
                                $key = 0;
                                $copy = array();
                                foreach ($revisedPrefExtCan as $next) {
                                    $copy[$key] = $next;
                                    $key++;
                                }

                                $revisedPrefExtCan = $copy;

                                $changed = true;
                            }
                        }
                    } while ($change);

                    //echo "<br>revised depois <br>";
                    //var_dump($revisedPrefExtCan);


                    $disqualifiedByPrefExts = false;

                    if (time() >= $finish) {
                        return "Maximum execution time";
                    }

                    //echo "<br> tentou: <br>";
                    //var_dump($revisedPrefExtCan);
                    //exit;

                    while (! $disqualifiedByPrefExts && ! $this->admissibleSetsContain(array($revisedPrefExtCan))) {

                        if (time() >= $finish) {
                            return "Maximum execution time";
                        }

                        //echo "entrou";
                        //var_dump($revisedPrefExtCan);
                        //exit;
                        $key = 0;

                        //foreach (array($revisedPrefExtCan) as $nextRevised) {

                        //echo "entrou 2 <br>";
                        //var_dump($nextRevised);
                        //exit;

                            do {

                                $change = false;

                                foreach ($revisedPrefExtCan as $nextArg) {
                                //for (Iterator<String> it = revisedPrefExtCan.iterator(); it.hasNext();) {
                                    /* assume that revisedPrefExtCan subsumes none of the next argument's defence-sets. */
                                    $argIsDefended = false;
                                    /* check that assumption. */
                                    foreach ($this->argsToDefenceSets[$nextArg] as $nextDefenceSet) {
                                        //revisedPrefExtCan.containsAll(nextDefenceSet)
                                        //if (revisedPrefExtCan.containsAll(nextDefenceSet)) { 

                                        //foreach ($revisedPrefExtCan as $nextArgRevise) {
                                        //
                                        //}
                                        if (sizeof(array_intersect($nextDefenceSet, $revisedPrefExtCan)) == sizeof($nextDefenceSet)) {
                                        //if (! array_diff($nextDefenceSet, $revisedPrefExtCan)) { 
                                            $argIsDefended = true;
                                            break; 
                                        }
                                    }

                                    if (! $argIsDefended) {
                                        $key = array_search($nextArg, $revisedPrefExtCan);

                                        //echo "<br> size antes: " . sizeof($revisedPrefExtCan) . "<br>";;

                                        unset($revisedPrefExtCan[$key]);
                                        $change = true;

                                        // Fix indexes
                                        $key = 0;
                                        $copy = array();
                                        foreach ($revisedPrefExtCan as $next) {
                                            $copy[$key] = $next;
                                            $key++;
                                        }

                                        $revisedPrefExtCan = $copy;

                                        //echo "<br> size depois: " . sizeof($revisedPrefExtCan) . "<br>";;

                                        break;
                                    }
                                }

                                if (time() >= $finish) {
                                    return "Maximum execution time";
                                }
                                
                                //cho "<br> nextRevised: <br>"; 
                                //var_dump($revisedPrefExtCan);

                            } while($change);

                            //echo "<br>nextRevised <br>";
                            //var_dump($revisedPrefExtCan);
                            //exit;

                            /* revisedPrefExtCan might now be too small. */
                            foreach ($revisedPrefExtCandidates as $nextRevCan) {
                                //nextRevCan.containsAll(nextRevCan)
                                if (! array_diff($nextRevCan, $revisedPrefExtCan)) { 
                                    $disqualifiedByPrefExts = true;
                                    break;
                                }
                            }
                        //}
                    }

                    if (! $disqualifiedByPrefExts) {
                        //echo "<br>Tentativa<br>";
                        //var_dump($revisedPrefExtCan);
                        //echo "<br>Atual<br>";
                        //var_dump($revisedPrefExtCandidates);
                        //echo "<br>";
                        if (! $this->in_array_2D($revisedPrefExtCandidates, $revisedPrefExtCan)) {
                            array_push($revisedPrefExtCandidates, $revisedPrefExtCan);
                            //echo "<br>Entrou<br>";
                            //echo "<br>Revised below<br>";
                            //var_dump($revisedPrefExtCan);
                        }
                    }
                }
            }

            /* the above process might place non-preferred admissible sets into revisedPrefExtCandidates - if 
             (i) nextExtCan was reduced to such a set nonPrefAdmiSet, and (ii) none of the preferred extensions 
             subsuming nonPrefAdmiSet had yet been found, then nonPrefAdmiSet would be added to 
             revisedPrefExtCandidates. */

            //echo "<br>revised antes<br>";
            //var_dump($revisedPrefExtCandidates);

            if (isset($revisedPrefExtCandidates[0])) {
                $revisedPrefExtCandidates = $this->removeNonMaximalMembersOf($revisedPrefExtCandidates);

                if (time() >= $finish) {
                    return "Maximum execution time";
                }
            }

            //echo "<br>revised depois<br>";
            //var_dump($revisedPrefExtCandidates);
        }

        /* if revisedPrefExtCandidates is empty, there is just one preferred extension: the empty set. */
        if (sizeof($revisedPrefExtCandidates) == 0) {
            $this->preferredExts = array();
        } else {
            $this->preferredExts = $revisedPrefExtCandidates;
        }

        //var_dump($this->preferredExts);

        return $this->preferredExts;
    }

    /**
     * Returns the union of this AF's admissible sets.
     *
     * @return a set of {@code String}s, denoting the union of this AF's admissible sets.
     */ 
    /*public HashSet<String> getAdmissibleArgs() {
        
        return getExtsUnion("admissible");
    }*/
    
    /**
     * Returns this AF's admissible sets. 
     *
     * @return a set of {@code String}-sets, denoting this AF's admissible sets.
     */ 
    public function getAdmissibleSets() {

        $admiSetCandidate = array();
        $copiesOfAdmiSets = array();
        $toDoAdmiSets = array();
        $newAdmiSets = array();

        $finish = time() + MAX_TIME;

        /* the admissible sets might already be recorded */
        if (! empty($this->admissibleSets)) {
            return $this->admissibleSets;
        }

        /* the empty set is always admissible. */
        $this->admissibleSets = array();

        /* find the minimal non-empty admissible sets. */
        foreach ($this->args as $nextArg) {
            foreach ($this->getDefenceSetsAround($nextArg) as $nextDefSet) {
                if (empty($this->admissibleSets)) {
                    array_push($this->admissibleSets, $nextDefSet);
                } else if (! $this->in_array_2D($this->admissibleSets, $nextDefSet)) {
                    array_push($this->admissibleSets, $nextDefSet);
                }
            }
        }

        if (time() >= $finish) {
            return "Maximum execution time";
        }

        /* find the maximal admissible sets. */

        $preferred = $this->getPreferredExts();
        if (time() >= $finish) {
            return "Maximum execution time";
        }

        foreach ($preferred as $nextPrefExt) { 
            if (empty($this->admissibleSets)) {
                array_push($this->admissibleSets, $nextPrefExt);
            } else if (! $this->in_array_2D($this->admissibleSets, $nextPrefExt)) {
                array_push($this->admissibleSets, $nextPrefExt);
            }
        }

        if (time() >= $finish) {
            return "Maximum execution time";
        }

        // Empty array is admissible
        array_push($this->admissibleSets, array());

        /* find the intermediate-sized admissible sets. To do this, proceed from the preferred extensions. 
         For each preferred extension argSet, for each arg1 in argSet, proceed as follows. Remove arg1 from argSet. 
         Then (i) remove every arg2 from argSet, such that arg2 is not acceptable wrt argSet; and 
         (ii) repeat (i) until argSet is admissible. 
         If argSet is already in admissibleSets, discard it; otherwise add it to admissibleSets, and repeat the 
         whole exercise on it. */
        foreach($this->preferredExts as $nextPrefExt) {
            array_push($toDoAdmiSets, $nextPrefExt);
        }

        while (! empty($toDoAdmiSets)) {

            if (time() >= $finish) {
                return "Maximum execution time";
            }

            foreach ($toDoAdmiSets as $nextAdmiSet) {

                if (time() >= $finish) {
                    return "Maximum execution time";
                }

                /* find all of the admissible sets subsumed by nextAdmiSet. */
                foreach ($nextAdmiSet as $nextArg) {

                    if (time() >= $finish) {
                        return "Maximum execution time";
                    }

                    /* create admiSetCandidate by removing nextArg from nextAdmiSet. */
                    $admiSetCandidate = $nextAdmiSet;
                    //$admiSetCandidate = $this->remove($admiSetCandidate, $nextArg);
                    $key = array_search($nextArg, $admiSetCandidate);
                    unset($admiSetCandidate[$key]);
                    /* revise admiSetCandidate, until it is admissible. */
                    while (! $this->argsAccept($admiSetCandidate, $admiSetCandidate)) {

                        if (time() >= $finish) {
                            return "Maximum execution time";
                        }

                        do {
                            $change = false;
                            foreach ($admiSetCandidate as $nextArgAdmi) {

                                if (time() >= $finish) {
                                    return "Maximum execution time";
                                }

                                /* assume that admiSetCandidate subsumes none of the argument's defence-sets. */
                                $argIsDefended = false;
                                /* check that assumption. */
                                foreach ($this->argsToDefenceSets[$nextArgAdmi] as $nextDefenceSet) { 

                                    if (time() >= $finish) {
                                        return "Maximum execution time";
                                    }

                                    //admiSetCandidate.containsAll(nextDefenceSet)
                                    $containsAllArgs = ! array_diff($nextDefenceSet, $admiSetCandidate);
                                    if ($containsAllArgs) { 
                                        $argIsDefended = true;
                                        break; 
                                    } 
                                }

                                if (! $argIsDefended) {
                                    //$admiSetCandidate = $this->remove($admiSetCandidate, $nextArgAdmi);
                                    $key = array_search($nextArgAdmi, $admiSetCandidate);
                                    unset($admiSetCandidate[$key]);
                                    $change = true;
                                    break;
                                }
                            }
                         } while ($change);
                    }

                    /* if it has already been found, admiSetCandidate must be disregarded */
                    if (empty($this->admissibleSets)) {
                        array_push($this->admissibleSets, $admiSetCandidate);

                        if (empty($newAdmiSets)) {
                            array_push($newAdmiSets, $admiSetCandidate);
                        } else if (! $this->in_array_2D($newAdmiSets, $admiSetCandidate)) {
                            array_push($newAdmiSets, $admiSetCandidate);
                        }
                    } else if (! $this->in_array_2D($this->admissibleSets, $admiSetCandidate)) {
                        array_push($this->admissibleSets, $admiSetCandidate);

                        if (empty($newAdmiSets)) {
                            array_push($newAdmiSets, $admiSetCandidate);
                        } else if (! $this->in_array_2D($newAdmiSets, $admiSetCandidate)) {
                            array_push($newAdmiSets, $admiSetCandidate);
                        }
                    }
                }
            }
            /* every newly-found admissible set is a toDoAdmiSet, because it might subsume other admissible sets. */
            $toDoAdmiSets = $newAdmiSets;
            $newAdmiSets = array();
        }

        return $this->admissibleSets;
    }

    /* Returns this AF's stable extensions. 
     *
     * @return a set of {@code String}-sets, denoting this AF's stable extensions.
     */
    public function getStableExts() {

        $finish = time() + MAX_TIME;

        $copiesOfStableExts = array();
        $argsNotAttackedByNextExt = array();

        if (empty($this->stableExts)) {
            $this->stableExts = array();
            /* seek stable extensions among the preferred extensions. */

            $preferred = $this->getPreferredExts();

            if (time() >= $finish) {
                return "Maximum execution time";
            }

            foreach ($preferred as $nextExt) {

                $argsNotAttackedByNextExt = $this->getArgs();
                foreach ($nextExt as $nextArg) {
                    //argsNotAttackedByNextExt.removeAll(getTargetsOf(nextArg));
                    do {
                        $change = false;
                        foreach ($argsNotAttackedByNextExt as $nextRevised) {
                            if (in_array($nextRevised, $this->getTargetsOf($nextArg))) {
                                //$argsNotAttackedByNextExt = $this->remove($argsNotAttackedByNextExt, $nextRevised);
                                $key = array_search($nextRevised, $argsNotAttackedByNextExt);
                                unset($argsNotAttackedByNextExt[$key]);
                                $changed = true;
                            }
                        }
                    } while ($change);
                }

                if (time() >= $finish) {
                    return "Maximum execution time";
                }

                $diff = array_diff($argsNotAttackedByNextExt, $nextExt);
                if (empty($diff)) {
                    array_push($this->stableExts, $nextExt);
                }
            }
        }

        return $this->stableExts;
    }

    public function getSemiStableExts() {

        $finish = time() + MAX_TIME;

        $candidateSet = array();
        $altCandidateSet = array();
        $candidateSetRange = array();
        $altCandidateSetRange = array();
        $candidateSets = array();

        $copiesOfSemiStableExts = array();

        /* the semi-stable extensions might already be recorded... */
        if (! empty($this->semiStableExts)) { 
            foreach ($this->semiStableExts as $nextExt) {
                array_push($copiesOfSemiStableExts, $nextExt);
            }

            return $copiesOfSemiStableExts;
        }

        if (time() >= $finish) {
            return "Maximum execution time";
        }

        $this->semiStableExts = array();

        /* ...or there might be stable extensions... */
        $stables = $this->getStableExts();
        if (! empty($stables)) {
            foreach ($this->stableExts as $nextExt) { 
                array_push($this->semiStableExts, $nextExt); 
            }
        }

        if (time() >= $finish) {
            return "Maximum execution time";
        }

        /* ...otherwise, seek the semi-stable extensions among the preferred extensions. */
        $candidateSets = $this->getPreferredExts();

        if (time() >= $finish) {
            return "Maximum execution time";
        }

        /* proceed through candidateSets, comparing their ranges */
        for ($i = 0; $i < sizeof($candidateSets); $i++) {
            $candidateSet = $candidateSets[$i];

            $candidateSetRange = array();
            foreach ($candidateSet as $nextArg) { 
                array_push($candidateSetRange, $nextArg);

                foreach ($this->getTargetsOf($nextArg) as $nextTarget) {
                    if (! in_array($nextTarget, $candidateSetRange, true)) {
                        array_push($candidateSetRange, $nextTarget);
                    }
                }
            }

            if (time() >= $finish) {
                return "Maximum execution time";
            }

            /* compare candidateSetRange with the ranges of the alternative candidate-sets, breaking if an alternative 
             candidate-set with greater range is found. */
            for ($j = ($i+1); $j < sizeof($candidateSets); $j++) {
                $altCandidateSet = $candidateSets[$j];

                $altCandidateSetRange = array();
                foreach ($altCandidateSet as $nextArg) {
                    array_push($altCandidateSetRange, $nextArg);

                    foreach ($this->getTargetsOf($nextArg) as $nextTarget) {
                        if (! in_array($nextTarget, $altCandidateSetRange, true)) {
                            array_push($altCandidateSetRange, $nextTarget);
                        }
                    }
                }

                if (time() >= $finish) {
                    return "Maximum execution time";
                }

                //candidateSetRange.containsAll(altCandidateSetRange)
                $containsAllCandidate = ! array_diff($altCandidateSetRange, $candidateSetRange);

                //altCandidateSetRange.containsAll(candidateSetRange)
                $containsAllAlt = ! array_diff($candidateSetRange, $altCandidateSetRange);

                if (sizeof($candidateSetRange) > sizeof($altCandidateSetRange) && $containsAllCandidate) {
                    /* altCandidateSet cannot be semi-stable. */

                    unset($candidateSets[$j]);

                    // Fix indexes
                    $key = 0;
                    $copy = array();
                    foreach ($candidateSets as $nextCandidate) {
                        $copy[$key] = $nextCandidate;
                        $key++;
                    }

                    $candidateSets = $copy;

                    $j--;
                } else if (sizeof($altCandidateSetRange) > sizeof($candidateSetRange) && $containsAllAlt) {
                    /* candidateSet cannot be semi-stable, so remove it from candidateSets, and break. */

                    unset($candidateSets[$i]);

                    // Fix indexes
                    $key = 0;
                    $copy = array();
                    foreach ($candidateSets as $nextCandidate) {
                        $copy[$key] = $nextCandidate;
                        $key++;
                    }

                    $candidateSets = $copy;

                    $i--;

                    break;
                }
            }
        }

        if (time() >= $finish) {
            return "Maximum execution time";
        }

        foreach ($candidateSets as $nextCandidate) {
            if (! $this->in_array_2D($this->semiStableExts, $nextCandidate)) {
                array_push($this->semiStableExts, $nextCandidate);
            }
        }

        return $this->semiStableExts;
    }

    /**
     * Returns this AF's ideal extension. 
     *
     * @return a set of {@code String}s, denoting this AF's ideal extension.
     */
    public function getIdealExt() {

        $finish = time() + MAX_TIME;

        if (empty($this->idealExt)) { 
            $this->findIdealExtOrEagerExt("ideal"); 
        }

        if (time() >= $finish) {
            return "Maximum execution time";
        }

        return $this->idealExt;
    }

    /**
     * Returns this AF's eager extension. 
     *
     * @return a set of {@code String}s, denoting this AF's eager extension.
     */
    public function getEagerExt() {

        $finish = time() + MAX_TIME;

        if (empty($this->eagerExt)) { 
            $this->findIdealExtOrEagerExt("eager"); 
        }

        if (time() >= $finish) {
            return "Maximum execution time";
        }

        return $this->eagerExt;
    }

    /**
     * Finds and records this AF's ideal extension or eager extension. 
     *
     * @param semantics either "ideal" or "eager", depending on whether the ideal extension or the eager extension 
     * is required.
     * @throws IllegalArgumentException if {@code semantics} is neither "ideal" nor "eager".
     */
    private function findIdealExtOrEagerExt($semantics) {

        $finish = time() + MAX_TIME;

        $requiredExt = array();
        $relevantExts = array();

        /* find the intersection of the relevant extensions. */
        if ($semantics == "ideal") {
            $relevantExts = $this->getPreferredExts();
        } else if ($semantics == "eager") {
            $relevantExts = $this->getSemiStableExts();
        } else {
            throw new Exception("parameter 'semantics' is neither ideal nor eager.");
        }

        if (time() >= $finish) {
            return "Maximum execution time";
        }


        //requiredExt.addAll(relevantExts.iterator().next());
        foreach ($relevantExts[0] as $nextArg) {
            if (! in_array($nextArg, $requiredExt, true)) {
                array_push($requiredExt, $nextArg);
            }
        }

        foreach ($relevantExts as $nextSet) {
            //requiredExt.retainAll(nextSet);
            do {
                $change = false;
                foreach($requiredExt as $nextSetArg) {
                    if (! in_array($nextSetArg, $nextSet, true)){
                        //$requiredExt = $this->remove($requiredExt, $nextSetArg);
                        $key = array_search($nextSetArg, $requiredExt);
                        unset($requiredExt[$key]);
                        $change = true;
                        break;
                    }
                }

                // Fix indexes
                $key = 0;
                $copy = array();
                foreach ($requiredExt as $nextRequired) {
                    $copy[$key] = $nextRequired;
                    $key++;
                }

                $requiredExt = $copy;
            } while ($change);
        }

        if (time() >= $finish) {
            return "Maximum execution time";
        }

        /* remove all members of requiredExt which are not acceptable wrt requiredExt; and do so repeatedly, 
         until requiredExt is admissible. */
        while (! $this->argsAccept($requiredExt, $requiredExt)) {
            do {
                $change = false;
                foreach($requiredExt as $nextArg) {

                    $nextArgArray = array();
                    array_push($nextArgArray, $nextArg);

                    if (! $this->argsAccept($requiredExt, $nextArgArray)) {
                        $key = array_search($nextArg, $requiredExt);
                        unset($requiredExt[$key]);

                        // Fix indexes
                        $key = 0;
                        $copy = array();
                        foreach ($requiredExt as $nextRequired) {
                            $copy[$key] = $nextRequired;
                            $key++;
                        }

                        $requiredExt = $copy;
                        $change = true;
                        break;
                    }
                }
            }  while ($change);

            if (time() >= $finish) {
                return "Maximum execution time";
            }
        }

        if ($semantics == "ideal") {
            $this->idealExt = $requiredExt;
        } else {
            $this->eagerExt = $requiredExt;
        }
    }

    public function random_0_1()
    {   // auxiliary function
        // returns random number with flat distribution from 0 to 1
        return (float)rand()/(float)getrandmax();
    }

    public function cartesianProduct($pairsInConflict)
    {
        $finish = time() + MAX_TIME;

        $pairs = array();

        if (sizeof($pairsInConflict) == 1) {
            array_push($pairs, array($pairsInConflict[0][0]));
            array_push($pairs, array($pairsInConflict[0][1]));
            return $pairs;
        }

        //for ($i = 0; $i < sizeof($pairsInConflict) - 1; $i++) {

            if (time() >= $finish) {
                return "Maximum execution time";
            }

            //for ($j = $i + 1; $j < sizeof($pairsInConflict); $j++) {

            //if (time() >= $finish) {
            //    return "Maximum execution time";
            //}

            //$product = new CartesianProduct(
            //    array(["AR1", "PA4"], ["PA4", "PD1"], ["PA4", "PS1"], ["PA4", "PF4"], ["PA4", "SP1"], ["TD1", "PA4"], ["PA4", "TS1"], ["PA4", "SR1"], ["PA4", "MV1"], ["PA4", "MR1"])
            //);

            //echo "<br>product<br>";
            //echo sizeof($product);
            //exit;

            //foreach($product as $i => $tuple)
            //    echo $i, ' = (', implode(', ', $tuple), ')', "\n";
            //    exit;


            //$pairsAhead = array($pairsInConflict[0][0], $pairsInConflict[0][1]);
            //var_dump($pairsAhead);
            //exit;

            $pairsAhead = array();
            for ($j = 0; $j < sizeof($pairsInConflict); $j++) {
                array_push($pairsAhead, array($pairsInConflict[$j][0], $pairsInConflict[$j][1]));
                //array_push($pairsAhead, $pairsInConflict[$j][1]);
                //$pairsAhead[0] += $pairsInConflict[$j][0];
                //$pairsAhead[0] += $pairsInConflict[$j][1];
            }

            //echo "<br>all pairs<br>";
            //var_dump($pairsAhead);
            //exit;

            //echo "<br>all pairs<br>";
            //var_dump($pairsInConflict);

            //echo "<br>pair 0<br>";
            //var_dump($pairsInConflict[$i]);

            //echo "<br>pairs ahead<br>";
            //var_dump($pairsAhead);

            //echo "<br><br>";
            $product = new CartesianProduct($pairsAhead);

            $size = 0;
            foreach($product as $i => $elements) {
                $size++;
            }

            // What is this???
            //if ($size < pow(sizeof($pairsAhead), 2)) {
            //    return "Maximum execution time";
            //}

            //echo "<br>product<br>";
            //echo sizeof($product);
            //exit;

            foreach($product as $i => $elements) {

                //echo "<br>pairs<br>";
                //var_dump($elements); 

                // Remove duplicates from cartesian product
                $uniqueProduct = array_unique($elements, SORT_REGULAR);
                array_push($pairs, $uniqueProduct);

                /*$samePair = false;
                foreach ($pairs as $nextPair) {
                    if (sizeof($nextPair) == 2) {
                        if ($nextPair[1] == $elements[0] && $nextPair[0] == $elements[1]) {
                            $samePair = true;
                            break;
                        }

                        if ($nextPair[0] == $elements[0] && $nextPair[1] == $elements[1]) {
                            $samePair = true;
                            break;
                        }
                    } else {
                        if ($nextPair[0] == $elements[0]) {
                            break;
                        }
                    }
                }

                if ($samePair) {
                    continue;
                }

                if ($elements[0] != $elements[1]) {
                    array_push($pairs, array($elements[0], $elements[1]));
                } else {
                    array_push($pairs, array($elements[0]));
                }*/
            }

            //exit;
            //}
        //}

        //var_dump ($pairs);
        //exit;
        return $pairs;
    }
}


?>
