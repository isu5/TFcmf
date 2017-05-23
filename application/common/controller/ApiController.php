<?php

namespace app\common\controller;

use app\common\api\ConfigApi;
use think\Controller;
use think\exception\HttpResponseException;
use think\Request;
use think\Response;

class ApiController extends Controller
{

    public function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub

        //注册配置项
        ConfigApi::config();
    }

    /**
     * 默认操作失败，只有一个参数时为操作成功
     * @author yc <cotyxpp@gmail.com>
     * @param null   $code
     * @param null   $msg
     * @param null   $url
     * @param string $data
     * @param int    $wait
     * @param array  $header
     */
    protected final function outInfo($code = null, $msg = null, $data = [])
    {
        //默认操作成功
        if (is_null($code) && is_null($msg)) {
            $code = 0;
            $msg  = '操作成功';
        } else if ($code && is_null($msg)) {
            $msg  = $code;
            $code = 0;
        }
        //结果数组
        $result = [
            'code' => $code,
            'msg'  => $msg,
        ];

        if ($data) {
            $result['data'] = $data;
        }

        //将返回结果统一为字符串，方便客户端操作
        array_walk_recursive($result, 'json_format');
        $response = Response::create($result, 'json');
        throw new HttpResponseException($response);
    }

    //快速返回数据
    protected final function outData($data = [])
    {
        //默认操作成功
        $code   = 0;
        $msg    = '操作成功';
        $result = [
            'code' => 0,
            'msg'  => '操作成功'
        ];
        //结果数组
        if ($data) {
            $result['data'] = $data;
        }
        //将返回结果统一为字符串，方便客户端操作
        array_walk_recursive($result, 'json_format');
        $response = Response::create($result, 'json');
        throw new HttpResponseException($response);
    }


    /**
     * 空方法，404页面
     */
    public function _empty()
    {
        return $this->outInfo(404, '请求的路径不存在');
    }
}
