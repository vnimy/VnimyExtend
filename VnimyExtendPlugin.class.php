<?php

/**
 * Created by PhpStorm.
 * User: vnimy_000
 * Date: 2016-04-15
 * Time: 14:19
 */
namespace plugins\VnimyExtend;
use Common\Lib\Plugin;

class VnimyExtendPlugin extends Plugin{

    public $info = array(
        'name'=>'VnimyExtend',
        'title'=>'自建模板列表管理',
        'description'=>'系统本身不带模板管理，导致仅仅能获取Portal下一级的模板，这个插件能建立一个模板列表，并且可以修改模板的信息。',
        'status'=>1,
        'author'=>'Vnimy',
        'version'=>'0.9'
    );

    public $has_admin=1;//插件是否有后台管理界面

    public function install(){
        //读取SQL文件
        $sql = file_get_contents($this->plugin_path . 'Data/plugin_vnimy_extend.sql');
        $sql = str_replace("\r", "\n", $sql);
        $sql = explode(";\n", $sql);

        //替换表前缀
        $tablepre = C('DB_PREFIX');
        $default_tablepre = "cmf_";
        $sql = str_replace(" `{$default_tablepre}", " `{$tablepre}", $sql);

        $Model = M();

        foreach ($sql as $item) {
            $item = trim($item);
            if(empty($item)) continue;
            if(false === $Model->execute($item)){
                return false;
            }
        }

        // 创建插件菜单
        $json = file_get_contents($this->plugin_path . 'Data/menu.json');
        $menu = json_decode($json);
        $menu_ids = $this->ve_install_menus($menu);
        $this->ve_write_menu_ids($menu_ids, $this->plugin_path . 'Data/menuids.txt');

        return true;
    }

    public function uninstall(){
        $txt = file_get_contents($this->plugin_path . 'Data/menuids.txt');
        if(!empty($txt)){
            $ids = explode(',', $txt);
            $this->ve_uninstall_menus($ids);
        }
        return true;
    }

    // 创建插件菜单函数
    function ve_install_menus($menus=null,$parentid=0){
        $ids = array();
        foreach($menus as $menu){
            $data = array(
                'parentid' =>  $parentid,
                'app'       =>  'api',
                'model'     =>  'plugin',
                'action'    =>  'execute',
                'data'      =>  "_plugin={$menu->plugin}&_controller={$menu->controller}&_action={$menu->action}",
                'name'      =>  $menu->name,
                'type'      =>  1,
                'status'    =>  1,
                'listorder' =>  $menu->listorder ? $menu->listorder : 0,
            );
            $ids[] = $id = M('Menu')->add($data);
            if($id){
                $this->ve_install_menus($menu->submenu, $id);
            }
        }
        return implode(',', $ids);
    }

    // 删除插件菜单函数
    function ve_uninstall_menus($ids){
        $Menu = M('Menu');
        foreach($ids as $id){
            $subids = $Menu->where("parentid={$id}")->getField('id', true);
            $this->ve_uninstall_menus($subids);
            $Menu->delete($id);
        }
    }

    function ve_write_menu_ids($ids, $file){
        $menu_file = fopen($file, "w") or die("Unable to open file!");
        fwrite($menu_file, $ids);
        fclose($menu_file);
    }

}