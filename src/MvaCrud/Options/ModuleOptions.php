<?php

namespace MvaCrud\Options;

use Zend\Stdlib\AbstractOptions;

class ModuleOptions extends AbstractOptions
{
    /**
     * @var string
     */
    protected $indexPageTitle = '';

    /**
     * set login redirect route
     *
     * @param string $s_indexPageTitle
     * @return ModuleOptions
     */
    public function setIndexPageTitle($s_indexPageTitle)
    {
        $this->indexPageTitle = $s_indexPageTitle;
        return $this;
    }

    /**
     * get login redirect route
     *
     * @return string
     */
    public function getIndexPageTitle()
    {
        return $this->indexPageTitle;
    }

}
