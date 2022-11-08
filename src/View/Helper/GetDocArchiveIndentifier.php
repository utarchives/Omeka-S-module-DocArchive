<?php
namespace DocArchive\View\Helper;

use Laminas\View\Helper\AbstractHelper;
use Interop\Container\ContainerInterface;

class GetDocArchiveIndentifier extends AbstractHelper
{
    protected $targetProperty;
    public function __construct($targetProperty)
    {
        $this->targetProperty = $targetProperty;
    }
    public function __invoke()
    {
        return $this;
    }

    public function getCleanUrl() {
        if (empty($this->targetProperty)){
            return false;
        }
        return true;
    }
    public function getIdentifier($item) {
        if (isset($item->values()[$this->targetProperty])) {
            $view = $this->getView();
            $result =  $view->getResourceIdentifier($item);
            if (empty(strval($result))) {
                return $item->id();
            }
            return $result;
        }
        return $item->id();
    }
}

