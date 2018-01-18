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
 * 資産表示 ウィジット（資産タブ内のコンテンツ） - 本パーツは直接呼出し不可
 *  
 * - - -
 * @param mixed $conf 表示/入力の設定情報
 */
?>
<!-- ********************************** -->
<!-- 資産情報                           -->
<!-- ********************************** -->
<!-- form -->
<?= $this->Form->create(null, ['id' => 'form-elem-asset', 'type' => 'post', 'class' => "smart-form"]) ?>

    <div id="elem-asset-input" class="<?= ($conf['asset'] && $conf['asset'] == 'input') ? '' : 'hidden' ?>">
        <!-- ********************************** -->
        <!-- 操作アクション（モデル）           -->
        <!-- ********************************** -->
        <div class="widget-actions" id="elemAsset-actions">
            <div class="widget-action" data-app-action-key="elemAsset-view-detail-actions">
                <a href="javascript:void(0);" class="btn btn-success" data-app-action-key="elemAsset-edit-detail">編集</a>
            </div>
            <div class="widget-action hidden" data-app-action-key="elemAsset-edit-detail-actions">
                <a href="javascript:void(0);" class="btn btn-default" data-app-action-key="elemAsset-cancel-detail">キャンセル</a>
                <a href="javascript:void(0);" class="btn btn-primary" data-app-action-key="elemAsset-save-detail">保存</a>
            </div>
        </div>

        <!-- elem-asset-edit-id -->
        <div class="hidden" id="elem-asset-edit-id">

            <header>
                資産編集
            </header>

            <fieldset>
                <section>
                    <!-- 資産管理番号 -->
                    <label class="input">
                        <input type="text" name="elem_asset.asset_no" id="elemAsset_asset_no" class="input-sm"
                               data-app-form="form-elem-asset"
                               maxlength=120
                               placeholder="資産管理番号 - 最大120文字"
                               disabled="disabled">
                    </label>
                </section>
                <section>
                    <!-- 資産名称 -->
                    <label class="input">
                        <input type="text" name="elem_asset.kname" id="elemAsset_kname" class="input-sm"
                               data-app-form="form-elem-asset"
                               maxlength=100
                               placeholder="資産名称 - 最大100文字"
                               disabled="disabled">
                    </label>
                </section>
                <section>
                    <!-- 資産状況 -->
                    <?= $this->element('Parts/select-snames', [
                        'snames'  => $assetSts,
                        'name'    => 'elem_asset.asset_sts',
                        'id'      => 'elemAsset_asset_sts',
                        'form'    => 'form-elem-asset'
                    ]) ?>
                </section>
                <section>
                    <!-- 資産状況(サブ) -->
                    <?= $this->element('Parts/select-snames', [
                        'snames'  => $assetSubSts,
                        'name'    => 'elem_asset.asset_sub_sts',
                        'id'      => 'elemAsset_asset_sub_sts',
                        'form'    => 'form-elem-asset'
                    ]) ?>
                </section>
                <section>
                    <!-- 初回入庫日 -->
                    <label class="input">
                        <input type="text" name="elem_asset.first_instock_date" id="elemAsset_first_instock_date" class="input-sm"
                            data-app-form="form-elem-asset"
                            maxlength=10
                            placeholder="初回入庫日 - yyyy/mm/dd形式"
                            disabled="disabled">
                    </label>
                </section>
                <section>
                    <!-- 初回出庫日 -->
                    <label class="input">
                        <input type="text" name="elem_asset.account_date" id="elemAsset_account_date" class="input-sm"
                            data-app-form="form-elem-asset"
                            maxlength=10
                            placeholder="初回出荷日(計上日) - yyyy/mm/dd形式"
                            disabled="disabled">
                    </label>
                </section>
                <section>
                    <!-- 廃棄日 -->
                    <label class="input">
                        <input type="text" name="elem_asset.abrogate_date" id="elemAsset_abrogate_date" class="input-sm"
                            data-app-form="form-elem-asset"
                            maxlength=10
                            placeholder="廃棄日 - yyyy/mm/dd形式"
                            disabled="disabled">
                    </label>
                </section>
                <section>
                    <!-- 保守期限日 -->
                    <label class="input">
                        <input type="text" name="elem_asset.support_limit_date" id="elemAsset_support_limit_date" class="input-sm"
                            data-app-form="form-elem-asset"
                            maxlength=10
                            placeholder="保守期限日 - yyyy/mm/dd形式"
                            disabled="disabled">
                    </label>
                </section>
                <section>
                    <!-- 補足 -->
                    <label class="textarea textarea-resizable">
                        <textarea name="elem_asset.remarks" id="elemAsset_remarks" rows="3" class="custom-scroll"
                                  data-app-form="form-elem-asset" placeholder="【任意】補足（コメント）"
                                  disabled="disabled"></textarea>
                    </label>
                </section>
            </fieldset>

            <!-- End elem-asset-edit-id -->
        </div>

    </div>

    <header>
        資産情報
    </header>

    <fieldset>
        <section>
            <dl class="dl-horizontal">
                <dt>資産タイプ：</dt><dd name="elem_assetview.asset_type" id="elemAssetview_asset_type_name" data-app-form="form-elem-assetview"></dd>
                <dt>カテゴリ：</dt><dd name="elem_assetview.category_name" id="elemAssetview_category_name" data-app-form="form-elem-assetview"></dd>
                <dt>分類：</dt><dd name="elem_assetview.classification_name" id="elemAssetview_classification_name" data-app-form="form-elem-assetview"></dd>
            </dl>
        </section>
        <section>
            <dl class="dl-horizontal">
                <dt>製造番号／シリアル：</dt><dd name="elem_assetview.serial_no" id="elemAssetview_serial_no" data-app-form="form-elem-assetview"></dd>
            </dl>
        </section>
        <section>
            <dl class="dl-horizontal">
                <dt>資産管理番号：</dt><dd name="elem_assetview.asset_no" id="elemAssetview_asset_no" data-app-form="form-elem-assetview"></dd>
            </dl>
        </section>
        <section>
            <dl class="dl-horizontal">
                <dt>メーカー：</dt><dd name="elem_assetview.maker_name" id="elemAssetview_maker_name" data-app-form="form-elem-assetview"></dd>
                <dt>製品名：</dt><dd name="elem_assetview.product_name" id="elemAssetview_product_name" data-app-form="form-elem-assetview"></dd>
                <dt>モデル名：</dt><dd name="elem_assetview.product_model_name" id="elemAssetview_product_model_name" data-app-form="form-elem-assetview"></dd>
            </dl>
        </section>
        <section>
            <dl class="dl-horizontal">
                <dt>資産名称：</dt><dd name="elem_assetview.kname" id="elemAssetview_kname" data-app-form="form-elem-assetview"></dd>
                <dt>資産状況：</dt><dd name="elem_assetview.asset_sts_name" id="elemAssetview_asset_sts_name" data-app-form="form-elem-assetview"></dd>
                <dt>資産状況（サブ）：</dt><dd name="elem_assetview.asset_sub_sts_name" id="elemAssetview_asset_sub_sts_name" data-app-form="form-elem-assetview"></dd>
                <dt>初回入庫日：</dt><dd name="elem_assetview.first_instock_date" id="elemAssetview_first_instock_date" data-app-form="form-elem-assetview"></dd>
                <dt>初回出荷日：</dt><dd name="elem_assetview.account_date" id="elemAssetview_account_date" data-app-form="form-elem-assetview"></dd>
                <dt>廃棄日：</dt><dd name="elem_assetview.abrogate_date" id="elemAssetview_abrogate_date" data-app-form="form-elem-assetview"></dd>
                <dt>保守期限日：</dt><dd name="elem_assetview.support_limit_date" id="elemAssetiew_support_limit_date" data-app-form="form-elem-assetview"></dd>
                <dt>補足：</dt><dd name="elem_assetview.remarks" id="elemAssetview_remarks" data-app-form="form-elem-assetview"></dd>
            </dl>
        </section>
        <section>
            <dl class="dl-horizontal">
                <dt>登録日時：</dt><dd name="elem_assetview.created_at" id="elemAssetview_created_at" data-app-form="form-elem-assetview"></dd>
                <dt>更新日時：</dt><dd name="elem_assetview.modified_at" id="elemAssetview_modified_at" data-app-form="form-elem-assetview"></dd>
                <dt>更新者：</dt><dd name="elem_assetview.modified_user_name" id="elemAssetview_modified_user_name" data-app-form="form-elem-assetview"></dd>
            </dl>
        </section>
    </fieldset>

<!-- End form -->
<?= $this->Form->end() ?>

