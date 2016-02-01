<?php

namespace RcmRedirectEditor;

class Module
{
    /**
     * Returns the config array for this ZF2 module
     *
     * @return array Returns config array for this zf2 module
     */
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }
}
