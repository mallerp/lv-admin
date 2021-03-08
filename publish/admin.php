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
use HPlus\Admin\Middleware\AuthMiddleware;
use HPlus\Admin\Model\Admin\Administrator;
use HPlus\Admin\Model\Admin\Menu;
use HPlus\Admin\Model\Admin\Permission;
use HPlus\Admin\Model\Admin\Role;

return [
    //后台名称 null不显示
    'name' => 'HPlus',
    //后台标题
    'title' => 'HPlus Admin',
    //登录界面描述
    'loginDesc' => 'HPlus Admin 是开箱即用的 Hyperf 后台扩展',
    //logo 地址 null为内置默认 分为黑暗和明亮两种
    'logo_show' => true,
    'logo' => null,
    'logo_mini' => null,
    'logo_light' => null,
    'logo_mini_light' => null,
    //版权
    'copyright' => 'Copyright © 2020 HPlus',
    //默认头像
    'default_avatar' => 'https://gw.alipayobjects.com/zos/antfincdn/XAosXuNZyF/BiazfanxmamNRoxxVxka.png',
    //登录页面背景
    'login_background_image' => 'https://gw.alipayobjects.com/zos/rmsportal/TVYTbAXWheQpRcWDaDMu.svg',
    //登录框默认用户
    'auto_user' => [
        'username' => 'admin',
        'password' => 'admin',
    ],
    //底部菜单
    'footerLinks' => [
        [
            'href' => 'https://github.com/hyperf-plus/admin',
            'title' => 'hyperf版官网',
        ],
        [
            'href' => 'https://www.yuque.com/hyperf-plus/ui/hplus-ui',
            'title' => 'UI文档',
        ],
    ],
    //是否只保持一个子菜单的展开
    'unique_opened' => false,
    'bootstrap' => '', //app_path('Admin/bootstrap.php'),
    'route' => [
        'domain' => null,
        'prefix' => env('ADMIN_ROUTE_PREFIX', 'admin'),       # 默认后台注册路由，可自定义路径，防止后台地址被扫，修改此项后admin_menu中的链接也需要对应修改
        'api_prefix' => env('ADMIN_ROUTE_API_PREFIX', '/'),  # 默认API地址
        'home' => env('ADMIN_ROUTE_HOME_URL', '/auth/main'),              # 默认后台首页
        'namespace' => 'App\\Admin\\Controllers',
        'middleware' => [AuthMiddleware::class, HPlus\Permission\Middleware\PermissionMiddleware::class], #默认权限处理器
    ],
    'directory' => '',
    'https' => env('ADMIN_HTTPS', false),
    'auth' => [
        'guard' => 'jwt', // 对应auth里面的guard
        'cookie_name' => 'HPLUSSESSIONID', // 对应auth里面的guard
        // Add "remember me" to login form
        'remember' => true,
        // Redirect to the specified URI when user is not authorized.
        'redirect_to' => '/auth/login',
        'login_api' => '/auth/login',
        // The URIs that should be excluded from authorization.
        'excepts' => [
            'auth/login',
            'auth/logout',
            '_handle_action_',
        ],
    ],
    'upload' => [
        // Disk in `config/filesystem.php`.
        'disk' => 'local',
        'host' => '',
        'save_path' => '/upload',
        'uniqueName' => false,
        // Image and file upload path under the disk above.
        'directory' => [
            'image' => 'images',
            'file' => 'files',
        ],
        'image_size' => 1024 * 1024 * 5,
        'file_size' => 1024 * 1024 * 5,
        //文件上传类型
        'file_mimes' => 'txt,sql,zip,rar,ppt,word,xls,xlsx,doc,docx',
        //文件上传类型
        'image_mimes' => 'jpeg,bmp,png,gif,jpg',
    ],
    'database' => [
        // Database connection for following tables.
        'connection' => '',
        // User tables and model.
        'users_table' => 'admin_users',
        'users_model' => Administrator::class,
        // Role table and model.
        'roles_table' => 'admin_roles',
        'roles_model' => Role::class,
        // Permission table and model.
        'permissions_table' => 'admin_permissions',
        'permissions_model' => Permission::class,
        // Menu table and model.
        'menu_table' => 'admin_menu',
        'menu_model' => Menu::class,
        // Pivot table for table above.
        'operation_log_table' => 'admin_operation_log',
        'user_permissions_table' => 'admin_user_permissions',
        'role_users_table' => 'admin_role_users',
        'role_permissions_table' => 'admin_role_permissions',
        'role_menu_table' => 'admin_role_menu',
    ],
    //操作日志
    'operation_log' => [
        'enable' => true,  #开启或关闭日志记录功能
        /*
         * Only logging allowed methods in the list
         */
        'allowed_methods' => ['GET', 'HEAD', 'POST', 'PUT', 'DELETE', 'CONNECT', 'OPTIONS', 'TRACE', 'PATCH'],
        /*
         * Routes that will not log to database.
         *
         * All method to path like: admin/auth/logs
         * or specific method to path like: get:admin/auth/logs.
         */
        'except' => [
            '/admin/logs*',
        ],
    ],
    'check_route_permission' => true,
    'check_menu_roles' => true,
    'map_provider' => 'google',
    'show_version' => true,
    'show_environment' => true,
    'menu_bind_permission' => true,
    'which-composer' => 'composer',
];
