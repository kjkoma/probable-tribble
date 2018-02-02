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
$this->assign('title', '棚卸実施');
$this->assign('onlySysAdmin', true);
$this->Breadcrumbs->add('Home', ['prefix' => false, 'controller' => 'Home', 'action' => 'home']);
$this->Breadcrumbs->add('棚卸', '#');
$this->Breadcrumbs->add('棚卸実施', ['controller' => 'Stocktakes', 'action' => 'work']);

?>

<!-- widget grid -->
<section id="widget-grid">

    <!-- widget grid row -->
    <div class="row">

        <!-- ********************************** -->
        <!-- Stocktake List                     -->
        <!-- ********************************** -->
        <?= $this->element('Parts/side-list', [
            'title' => '棚卸対象（在庫締済）',
            'list' => $stocktakes,
            'itemName' => 'stocktake_date',
            'colSize' => 'col-sm-12'
        ]) ?>

        <!-- ********************************** -->
        <!-- Stocktake Work Details             -->
        <!-- ********************************** -->

        <!-- DETAIL stocktake work widget -->
        <article class="col-sm-12 sortable-grid ui-sortable">

            <!-- Stocktake Work Widget -->
            <?= $this->element('Stocktake/stocktake-work', ['conf' => [
                'hidden' => true]]) ?>

            <!-- End DETAILS stocktake work widget -->
        </article>

        <!-- End widget grid row -->
    </div>

    <!-- End widget grid-->
</section>


<!-- load script -->
<?= $this->element('Common/load-datatable') ?>
<?php $this->Html->script('wnote/element/wnote.stocktake_work.js', ['block' => true]); ?>
<?php $this->Html->script('wnote/element/wnote.stocktake_work.asset.js', ['block' => true]); ?>
<?php $this->Html->script('wnote/element/wnote.stocktake_work.count.js', ['block' => true]); ?>
<?php $this->Html->script('wnote/stocktake/stocktakes.work.js', ['block' => true]); ?>

