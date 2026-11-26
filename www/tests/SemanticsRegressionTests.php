<?php

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../semantics/dungAF.php';

class SemanticsRegressionTests extends TestCase
{
    public static function frameworks(): array
    {
        return [
            'empty' => [[], [], [], [[]], [[]], [[]], [], [], []],
            'isolated' => [[], ['a', 'b'], ['a', 'b'], [['a', 'b']], [['a', 'b']], [[], ['a'], ['b'], ['a', 'b']], ['a', 'b'], ['a', 'b'], ['a', 'b']],
            'chain' => [[['a'=>'b'], ['b'=>'c']], [], ['a', 'c'], [['a', 'c']], [['a', 'c']], [[], ['a'], ['a', 'c']], ['a', 'c'], ['a', 'c'], ['a']],
            'mutual attack' => [[['a'=>'b'], ['b'=>'a']], [], [], [['a'], ['b']], [['a'], ['b']], [[], ['a'], ['b']], [], [], []],
            'self attack' => [[['a'=>'a']], [], [], [[]], [], [[]], [], [], []],
            'odd cycle' => [[['a'=>'b'], ['b'=>'c'], ['c'=>'a']], [], [], [[]], [], [[]], [], [], []],
            'even cycle' => [[['a'=>'b'], ['b'=>'c'], ['c'=>'d'], ['d'=>'a']], [], [], [['a','c'], ['b','d']], [['a','c'], ['b','d']], [[], ['a','c'], ['b','d']], [], [], []],
            'disconnected' => [[['a'=>'b'], ['c'=>'d'], ['d'=>'c']], ['e'], ['a','e'], [['a','c','e'], ['a','d','e']], [['a','c','e'], ['a','d','e']], [[], ['a'], ['c'], ['d'], ['e'], ['a','c'], ['a','d'], ['a','e'], ['c','e'], ['d','e'], ['a','c','e'], ['a','d','e']], ['a','e'], ['a','e'], ['a','e']],
        ];
    }

    private static function makeAF(array $attacks, array $isolated): DungAF
    {
        return DungAF::PopulatedDungAF($attacks, $isolated ?: '');
    }

    private function assertExtension(array $expected, $actual): void
    {
        self::assertIsArray($actual);
        self::assertEqualsCanonicalizing($expected, array_values($actual));
    }

    private function assertExtensions(array $expected, $actual): void
    {
        self::assertIsArray($actual);
        $normalize = static function (array $sets): array {
            $result = [];
            foreach ($sets as $set) {
                $set = array_values($set);
                sort($set, SORT_STRING);
                $result[] = $set;
            }
            return $result;
        };
        self::assertEqualsCanonicalizing($normalize($expected), $normalize($actual));
    }

    #[DataProvider('frameworks')]
    public function testGrounded(array $attacks, array $isolated, array $grounded, array ...$unused): void
    {
        $this->assertExtension($grounded, self::makeAF($attacks, $isolated)->getGroundedExt());
    }

    #[DataProvider('frameworks')]
    public function testPreferred(array $attacks, array $isolated, array $grounded, array $preferred, array ...$unused): void
    {
        $this->assertExtensions($preferred, self::makeAF($attacks, $isolated)->getPreferredExts());
    }

    #[DataProvider('frameworks')]
    public function testStable(array $attacks, array $isolated, array $grounded, array $preferred, array $stable, array ...$unused): void
    {
        $this->assertExtensions($stable, self::makeAF($attacks, $isolated)->getStableExts());
    }

    #[DataProvider('frameworks')]
    public function testAdmissible(array $attacks, array $isolated, array $grounded, array $preferred, array $stable, array $admissible, array ...$unused): void
    {
        $this->assertExtensions($admissible, self::makeAF($attacks, $isolated)->getAdmissibleSets());
    }

    #[DataProvider('frameworks')]
    public function testSemiStable(array $attacks, array $isolated, array $grounded, array $preferred, array $stable, array ...$unused): void
    {
        // In these fixtures, semi-stable equals stable when stable extensions exist.
        $this->assertExtensions($stable ?: [[]], self::makeAF($attacks, $isolated)->getSemiStableExts());
    }

    #[DataProvider('frameworks')]
    public function testIdeal(array $attacks, array $isolated, array $grounded, array $preferred, array $stable, array $admissible, array $ideal, array ...$unused): void
    {
        $this->assertExtension($ideal, self::makeAF($attacks, $isolated)->getIdealExt());
    }

    #[DataProvider('frameworks')]
    public function testEager(array $attacks, array $isolated, array $grounded, array $preferred, array $stable, array $admissible, array $ideal, array $eager, array ...$unused): void
    {
        $this->assertExtension($eager, self::makeAF($attacks, $isolated)->getEagerExt());
    }

    #[DataProvider('frameworks')]
    public function testExpertSystem(array $attacks, array $isolated, array $grounded, array $preferred, array $stable, array $admissible, array $ideal, array $eager, array $expert): void
    {
        $this->assertExtension($expert, self::makeAF($attacks, $isolated)->getExpertSystem());
    }

    public static function categoriserCases(): array
    {
        return [
            'empty' => [[], [], []],
            'isolated' => [[], ['a','b'], ['a'=>1.0, 'b'=>1.0]],
            'chain' => [[['a'=>'b'], ['b'=>'c']], [], ['a'=>1.0, 'c'=>2/3, 'b'=>0.5]],
            'multiple attackers' => [[['a'=>'c'], ['b'=>'c']], [], ['a'=>1.0, 'b'=>1.0, 'c'=>1/3]],
            'mutual attack' => [[['a'=>'b'], ['b'=>'a']], [], ['a'=>0.618033989, 'b'=>0.618033989]],
            'self attack' => [[['a'=>'a']], [], ['a'=>0.618033989]],
        ];
    }

    #[DataProvider('categoriserCases')]
    public function testCategoriser(array $attacks, array $isolated, array $expected): void
    {
        $actual = self::makeAF($attacks, $isolated)->getCategoriser();
        self::assertIsArray($actual);
        self::assertCount(1, $actual);
        self::assertIsArray($actual[0]);
        $scores = $actual[0];
        self::assertEqualsCanonicalizing(array_keys($expected), array_keys($scores));
        foreach ($expected as $argument => $score) {
            self::assertEqualsWithDelta($score, $scores[$argument], 1e-8, $argument);
        }
    }
}
