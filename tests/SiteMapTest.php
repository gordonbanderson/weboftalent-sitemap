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
		}
		$response = $this->get('/sitemap/');
        $this->assertEquals(200, $response->getStatusCode());
        $positions = array();
        $body = $response->getBody();
        for ($i=1; $i <= 4; $i++) {
        	$row = '<li class="link"><a href="/page-' . $i . '/" title="Page '
        	. $i . '">Page ' . $i . '</a></li>';
        	$this->assertContains($row, $body);

        	$positions["{$i}"] = strpos($body, $row);
        }

        //assert order
        $this->assertGreaterThan($positions['3'], $positions['4']);
        $this->assertGreaterThan($positions['2'], $positions['3']);
        $this->assertGreaterThan($positions['1'], $positions['2']);
    }
}
