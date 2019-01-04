<?php

namespace App\Contracts\CacheFrontend;

/**
 * Cache frontend interface
 */

interface CacheFrontendInterface
{
    public function exist($hash = null, $field = null);
    public function del($hash = null);
    public function store($hash = null, $field = null, $value = null);
    public function get($hash = null, $field = null);
    public function findKey($hash = null);
}