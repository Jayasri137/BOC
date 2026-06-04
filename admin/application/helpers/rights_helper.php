<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('hasRight')) {
    /**
     * hasRight
     * @param array $rightsArray - array returned by Master_model->getUserAuthentication()
     * @param int $sessionNumber - menu_master.mm_session_number (e.g. 1015)
     * @param string $prop - property to check (ur_view, ur_edit, ur_add, ur_delete)
     * @return bool
     */
    function hasRight($rightsArray, $sessionNumber, $prop = 'ur_view') {
        if (!is_array($rightsArray)) return false;
        if (!isset($rightsArray[$sessionNumber])) return false;
        $obj = $rightsArray[$sessionNumber];
        if (!is_object($obj)) return false;
        return (isset($obj->{$prop}) && (int)$obj->{$prop} === 1);
    }
}
