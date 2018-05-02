<?php
namespace WebOfTalent\PageSiteMap;

class SiteMap extends \Page
{
  // hide from menus and search
    private static $defaults = array(
    'ShowInMenus' => 0,
    'ShowInSearch' => 0,
    );
}
