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

<!-- HEADER -->
<header id="header">

    <!-- ## logo-group ## -->
    <div id="logo-group">

        <!-- LOGO -->
        <span id="logo"> <img src="/img/wnote-logo.png" alt="WarehouseNote"> </span>

        <!-- NOTIFICATION -->
        <?php // $this->element('Common/header-notification') ?>

    </div>
    <!-- End logo-group -->

    <!-- DOMAIN DROPDOWN -->
    <?= $this->element('Common/header-domain') ?>


    <!-- ## pulled right: nav area ## -->
    <div class="pull-right">

        <!-- COLLAPSE MENU BUTTON -->
        <?= $this->element('Common/header-menu') ?>

        <!-- LOGOUT BUTTON -->
        <div id="logout" class="btn-header transparent pull-right">
            <span> <a href="/logout" title="ログアウト" data-action="userLogout"
                      data-logout-msg="ログアウトするとログイン状態が解除され、再度のログインが必要になります。"><i class="fa fa-sign-out"></i></a> </span>
        </div>
        <!-- end logout button -->

        <!-- SEARCH BUTTON - Mobile (this is hidden till mobile view port) -->
        <div id="search-mobile" class="btn-header transparent pull-right">
            <span> <a href="javascript:void(0)" title="Search"><i class="fa fa-search"></i></a> </span>
        </div>
        <!-- end search mobile button -->

        <!-- SEARCH FIELD -->
        <?= $this->element('Common/input-search') ?>
        <!-- end input: search field -->

        <!-- HOME BUTTON -->
        <div id="home" class="btn-header transparent pull-right">
            <span> <a href="/" title="ホーム"><i class="fa fa-home"></i></a> </span>
        </div>
        <!-- end logout button -->

        <!-- FULL SCREEN BUTTON -->
        <div id="fullscreen" class="btn-header transparent pull-right">
            <span> <a href="javascript:void(0);" data-action="launchFullscreen" title="Full Screen"><i
                            class="fa fa-arrows-alt"></i></a> </span>
        </div>

    </div>
    <!-- end pulled right: nav area -->

</header>
<!-- END HEADER -->