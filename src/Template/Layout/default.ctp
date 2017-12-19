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
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="content-language" content="ja">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no">
    <meta name="description" content="<?= $this->App->conf('WNote.App.description') ?>">
    <meta name="robots" content="noindex,nofollow">
    <meta name="author" content="<?= $this->App->conf('WNote.App.auther') ?>">

    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/manifest.json">
    <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="theme-color" content="#ffffff">

    <title><?= $this->fetch('title') ?></title>
    <?= $this->Html->meta('icon') ?>
    <?= $this->fetch('meta') ?>

    <!-- Basic Styles -->
    <?= $this->Html->css('bootstrap.min.css', ['media' => 'screen']) ?>
    <?= $this->Html->css('bootstrap-treeview.min.css', ['media' => 'screen']) ?>
    <?= $this->Html->css('font-awesome.min.css', ['media' => 'screen']) ?>

    <!-- SmartAdmin Styles : Caution! DO NOT change the order -->
    <?= $this->Html->css('smartadmin-production-plugins.min.css', ['media' => 'screen']) ?>
    <?= $this->Html->css('smartadmin-production.min.css', ['media' => 'screen']) ?>
    <?= $this->Html->css('smartadmin-skins.min.css', ['media' => 'screen']) ?>

    <!-- Tree Style -->
    <?= $this->Html->css('fancytree/ui.fancytree.min.css', ['media' => 'screen']) ?>

    <!-- Wnote Style -->
    <?= $this->Html->css('wnote.css', ['media' => 'screen']) ?>

    <?= $this->fetch('css') ?>

    <!-- GOOGLE FONT -->
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,300,400,700">
</head>
<body class="smart-style-5 fixed-navigation fixed-header fixed-ribbon">

<!-- ** header ** -->
<?= $this->element('Common/header'); ?>

<!-- ** side menu ** -->
<?= $this->element('Common/side-menu'); ?>

<!-- ** main ** -->
<div id="main" role="main">
    <!-- ** ribbon ** -->
    <?= $this->element('Common/ribbon'); ?>

    <!-- ** contents ** -->
    <div id="content">
        <?php
        if ($this->fetch('onlySysAdmin') && !$this->AppUser->hasAdmin()) {
            echo $this->element('Parts/unauthorized');
        } else if ($this->fetch('onlyDomainAdmin') && !$this->AppUser->hasDomainAdmin()) {
            echo $this->element('Parts/unauthorized');
        } else {
            echo $this->fetch('content');
        }
        ?>
    </div>
</div>

<!-- IMPORTANT: jQuery + jQueryUI -->
<?= $this->Html->script('libs/jquery-2.1.1.min.js') ?>
<?= $this->Html->script('libs/jquery-ui-1.10.3.min.js') ?>

<!-- IMPORTANT: APP CONFIG -->
<?= $this->Html->script('app.config.js') ?>

<!-- BOOTSTRAP JS -->
<?= $this->Html->script('bootstrap/bootstrap.min.js') ?>

<!-- CUSTOM NOTIFICATION -->
<?= $this->Html->script('notification/SmartNotification.min.js') ?>

<!-- JARVIS WIDGETS -->
<?= $this->Html->script('smartwidgets/jarvis.widget.min.js') ?>

<!-- SPARKLINES -->
<?= $this->Html->script('plugin/sparkline/jquery.sparkline.min.js') ?>

<!-- JQUERY VALIDATE -->
<?= $this->Html->script('plugin/jquery-validate/jquery.validate.min.js') ?>

<!-- JQUERY MASKED INPUT -->
<?= $this->Html->script('plugin/masked-input/jquery.maskedinput.min.js') ?>

<!-- JQUERY SELECT2 INPUT -->
<?= $this->Html->script('plugin/select2/select2.min.js') ?>

<!-- JQUERY UI + Bootstrap Slider -->
<?= $this->Html->script('plugin/bootstrap-slider/bootstrap-slider.min.js') ?>

<!-- browser msie issue fix -->
<?= $this->Html->script('plugin/msie-fix/jquery.mb.browser.min.js') ?>

<!-- FastClick: For mobile devices -->
<?= $this->Html->script('plugin/fastclick/fastclick.min.js') ?>

<!--[if IE 8]>
<h1>Your browser is out of date, please update your browser by going to www.microsoft.com/download</h1>
<![endif]-->

<!-- Bootstrap Tree View JS FILE -->
<?= $this->Html->script('fancytree/fancytree-all.min.js') ?>

<!-- MAIN APP JS FILE -->
<?= $this->Html->script('app.min.js') ?>

<!-- WNote JS FILE -->
<?= $this->Html->script('wnote.js') ?>

<!-- Pages JS FILE -->
<?= $this->fetch('script') ?>


<?= $this->Form->hidden('jwt', ['id' => 'jwt', 'value' => $this->AppGlobal->jwt()]) ?>
</body>
</html>
