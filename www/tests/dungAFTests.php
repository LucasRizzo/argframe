<?php

require_once ('../simpletest/autorun.php');

class DungAFTests extends UnitTestCase
{

    private $af;

    public function setUp () {
        require_once ("../semantics/dungAF.php");

        // Empty af
        $this -> af = DungAF::PopulatedDungAF(array(), "");
    }

    public function tearDown () {
        $this-> af = NULL ;
    }


    public function testConflictFunc () {

        // Empty af
        $this->assertTrue($this->af->hasAsConflictFreeSet(array()) &&
                          $this->af->containsNoConflictAmong(array()));

        $atts = array(0=>array('a'=>'b'), 1=>array('c'=>'d'), 2=>array('e'=>'f'));
        $this->af = DungAF::PopulatedDungAF($atts, "");
        $this->assertTrue(! $this->af->hasAsConflictFreeSet(array('g','h')) && $this->af->containsNoConflictAmong(array('g', 'h')));
        $this->assertTrue($this->af->hasAsConflictFreeSet(array('a', 'c', 'e')) && $this->af->containsNoConflictAmong(array('a', 'c', 'e')));
        $this->assertTrue(! $this->af->hasAsConflictFreeSet(array('a', 'b', 'd')) && ! $this->af->containsNoConflictAmong(array('a', 'b', 'd')));
        $this->assertFalse($this->af->collnIsInConflictWithAnyOf(array('a', 'c'), array('e', 'f')));
        $this->assertTrue($this->af->collnIsInConflictWithAnyOf(array('a', 'b'), array('b')));
        $this->assertTrue($this->af->hasUnionOfAsConflictFreeSet(array(array('a','c'), array('e'))) && $this->af->containsNoConflictAmongUnionOf(array(array('a','c'),array('e'))));
        $this->assertTrue(! $this->af->hasUnionOfAsConflictFreeSet(array(array('a','c'), array('b'))) && ! $this->af->containsNoConflictAmongUnionOf(array(array('a','c'),array('b'))));
        $this->assertTrue(! $this->af->hasUnionOfAsConflictFreeSet(array(array('a','g'), array('e'))) && $this->af->containsNoConflictAmongUnionOf(array(array('a','g'),array('e'))));
    }

    public function testAcceptabilityFunc() {

        $atts = array(0=>array('a'=>'b'), 1=>array('c'=>'d'), 2=>array('e'=>'f'));
        $this->af = DungAF::PopulatedDungAF($atts, "");

        $this->assertTrue($this->af->getArgsAcceptedBy($this->af->getArgs()) && $this->af->argsAccept($this->af->getArgs(), array('a', 'c', 'e')));
        $this->assertTrue($this->af->getArgsAcceptedBy($this->af->getArgs()) && $this->af->argsAccept($this->af->getArgs(), array('a', 'c', 'e')));

        $atts = array(0=>array('a'=>'b'), 1=>array('c'=>'d'), 2=>array('e'=>'f'), 3=>array('f'=>'g'));
        $this->af = DungAF::PopulatedDungAF($atts, "");

        $this->assertTrue(sizeof(array_diff($this->af->getArgsAcceptedBy(array('e')), array('a','c','e','g'))) == 0);
        $this->assertTrue($this->af->argsAccept(array('e'), array('a','c','e','g')));
    }

    public function testDefenceSets() {

        // argpool from 'a' to 'z'
        $argPool = array();
        for($i = 97; $i <= 122; $i++) {
            array_push($argPool, chr($i)); 
        }

        $ITERATIONS = 100;
        $MIN_ARGS = 5;
        $MAX_ARGS = 10;
        $MIN_ATTS = 5;
        $MAX_ATTS = 20; 

        for ($i = 1; $i <= $ITERATIONS; $i++) {
            $af = $this->af->getRandomDungAF($MIN_ARGS, $MAX_ARGS, $MIN_ATTS, $MAX_ATTS, $argPool);

            $returnedDefSets = array();
            $argSets = array();
            $argsToSubsumedAdmiSetSets = array();
            $argsToInadmissibleReturnedSetSets = array();
            $argsToMissingDefSetSets = array();
            $resultsOK = true;

            foreach ($af->getArgs() as $nextArg) {
                $returnedDefSets = $af->getDefenceSetsAround($nextArg);

                /* check that returnedDefSets includes no strict superset of a defence-set for nextArg, assuming that
                getAdmissibleSets() is correct. */
                $argSets = array();

                foreach ($returnedDefSets as $nextReturnedDefSet) {
                    //var_dump($af->getAdmissibleSets());

                    $admissible = $af->getAdmissibleSets();

                    if ($admissible == array(array())) {
                        continue;
                    }

                    foreach ($admissible as $nextAdmiSet) {

                        //nextReturnedDefSet.containsAll(nextAdmiSet)
                        $containsAll = ! array_diff($nextAdmiSet, $nextReturnedDefSet);

                        if (sizeof($nextReturnedDefSet) > sizeof($nextAdmiSet) &&
                            in_array($nextArg, $nextAdmiSet, true) &&
                            $containsAll) {

                            array_push($argSets, $nextAdmiSet);
                            break;
                        }
                    }
                }

                $argsToSubsumedAdmiSetSets += array($nextArg => $argSets);

                /* check that returnedDefSets includes only admissible sets, assuming that
                admissibleSetsContain(Collection<String>... ) is correct. */
                $argSets = array();

                foreach ($returnedDefSets as $nextSet) {
                    if(! $af->admissibleSetsContain(array($nextSet))) {
                        array_push($argSets, $nextSet);
                    }
                }

                $argsToInadmissibleReturnedSetSets += array($nextArg => $argSets);

                /* check that returnedDefSets includes all defence-sets for nextArg, assuming that getAdmissibleSets() is 
                correct. */
                $argSets = array();

                //var_dump($af->getAdmissibleSets());
                $admissible = $af->getAdmissibleSets();

                if ($admissible != array(array())) {

                    foreach ($admissible as $nextAdmiSet) {
                        if (in_array($nextArg, $nextAdmiSet, true)) {
                            $resultsOK = false;
                            foreach ($returnedDefSets as $nextReturnedSet) {

                                //nextAdmiSet.containsAll(nextReturnedSet)
                                $containsAll = ! array_diff($nextReturnedSet, $nextAdmiSet);

                                if ($containsAll) {
                                    $resultsOK = true;
                                    break;
                                }
                            }

                            if (! $resultsOK) {
                                array_push($argSets, $nextAdmiSet);
                            }
                        }
                    }
                }

                $argSets = $af->removeNonMinimalMembersOf($argSets);
                $argsToMissingDefSetSets += array($nextArg => $argSets);
            }

            foreach ($af->getArgs() as $nextArg) {
                if (! isset($argsToSubsumedAdmiSetSets[$nextArg])) {
                    $resultsOK = false;
                }

                if (! isset($argsToInadmissibleReturnedSetSets[$nextArg])) {
                    $resultsOK = false;
                }

                if (! isset($argsToMissingDefSetSets[$nextArg])) {
                    $resultsOK = false;
                }
            }

            $this->assertTrue($resultsOK);
        }
    }

