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

?>
<!-- NAVIGATION AREA -->
<aside id="left-panel">

    <!-- USER INFO -->
    <div class="login-info">
    <span>
      <a href="javascript:void(0);" id="show-shortcut" data-action="toggleShortcut">
        <img src="/img/account.png" alt="ユーザー" class="online"/>
        <span>
          <?= $this->AppUser->kname() ?>
        </span>
        <i class="fa fa-angle-down"></i>
      </a> 
    </span>
    </div>
    <!-- END USER INFO -->

    <!-- NAVIGATION : This navigation is also responsive-->
    <nav id="side-menu">
        <ul>

            <!-- イベント -->
            <?php if ($this->AppUser->allowSapp($this->App->conf('WNote.DB.Sapps.Kname.event'))) { ?>
            <li class="">
                <a href="#" title="Dashboard"><i class="fa fa-lg fa-fw fa-star"></i> <span
                            class="menu-item-parent">イベント</span></a>
                <ul>
                    <li class=""><a href="index.html" title="Dashboard"><span class="menu-item-parent">準備中・・・</span></a>
                    </li>
                    <li class=""><a href="dashboard-social.html" title="Dashboard"><span
                                    class="menu-item-parent">準備中・・・</span></a></li>
                </ul>
            </li>
            <?php } ?>

            <!-- 入庫 -->
            <?php if ($this->AppUser->allowSapp($this->App->conf('WNote.DB.Sapps.Kname.instock'))) { ?>
            <li class="">
                <a href="#">
                    <i class="fa fa-lg fa-fw fa-gear txt-color-blue"></i> <span class="menu-item-parent">入庫</span>
                </a>
                <ul>
                    <?= $this->appUser->menuGeneral('/instock/instock-plans/list-new', '入庫予定登録', 'fa-pencil-square-o') ?>
                    <?= $this->appUser->menuGeneral('/instock/instock-plans/list'    , '入庫予定一覧', 'fa-list') ?>
                    <?= $this->appUser->menuGeneral('/instock/instocks/index'        , '入庫'        , 'fa-cubes') ?>
                    <?= $this->app->menu('/instock/instocks/search'                  , '入庫検索'    , 'fa-search') ?>
                </ul>
            </li>
            <?php } ?>

            <!-- 出庫 -->
            <?php if ($this->AppUser->allowSapp($this->App->conf('WNote.DB.Sapps.Kname.picking'))) { ?>
            <li class="">
                <a href="#"><i class="fa fa-lg fa-fw fa-truck txt-color-blue"></i> <span
                            class="menu-item-parent">出庫</span></a>
                <ul>
                    <?= $this->appUser->menuGeneral('/picking/picking-plans/entry', '出庫依頼登録', 'fa-pencil-square-o') ?>
                    <?= $this->appUser->menuGeneral('/picking/picking-plans/list' , '出庫予定一覧', 'fa-list') ?>
                    <?= $this->appUser->menuGeneral('/picking/pickings/index'     , '出庫'        , 'fa-cubes') ?>
                    <?= $this->app->menu('/picking/pickings/search'               , '出庫検索'    , 'fa-search') ?>
                </ul>
            </li>
            <?php } ?>

            <!-- 在庫 -->
            <?php if ($this->AppUser->allowSapp($this->App->conf('WNote.DB.Sapps.Kname.stock'))) { ?>
            <li class="">
                <a href="#"><i class="fa fa-lg fa-fw fa-briefcase txt-color-blue"></i> <span
                            class="menu-item-parent">在庫</span></a>
                <ul>
                    <?= $this->app->menu('/stock/stocks/search' , '在庫検索', 'fa-search') ?>
                    <?= $this->app->menu('/stock/stocks/summary', '在庫集計', 'fa-list') ?>
                </ul>
            </li>
            <?php } ?>

            <!-- 棚卸 -->
            <?php if ($this->AppUser->allowSapp($this->App->conf('WNote.DB.Sapps.Kname.stocktake'))) { ?>
            <li class="">
                <a href="#"><i class="fa fa-lg fa-fw fa-pencil txt-color-blue"></i> <span
                            class="menu-item-parent">棚卸</span></a>
                <ul>
                    <?= $this->appUser->menuGeneral('/stocktake/stocktakes/entry' , '棚卸登録', 'fa-pencil-square-o') ?>
                    <?= $this->appUser->menuGeneral('/stocktake/stocktakes/work'  , '棚卸実施', 'fa-wrench') ?>
                    <?= $this->app->menu('/stocktake/stocktakes/search'           , '棚卸検索', 'fa-search') ?>
                </ul>
            </li>
            <?php } ?>

            <!-- 資産 -->
            <?php if ($this->AppUser->allowSapp($this->App->conf('WNote.DB.Sapps.Kname.asset'))) { ?>
            <li class="">
                <a href="#"><i class="fa fa-lg fa-fw fa-suitcase txt-color-blue"></i> <span
                            class="menu-item-parent">資産</span></a>
                <ul>
                    <?= $this->app->menu('/asset/assets/search'                   , '資産検索', 'fa-search') ?>
                    <?= $this->app->menu('/asset/assets/summary'                  , '資産集計', 'fa-list') ?>
                    <?= $this->appUser->menuGeneral('/asset/assets/entry'         , '資産登録', 'fa-pencil-square-o') ?>
                    <?= $this->appUser->menuGeneral('/asset/assets/abrogate_plans', '廃棄予定', 'fa-list') ?>
                    <?= $this->app->menu('/asset/assets/abrogates'                , '廃棄検索', 'fa-search') ?>
                </ul>
            </li>
            <?php } ?>

            <!-- 修理・交換 -->
            <?php if ($this->AppUser->allowSapp($this->App->conf('WNote.DB.Sapps.Kname.recycle'))) { ?>
            <li class="">
                <a href="#"><i class="fa fa-lg fa-fw fa-recycle txt-color-blue"></i> <span
                            class="menu-item-parent">修理・交換</span></a>
                <ul>
                    <?= $this->appUser->menuGeneral('/repair/repairs/entry' , '修理登録', 'fa-pencil-square-o') ?>
                    <?= $this->app->menu('/repair/repairs/list'             , '修理一覧', 'fa-list') ?>
                    <?= $this->app->menu('/exchange/exchanges/list'         , '交換一覧', 'fa-list') ?>
                </ul>
            </li>
            <?php } ?>

            <!-- 貸出・返却 -->
            <?php if ($this->AppUser->allowSapp($this->App->conf('WNote.DB.Sapps.Kname.rental'))) { ?>
            <li class="">
                <a href="#"><i class="fa fa-lg fa-fw fa-share-alt txt-color-blue"></i> <span class="menu-item-parent">貸出・返却</span></a>
                <ul>
                    <?= $this->appUser->menuGeneral('/rental/rentals/rental', '貸出', 'fa-book') ?>
                    <?= $this->appUser->menuGeneral('/rental/rentals/back'  , '返却', 'fa-reply') ?>
                    <?= $this->app->menu('/rental/rentals/search'           , '検索', 'fa-search') ?>
                </ul>
            </li>
            <?php } ?>

            <!-- マスタ（一般） -->
            <?php if ($this->AppUser->hasDomainGeneral()) { ?>
            <li class="">
                <a href="#">
                    <i class="fa fa-lg fa-fw fa-gear txt-color-blue"></i> <span class="menu-item-parent">マスタ（一般）</span>
                </a>
                <ul>
                    <?= $this->app->menu('/master/general/companies' , '仕入先/メーカー', 'fa-building-o') ?>
                    <?= $this->app->menu('/master/general/products'  , '製品/モデル'    , 'fa-shopping-cart') ?>
                    <?= $this->app->menu('/master/general/cpus'      , 'CPU'            , 'fa-microchip') ?>
                    <?= $this->app->menu('/master/general/deliveries', '配送先'         , 'fa-truck') ?>
                </ul>
            </li>
            <?php } ?>

            <!-- マスタ（管理） -->
            <?php if ($this->AppUser->hasDomainAdmin()) { ?>
            <li class="">
                <a href="#">
                    <i class="fa fa-lg fa-fw fa-gear txt-color-blue"></i> <span class="menu-item-parent">マスタ（管理）</span>
                </a>
                <ul>
                    <?= $this->app->menu('/master/admin/categories'     , '資産カテゴリ'    , 'fa-book') ?>
                    <?= $this->app->menu('/master/admin/classifications', '資産分類'        , 'fa-tag') ?>
                    <?= $this->app->menu('/master/admin/customers'      , '資産管理会社'    , 'fa-building') ?>
                    <?= $this->app->menu('/master/admin/organizations'  , '資産管理グループ', 'fa-sitemap') ?>
                    <?= $this->app->menu('/master/admin/users'          , '資産利用者'      , 'fa-users') ?>
                </ul>
            </li>
            <?php } ?>

            <!-- マスタ（システム） -->
            <?php if ($this->AppUser->hasAdmin()) { ?>
            <li class="">
                <a href="#">
                    <i class="fa fa-lg fa-fw fa-gear txt-color-blue"></i> <span class="menu-item-parent">マスタ（システム）</span>
                </a>
                <ul>
                    <?= $this->app->menu('/master/system/domains', 'ドメイン管理', 'fa-object-group') ?>
                    <?= $this->app->menu('/master/system/susers' , 'ユーザー管理', 'fa-users') ?>
                </ul>
            </li>
            <?php } ?>

            <!-- End Navigation List -->
        </ul>
    </nav>
    <!-- END NAVIGATION -->

    <span class="minifyme" data-action="minifyMenu">
    <i class="fa fa-arrow-circle-left hit"></i> 
  </span>

</aside>
<!-- END NAVIGATION AREA -->

<!-- SHORTCUT AREA : With large tiles (activated via clicking user name tag) -->
<div id="shortcut">
    <ul>
        <li>
            <a href="profile.html" class="jarvismetro-tile big-cubes selected bg-color-pinkDark"> <span class="iconbox"> <i
                            class="fa fa-user fa-4x"></i> <span>My Profile </span> </span> </a>
        </li>
    </ul>
</div>
<!-- END SHORTCUT AREA -->

