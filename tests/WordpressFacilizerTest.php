<?php
declare(strict_types=1);

namespace Madez\Tests;

use Madez\ChildThemeSetup;
use Madez\WordpressFacilizer;

class WordpressFacilizerTest extends \WP_UnitTestCase
{
    /**
     * @var WordpressFacilizer
     */
    protected $facilizer;

    function setUp()
    {
        parent::setUp();

        $this->facilizer = ChildThemeSetup::getInstance()->getFacilizer();
    }

    public function testShouldFindJsFile(): void
    {
        $rel_filename = 'js/build.min.js';
        $url = $this->facilizer->resolveScriptUri($rel_filename);

        $data = file_get_contents($url, false, null, 0, 1);

        $this->assertNotFalse($data);
    }
}