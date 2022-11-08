<?php
namespace DocArchive\Controller\Site;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    protected $defaultSearch = false;
    protected $defaultSort = 'dcterms:title';
    protected $searchFolder = false;
    protected $targetProperty = '';
    protected $viewManager ;
    protected $defaultSearchFolder;
    protected $parentSort;
    public function __construct($defaultSearch, $defaultSort, $searchFolder, $targetProperty, $viewManager) {
        $this->defaultSearch = $defaultSearch;
        $this->defaultSort = $defaultSort;
        $this->searchFolder = $searchFolder;
        $this->defaultSearchFolder = $searchFolder;
        $this->targetProperty = $targetProperty;
        $this->viewManager = $viewManager;
//         $this->parentSort = $parentSort;
    }
    public function browseAction()
    {
        $site = $this->currentSite();
        $query = $this->params()->fromQuery();
//         if (isset($query['targetItem'])) {
//             var_dump($query['targetItem']);
//             exit;
//         }
        $searching = true;
        if (count($query) == 0) {
            $searching = false;
        }
        $params = $this->setSearchParameters($query, $site);
        $params += $this->setSortParameters($query);
        $group = false;
        $view = $this->getView();
        $view->setVariable('searching', $searching);
//         $response = $this->api()->search('search_items', $params);
        if ($searching || $this->defaultSearch) {
            if (isset($query['target']) && strcmp($query['target'], 'group') == 0) {
                if (!$this->identity()) {
                    $response = $this->api()->search('search_group_public_items', $params);
                } else {

                    $response = $this->api()->search('search_group_items', $params);
                }

                $group = true;
            } else {
                if (!$this->identity()) {
                    $response = $this->api()->search('search_public_items', $params);
                } else {
                    $response = $this->api()->search('search_items', $params);
                }

            }
            $this->paginator($response->getTotalResults(), $params['page'], $params['per_page']);
            $items = $response->getContent();
            $view->setVariable('items', $items);
            $view->setVariable('itemCount', $response->getTotalResults());
        }
//         if (isset($query['show_parent'])) {
//             $items = $this->getParentFolder($items);
//         }
        $view->setVariable('searchFolder', $this->searchFolder);
        $view->setVariable('defaultSearch', $this->defaultSearch);
//         $view->setVariable('parentSort', $this->parentSort);
        $view->setVariable('sortBy', $params['sort_by']);
        $view->setVariable('site', $site);
        $view->setVariable('page', $params['page']);
        $view->setVariable('group', $group);
        return $view;
    }
    public function showAction()
    {
        $site = $this->currentSite();
        $query = $this->params()->fromQuery();
        $group = false;
        $view = $this->getView();
        if (empty($this->targetProperty)) {
            $response = $this->api()->read('items', ['id' => $this->params('id')]);
            if ($response) {
                $item = $response->getContent();
            } else {
                $this->redirect($this->url('site/docarchive/', [], [], true));
            }
        } else {
            if($this->params('identifier')) {
                $identifier = $this->params('identifier');
            } else {
                $identifier = $this->params('id');
            }
            $resourceView = $this->viewManager->get('getResourceFromIdentifier');
            $item = $resourceView($identifier, false, 'items');
        }
        $params = ['parent_item_id' => $item->id()];
        $params['is_here'] = true;
        $params += $this->setSortParameters($query);
//         $params['sort_by'] = 'dcterms:date';
        $response = $this->api()->search('search_items', $params);
        $this->paginator($response->getTotalResults(), $params['page'], $params['per_page']);
        $items = $response->getContent();

        $view->setVariable('searchFolder', $this->searchFolder);
        $view->setVariable('sortBy', $params['sort_by']);
//         $view->setVariable('parentSort', $this->parentSort);
        $view->setVariable('itemCount', $response->getTotalResults());
        $view->setVariable('site', $site);
        $view->setVariable('item', $item);
        $view->setVariable('items', $items);
        $view->setVariable('page', $params['page']);
        $view->setVariable('group', $group);
        return $view;
    }
    public function searchChildrenAction()
    {
        $site = $this->currentSite();
        $view = $this->getView();
        if (empty($this->targetProperty)) {
            $response = $this->api()->read('items', ['id' => $this->params('id')]);
            if ($response) {
                $item = $response->getContent();
            } else {
                $this->redirect($this->url('site/docarchive/', [], [], true));
            }
        } else {
            if($this->params('identifier')) {
                $identifier = $this->params('identifier');
            } else {
                $identifier = $this->params('id');
            }
            $resourceView = $this->viewManager->get('getResourceFromIdentifier');
            $item = $resourceView($identifier, false, 'items');
        }
        $query = $this->params()->fromQuery();
        $this->searchFolder = true;
        $params = $this->setSearchParameters($query, $site);
        $params += $this->setSortParameters($query);
        // default search folder is false filter all folder and document
        if ($this->defaultSearchFolder) {
            $params['target_all'] = true;
        } else {
            $params['target_all'] = false;
        }
        $group = false;
        $params['parent_item_id'] = $item->id();
        // filtered by direct chilld
        $searching = true;
        if (!isset($query['search'])) {
//             $params['related_item_id'] = $item->id();
            $items = null;
            $searching = false;
        } else {
            if (!$this->identity()) {
                $response = $this->api()->search('search_child_public_items', $params);
            } else {
                $response = $this->api()->search('search_child_items', $params);
            }

            $this->paginator($response->getTotalResults(), $params['page'], $params['per_page']);
            $items = $response->getContent();
            $view->setVariable('itemCount', $response->getTotalResults());
        }
        $view->setVariable('searching', $searching);
        $view->setVariable('items', $items);
        $view->setVariable('searchFolder', $this->defaultSearchFolder);
//         $view->setVariable('parentSort', $this->parentSort);
        $view->setVariable('sortBy', $params['sort_by']);
        $view->setVariable('site', $site);
        $view->setVariable('item', $item);
        $view->setVariable('page', $params['page']);
        $view->setVariable('group', $group);
        return $view;
    }
    protected function getView() {
        $action= $this->getEvent()->getRouteMatch()->getParam('action');
        $controller= $this->getEvent()->getRouteMatch()->getParam('controller');
        return new ViewModel(array('action' =>$action,'controller' =>$controller));
    }
    protected function setSearchParameters($query, $site)
    {
        $params = array();
        $index = 0;
        foreach($query as $key => $param) {
            if ($param == null || empty($param)) {
                continue;
            }
            if (strcmp(urldecode($key), 'targetItem') == 0) {
                $params['targetItem'] = $param;
                continue;
            }
            if (strcmp(urldecode($key), 'media') == 0) {
                $params['media'] = true;
                continue;
            }
            if (strcmp(urldecode($key), 'target') == 0) {
                if (strcmp($param, 'all') == 0) {
                    $params['target_all'] = $param;
                    continue;
                }
            }
            $property = null;
            if (strcmp(urlencode($key), 'search') != 0) {
                $term = explode(':', urldecode($key));
                if (count($term) == 1) {
                    continue;
                }
                $response = $this->api()->searchOne('properties',
                    ['vocabulary_prefix' => $term[0],
                        'local_name' => $term[1]]);
                $property = $response->getContent()->id();
            }
            if (!is_array($param)) {
                $value = str_replace('　', ' ', $param);
                $singleValues = explode(' ', $value);
                foreach ($singleValues as $singleValue) {
                    $params['property'][$index] = [
                        'joiner' => 'and',
                        'type' => 'in',
                        'property' => $property,
                        'text' => $singleValue,
                    ];
                    $index++;
                }
            } else {
                $params['property'][$index] = [
                    'joiner' => 'and',
                    'type' => 'eq',
                    'property' => $property,
                    'text' => null,
                    'array' => $param
                ];
                $index++;
            }
        }
        $params['site_id'] = $site->id();
        if ($this->siteSettings()->get('browse_attached_items', false)) {
            $params['site_attachments_only'] = true;
        }
        if (!$this->identity()) {
            $params['is_public'] = 1;
        }
//         $params['search_folder'] = $this->searchFolder;
        return $params;
    }
    protected function setSortParameters($query, $parent = true) {
        if (isset($query['sort_by']) && !empty($query['sort_by'])) {
            $params['sort_by'] = urldecode($query['sort_by']);
        } else {
            if ($this->defaultSearchFolder && $parent) {
                $params['sort_by'] = 'parent';
            } else {
                $params['sort_by'] = $this->defaultSort;
            }
        }
        if (isset($query['sort_order']) && !empty($query['sort_order'])) {
            $params['sort_order'] = $query['sort_order'];
        } else {
            $params['sort_order'] = 'asc';
        }
        $params['per_page'] = 50;
        $params['limit'] = 50;
        if (isset($query['display_record']) && !empty($query['display_record'])) {
            $params['per_page'] = $query['display_record'];
            $params['limit'] = $query['display_record'];
        }
        $params['page'] = 1;
        if (isset($query['page']) && !empty($query['page'])) {
            $displayRecord = $query['page'];
            $params['page'] = $query['page'];
        }
        if(!isset($query['old_per_page'])) {
            $query['old_per_page'] = '10';
        }
        if (strcmp($query['old_per_page'], $params['per_page']) != 0) {
            $params['page'] = 1;
        }
        return $params;
    }
}

