<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.plus
 *
 * @link     https://www.hyperf.plus
 * @document https://doc.hyperf.plus
 * @contact  4213509@qq.com
 * @license  https://github.com/hyperf-plus/admin/blob/master/LICENSE
 */
namespace HPlus\Admin\Service;

use Hyperf\HttpServer\Router\DispatcherFactory;

class AuthService
{
    public function getSystemRouteOptions($isUrl = false)
    {
        $router = get_container(DispatcherFactory::class)->getRouter('http');
        $data = $router->getData();
        $options = [];
        $options['*'] = [
            'label' => '*',
            'pid' => 0,
            'id' => 0,
            'value' => '*',
        ];
        $ids = [];
        if ($isUrl) {
            unset($options['*']);
            $route_key = '#/';
            $options[$route_key] = [
                'label' => $route_key,
                'value' => $route_key,
                'pid' => 0,
                'id' => 0,
            ];
        }
        foreach ($data as $routes_data) {
            foreach ($routes_data as $http_method => $routes) {
                $route_list = [];
                if (isset($routes[0]['routeMap'])) {
                    foreach ($routes as $map) {
                        array_push($route_list, ...$map['routeMap']);
                    }
                } else {
                    $route_list = $routes;
                }
                foreach ($route_list as $route => $v) {
                    //p($route);
                    // 过滤掉脚手架页面配置方法
                    $callback = is_array($v) ? ($v[0]->callback) : $v->callback;
                    if (! is_array($callback)) {
                        if (is_callable($callback)) {
                            continue;
                        }
                        if (strpos($callback, '@') !== false) {
                            [$controller, $action] = explode('@', $callback);
                        } else {
                            continue;
                        }
                    } else {
                        [$controller, $action] = $callback;
                    }
                    $route = is_string($route) ? rtrim($route) : rtrim($v[0]->route);
//                    $prefix = trim(config('admin.route.api_prefix') . '/', '/') . '/';
//                    $route = str_replace($prefix, '', $route);
                    if ($isUrl) {
                        $route_key = $route;
                    } else {
                        $route_key = "{$http_method}::{$route}";
                    }
                    $pid = md5($controller);
                    if (in_array($pid, $ids)) {
                        $id = uniqid();
                    } else {
                        $ids[] = $pid;
                        if (! $isUrl) {
                            $arr = explode('/', $route);
                            array_pop($arr);
                            $arr[] = '*';
                            $route = implode('/', $arr);
                            $route_key = "ANY::{$route}";
                            $options[$route_key] = [
                                'label' => $route_key,
                                'value' => $route_key,
                                'pid' => 0,
                                'id' => $pid,
                            ];
                        }
                        continue;
                    }
                    $options[$route_key] = [
                        'value' => $route_key,
                        'label' => $route_key,
                        'pid' => $pid,
                        'id' => $id,
                    ];
                }
            }
        }
        return array_values($options);
    }

    public function getUserRoleIds($userId)
    {
        return '';
    }
}
