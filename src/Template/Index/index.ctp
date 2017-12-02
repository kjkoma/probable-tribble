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
<html id="extr-page">

<head>
    <?= $this->Html->charset() ?>
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

    <title>Warehouse Note</title>
    <?= $this->Html->meta('icon') ?>

    <?php // Basic Styles ?>
    <?= $this->Html->css('bootstrap.min.css', ['media' => 'screen']) ?>
    <?= $this->Html->css('font-awesome.min.css', ['media' => 'screen']) ?>
    <?php // Template Styles ?>
    <?= $this->Html->css('smartadmin-production-plugins.min.css', ['media' => 'screen']) ?>
    <?= $this->Html->css('smartadmin-production.min.css', ['media' => 'screen']) ?>
    <?= $this->Html->css('smartadmin-skins.min.css', ['media' => 'screen']) ?>
    <?= $this->Html->css('smartadmin-rtl.min.css', ['media' => 'screen']) ?>
    <?php // Page Styles ?>
    <?= $this->Html->css('login.css', ['media' => 'screen']) ?>
</head>

<body class="animated fadeInDown">

<!-- HEADER -->
<header id="header">
    <div id="logo-group" class=" hidden-md hidden-lg">
        <h1 class="txt-color-white login-header-big"><b class="c-green">W</b>arehouse <b class="c-green">Note</b></h1>
    </div>
</header>

<!-- MAIN CONTENT -->
<div id="main" role="main">

    <div id="content" class="container">

        <div class="row">

            <div class="col-xs-12 col-sm-12 col-md-7 col-lg-8 hidden-xs hidden-sm">

                <h1 class="login-header-big text-color-white"><b class="c-green">W</b>arehouse <b
                            class="c-green">Note</b></h1>

                <div class="hero">
                    <div class="pull-left login-desc-box-l">
                        <h4 class="paragraph-header">IT機器のライフサイクル管理における入出庫、在庫、棚卸業務を効率化しましょう</h4>
                    </div>
                </div> <!-- hero -->

            </div> <!-- col 1 (outside) -->

            <div class="col-xs-12 col-sm-12 col-md-5 col-lg-4">

                <div class="well no-padding">
                    <?= $this->Form->create($login, ['type' => 'post', 'url' => ['controller' => 'Auth', 'action' => 'login'], 'id' => 'login-form', 'class' => 'smart-form client-form']) ?>
                    <header>Warehouse Noteへログイン</header>

                    <fieldset>
                        <section>
                            <?= $this->Flash->render('auth') ?>
                        </section>

                        <section>
                            <label class="label">E-mail</label>
                            <label class="input"> <i class="icon-append fa fa-user"></i>
                                <?= $this->Form->email('email', [
                                    'id' => "email",
                                    'class' => '',
                                    'placeholder' => 'Emailアドレスを入力してください。',
                                    'length' => '255',
                                    'maxlength' => '255',
                                    'required' => 'required',
                                    'tabindex' => '1',
                                    'data-platm-assist' => 'email',
                                ]) ?>
                                <b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-teal"></i>
                                    E-mailアドレスを入力してください。</b>
                            </label>
                            <?= $this->AppForm->errorInput('email', $errors) ?>
                        </section>

                        <section>
                            <label class="label">パスワード</label>
                            <label class="input"> <i class="icon-append fa fa-lock"></i>
                                <?= $this->Form->password('password', [
                                    'id' => "password",
                                    'class' => '',
                                    'placeholder' => 'パスワードを入力してください。',
                                    'length' => '20',
                                    'maxlength' => '20',
                                    'required' => 'required',
                                    'tabindex' => '2']) ?>
                                <b class="tooltip tooltip-top-right"><i class="fa fa-lock txt-color-teal"></i>
                                    パスワードを入力してください</b>
                            </label>
                            <?= $this->AppForm->errorInput('password', $errors) ?>
                            <div class="note">
                                <a href="forgotpassword.html">パスワードを忘れた場合はこちら</a>
                            </div>
                        </section>

                        <section>
                            <label class="checkbox">
                                <?= $this->Form->checkbox('rememberme') ?>
                                <i></i>ログインしたままにする
                            </label>
                        </section>
                    </fieldset>

                    <footer>
                        <button type="submit" class="btn btn-primary">
                            ログイン
                        </button>
                    </footer>

                    <?= $this->Form->end() ?>
                </div> <!-- well no-padding -->

            </div> <!-- col 2 (outside) -->

        </div> <!-- row (outside) -->

    </div> <!-- content -->

</div> <!-- main -->

<? // Include Scripts ?>
<?= $this->Html->script('libs/jquery-2.1.1.min.js') ?>
<?= $this->Html->script('libs/jquery-ui-1.10.3.min.js') ?>
<?= $this->Html->script('app.config.js') ?>
<?= $this->Html->script('bootstrap/bootstrap.min.js') ?>
<?= $this->Html->script('plugin/jquery-validate/jquery.validate.min.js') ?>
<?= $this->Html->script('plugin/masked-input/jquery.maskedinput.min.js') ?>
<!--[if IE 8]>
<h1>Your browser is out of date, please update your browser by going to www.microsoft.com/download</h1>
<![endif]-->
<?= $this->Html->script('app.min.js') ?>

<? // Page Script ?>
<script type="text/javascript">
    runAllForms();

    $(function () {
        // Validation
        $("#login-form").validate({
            // Rules for form validation
            rules: {
                email: {
                    required: true,
                    email: true
                },
                password: {
                    required: true,
                    minlength: 6,
                    maxlength: 20
                }
            },

            // Messages for form validation
            messages: {
                email: {
                    required: 'E-mailアドレスを入力してください。',
                    email: '正しいE-mailアドレスを入力してください。'
                },
                password: {
                    required: 'パスワードを入力してください。'
                }
            },

            // Do not change code below
            errorPlacement: function (error, element) {
                error.insertAfter(element.parent());
            }
        });
    });
</script>

</body>

</html>
