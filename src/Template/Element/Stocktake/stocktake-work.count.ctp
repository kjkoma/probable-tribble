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
 * 数量棚卸 ウィジット - 本パーツは直接呼出し不可
 *  
 * - - -
 * @param mixed $conf 表示/入力の設定情報
 */
?>
<!-- ********************************** -->
<!-- 資産検索                           -->
<!-- ********************************** -->

<!-- form -->
<?= $this->Form->create(null, ['id' => 'form-elem-stocktake-work-count', 'type' => 'post', 'class' => "smart-form"]) ?>

    <header>
        資産検索
    </header>

    <fieldset>
        <div class="row">
            <!-- 製品分類 -->
            <section class="col col-4">
                <div class="form-group">
                    <select name="elem-stocktake-work-count.classification_id" id="elemStocktakeWorkCount_classification_id" class="select2 form-control input-sm"
                           data-app-form="form-elem-stocktake-work-count-cond"
                           data-placeholder="製品分類" style="width:100%;"></select>
                </div>
            </section>
            <!-- 製品 -->
            <section class="col col-4">
                <div class="form-group">
                    <select name="elem-stocktake-work-count.product_id" id="elemStocktakeWorkCount_product_id" class="select2 form-control input-sm"
                           data-app-form="form-elem-stocktake-work-count-cond"
                           data-placeholder="製品" style="width:100%;"></select>
                </div>
            </section>
            <!-- モデル -->
            <section class="col col-4">
                <div class="form-group">
                    <select name="elem-stocktake-work-count.product_model_id" id="elemStocktakeWorkCount_product_model_id" class="select2 form-control input-sm"
                           data-app-form="form-elem-stocktake-work-count-cond"
                           data-placeholder="製品モデル／型" style="width:100%;"></select>
                </div>
            </section>
        </div>

        <section>
            <button type="button" class="btn btn-lg btn-block btn-info" data-app-action-key="stocktake-work-count-search"><i class="fa fa-search"></i>　検索</button>
        </section>

    </fieldset>


    <header>
        資産一覧
    </header>

    <fieldset>
        <table class="table table-striped table-bordered table-hover" id="elemStocktakeWorkAsset_stocktake-work-count-datatable">
            <thead>
                <tr>
                    <th>カテゴリ</th>
                    <th>分類</th>
                    <th>メーカー</th>
                    <th>製品名</th>
                    <th>モデル／型</th>
                    <th>在庫数量</th>
                    <th>棚卸数量</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </fieldset>

    <footer class="hidden" id="elemStocktakeWorkCount_savearea">
        <label class="input">
            <input type="number" name="elem-stocktake-work-count.stocktake_count" id="elemStocktakeWorkCount_stocktake_count" class="input-sm col-sm-3"
                   data-app-form="form-elem-stocktake-work-count"
                   maxlength="4"
                   placeholder="棚卸数量">
        </label>
        <button type="button" class="btn btn-primary" data-app-action-key="stocktake-work-count-save"> <i class="fa fa-save"></i>　選択資産の棚卸数量を保存する </button>
    </footer>

<!-- End form -->
<?= $this->Form->end() ?>
