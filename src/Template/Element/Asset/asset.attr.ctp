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
 * 資産表示 ウィジット（属性タブ内のコンテンツ） - 本パーツは直接呼出し不可
 *  
 * - - -
 * @param mixed $conf 表示/入力の設定情報
 */
?>
<!-- ********************************** -->
<!-- 資産属性情報                       -->
<!-- ********************************** -->
<!-- form -->
<?= $this->Form->create(null, ['id' => 'form-elem-asset-attr', 'type' => 'post', 'class' => "smart-form"]) ?>

    <!-- input block -->
    <div id="elem-asset-attr-input" class="<?= ($conf['attr'] && $conf['attr'] != 'view') ? '' : 'hidden' ?>">

        <!-- ********************************** -->
        <!-- 操作アクション（モデル）           -->
        <!-- ********************************** -->
        <?php if ($this->AppUser->hasDomainGeneral()) { ?>
            <div class="widget-actions <?= ($conf['attr'] && $conf['attr'] == 'edit') ? '' : 'hidden' ?>" id="elemAssetAttr-actions">
                <div class="widget-action" data-app-action-key="elemAssetAttr-view-actions">
                    <a href="javascript:void(0);" class="btn btn-success" data-app-action-key="elemAssetAttr-edit"><i class="fa fa-edit"></i>　編集</a>
                </div>
                <div class="widget-action hidden" data-app-action-key="elemAssetAttr-edit-actions">
                    <a href="javascript:void(0);" class="btn btn-default" data-app-action-key="elemAssetAttr-cancel"><i class="fa fa-times"></i>　キャンセル</a>
                    <a href="javascript:void(0);" class="btn btn-primary" data-app-action-key="elemAssetAttr-save"><i class="fa fa-save"></i>　保存</a>
                </div>
            </div>
        <?php } ?>

        <!-- elem-asset-attr-edit-id -->
        <div class="hidden" id="elem-asset-attr-edit-id">

            <header>
                属性入力
            </header>

            <fieldset>
                <section>
                    <!-- GWアドレス -->
                    <label class="input">
                        <input type="text" name="elem_asset_attr.gw" id="elemAssetAttr_gw" class="input-sm"
                               data-app-form="form-elem-asset-attr"
                               maxlength=15
                               placeholder="GWアドレス - 最大15文字"
                               disabled="disabled">
                    </label>
                </section>
                <section>
                    <!-- IPアドレス -->
                    <label class="input">
                        <input type="text" name="elem_asset_attr.ip" id="elemAssetAttr_ip" class="input-sm"
                               data-app-form="form-elem-asset-attr"
                               maxlength=15
                               placeholder="IPアドレス - 最大15文字"
                               disabled="disabled">
                    </label>
                </section>
                <section>
                    <!-- IPアドレス(v6) -->
                    <label class="input">
                        <input type="text" name="elem_asset_attr.ip_v6" id="elemAssetAttr_ip_v6" class="input-sm"
                               data-app-form="form-elem-asset-attr"
                               maxlength=39
                               placeholder="IPアドレス(v6) - 最大39文字"
                               disabled="disabled">
                    </label>
                </section>
                <section>
                    <!-- IPアドレス(無線) -->
                    <label class="input">
                        <input type="text" name="elem_asset_attr.ip_wifi" id="elemAssetAttr_ip_wifi" class="input-sm"
                               data-app-form="form-elem-asset-attr"
                               maxlength=15
                               placeholder="IPアドレス(無線) - 最大15文字"
                               disabled="disabled">
                    </label>
                </section>
                <section>
                    <!-- MACアドレス -->
                    <label class="input">
                        <input type="text" name="elem_asset_attr.mac" id="elemAssetAttr_mac" class="input-sm"
                               data-app-form="form-elem-asset-attr"
                               maxlength=18
                               placeholder="MACアドレス - 最大18文字"
                               disabled="disabled">
                    </label>
                </section>
                <section>
                    <!-- MACアドレス（無線） -->
                    <label class="input">
                        <input type="text" name="elem_asset_attr.mac_wifi" id="elemAssetAttr_mac_wifi" class="input-sm"
                               data-app-form="form-elem-asset-attr"
                               maxlength=18
                               placeholder="MACアドレス(無線) - 最大18文字"
                               disabled="disabled">
                    </label>
                </section>
                <section>
                    <!-- サブネット -->
                    <label class="input">
                        <input type="text" name="elem_asset_attr.subnet" id="elemAssetAttr_subnet" class="input-sm"
                               data-app-form="form-elem-asset-attr"
                               maxlength=15
                               placeholder="サブネット - 最大15文字"
                               disabled="disabled">
                    </label>
                </section>
                <section>
                    <!-- DNS -->
                    <label class="input">
                        <input type="text" name="elem_asset_attr.dns" id="elemAssetAttr_dns" class="input-sm"
                               data-app-form="form-elem-asset-attr"
                               maxlength=15
                               placeholder="DNS - 最大15文字"
                               disabled="disabled">
                    </label>
                </section>
                <section>
                    <!-- DHCP -->
                    <label class="input">
                        <input type="text" name="elem_asset_attr.dhcp" id="elemAssetAttr_dhcp" class="input-sm"
                               data-app-form="form-elem-asset-attr"
                               maxlength=15
                               placeholder="DHCP - 最大15文字"
                               disabled="disabled">
                    </label>
                </section>
                <section>
                    <!-- OS -->
                    <label class="input">
                        <input type="text" name="elem_asset_attr.os" id="elemAssetAttr_os" class="input-sm"
                               data-app-form="form-elem-asset-attr"
                               maxlength=80
                               placeholder="OS - 最大80文字"
                               disabled="disabled">
                    </label>
                </section>
                <section>
                    <!-- OSバージョン -->
                    <label class="input">
                        <input type="text" name="elem_asset_attr.os_version" id="elemAssetAttr_os_version" class="input-sm"
                               data-app-form="form-elem-asset-attr"
                               maxlength=80
                               placeholder="OSバージョン - 最大80文字"
                               disabled="disabled">
                    </label>
                </section>
                <section>
                    <!-- Office -->
                    <label class="input">
                        <input type="text" name="elem_asset_attr.office" id="elemAssetAttr_office" class="input-sm"
                               data-app-form="form-elem-asset-attr"
                               maxlength=80
                               placeholder="Office - 最大80文字"
                               disabled="disabled">
                    </label>
                </section>
                <section>
                    <!-- Office（補足） -->
                    <label class="input">
                        <input type="text" name="elem_asset_attr.office_remarks" id="elemAssetAttr_office_remarks" class="input-sm"
                               data-app-form="form-elem-asset-attr"
                               maxlength=80
                               placeholder="Office(補足) - 最大256文字"
                               disabled="disabled">
                    </label>
                </section>
                <section>
                    <!-- ソフトウェア -->
                    <label class="textarea textarea-resizable">
                        <textarea name="elem_asset_attr.software" id="elemAssetAttr_software" rows="4" class="custom-scroll"
                                  data-app-form="form-elem-asset-attr" placeholder="ソフトウェア"
                                  disabled="disabled"></textarea>
                    </label>
                </section>
                <section>
                    <!-- IMEI番号 -->
                    <label class="input">
                        <input type="text" name="elem_asset_attr.imei_no" id="elemAssetAttr_imei_no" class="input-sm"
                               data-app-form="form-elem-asset-attr"
                               maxlength=30
                               placeholder="IMEI番号 - 最大30文字"
                               disabled="disabled">
                    </label>
                </section>
                <section>
                    <!-- 証明書番号 -->
                    <label class="input">
                        <input type="text" name="elem_asset_attr.certificate_no" id="elemAssetAttr_certificate_no" class="input-sm"
                               data-app-form="form-elem-asset-attr"
                               maxlength=30
                               placeholder="証明書番号 - 最大30文字"
                               disabled="disabled">
                    </label>
                </section>
                <section>
                    <!-- 申請番号（購入申請など） -->
                    <label class="input">
                        <input type="text" name="elem_asset_attr.apply_no" id="elemAssetAttr_apply_no" class="input-sm"
                               data-app-form="form-elem-asset-attr"
                               maxlength=60
                               placeholder="申請番号（購入申請など） - 最大60文字"
                               disabled="disabled">
                    </label>
                </section>
                <section>
                    <!-- 保管場所 -->
                    <label class="input">
                        <input type="text" name="elem_asset_attr.place" id="elemAssetAttr_place" class="input-sm"
                               data-app-form="form-elem-asset-attr"
                               maxlength=80
                               placeholder="保管場所 - 最大80文字"
                               disabled="disabled">
                    </label>
                </section>
                <section>
                    <!-- 購入日 -->
                    <label class="input">
                        <i class="icon-append fa fa-calendar"></i>
                        <input type="text" name="elem_asset_attr.purchase_date" id="elemAssetAttr_purchase_date" class="input-sm datepicker"
                            data-app-form="form-elem-asset-attr"
                            data-dateformat="yy/mm/dd"
                            maxlength=10
                            placeholder="購入日 - yyyy/mm/dd形式"
                            disabled="disabled">
                    </label>
                </section>
                <section>
                    <!-- サポート期間（年） -->
                    <label class="input">
                        <input type="number" name="elem_asset_attr.support_term_year" id="elemAssetAttr_support_term_year" class="input-sm"
                               data-app-form="form-elem-asset-attr"
                               min="0"
                               max="99"
                               placeholder="サポート期間(年)を入力してください">
                    </label>
                </section>
                <section>
                    <!-- 付属マウス -->
                    <label class="input">
                        <input type="text" name="elem_asset_attr.at_mouse" id="elemAssetAttr_at_mouse" class="input-sm"
                               data-app-form="form-elem-asset-attr"
                               maxlength=80
                               placeholder="付属マウス - 最大80文字"
                               disabled="disabled">
                    </label>
                </section>
                <section>
                    <!-- 付属キーボード -->
                    <label class="input">
                        <input type="text" name="elem_asset_attr.at_keyboard" id="elemAssetAttr_at_keyboard" class="input-sm"
                               data-app-form="form-elem-asset-attr"
                               maxlength=80
                               placeholder="付属キーボード - 最大80文字"
                               disabled="disabled">
                    </label>
                </section>
                <section>
                    <!-- 付属AC -->
                    <label class="input">
                        <input type="text" name="elem_asset_attr.at_ac" id="elemAssetAttr_at_ac" class="input-sm"
                               data-app-form="form-elem-asset-attr"
                               maxlength=80
                               placeholder="付属AC - 最大80文字"
                               disabled="disabled">
                    </label>
                </section>
                <section>
                    <!-- 付属マニュアル類 -->
                    <label class="input">
                        <input type="text" name="elem_asset_attr.at_manual" id="elemAssetAttr_at_manual" class="input-sm"
                               data-app-form="form-elem-asset-attr"
                               maxlength=80
                               placeholder="付属マニュアル類 - 最大80文字"
                               disabled="disabled">
                    </label>
                </section>
                <section>
                    <!-- 付属その他 -->
                    <label class="textarea textarea-resizable">
                        <textarea name="elem_asset_attr.at_other" id="elemAssetAttr_at_other" rows="4" class="custom-scroll"
                                  data-app-form="form-elem-asset-attr" placeholder="付属その他"
                                  disabled="disabled"></textarea>
                    </label>
                </section>
                <section>
                    <!-- 管理ユーザー（ローカルPC） -->
                    <label class="input">
                        <input type="text" name="elem_asset_attr.local_user" id="elemAssetAttr_local_user" class="input-sm"
                               data-app-form="form-elem-asset-attr"
                               maxlength=30
                               placeholder="管理ユーザー（ローカル） - 最大30文字"
                               disabled="disabled">
                    </label>
                </section>
                <section>
                    <!-- 管理パスワード（ローカルPC） -->
                    <label class="input">
                        <input type="text" name="elem_asset_attr.local_password" id="elemAssetAttr_local_password" class="input-sm"
                               data-app-form="form-elem-asset-attr"
                               maxlength=30
                               placeholder="管理パスワード（ローカル） - 最大30文字"
                               disabled="disabled">
                    </label>
                </section>
                <section>
                    <!-- UEFIパスワード(supervisor) -->
                    <label class="input">
                        <input type="text" name="elem_asset_attr.uefi_password" id="elemAssetAttr_uefi_password" class="input-sm"
                               data-app-form="form-elem-asset-attr"
                               maxlength=30
                               placeholder="UEFIパスワード(supervisor) - 最大30文字"
                               disabled="disabled">
                    </label>
                </section>
                <section>
                    <!-- UEFIパスワード(user) -->
                    <label class="input">
                        <input type="text" name="elem_asset_attr.uefi_user_password" id="elemAssetAttr_uefi_user_password" class="input-sm"
                               data-app-form="form-elem-asset-attr"
                               maxlength=30
                               placeholder="UEFIパスワード(user) - 最大30文字"
                               disabled="disabled">
                    </label>
                </section>
                <section>
                    <!-- HDDパスワード(supervisor) -->
                    <label class="input">
                        <input type="text" name="elem_asset_attr.hdd_password" id="elemAssetAttr_hdd_password" class="input-sm"
                               data-app-form="form-elem-asset-attr"
                               maxlength=30
                               placeholder="HDDパスワード(supervisor)  - 最大30文字"
                               disabled="disabled">
                    </label>
                </section>
                <section>
                    <!-- HDDパスワード(user) -->
                    <label class="input">
                        <input type="text" name="elem_asset_attr.hdd_user_password" id="elemAssetAttr_hdd_user_password" class="input-sm"
                               data-app-form="form-elem-asset-attr"
                               maxlength=30
                               placeholder="HDDパスワード(user) - 最大30文字"
                               disabled="disabled">
                    </label>
                </section>

            </fieldset>

            <!-- End elem-asset-attr-edit-id -->
        </div>

        <!-- End input block -->
    </div>

    <!-- elem-asset-attr-view-id -->
    <div id="elem-asset-attr-view-id">

        <header>
            属性情報
        </header>

        <fieldset>
            <section>
                <dl class="dl-horizontal">
                    <dt>GWアドレス：</dt>
                        <dd name="elem_assetview_attr.gw" id="elemAssetviewAttr_gw" data-app-form="form-elem-assetview-attr"></dd>
                    <dt>IPアドレス：</dt>
                        <dd name="elem_assetview_attr.ip" id="elemAssetviewAttr_ip" data-app-form="form-elem-assetview-attr"></dd>
                    <dt>IPアドレス（v6）：</dt>
                        <dd name="elem_assetview_attr.ip_v6" id="elemAssetviewAttr_ip_v6" data-app-form="form-elem-assetview-attr"></dd>
                    <dt>IPアドレス（無線）：</dt>
                        <dd name="elem_assetview_attr.ip_wifi" id="elemAssetviewAttr_ip_wifi" data-app-form="form-elem-assetview-attr"></dd>
                    <dt>MACアドレス：</dt>
                        <dd name="elem_assetview_attr.mac" id="elemAssetviewAttr_mac" data-app-form="form-elem-assetview-attr"></dd>
                    <dt>MACアドレス（無線）：</dt>
                        <dd name="elem_assetview_attr.mac_wifi" id="elemAssetviewAttr_mac_wifi" data-app-form="form-elem-assetview-attr"></dd>
                    <dt>サブネット：</dt>
                        <dd name="elem_assetview_attr.subnet" id="elemAssetviewAttr_subnet" data-app-form="form-elem-assetview-attr"></dd>
                    <dt>DNS：</dt>
                        <dd name="elem_assetview_attr.dns" id="elemAssetviewAttr_dns" data-app-form="form-elem-assetview-attr"></dd>
                    <dt>DHCP：</dt>
                        <dd name="elem_assetview_attr.dhcp" id="elemAssetviewAttr_dhcp" data-app-form="form-elem-assetview-attr"></dd>
                </dl>
            </section>
            <section>
                <dl class="dl-horizontal">
                    <dt>OS：</dt>
                        <dd name="elem_assetview_attr.os" id="elemAssetviewAttr_os" data-app-form="form-elem-assetview-attr"></dd>
                    <dt>OS（バージョン）：</dt>
                        <dd name="elem_assetview_attr.os_version" id="elemAssetviewAttr_os_version" data-app-form="form-elem-assetview-attr"></dd>
                    <dt>Office：</dt>
                        <dd name="elem_assetview_attr.office" id="elemAssetviewAttr_office" data-app-form="form-elem-assetview-attr"></dd>
                    <dt>Office（補足）：</dt>
                        <dd name="elem_assetview_attr.office_remarks" id="elemAssetviewAttr_office_remarks" data-app-form="form-elem-assetview-attr"></dd>
                    <dt>ソフトウェア：</dt>
                        <dd name="elem_assetview_attr.software" id="elemAssetviewAttr_software" data-app-form="form-elem-assetview-attr"></dd>
                </dl>
            </section>
            <section>
                <dl class="dl-horizontal">
                    <dt>IMEI番号：</dt>
                        <dd name="elem_assetview_attr.imei_no" id="elemAssetviewAttr_imei_no" data-app-form="form-elem-assetview-attr"></dd>
                    <dt>証明書番号：</dt>
                        <dd name="elem_assetview_attr.certificate_no" id="elemAssetviewAttr_certificate_no" data-app-form="form-elem-assetview-attr"></dd>
                    <dt>購入申請番号：</dt>
                        <dd name="elem_assetview_attr.apply_no" id="elemAssetviewAttr_apply_no" data-app-form="form-elem-assetview-attr"></dd>
                    <dt>保管場所：</dt>
                        <dd name="elem_assetview_attr.place" id="elemAssetviewAttr_place" data-app-form="form-elem-assetview-attr"></dd>
                    <dt>購入日：</dt>
                    <dd name="elem_assetview_attr.purchase_date" id="elemAssetviewAttr_purchase_date" data-app-form="form-elem-assetview-attr"></dd>
                <dt>サポート期間（年）：</dt>
                    <dd name="elem_assetview_attr.support_term_year" id="elemAssetviewAttr_support_term_year" data-app-form="form-elem-assetview-attr"></dd>
                </dl>
            </section>
            <section>
                <dl class="dl-horizontal">
                    <dt>付属マウス：</dt>
                        <dd name="elem_assetview_attr.at_mouse" id="elemAssetviewAttr_at_mouse" data-app-form="form-elem-assetview-attr"></dd>
                    <dt>付属キーボード：</dt>
                        <dd name="elem_assetview_attr.at_keyboard" id="elemAssetviewAttr_at_keyboard" data-app-form="form-elem-assetview-attr"></dd>
                    <dt>付属AC：</dt>
                        <dd name="elem_assetview_attr.at_ac" id="elemAssetviewAttr_at_ac" data-app-form="form-elem-assetview-attr"></dd>
                    <dt>付属マニュアル類：</dt>
                        <dd name="elem_assetview_attr.at_manual" id="elemAssetviewAttr_at_manual" data-app-form="form-elem-assetview-attr"></dd>
                    <dt>付属その他：</dt>
                        <dd name="elem_assetview_attr.at_other" id="elemAssetviewAttr_at_other" data-app-form="form-elem-assetview-attr"></dd>
                </dl>
            </section>
            <section>
                <dl class="dl-horizontal">
                    <dt>管理ユーザー(local)：</dt>
                        <dd name="elem_assetview_attr.local_user" id="elemAssetviewAttr_local_user" data-app-form="form-elem-assetview-attr"></dd>
                    <dt>管理パスワード(local)：</dt>
                        <dd name="elem_assetview_attr.local_password" id="elemAssetviewAttr_local_password" data-app-form="form-elem-assetview-attr"></dd>
                    <dt>UEFIパスワード(supervisor)：</dt>
                        <dd name="elem_assetview_attr.uefi_password" id="elemAssetviewAttr_uefi_password" data-app-form="form-elem-assetview-attr"></dd>
                    <dt>UEFIパスワード(user)：</dt>
                        <dd name="elem_assetview_attr.uefi_user_password" id="elemAssetviewAttr_uefi_user_password" data-app-form="form-elem-assetview-attr"></dd>
                    <dt>HDDパスワード(supervisor)：</dt>
                        <dd name="elem_assetview_attr.hdd_password" id="elemAssetviewAttr_hdd_password" data-app-form="form-elem-assetview-attr"></dd>
                    <dt>HDDパスワード(user)：</dt>
                        <dd name="elem_assetview_attr.hdd_user_password" id="elemAssetviewAttr_hdd_user_password" data-app-form="form-elem-assetview-attr"></dd>
                </dl>
            </section>
            <section>
                <dl class="dl-horizontal">
                    <dt>登録日時：</dt><dd name="elem_assetview_attr.created_at" id="elemAssetviewAttr_created_at" data-app-form="form-elem-assetview-attr"></dd>
                    <dt>更新日時：</dt><dd name="elem_assetview_attr.modified_at" id="elemAssetviewAttr_modified_at" data-app-form="form-elem-assetview-attr"></dd>
                    <dt>更新者：</dt><dd name="elem_assetview_attr.modified_user_name" id="elemAssetviewAttr_modified_user_name" data-app-form="form-elem-assetview-attr"></dd>
                </dl>
            </section>
        </fieldset>

        <!-- End elem-asset-attr-view-id -->
    </div>

<!-- End form -->
<?= $this->Form->end() ?>


