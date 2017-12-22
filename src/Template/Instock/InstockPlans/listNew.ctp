<?php
/**
 * Copyright (c) Japan Computer Services, Inc.
 *
 * Licensed under The MIT License
 *
 * @author    Japan Computer Services, Inc
 * @copyright Copyright (c) Japan Computer Services, Inc. (http://www.japacom.co.jp)
 * @since     1.0.0
 * @version   1.0.0
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 *
 * -- Histories --
 * 2017.12.31 R&D 新規作成
 */
$this->assign('title', '入庫予定（新規）');
$this->assign('onlyDomainAdmin', true);
$this->Breadcrumbs->add('Home', ['prefix' => false, 'controller' => 'Home', 'action' => 'home']);
$this->Breadcrumbs->add('マスタ（管理）', '#');
$this->Breadcrumbs->add('入庫予定（新規）', ['controller' => 'InstockPlans', 'action' => 'listNew']);

?>

<!-- widget grid -->
<section id="widget-grid">

    <!-- ********************************** -->
    <!-- Category Details                       -->
    <!-- ********************************** -->

    <!-- list widget grid row -->
    <div class="row">
        <!-- DETAIL list widget -->
        <article class="col-sm-12 sortable-grid ui-sortable">

            <!-- widget ID -->
            <div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-list"
                 data-widget-deletebutton="false"
                 data-widget-editbutton="false"
                 data-widget-colorbutton="false"
                 data-widget-togglebutton="false"
                 data-widget-sortable="false">

                <!-- DETAILS widget header -->
                <header role="heading">
                    <span class="widget-icon"> <i class="fa fa-lg fa-building"></i> </span>
                    <h2>入庫予定（新規）一覧</h2>
                </header>

                <!-- content -->
                <div role="content">

                    <table class="table table-striped table-bordered table-hover" id="model-datatable">
                        <thead>
                            <tr>
                                <th>予定日</th>
                                <th>状況</th>
                                <th>件名</th>
                                <th>備考</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>

                    <!-- End content -->
                </div>

                <!-- End widget ID -->
            </div>

            <!-- End DETAILS list widget -->
        </article>

        <!-- End list widget grid row -->
    </div>

    <!-- widget grid row -->
    <div class="row">

        <!-- ********************************** -->
        <!-- Instock Plan Details               -->
        <!-- ********************************** -->

        <!-- DETAIL widget -->
        <article class="col-sm-12 sortable-grid ui-sortable">

            <!-- widget ID -->
            <div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-1"
                 data-widget-deletebutton="false"
                 data-widget-editbutton="false"
                 data-widget-colorbutton="false"
                 data-widget-togglebutton="false"
                 data-widget-sortable="false">

                <!-- DETAILS widget header -->
                <header role="heading">
                    <span class="widget-icon"> <i class="fa fa-lg fa-building"></i> </span>
                    <h2>入庫予定内容</h2>
                    <div class="widget-toolbar hidden" role="menu" data-app-action-key="view-actions">
                        <a href="javascript:void(0);" class="btn btn-success" data-app-action-key="edit">編集</a>
                    </div>
                    <div class="widget-toolbar" role="menu" data-app-action-key="add-actions">
                        <a href="javascript:void(0);" class="btn btn-success" data-app-action-key="add">追加</a>
                    </div>
                    <div class="widget-toolbar hidden" role="menu" data-app-action-key="edit-actions">
                        <a href="javascript:void(0);" class="btn btn-default" data-app-action-key="cancel">キャンセル</a>
                        <a href="javascript:void(0);" class="btn btn-primary" data-app-action-key="save">保存</a>
                    </div>
                    <div class="widget-toolbar hidden" role="menu" data-app-action-key="delete-actions">
                        <a href="javascript:void(0);" class="btn btn-danger" data-app-action-key="delete">削除</a>
                    </div>
                </header>

                <!-- content -->
                <div role="content">

                    <!-- DETAILS widget body -->
                    <div class="widget-body">

                        <!-- form -->
                        <?= $this->Form->create(null, ['id' => 'form-plan', 'type' => 'post', 'class' => "smart-form"]) ?>

                            <header>
                                概要
                            </header>

                            <fieldset>
                                <!-- 入庫予定日 -->
                                <section>
                                    <label class="input">
                                        <i class="icon-append fa fa-calendar"></i>
                                        <input type="text" name="plan.plan_date" id="plan_date" class="input-sm datepicker"
                                               data-dateformat="yy/mm/dd"
                                               data-app-form="form-plan"
                                               placeholder="入庫予定日　－　yyyy/mm/dd形式で入力してください（例：2017/10/09）"
                                               disabled="disabled">
                                    </label>
                                </section>
                                <!-- 件名 -->
                                <section>
                                    <label class="input">
                                        <input type="text" name="plan.name" id="name" class="input-xs"
                                               data-app-form="form-plan" placeholder="件名　－　最大60文字"
                                               disabled="disabled">
                                    </label>
                                </section>
                                <!-- 補足（コメント） -->
                                <section>
                                    <label class="textarea textarea-resizable">
                                        <textarea name="plan.remarks" id="remarks" row="3" class="custom-scroll"
                                                  data-app-form="form-plan" placeholder="【任意】補足（コメント）"
                                                  disabled="disabled"></textarea>
                                    </label>
                                </section>
                            </fieldset>

                            <header>
                                入庫予定内容
                            </header>

                            <fieldset>
                            </fieldset>

                        <!-- End form -->
                        <input type="hidden" name="plan.id" id="id" data-app-form="form-plan">
                        <?= $this->Form->end() ?>
                        </form>

                        <!-- End DETAILS widget body -->
                    </div>

                    <!-- End content -->
                </div>

                <!-- End widget ID -->
            </div>

            <!-- End DETAILS widget -->
        </article>

        <!-- End widget grid row -->
    </div>

    <!-- End widget grid-->
</section>


<!-- load script -->
<?php $this->Html->script('wnote/master/admin/categories.index.js', ['block' => true]); ?>

