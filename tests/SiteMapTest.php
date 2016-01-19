<?php

class SiteMapTest extends FunctionalTest
{
    protected static $fixture_file = 'SiteMapTest.yml';

    public function testSiteMap()
    {
        $this->logInWithPermission('ADMIN');
        // pages need published, fixtures are not
		foreach (Page::get() as $page) {
			$page->doPublish();
			error_log($page->ClassName . ':' . $page->Link());
		}
		$response = $this->get('/sitemap/');
        $this->assertEquals(200, $response->getStatusCode());
        $positions = array();
        $body = $response->getBody();

        // assert is a sitemap
       	$this->assertContains('<ul class="sitemap-list">', $body);

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
    }
}
