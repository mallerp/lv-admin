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
namespace HPlus\Admin\Controller;

use HPlus\Admin\Model\Admin\OperationLog;
use HPlus\Route\Annotation\AdminController;
use HPlus\UI\Components\Attrs\SelectOption;
use HPlus\UI\Components\Form\Input;
use HPlus\UI\Components\Form\Select;
use HPlus\UI\Components\Grid\Avatar;
use HPlus\UI\Components\Grid\Route;
use HPlus\UI\Components\Grid\Tag;
use HPlus\UI\Components\Widgets\Dialog;
use HPlus\UI\Components\Widgets\Markdown;
use HPlus\UI\Form;
use HPlus\UI\Grid;
use HPlus\UI\Layout\Content;
use Hyperf\Database\Model\Model;

/**
 * @AdminController(prefix="logs", tag="日志管理"))
 */
class Logs extends AbstractAdminController
{
    protected function grid()
    {
        $grid = new Grid(new OperationLog());
        $grid->perPage(20)
            ->selection()
            ->defaultSort('id', 'desc')
            ->stripe()
            ->emptyText('暂无日志')
            ->height('auto')
            ->appendFields(['user.id']);
        $grid->pageSizes([10, 20]);
        $grid->column('id', 'ID')->width('100');
        $grid->column('user.avatar', '头像', 'user_id')->component(Avatar::make()->size('small'))->width(80);
        $grid->column('user.name', '用户', 'user_id')->width(80)->help('操作用户')->sortable()->component(Route::make('/admin/logs/list?user_id={user.id}')->type('primary'));
        $grid->column('method', '请求方式')->width(80)->align('center')->component(Tag::make()->type(['GET' => 'info', 'POST' => 'success']));
        $grid->column('path', '路径')->help('操作URL')->sortable();
        $grid->column('runtime', '执行时间')->help('毫秒')->width(80);
        $grid->column('ip', 'IP')->component(Route::make('/admin/logs/list?ip={ip}')->type('primary'))->width(100);
        $grid->column('created_at', '创建时间')->sortable();
        $grid->actions(function (Grid\Actions $actions) {
            $row = $actions->getRow();
            $action = new Grid\Actions\ActionButton('请求头');
            $action->order(5);
            $action->dialog(function (Dialog $dialog) use ($row) {
                $dialog->title('查看请求头信息');
                $dialog->slot(function (Content $content) use ($row) {
                    $code = "```json\n";
                    $code .= json_encode($row->header, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
                    $code .= "\n```";
                    $content->body(Markdown::make($code)->style('height:60vh;'));
                });
            });
            $actions->add($action);
            unset($action);
            $action = new Grid\Actions\ActionButton('请求值');
            $action->order(5);
            $action->dialog(function (Dialog $dialog) use ($row) {
                $dialog->title('查看提交参数信息');
                $dialog->slot(function (Content $content) use ($row) {
                    $code = "```json\n";
                    $code .= json_encode($row->request, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
                    $code .= "\n```";
                    $content->body(Markdown::make($code)->style('height:60vh;'));
                });
            });
            $actions->add($action);
            unset($action);

            $action = new Grid\Actions\ActionButton('响应结果');
            $action->order(4);
            $action->dialog(function (Dialog $dialog) use ($row) {
                $dialog->title('查看响应结果');
                $dialog->slot(function (Content $content) use ($row) {
                    $code = "```json\n";
                    $code .= json_encode($row->result, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
                    $code .= "\n```";
                    $content->body(Markdown::make($code)->style('height:60vh;'));
                });
            });
            $actions->add($action);
            $actions->hideEditAction();
            $actions->hideViewAction();
        })->toolbars(function (Grid\Toolbars $toolbars) {
            $toolbars->hideCreateButton();
        });

        $grid->filter(function (Grid\Filter $filter) {
            $user_id = (int) request('user_id');
            $filter->equal('user_id')->component(Select::make($user_id)->placeholder('请选择用户')->options(function () {
                $user_ids = OperationLog::query()->groupBy('user_id')->get(['user_id'])->pluck('user_id')->toArray();
                /*@var Model $userModel */
                $userModel = config('admin.database.users_model');
                return $userModel::query()->whereIn('id', $user_ids)->get()->map(function ($user) {
                    return SelectOption::make($user->id, $user->name);
                })->all();
            }));
            $filter->equal('ip')->component(Input::make(request('ip'))->placeholder('IP'));
        });

        return $grid;
    }

    protected function form($isEdit = false)
    {
        $form = new Form(new OperationLog());
        $form->setEdit($isEdit);
        return $form;
    }
}
