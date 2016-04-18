<?php

/**
 * Created by PhpStorm.
 * User: vnimy_000
 * Date: 2016-04-15
 * Time: 14:18
 */
namespace plugins\VnimyExtend\Controller;
use Api\Controller\PluginController;

class AdminTplController extends PluginController{

    protected $tpl_model;

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
        $this->tpl_model = D("plugins://VnimyExtend/Tpl");
    }
    function index(){
        $tpls = $this->tpl_model->order(array("listorder"=>"asc"))->select();
        foreach($tpls as $k => $v){
            $tpls[$k]['file_exists'] = sp_template_file_exists(C('DEFAULT_MODULE').'/'.$v['tpl_path']);
        }
        $this->assign("tpls", $tpls);
        $this->display();
    }


    function add(){
        $this->display();
    }

    function add_post(){
        if (IS_POST) {
            if ($this->tpl_model->create()) {
                if ($this->tpl_model->add()!==false) {
                    F('all_terms',null);
                    $this->success("添加成功！",sp_plugin_url('VnimyExtend://AdminIndex/index'));
                } else {
                    $this->error("添加失败！");
                }
            } else {
                $this->error($this->tpl_model->getError());
            }
        }
    }

    function edit(){
        $id = intval(I("get.tpl_id"));
        $tpl=$this->tpl_model->where(array("tpl_id" => $id))->find();
        $this->assign($tpl);
        $this->display();
    }

    function edit_post(){
        if (IS_POST) {
            if ($this->tpl_model->create()) {
                if ($this->tpl_model->save()!==false) {
                    F('all_terms',null);
                    $this->success("修改成功！");
                } else {
                    $this->error("修改失败！");
                }
            } else {
                $this->error($this->tpl_model->getError());
            }
        }
    }

    //排序
    public function listorders() {
        $status = parent::_listorders($this->tpl_model);
        if ($status) {
            $this->success("排序更新成功！");
        } else {
            $this->error("排序更新失败！");
        }
    }

    /**
     *  删除
     */
    public function delete() {
        $id = intval(I("get.id"));

        if ($this->tpl_model->delete($id)!==false) {
            $this->success("删除成功！");
        } else {
            $this->error("删除失败！");
        }
    }
}