    public function testSetComparison() {

        $setColl = array();
        $setStr0 = array("a");
        $setStr1 = array("a", "b");
        $setStr2 = array("c");

        $setSetStr = array();
        $listSetStr = array();

        //-------------------------------------

        // testName = "set of sets - comparable members";
        array_push($setSetStr, $setStr0);
        array_push($setSetStr, $setStr1);
        $setSetStr = $this->af->removeNonMinimalMembersOf($setSetStr);
        $this->assertTrue(sizeof(array_diff($setSetStr[0], $setStr0)) == 0);
        
        array_push($setSetStr, $setStr1);
        $setSetStr = $this->af->removeNonMaximalMembersOf($setSetStr);
        $this->assertTrue(sizeof(array_diff($setSetStr[0], $setStr1)) == 0);

        // testName = "set of sets - incomparable members";
        $setSetStr = array();
        array_push($setSetStr, $setStr0);
        array_push($setSetStr, $setStr2);
        $setSetStr = $this->af->removeNonMinimalMembersOf($setSetStr);

        $expected = array();
        array_push($expected, $setStr0);
        array_push($expected, $setStr2);

        $equal = true;
        for ($i = 0; $i < sizeof($setSetStr); $i++) {
            if (sizeof(array_diff($setSetStr[$i], $expected[$i])) > 0) {
                $equal = false;
                break;
            }
        }

        $this->assertTrue($equal);

        $setSetStr = $this->af->removeNonMaximalMembersOf($setSetStr);
        $this->assertTrue($setSetStr == array($setStr0, $setStr2));

        // testName = "set of sets - the empty set";
        $setSetStr = array(array());
        $setSetStr = $this->af->removeNonMinimalMembersOf($setSetStr);
        $this->assertTrue($setSetStr == array(array()));

        $setSetStr = $this->af->removeNonMaximalMembersOf($setSetStr);
        $this->assertTrue($setSetStr == array(array()));

        // testName = "a singleton set";
        $setSetStr = array($setStr0);
        $setSetStr = $this->af->removeNonMinimalMembersOf($setSetStr);
        $this->assertTrue($setSetStr == array($setStr0));

        $setSetStr = $this->af->removeNonMaximalMembersOf($setSetStr);
        $this->assertTrue($setSetStr == array($setStr0));

        // testName = "two sets are the same and are comparable with the third";
        $listSetStr = array();
        array_push($listSetStr, $setStr0);
        array_push($listSetStr, $setStr1);
        array_push($listSetStr, $setStr1);

        $listSetStr = $this->af->removeNonMinimalMembersOf($listSetStr);
        $this->assertTrue($listSetStr == array($setStr0));

        $listSetStr = array();
        array_push($listSetStr, $setStr0);
        array_push($listSetStr, $setStr1);
        array_push($listSetStr, $setStr1);

        $listSetStr = $this->af->removeNonMaximalMembersOf($listSetStr);
        $this->assertTrue($listSetStr == array($setStr1, $setStr1));

        // testName = "no two sets are the same, two sets are comparable with one another";
        $listSetStr = array();
        array_push($listSetStr, $setStr0);
        array_push($listSetStr, $setStr1);
        array_push($listSetStr, $setStr2);
        $listSetStr = $this->af->removeNonMinimalMembersOf($listSetStr);
        $this->assertTrue($listSetStr == array($setStr0, $setStr2));

        $listSetStr = array();
        array_push($listSetStr, $setStr0);
        array_push($listSetStr, $setStr1);
        array_push($listSetStr, $setStr2);

        $listSetStr = $this->af->removeNonMaximalMembersOf($listSetStr);
        $this->assertTrue($listSetStr == array($setStr1, $setStr2));
    }

    /**
    * Test to multiply two numbers
    */
    public function testMultiply () {}

    /**
    * Test to divide two numbers
    */
    public function testDivide () {}
}
?>