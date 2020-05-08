<?php
function add_roles_on_plugin_activation() {
    add_role( 'silver', 'Silver', array( 'read' => true, 'level_0' => true ) );
    add_role( 'gold', 'Gold', array( 'read' => true, 'level_0' => true ) );
    add_role( 'platinum', 'Platinum', array( 'read' => true, 'level_0' => true ) );
}
register_activation_hook( plugin_dir_path(dirname(__FILE__)) . 'bbb-rooms-iom.php', 'add_roles_on_plugin_activation' );

function remove_roles_on_plugin_deactivation() {
    remove_role( 'silver');
    remove_role( 'gold');
    remove_role( 'platinum');
}
register_deactivation_hook( plugin_dir_path(dirname(__FILE__)) . 'bbb-rooms-iom.php', 'remove_roles_on_plugin_deactivation' );