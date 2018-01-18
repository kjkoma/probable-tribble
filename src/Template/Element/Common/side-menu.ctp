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
    <nav>
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
                    <li class=""><a href="/instock/instock-plans/list-new" title="入庫予定登録"><i
                                    class="fa fa-lg fa-fw fa-pencil-square-o"></i> <span class="menu-item-parent">入庫予定登録</span>
                        </a>
                    </li>
                    <li class=""><a href="/instock/instock-plans/list" title="入庫予定一覧"><i
                                    class="fa fa-lg fa-fw fa-list"></i> <span class="menu-item-parent">入庫予定一覧</span>
                        </a>
                    </li>
                    <li class=""><a href="/instock/instocks/index" title="入庫"><i
                                    class="fa fa-lg fa-fw fa-cubes"></i> <span class="menu-item-parent">入庫</span>
                        </a>
                    </li>
                    <li class=""><a href="/instock/instocks/search" title="入庫検索"><i
                                    class="fa fa-lg fa-fw fa-search"></i> <span class="menu-item-parent">入庫検索</span>
                        </a>
                    </li>
                </ul>
            </li>
            <?php } ?>

            <!-- 出庫 -->
            <?php if ($this->AppUser->allowSapp($this->App->conf('WNote.DB.Sapps.Kname.picking'))) { ?>
            <li class="">
                <a href="#"><i class="fa fa-lg fa-fw fa-truck txt-color-blue"></i> <span
                            class="menu-item-parent">出庫</span></a>
                <ul>
                    <li class=""><a href="/picking/picking-plans/entry" title="出庫依頼登録"><i
                                    class="fa fa-lg fa-fw fa-pencil-square-o"></i> <span class="menu-item-parent">出庫依頼登録</span>
                        </a>
                    </li>
                    <li class=""><a href="/picking/picking-plans/list" title="出庫予定一覧"><i
                                    class="fa fa-lg fa-fw fa-list"></i> <span class="menu-item-parent">出庫予定一覧</span>
                        </a>
                    </li>
                    <li class=""><a href="/picking/pickings/index" title="出庫"><i
                                    class="fa fa-lg fa-fw fa-cubes"></i> <span class="menu-item-parent">出庫</span>
                        </a>
                    </li>
                    <li class=""><a href="/picking/pickings/search" title="出庫検索"><i
                                    class="fa fa-lg fa-fw fa-search"></i> <span class="menu-item-parent">出庫検索</span>
                        </a>
                    </li>
                </ul>
            </li>
            <?php } ?>

            <!-- 在庫 -->
            <?php if ($this->AppUser->allowSapp($this->App->conf('WNote.DB.Sapps.Kname.stock'))) { ?>
            <li class="">
                <a href="#"><i class="fa fa-lg fa-fw fa-briefcase txt-color-blue"></i> <span
                            class="menu-item-parent">在庫</span></a>
                <ul>
                    <li class=""><a href="/stock/stocks/search" title="在庫検索"><i
                                    class="fa fa-lg fa-fw fa-pencil-square-o"></i> <span class="menu-item-parent">在庫検索</span>
                        </a>
                    </li>
                    <li class=""><a href="/stock/stocks/summary" title="在庫集計"><i
                                    class="fa fa-lg fa-fw fa-list"></i> <span class="menu-item-parent">在庫集計</span>
                        </a>
                    </li>
                    <li class=""><a href="/abrogate/abrogates/entry" title="廃棄登録"><i
                                    class="fa fa-lg fa-fw fa-pencil-square-o"></i> <span class="menu-item-parent">廃棄登録</span>
                        </a>
                    </li>
                    <li class=""><a href="/abrogate/abrogates/search" title="廃棄検索"><i
                                    class="fa fa-lg fa-fw fa-search"></i> <span class="menu-item-parent">廃棄検索</span>
                        </a>
                    </li>
                </ul>
            </li>
            <?php } ?>

            <!-- 棚卸 -->
            <?php if ($this->AppUser->allowSapp($this->App->conf('WNote.DB.Sapps.Kname.stocktake'))) { ?>
            <li class="">
                <a href="#"><i class="fa fa-lg fa-fw fa-pencil txt-color-blue"></i> <span
                            class="menu-item-parent">棚卸</span></a>
                <ul>
                    <li class=""><a href="/stocktake/stocktakes/entry" title="棚卸登録"><i
                                    class="fa fa-lg fa-fw fa-pencil-square-o"></i> <span class="menu-item-parent">棚卸登録</span>
                        </a>
                    </li>
                    <li class=""><a href="/stocktake/stocktakes/list" title="棚卸一覧"><i
                                    class="fa fa-lg fa-fw fa-list"></i> <span class="menu-item-parent">棚卸一覧</span>
                        </a>
                    </li>
                </ul>
            </li>
            <?php } ?>

            <!-- 資産 -->
            <?php if ($this->AppUser->allowSapp($this->App->conf('WNote.DB.Sapps.Kname.asset'))) { ?>
            <li class="">
                <a href="#"><i class="fa fa-lg fa-fw fa-suitcase txt-color-blue"></i> <span
                            class="menu-item-parent">資産</span></a>
                <ul>
                    <li class=""><a href="/asset/assets/search" title="資産検索"><i
                                    class="fa fa-lg fa-fw fa-search"></i> <span class="menu-item-parent">資産検索</span>
                        </a>
                    </li>
                    <li class=""><a href="/asset/assets/summary" title="資産集計"><i
                                    class="fa fa-lg fa-fw fa-list"></i> <span class="menu-item-parent">資産集計</span>
                        </a>
                    </li>
                </ul>
            </li>
            <?php } ?>

            <!-- 修理・交換 -->
            <?php if ($this->AppUser->allowSapp($this->App->conf('WNote.DB.Sapps.Kname.recycle'))) { ?>
            <li class="">
                <a href="#"><i class="fa fa-lg fa-fw fa-recycle txt-color-blue"></i> <span
                            class="menu-item-parent">修理・交換</span></a>
                <ul>
                    <li class=""><a href="/repair/repairs/list" title="修理一覧"><i
                                    class="fa fa-lg fa-fw fa-list"></i> <span class="menu-item-parent">修理一覧</span>
                        </a>
                    </li>
                    <li class=""><a href="/exchange/exchanges/list" title="交換一覧"><i
                                    class="fa fa-lg fa-fw fa-list"></i> <span class="menu-item-parent">交換一覧</span>
                        </a>
                    </li>
                </ul>
            </li>
            <?php } ?>

            <!-- 貸出・返却 -->
            <?php if ($this->AppUser->allowSapp($this->App->conf('WNote.DB.Sapps.Kname.rental'))) { ?>
            <li class="">
                <a href="#"><i class="fa fa-lg fa-fw fa-share-alt txt-color-blue"></i> <span class="menu-item-parent">貸出・返却</span></a>
                <ul>
                    <li class=""><a href="/rental/rentals/entry" title="貸出依頼登録"><i
                                    class="fa fa-lg fa-fw fa-mail-forward"></i> <span class="menu-item-parent">貸出依頼登録</span>
                        </a>
                    </li>
                    <li class=""><a href="/rental/rentals/search" title="貸出検索"><i
                                    class="fa fa-lg fa-fw fa-search"></i> <span class="menu-item-parent">貸出検索</span>
                        </a>
                    </li>
                    <li class=""><a href="/back/backs/entry" title="返却依頼登録"><i
                                    class="fa fa-lg fa-fw fa-reply"></i> <span class="menu-item-parent">返却依頼登録</span>
                        </a>
                    </li>
                    <li class=""><a href="/back/backs/search" title="返却検索"><i
                                    class="fa fa-lg fa-fw fa-search"></i> <span class="menu-item-parent">返却検索</span>
                        </a>
                    </li>
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
                    <li class="">
                        <a href="/master/general/companies" title="企業">
                            <i class="fa fa-lg fa-fw fa-building-o"></i> <span class="menu-item-parent">仕入先/メーカー</span>
                        </a>
                    </li>
                    <li class="">
                        <a href="/master/general/products" title="製品">
                            <i class="fa fa-lg fa-fw fa-shopping-cart"></i> <span class="menu-item-parent">製品/モデル</span>
                        </a>
                    </li>
                    <li class="">
                        <a href="/master/general/cpus" title="CPU">
                            <i class="fa fa-lg fa-fw fa-microchip"></i> <span class="menu-item-parent">CPU</span>
                        </a>
                    </li>
                    <li class="">
                        <a href="/master/general/deliveries" title="配送先">
                            <i class="fa fa-lg fa-fw fa-truck"></i> <span class="menu-item-parent">配送先</span>
                        </a>
                    </li>
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
                    <li class=""><a href="/master/admin/categories" title="資産カテゴリ"><i
                                    class="fa fa-lg fa-fw fa-book"></i> <span class="menu-item-parent">資産カテゴリ</span>
                        </a>
                    </li>
                    <li class="">
                        <a href="/master/admin/classifications" title="資産分類">
                            <i class="fa fa-lg fa-fw fa-tag"></i> <span class="menu-item-parent">資産分類</span>
                        </a>
                    </li>
                    <li class="">
                        <a href="/master/admin/customers" title="資産管理会社">
                            <i class="fa fa-lg fa-fw fa-building"></i> <span class="menu-item-parent">資産管理会社</span>
                        </a>
                    </li>
                    <li class="">
                        <a href="/master/admin/organizations" title="資産管理グループ">
                            <i class="fa fa-lg fa-fw fa-sitemap"></i> <span class="menu-item-parent">資産管理グループ</span>
                        </a>
                    </li>
                    <li class="">
                        <a href="/master/admin/users" title="資産利用者">
                            <i class="fa fa-lg fa-fw fa-users"></i><span class="menu-item-parent">資産利用者</span>
                        </a>
                    </li>
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
                    <li class="">
                        <a href="/master/system/domains" title="ドメイン管理">
                            <i class="fa fa-lg fa-fw fa-object-group"></i> <span class="menu-item-parent">ドメイン</span>
                        </a>
                    </li>
                    <li class="">
                        <a href="/master/system/susers" title="ユーザー管理">
                            <i class="fa fa-lg fa-fw fa-users"></i> <span class="menu-item-parent">ユーザー</span>
                        </a>
                    </li>
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
