<?php

namespace MvaCrud\Options;

interface IndexControllerOptionsInterface
{
    /**
     * set page title param if present
     *
     * @param bool $IndexPageTitleIfPresent
     */
    public function setIndexPageTitleIfPresent($IndexPageTitleIfPresent);

    /**
     * get use redirect param if present
     *
     * @return bool
     */
    public function getIndexPageTitleIfPresent();
}
