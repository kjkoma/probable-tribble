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
$this->assign('title', '資産利用者');
$this->assign('onlyDomainAdmin', true);
$this->Breadcrumbs->add('Home', ['prefix' => false, 'controller' => 'Home', 'action' => 'home']);
$this->Breadcrumbs->add('マスタ（管理）', '#');
$this->Breadcrumbs->add('資産利用者', ['controller' => 'Users', 'action' => 'index']);

?>

<!-- widget grid -->
<section id="widget-grid">

    <!-- widget grid row -->
    <div class="row">

        <!-- ********************************** -->
        <!-- User List                          -->
        <!-- ********************************** -->
        <?= $this->element('Parts/side-tree-organizations', [
            'title'     => '資産利用者',
            'customers' => $customers,
        ]) ?>

        <!-- ********************************** -->
        <!-- User Details                       -->
        <!-- ********************************** -->

        <!-- DETAIL widget -->
        <article class="col-sm-8 sortable-grid ui-sortable">

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
                    <h2>資産利用者詳細</h2>
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
                        <?= $this->Form->create(null, ['id' => 'form-user', 'type' => 'post', 'class' => "smart-form"]) ?>

                            <header>
                                基本情報
                            </header>

                            <fieldset>
                                <!-- 姓 -->
                                <section>
                                    <label class="input">
                                        <input type="text" name="user.sname" id="sname" class="input-xs"
                                               data-app-form="form-user"
                                               placeholder="姓　－　最大8文字／特殊文字は利用しないでください"
                                               disabled="disabled">
                                    </label>
                                </section>
                                <!-- 名 -->
                                <section>
                                    <label class="input">
                                        <input type="text" name="user.fname" id="fname" class="input-xs"
                                               data-app-form="form-user"
                                               placeholder="姓　－　最大8文字／特殊文字は利用しないでください"
                                               disabled="disabled">
                                    </label>
                                </section>
                                <!-- email -->
                                <section>
                                    <label class="input">
                                        <input type="text" name="user.email" id="email" class="input-xs"
                                               data-app-form="form-user" placeholder="【任意】Emailアドレス　－　半角・小文字で入力してください"
                                               disabled="disabled">
                                    </label>
                                </section>
                                <!-- 社員番号 -->
                                <section>
                                    <label class="input">
                                        <input type="text" name="user.employee_no" id="employee_no" class="input-xs"
                                               data-app-form="form-user"
                                               placeholder="【任意】社員番号　－　最大20文字／特殊文字は利用しないでください"
                                               disabled="disabled">
                                    </label>
                                </section>
                                <!-- 補足（コメント） -->
                                <section>
                                    <label class="textarea textarea-resizable">
                                        <textarea name="user.remarks" id="remarks" rows="3" class="custom-scroll"
                                                  data-app-form="form-user" placeholder="【任意】補足（コメント）"
                                                  disabled="disabled"></textarea>
                                    </label>
                                </section>
                                <!-- 利用可否 -->
                                <section>
                                    <?= $this->element('Parts/select-dsts2', [
                                        'name' => 'user.dsts',
                                        'id'   => 'dsts',
                                        'form' => 'form-user',
                                    ]) ?>
                                </section>
                            </fieldset>

                            <header>
                                所属組織（グループ）
                            </header>

                            <fieldset>
                                <!-- 所属組織（グループ） -->
                                <section>
                                    <div class="form-group">
                                        <select name="user.organization_id" id="organization_id" class="select2 form-control input-sm"
                                               data-app-form="form-user" data-placeholder="所属する資産管理組織（グループ）を入力・選択してください"
                                               disabled="disabled"
                                               style="width:100%;"></select>
                                    </div>
                                </section>
                            </fieldset>

                        <!-- End form -->
                        <input type="hidden" name="user.id" id="id" data-app-form="form-user">
                        <?= $this->Form->end() ?>

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
<?= $this->element('Common/load-datatable') ?>
<?php $this->Html->script('wnote/wnote.tree.users.js', ['block' => true]); ?>
<?php $this->Html->script('wnote/master/admin/users.index.js', ['block' => true]); ?>

