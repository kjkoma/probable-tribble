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
$this->assign('title', 'システムユーザー');
$this->assign('onlySysAdmin', true);
$this->Breadcrumbs->add('Home', ['prefix' => false, 'controller' => 'Home', 'action' => 'home']);
$this->Breadcrumbs->add('マスタ（システム）', '#');
$this->Breadcrumbs->add('ユーザー', ['controller' => 'Susers', 'action' => 'index']);

?>

<!-- widget grid -->
<section id="widget-grid">

    <!-- widget grid row -->
    <div class="row">

        <!-- ********************************** -->
        <!-- User List                          -->
        <!-- ********************************** -->
        <?= $this->element('Parts/side-datatable', [
            'title' => 'ユーザー',
            'list' => $users,
            'header' => '名称（email）',
            'itemName' => 'kname',
            'itemNameSub' => 'email',
            'fa' => 'user',
        ]) ?>

        <!-- ********************************** -->
        <!-- User Details                       -->
        <!-- ********************************** -->

        <!-- USER DETAILS widget -->
        <article class="col-sm-8 sortable-grid ui-sortable">

            <!-- widget ID -->
            <div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-1"
                 data-widget-deletebutton="false"
                 data-widget-editbutton="false"
                 data-widget-colorbutton="false"
                 data-widget-togglebutton="false"
                 data-widget-sortable="false">

                <!-- USER DETAILS widget header -->
                <header role="heading">
                    <span class="widget-icon"> <i class="fa fa-lg fa-user"></i> </span>
                    <h2>ユーザー詳細</h2>
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

                    <!-- USER DETAILS widget body -->
                    <div class="widget-body">

                        <!-- user form -->
                        <?= $this->Form->create(null, ['id' => 'form-suser', 'type' => 'post', 'class' => "smart-form"]) ?>

                        <header>
                            基本情報（変更を反映するには再ログインが必要です）
                        </header>

                        <fieldset>
                            <!-- Emailアドレス -->
                            <section>
                                <label class="input">
                                    <input type="text" name="suser.email" id="email" class="input-xs"
                                           data-app-form="form-suser"
                                           placeholder="Emailアドレス　－　ログインする際に必要となるEmailアドレスとなります／半角・小文字で入力してください"
                                           disabled="disabled">
                                </label>
                            </section>
                            <!-- 表示名 -->
                            <section>
                                <label class="input">
                                    <input type="text" name="suser.kname" id="kname" class="input-xs"
                                           data-app-form="form-suser"
                                           placeholder="表示名　－　最大12文字／識別キーとなるので重複しないようにしてください／特殊文字は利用しないでください"
                                           disabled="disabled">
                                </label>
                            </section>
                            <!-- 姓 -->
                            <section>
                                <label class="input">
                                    <input type="text" name="suser.sname" id="sname" class="input-xs"
                                           data-app-form="form-suser"
                                           placeholder="姓　－　最大16文字"
                                           disabled="disabled">
                                </label>
                            </section>
                            <!-- 名 -->
                            <section>
                                <label class="input">
                                    <input type="text" name="suser.fname" id="fname" class="input-xs"
                                           data-app-form="form-suser"
                                           placeholder="名　－　最大16文字"
                                           disabled="disabled"></label>
                            </section>
                            <!-- パスワード -->
                            <section>
                                <label class="input">
                                    <input type="password" name="suser.password" id="password" class="input-xs"
                                           data-app-form="form-suser"
                                           placeholder="パスワード／変更する場合にのみ入力してください／6文字 - 16文字"
                                           disabled="disabled">
                                </label>
                            </section>
                            <!-- パスワード（確認用）-->
                            <section>
                                <label class="input">
                                    <input type="password" name="suser.password_confirmation" id="password_confirmation" class="input-xs"
                                           data-app-form="form-suser"
                                           placeholder="パスワード（確認用）／変更する場合にのみ入力してください"
                                           disabled="disabled">
                                </label>
                            </section>
                            <!-- 補足（コメント） -->
                            <section>
                                <label class="textarea textarea-resizable">
                                    <textarea name="suser.remarks" id="remarks" rows="3" class="custom-scroll"
                                              data-app-form="form-suser"
                                              placeholder="【任意】補足（コメント）"
                                              disabled="disabled"></textarea>
                                </label>
                            </section>
                            <!-- 利用可否 -->
                            <section>
                                <?= $this->element('Parts/select-dsts', [
                                    'name' => 'suser.dsts',
                                    'id'   => 'dsts',
                                    'form' => 'form-suser',
                                ]) ?>
                            </section>
                        </fieldset>

                        <header>割り当てロール</header>

                        <fieldset>
                            <section>
                                <?= $this->element('Parts/select-sroles', [
                                    'name' => 'suser.srole_id',
                                    'id'   => 'srole_id',
                                    'form' => 'form-suser',
                                ]) ?>
                            </section>
                        </fieldset>

                        <header>割り当てドメイン <span class="font-xs">（このユーザーが利用できるドメインにチェックを入れてください）</span></header>
                        <fieldset>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>－</th>
                                        <th>ドメイン</th>
                                        <th>補足</th>
                                        <th>ロール</th>
                                        <th>デフォルト</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($this->AppGlobal->domains() as $index=>$domain) { ?>
                                    <tr>
                                        <td>
                                            <section>
                                                <label class="checkbox">
                                                    <input type="checkbox" name="suser_domains.<?= $index ?>.domain_id"
                                                           value="<?= h($domain['id']) ?>"
                                                           data-id="<?= h($domain['id']) ?>"
                                                           data-app-action-key="tbl-checkbox"
                                                           data-app-form="form-suser"
                                                           disabled="disabled">
                                                    <i></i></label>
                                            </section>
                                        </td>
                                        <td><?= h($domain['name']) ?></td>
                                        <td><?= h($domain['remarks']) ?></td>
                                        <td>
                                            <section>
                                                <?= $this->element('Parts/select-droles', [
                                                    'name' => 'suser_domains.' . $index . '.srole_id',
                                                    'form' => 'form-suser',
                                                    'attr' => 'data-app-action-key="tbl-srole" data-app-row="' . h($domain['id']) . '"'
                                                ]) ?>
                                            </section>
                                        </td>
                                        <td>
                                            <section>
                                                <label class="radio">
                                                    <input type="radio" name="suser_domains.default_domain"
                                                           value="1"
                                                           data-id="1"
                                                           data-app-row="<?= h($domain['id']) ?>"
                                                           data-app-action-key="tbl-default"
                                                           data-app-form="form-suser"
                                                           data-app-name="suser_domains.<?= $index ?>.default_domain"
                                                           disabled="disabled">
                                                    <i></i></label>
                                            </section>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </fieldset>

                        <!-- End user form -->
                        <input type="hidden" name="suser.id" id="id" data-app-form="form-suser">
                        <input type="hidden" id="page_current_id" value="<?= $this->AppUser->id() ?>">
                        <?= $this->Form->end() ?>

                        <!-- End USER DETAILS widget body -->
                    </div>

                    <!-- End content -->
                </div>

                <!-- End widget ID -->
            </div>

            <!-- End USER DETAILS widget -->
        </article>

        <!-- End widget grid row -->
    </div>

    <!-- End widget grid-->
</section>


<!-- load script -->
<?= $this->element('Common/load-datatable') ?>
<?php $this->Html->script('wnote/master/system/susers.index.js', ['block' => true]); ?>

