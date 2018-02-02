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
/**
 * 資産表示 ウィジット（在庫タブ内のコンテンツ） - 本パーツは直接呼出し不可
 *  
 * - - -
 * @param mixed $conf 表示/入力の設定情報
 */
?>
<!-- ********************************** -->
<!-- 在庫情報                       -->
<!-- ********************************** -->
<!-- form -->
<?= $this->Form->create(null, ['id' => 'form-elem-assetview-stock', 'type' => 'post', 'class' => "smart-form"]) ?>

    <header>
        在庫情報
    </header>

    <fieldset>
        <section>
            <dl class="dl-horizontal">
                <dt>在庫数：</dt><dd name="elem_assetview_stock.stock_count" id="elemAssetviewStock_stock_count" data-app-form="form-elem-assetview-stock"></dd>
                <dt>最終更新日時：</dt><dd name="elem_assetview_stock.modified_at" id="elemAssetviewStock_modified_at" data-app-form="form-elem-assetview-stock"></dd>
                <dt>最終更新者：</dt><dd name="elem_assetview_stock.modified_user_name" id="elemAssetviewStock_modified_user_name" data-app-form="form-elem-assetview-stock"></dd>
            </dl>
        </section>
    </fieldset>

<!-- End form -->
<?= $this->Form->end() ?>

<br>

<table class="table table-striped table-bordered table-hover" id="elemAssetStock-datatable">
    <thead>
        <tr>
            <th>履歴タイプ</th>
            <th>入庫日</th>
            <th>出庫日</th>
            <th>棚卸日</th>
            <th>変更日時</th>
            <th>変更前数</th>
            <th>変更後数</th>
            <th>変更理由区分</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>
