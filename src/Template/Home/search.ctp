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
$this->assign('title', '検索結果');
$this->Breadcrumbs->add('Home', ['prefix' => false, 'controller' => 'Home', 'action' => 'home']);
$this->Breadcrumbs->add('検索結果', '#');

?>

<!-- row -->
<div class="row">

    <!-- col -->
    <div class="col-sm-12">

        <ul class="nav nav-tabs bordered">
            <li class="active">
                <a href="#common-search-tab" data-toggle="tab">検索結果</a>
            </li>
        </ul>

        <!-- tab content -->
        <div class="tab-content bg-color-white padding-10">

            <!-- seach result tab -->
            <div class="tab-pane fade in active" id="common-search-tab">

                <?= $this->Form->create(null, ['url' => '/search', 'id' => 'form-re-search-criteria', 'type' => 'post', 'class' => '']) ?>
                    <div class="input-group input-group-lg">
                        <input class="form-control input-lg" type="text" placeholder="再建策" id="re-search-criteria" name="criteria">
                        <div class="input-group-btn">
                            <button type="submit" class="btn btn-default">
                                <i class="fa fa-fw fa-search fa-lg"></i>
                            </button>
                        </div>
                    </div>
                <?= $this->Form->end() ?>

                <h2 class="font-md"> 検索 「<strong><?= h($criteria) ?></strong>」：　資産<small class="txt-color-teal"> &nbsp;&nbsp;（　<?= count($assets) ?> 件　）</small></h2>

                <!-- search-result -->
                <?php if (count($assets) == 0) { ?>
                    <h4 class="font-md"><span class="semi-bold">検索結果がありません。</span></h4>

                <?php } else { ?>
                    <?php foreach($assets as $asset) { ?>
                        <div class="search-results clearfix smart-form">
                            <h4><a href="javascript:void(0);" class="text-primary"><?= h($asset['kname']) ?></a></h4>
                            <p class="description">
                                【シリアル番号】<?= h($asset['serial_no']) ?>　
                                【資産管理番号】<?= h($asset['asset_no']) ?>　
                                <br>
                                【カテゴリ】<?= h($asset['category_name']) ?>　
                                【分類】<?= h($asset['classification_name']) ?>　
                                【メーカー】<?= h($asset['maker_name']) ?>　
                                【製品名】<?= h($asset['product_name']) ?>　
                                【モデル／型】<?= h($asset['product_model_name']) ?>　
                                <br>
                                【状況】<?= h($asset['asset_sts_name']) ?>　
                                【状況（サブ）】<?= h($asset['asset_sub_sts_name']) ?>　
                                【在庫数】<?= h($asset['stock_count']) ?>
                                <br>
                                【利用者】<?= h($asset['user_name']) ?>
                            </p>
                        </div>
                    <?php } ?>

                <?php } ?>

        <!-- End seach result tab -->
        </div>

    <!-- End tab content -->
    </div>

    <!-- End col -->
    </div>

<!-- End row -->
</div>

