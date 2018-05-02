<?php

use SilverStripe\Dev\FunctionalTest;

class SiteMapTest extends FunctionalTest
{
    protected static $fixture_file = 'SiteMapTest.yml';

    public function testSiteMap()
    {
        $this->logInWithPermission('ADMIN');
        // pages need published, fixtures are not
        foreach (Page::get() as $page) {
            $page->doPublish();
        }
        $response = $this->get('/sitemap/');
        $this->assertEquals(200, $response->getStatusCode());
        $positions = array();
        $body = $response->getBody();

        // assert is a sitemap
        $count = substr_count($body, '<ul class="sitemap-list">');
        $this->assertEquals(2, $count);

        // assert root level pages
        for ($i=1; $i <= 4; $i++) {
            $row = '<li><a href="page-' . $i . '" title="Go to the Page ' . $i . ' page">Page ' . $i . '</a>';
            $this->assertContains($row, $body);
            $positions["{$i}"] = strpos($body, $row);
        }

        //assert order
        $this->assertGreaterThan($positions['3'], $positions['4']);
        $this->assertGreaterThan($positions['2'], $positions['3']);
        $this->assertGreaterThan($positions['1'], $positions['2']);

        // check sub pages
        $sub1 = '<li><a href="page-11" title="Go to the Page 1/1 page">Page 1/1</a>';
        $sub2 = '<li><a href="page-12" title="Go to the Page 1/2 page">Page 1/2</a>';
        $sub1pos = strpos($body, $sub1);
        $sub2pos = strpos($body, $sub2);
        $this->assertContains($sub1, $body);
        $this->assertContains($sub2, $body);
        $this->assertGreaterThan($positions[1], $sub1pos);
        $this->assertGreaterThan($sub1pos, $sub2pos);
    }
}
