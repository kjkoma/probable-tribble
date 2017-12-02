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
$this->assign('title', 'ドメイン');
$this->assign('onlySysAdmin', true);
$this->Breadcrumbs->add('Home', ['prefix' => false, 'controller' => 'Home', 'action' => 'home']);
$this->Breadcrumbs->add('マスタ（システム）', '#');
$this->Breadcrumbs->add('ドメイン', ['controller' => 'Domains', 'action' => 'index']);

?>

<!-- widget grid -->
<section id="widget-grid">

    <!-- widget grid row -->
    <div class="row">

        <!-- ********************************** -->
        <!-- Domain List                      -->
        <!-- ********************************** -->
        <?= $this->element('Parts/side-list', [
            'title' => 'ドメイン',
            'list' => $domains,
            'itemName' => 'name',
        ]) ?>

        <!-- ********************************** -->
        <!-- Domain Details                       -->
        <!-- ********************************** -->

        <!-- DOMAIN DETAILS widget -->
        <article class="col-sm-8 sortable-grid ui-sortable">

            <!-- widget ID -->
            <div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-1"
                 data-widget-deletebutton="false"
                 data-widget-editbutton="false"
                 data-widget-colorbutton="false"
                 data-widget-togglebutton="false"
                 data-widget-sortable="false">

                <!-- USER Details widget header -->
                <header role="heading">
                    <span class="widget-icon"> <i class="fa fa-lg fa-user"></i> </span>
                    <h2>ドメイン詳細</h2>
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

                    <!-- USER DATALIST widget body -->
                    <div class="widget-body">

                        <!-- user form -->
                        <?= $this->Form->create(null, ['id' => 'form-domain', 'type' => 'post', 'class' => "smart-form"]) ?>

                        <header>基本情報（変更を反映するには再ログインが必要です）</header>

                        <fieldset>
                            <!-- 表示名 -->
                            <section>
                                <label class="input">
                                    <input type="text" name="domain.kname" id="kname" class="input-xs"
                                           data-app-form="form-domain"
                                           placeholder="表示名　－　最大6文字／識別キーとなるので重複しないようにしてください／特殊文字は利用しないでください"
                                           disabled="disabled">
                                </label>
                            </section>
                            <!-- ドメイン名 -->
                            <section>
                                <label class="input">
                                    <input type="text" name="domain.name" id="name" class="input-xs"
                                           data-app-form="form-domain" placeholder="ドメイン名　－　最大40文字／通常は資産管理会社の正式名称となります"
                                           disabled="disabled">
                                </label>
                            </section>
                            <!-- 補足（コメント） -->
                            <section>
                                <label class="textarea textarea-resizable">
                                    <textarea name="domain.remarks" id="remarks" row="3" class="custom-scroll"
                                              data-app-form="form-domain" placeholder="【任意】補足（コメント）"
                                              disabled="disabled"></textarea>
                                </label>
                            </section>
                            <!-- 利用可否 -->
                            <section>
                                <?= $this->element('Parts/select-dsts', [
                                    'name' => 'domain.dsts',
                                    'id'   => 'dsts',
                                    'form' => 'form-domain',
                                ]) ?>
                            </section>
                        </fieldset>

                        <header>利用アプリケーション <span class="font-xs">（このドメインで利用するアプリケーションにチェックを入れてください）</span></header>
                        <fieldset>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>－</th>
                                        <th>アプリケーション</th>
                                        <th>補足</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($apps as $app) { ?>
                                        <tr>
                                            <td>
                                                <section><label class="checkbox">
                                                        <input type="checkbox" name="domain.domain_apps"
                                                               value="<?= h($app['id']) ?>"
                                                               data-id="<?= h($app['id']) ?>"
                                                               data-app-action-key="tbl-checkbox"
                                                               data-app-form="form-domain" disabled="disabled">
                                                        <i></i></label></section>
                                            </td>
                                            <td><?= h($app['name']) ?></td>
                                            <td><?= h($app['remarks']) ?></td>
                                        </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </fieldset>

                        <!-- End user form -->
                        <input type="hidden" name="domain.id" id="id" data-app-form="form-domain">
                        <input type="hidden" id="page_current_id" value="<?= $this->AppUser->domain() ?>">
                        <?= $this->Form->end() ?>

                        <!-- End DOMAIN DETAILS widget body -->
                    </div>

                    <!-- End content -->
                </div>

                <!-- End widget ID -->
            </div>

            <!-- End DOMAIN DETAILS widget -->
        </article>

        <!-- End widget grid row -->
    </div>

    <!-- End widget grid-->
</section>


<!-- load script -->
<?php $this->Html->script('wnote/master/system/domains.index.js', ['block' => true]); ?>

