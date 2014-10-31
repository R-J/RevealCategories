<?php defined('APPLICATION') or die;

$PluginInfo['RevealCategories'] = array(
    'Name' => 'Reveal Categories',
    'Description' => '"Hide from the recent discussions page" setting for categories also hides categories at other pages, too. This plugin restricts hiding to Recent Discussions and reveals categories elsewhere',
    'Version' => '0.1',
    'MobileFriendly' => true,
    'RequiredApplications' => array('Vanilla' => '2.1'),
    'HasLocale' => false,
    'License' => 'MIT',
    'Author' => 'Robin Jurinka',
    'AuthorUrl' => 'http://vanillaforums.org/profile/44046/R_J'
);
    
class RevealCategoriesPlugin extends Gdn_Plugin {
    public function base_categoryWatch_handler ($Sender) {
        $Dispatcher = Gdn::Dispatcher();
        if (strtolower($Dispatcher->ControllerName) == 'discussions' && strtolower($Dispatcher->ControllerMethod) == 'index') {
            // for /discussions/recent nothing must be done
            return;        
        }
        $Sender->EventArguments['CategoryIDs'] = $this->_newCategoryWatch();
    }
    
    // original CategoryWatch without HideAllDiscussions restriction
    private function _newCategoryWatch() {
        $CategoryModel = new CategoryModel();
        $Categories = $CategoryModel::Categories();
        $AllCount = count($Categories);
      
        $Watch = array();

        foreach ($Categories as $CategoryID => $Category) {
            if ($Category['PermsDiscussionsView'] && $Category['Following']) {
                $Watch[] = $CategoryID;
            }
        }
        
        if ($AllCount == count($Watch)) {
            return true;
        }
        
        return $Watch;
    }
}
