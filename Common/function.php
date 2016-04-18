<?php
/**
 * Created by PhpStorm.
 * User: vnimy_000
 * Date: 2016-04-15
 * Time: 14:55
 */

function ve_install_menus($menus=null){
    $menu_model = M('Menu');
    foreach($menus as $menu){
        echo $menu['data'];
        echo '<br>';
        if(count($menu['submenu'])){
            ve_install_menus($menu['submenu']);
        }
    }
}