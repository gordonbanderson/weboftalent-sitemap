<?php
namespace WebOfTalent\PageSiteMap;

use SilverStripe\ErrorPage\ErrorPage;
use SilverStripe\Core\Convert;
use SilverStripe\ORM\DataObject;


class SiteMapController extends \PageController
{
    /**
    * This function will return a unordered list of all pages on the site.
    * Watch for the switch between $page and $child in the second line of the
    * foreach().
    *
    * Note that this will only skip ErrorPage's at the top/root level of the site.
    * If you have an ErrorPage class somewhere else in the hierarchy, it will be
    * displayed.
    */
    public function SiteMap()
    {
        // Pages at the root level only
        $rootLevel = \Page::get()->filter('ParentID', 0);
        $output = '';
        $output = $this->makeList($rootLevel);
        return $output;
    }

    private function makeList($pages)
    {
        $output = '';
        if (count($pages)) {
            $output = '
			<ul>';
            foreach ($pages as $page) {
                if (!($page instanceof ErrorPage) && $page->ShowInMenus && $page->Title != $this->Title) {
                    $output .= '
					<li><a href="'.$page->URLSegment.'" title="Go to the '.Convert::raw2xml($page->Title).' page">'.Convert::raw2xml($page->MenuTitle).'</a>';
                    $whereStatement = 'ParentID = '.$page->ID;
                    $childPages = DataObject::get('Page', $whereStatement);
                    $output .= $this->makeList($childPages);
                    $output .= '</li>';
                }
            }
            $output .= '
			</ul>';
        }
        return $output;
    }
}
