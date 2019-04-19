<?php

namespace App\Tests\Utils;

use App\Utils\Slugger;
use PHPUnit\Framework\TestCase;

/**
 * Class SluggerTest.
 */
class SluggerTest extends TestCase
{
    /**
     * @dataProvider getSlugs
     */
    public function testSluggify(string $string, string $slug)
    {
        $this->assertSame($slug, Slugger::sluggify($string));
    }
    public function getSlugs()
    {
        yield ['Lorem Ipsum', 'lorem-ipsum'];
        yield ['  Lorem Ipsum  ', 'lorem-ipsum'];
        yield [' lOrEm  iPsUm  ', 'lorem-ipsum'];
        yield ['!Lorem Ipsum!', '!lorem-ipsum!'];
        yield ['lorem-ipsum', 'lorem-ipsum'];
        yield ['<p>lorem-ipsum', 'lorem-ipsum'];
    }
}
