<?php
function render_admin_active_toggle($entity, $id, $isActive, $label)
{
    $entity = htmlspecialchars($entity, ENT_QUOTES, 'UTF-8');
    $id = intval($id);
    $label = htmlspecialchars($label, ENT_QUOTES, 'UTF-8');
    $state = $isActive ? 'Active' : 'Inactive';
    $action = $isActive ? 'Deactivate' : 'Activate';

    return '<div class="admin-active-toggle-wrap">'
        . '<label class="admin-active-switch">'
        . '<input type="checkbox" class="admin-active-toggle" data-entity="' . $entity . '" data-id="' . $id . '" aria-label="' . $action . ' ' . $label . '" aria-checked="' . ($isActive ? 'true' : 'false') . '"' . ($isActive ? ' checked' : '') . '>'
        . '<span class="admin-active-slider"></span>'
        . '</label>'
        . '<span class="admin-active-label">' . $state . '</span>'
        . '</div>';
}
