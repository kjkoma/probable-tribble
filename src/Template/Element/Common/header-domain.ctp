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

/* 現在のドメイン */
$currentDomain = $this->AppGlobal->hasDomain($this->AppUser->domain());
$current = ['id' => '-1', 'kanme' => ''];
$current['id'] = is_null($currentDomain) ? "" : $currentDomain['id'];
$current['kname'] = is_null($currentDomain) ? "" : $currentDomain['kname'];
/* ドメイン一覧 */
$domains = $this->AppGlobal->domains();
?>

<!-- Domain Dropdown -->
<div class="project-context hidden-xs">

    <span class="label">Domain:</span>
    <span class="project-selector dropdown-toggle" data-toggle="dropdown"><?= $current['kname'] ?>　<i
                class="fa fa-angle-down"></i></span>

    <!-- Suggestion: populate this list with fetch and push technique -->
    <ul class="dropdown-menu">
        <?php foreach ($domains as $domain) { ?>
            <?php if ($this->AppUser->hasDomain($domain['id']) && $domain['id'] != $current['id']) { ?>
                <li><a href="javascript:void(0);" data-id="<?= $domain['id'] ?>"
                       data-app-action-key="change-domain"><?= $domain['kname'] ?></a></li>
            <?php } ?>
        <?php } ?>
    </ul>
    <!-- end dropdown-menu-->

    <?= $this->Form->create(null, ['type' => 'post', 'url' => '/home/cd', 'data-app-action-key' => 'change-domain-form']) ?>
    <?= $this->Form->hidden('to_domain_id', ['data-app-action-key' => 'change-domain-id']) ?>
    <?= $this->Form->end() ?>

</div>