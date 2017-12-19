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
$this->assign('title', '仕入先／メーカー');
$this->Breadcrumbs->add('Home', ['prefix' => false, 'controller' => 'Home', 'action' => 'home']);
$this->Breadcrumbs->add('マスタ（一般）', '#');
$this->Breadcrumbs->add('仕入先／メーカー', ['controller' => 'Companies', 'action' => 'index']);

?>

<!-- widget grid -->
<section id="widget-grid">

    <!-- widget grid row -->
    <div class="row">

        <!-- ********************************** -->
        <!-- Company List                       -->
        <!-- ********************************** -->
        <?= $this->element('Parts/side-datatable', [
            'title' => '企業（仕入先／メーカー他）',
            'list' => $companies,
            'header' => '名称（企業区分）',
            'itemName' => 'kname',
            'itemNameSub' => ['sname', 'name'],
            'fa' => 'building-o',
        ]) ?>

        <!-- ********************************** -->
        <!-- Company Details                    -->
        <!-- ********************************** -->

        <!-- DETAILS widget -->
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
                    <span class="widget-icon"> <i class="fa fa-lg fa-user"></i> </span>
                    <h2>企業（仕入先／メーカー他）詳細</h2>
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
                        <?= $this->Form->create(null, ['id' => 'form-company', 'type' => 'post', 'class' => "smart-form"]) ?>

                        <header>
                            基本情報
                        </header>

                        <fieldset>
                            <!-- 表示名 -->
                            <section>
                                <label class="input">
                                    <input type="text" name="company.kname" id="kname" class="input-xs"
                                           data-app-form="form-company"
                                           placeholder="表示名　－　最大12文字／特殊文字は利用しないでください"
                                           disabled="disabled">
                                </label>
                            </section>
                            <!-- 正式名 -->
                            <section>
                                <label class="input">
                                    <input type="text" name="company.name" id="name" class="input-xs"
                                           data-app-form="form-company"
                                           placeholder="企業名（正式名）　－　最大80文字"
                                           disabled="disabled">
                                </label>
                            </section>
                            <!-- 企業区分 -->
                            <section>
                                <?= $this->element('Parts/select-snames', [
                                    'snames' => $company_kbn,
                                    'name'   => 'company.company_kbn',
                                    'id'     => 'company_kbn',
                                    'form'   => 'form-company',
                                ]) ?>
                            </section>
                            <!-- 補足（コメント） -->
                            <section>
                                <label class="textarea textarea-resizable">
                                    <textarea name="company.remarks" id="remarks" row="3" class="custom-scroll"
                                              data-app-form="form-company"
                                              placeholder="【任意】補足（コメント）"
                                              disabled="disabled"></textarea>
                                </label>
                            </section>
                            <!-- 利用可否 -->
                            <section>
                                <?= $this->element('Parts/select-dsts', [
                                    'name' => 'company.dsts',
                                    'id'   => 'dsts',
                                    'form' => 'form-company',
                                ]) ?>
                            </section>
                        </fieldset>

                        <!-- End form -->
                        <input type="hidden" name="company.id" id="id" data-app-form="form-company">
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
<?php $this->Html->script('wnote/master/general/companies.index.js', ['block' => true]); ?>

