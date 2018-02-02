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
$this->assign('title', '資産');
$this->Breadcrumbs->add('Home', ['prefix' => false, 'controller' => 'Home', 'action' => 'home']);
$this->Breadcrumbs->add('資産', '#');
$this->Breadcrumbs->add('資産登録', ['controller' => 'Assets', 'action' => 'entry']);

?>

<!-- widget grid -->
<section id="widget-grid">

    <!-- ********************************** -->
    <!-- Asset Entry                        -->
    <!-- ********************************** -->

    <!-- widget grid row -->
    <div class="row" id="grid-row-entry">
        <!-- widget -->
        <article class="col-sm-12 sortable-grid ui-sortable">

            <!-- Asset Widget -->
            <?= $this->element('Asset/asset', ['conf' => [
                'asset'   => 'add',
                'attr'    => 'add',
                'user'    => false,
                'stock'   => false,
                'repair'  => false,
                'rental'  => false,
                'hidden' => true]]) ?>

            <!-- End widget -->
        </article>

        <!-- End widget grid row -->
    </div>

    <!-- End widget grid-->
</section>

<!-- load script -->
<?= $this->element('Common/load-datatable') ?>
<?php $this->Html->script('wnote/libs/wnote.lib.form.js', ['block' => true]); ?>
<?= $this->element('Asset/load-asset') ?>
<?php $this->Html->script('wnote/asset/assets.entry.js', ['block' => true]); ?>
