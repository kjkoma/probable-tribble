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
 * 資産棚卸 ウィジット - 本パーツは直接呼出し不可
 *  
 * - - -
 * @param mixed $conf 表示/入力の設定情報
 */
?>
<!-- ********************************** -->
<!-- 棚卸入力                           -->
<!-- ********************************** -->

<!-- form -->
<?= $this->Form->create(null, ['id' => 'form-elem-stocktake-work-asset', 'type' => 'post', 'class' => "smart-form"]) ?>

    <header>
        棚卸物品入力
    </header>

    <fieldset>
        <div class="row">
            <section class="col col-sm-6">
                <label class="input">
                    <input type="text" name="elem-stocktake-work-asset.asset_input" id="elemStocktakeWorkAsset_asset_input" class="input-sm"
                           data-app-form="form-elem-stocktake-work-asset"
                           placeholder="資産管理番号を入力してください - 入力後はEnterキーを押して確定してください">
                </label>
            </section>
            <section class="col col-sm-6">
                <label class="input">
                    <input type="text" name="elem-stocktake-work-asset.serial_input" id="elemStocktakeWorkAsset_serial_input" class="input-sm"
                           data-app-form="form-elem-stocktake-work-asset"
                           placeholder="シリアルを入力してください - 入力後はEnterキーを押して確定してください">
                </label>
            </section>
        </div>
        <label class="label">注意）バーコードスキャナ―を利用する場合、IME-MODEを"OFF"（英数字入力）にして入力してください。</label>
    </fieldset>

    <div class="widget-actions">
        <div class="widget-action">
            <span>入力件数： <strong id="elemStocktakeWorkAsset_stocktake_stock_count">0</strong> 件　</span>
            <span>未在庫件数： <strong id="elemStocktakeWorkAsset_stocktake_nostock_count">0</strong> 件　</span>
            <a href="javascript:void(0);" class="btn btn-warning" data-app-action-key="stocktake-work-asset-delete-all">全ての棚卸入力を削除</a>
            <a href="javascript:void(0);" class="btn btn-warning" data-app-action-key="stocktake-work-asset-delete">選択行を削除</a>
            <a href="javascript:void(0);" class="btn btn-info" data-app-action-key="stocktake-work-asset-clear">選択状態を全てクリア</a>
        </div>
    </div>

    <header>
        棚卸入力一覧
    </header>

    <fieldset>
        <table class="table table-striped table-bordered table-hover" id="elemStocktakeWorkAsset_stocktake-work-asset-datatable">
            <thead>
                <tr>
                    <th>在庫有無</th>
                    <th>資産管理</th>
                    <th>シリアル</th>
                    <th>分類</th>
                    <th>製品名</th>
                    <th>モデル／型</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </fieldset>

    <footer>
        <button type="button" class="btn btn-primary" data-app-action-key="stocktake-work-asset-save"> 棚卸結果を保存する </button>
    </footer>

<!-- End form -->
<?= $this->Form->end() ?>
