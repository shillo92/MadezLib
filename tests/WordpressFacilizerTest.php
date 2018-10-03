<?php
declare(strict_types=1);

namespace Madez\Tests;

use Madez\WordpressFacilizer;

class WordpressFacilizerTest extends \WP_UnitTestCase
{
    public function testShouldFindJsFile(): void
    {
        $rel_filename = '/js/build.min.js';
        $url = WordpressFacilizer::resolveScriptUri($rel_filename);

        $data = file_get_contents($url, false, null, 0, 1);

        $this->assertNotFalse($data);
    }
}