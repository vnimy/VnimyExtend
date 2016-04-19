<?php

/**
 * Created by PhpStorm.
 * User: vnimy_000
 * Date: 2016-04-15
 * Time: 14:18
 */
namespace plugins\VnimyExtend\Controller;
use Api\Controller\PluginController;

class AdminIndexController extends PluginController{
    function _initialize(){
        $adminid=sp_get_current_admin_id();//获取后台管理员id，可判断是否登录
        if(!empty($adminid)){
            $this->assign("adminid",$adminid);
        }else{
            if(IS_AJAX){
                $this->error("您还没有登录！",U("admin/public/login"));
            }else{
                header("Location:".U("admin/public/login"));
                exit();
            }
        }
    }
    function index(){
        $this->display();
    }

    function media(){
        redirect('./plugins/VnimyExtend/view/assets/js/plugins/ckfinder/ckfinder.html');
    }
}