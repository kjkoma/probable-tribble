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
$this->assign('title', 'ホーム');
$this->Breadcrumbs->add(
    'Home', ['controller' => 'home', 'action' => 'home']
);

?>
<!-- widget grid -->
<section id="widget-grid">

    <!-- ********************************** -->
    <!-- Instock Plan List                  -->
    <!-- ********************************** -->

    <!-- list widget grid row -->
    <div class="row">
        <!-- DETAIL list widget -->
        <article class="col-sm-12 sortable-grid ui-sortable">

            <!-- widget ID -->
            <div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-list"
                 data-widget-deletebutton="false"
                 data-widget-editbutton="false"
                 data-widget-colorbutton="false"
                 data-widget-togglebutton="false"
                 data-widget-sortable="false">

                <!-- DETAILS widget header -->
                <header role="heading">
                    <span class="widget-icon"> <i class="fa fa-lg fa-th-list"></i> </span>
                    <h2>本日の状況</h2>
                </header>

                <!-- content -->
                <div role="content">

                    <!-- DETAILS widget body -->
                    <div class="widget-body">

                        <div class="row no-space">
                             <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 show-stats">
                                 <div class="row">
                                     <div class="col-xs-6 col-sm-6 col-md-12 col-lg-12">
                                         <span class="text"> 入庫 <span class="pull-right"><?= intVal($instockPlansInstockCount) ?>/<?= intVal($instockPlanCount) ?></span> </span>
                                         <div class="progress">
                                             <div class="progress-bar bg-color-blue" style="width: <?= intVal($instockPlanRate) ?>%;"></div>
                                         </div>
                                     </div>
                                     <div class="col-xs-6 col-sm-6 col-md-12 col-lg-12">
                                         <span class="text"> 出庫 <span class="pull-right"><?= intVal($pickingPlansPickingCount) ?>/<?= intVal($pickingPlanCount) ?></span> </span>
                                         <div class="progress">
                                             <div class="progress-bar bg-color-green" style="width: <?= intVal($pickingPlanRate) ?>%;"></div>
                                         </div>
                                     </div>

                                 </div>
                             </div>
                        </div>

                        <hr>

                        <div class="row no-space">
                             <div class="col-xs-4 col-sm-3 col-md-2 show-stats">
                                 <header>資産数</header>
                                 <h2 style="color:red;"><?= $assetCount ?></h2>
                             </div>
                             <div class="col-xs-4 col-sm-3 col-md-2 show-stats">
                                 <header>在庫数</header>
                                 <h2 style="color:red;"><?= $stockCount ?></h2>
                             </div>
                             <div class="col-xs-4 col-sm-3 col-md-2 show-stats">
                                 <header>修理数</header>
                                 <h2 style="color:red;"><?= $repairCount ?></h2>
                             </div>
                             <div class="col-xs-4 col-sm-3 col-md-2 show-stats">
                                 <header>未入庫数</header>
                                 <h2 style="color:red;"><?= intVal($instockPlanCount) - intVal($instockPlansInstockCount) ?></h2>
                             </div>
                             <div class="col-xs-4 col-sm-3 col-md-2 show-stats">
                                 <header>未出庫数</header>
                                 <h2 style="color:red;"><?= intVal($pickingPlanCount) - intVal($pickingPlansPickingCount)  ?></h2>
                             </div>
                             <div class="col-xs-4 col-sm-3 col-md-2 show-stats">
                                 <header>出庫依頼数</header>
                                 <h2 style="color:red;"><?= $pickingRequestCount ?></h2>
                             </div>
                        </div>

                        <!-- End widget body -->
                    </div>

                    <!-- End content -->
                </div>

                <!-- End widget ID -->
            </div>

            <!-- End DETAILS list widget -->
        </article>




        <!-- DETAIL list widget -->
        <article class="col-sm-6 sortable-grid ui-sortable">

            <!-- widget ID -->
            <div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-list"
                 data-widget-deletebutton="false"
                 data-widget-editbutton="false"
                 data-widget-colorbutton="false"
                 data-widget-togglebutton="false"
                 data-widget-sortable="false">

                <!-- DETAILS widget header -->
                <header role="heading">
                    <span class="widget-icon"> <i class="fa fa-lg fa-th-list"></i> </span>
                    <h2>本日の入庫予定</h2>
                </header>

                <!-- content -->
                <div role="content">

                    <!-- DETAILS widget body -->
                    <div class="widget-body">

                        <table class="table table-striped table-bordered table-hover" id="instock-datatable">
                            <thead>
                                <tr>
                                    <th>入庫区分</th>
                                    <th>状況</th>
                                    <th>入庫件名</th>
                                    <th>入庫数</th>
                                    <th>予定数</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($instockPlans as $instockPlan) { ?>
                                    <tr>
                                        <td><?= $instockPlan['instock_plans_kbn']['name'] ?></td>
                                        <td><?= $instockPlan['instock_plans_st']['name'] ?></td>
                                        <td><?= $instockPlan['name'] ?></td>
                                        <td><?= ($instockPlan['instocks'][0]['sum_instock_count'] == '') ? 0 : $instockPlan['instocks'][0]['sum_instock_count'] ?></td>
                                        <td><?= ($instockPlan['instock_plan_details'][0]['sum_plan_count'] == '') ? 0 : $instockPlan['instock_plan_details'][0]['sum_plan_count'] ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>

                        <!-- End widget body -->
                    </div>

                    <!-- End content -->
                </div>

                <!-- End widget ID -->
            </div>

            <!-- End DETAILS list widget -->
        </article>




        <!-- DETAIL list widget -->
        <article class="col-sm-6 sortable-grid ui-sortable">

            <!-- widget ID -->
            <div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-list"
                 data-widget-deletebutton="false"
                 data-widget-editbutton="false"
                 data-widget-colorbutton="false"
                 data-widget-togglebutton="false"
                 data-widget-sortable="false">

                <!-- DETAILS widget header -->
                <header role="heading">
                    <span class="widget-icon"> <i class="fa fa-lg fa-th-list"></i> </span>
                    <h2>本日の出庫予定</h2>
                </header>

                <!-- content -->
                <div role="content">

                    <!-- DETAILS widget body -->
                    <div class="widget-body">

                        <table class="table table-striped table-bordered table-hover" id="instock-datatable">
                            <thead>
                                <tr>
                                    <th>出庫区分</th>
                                    <th>状況</th>
                                    <th>出庫件名</th>
                                    <th>出庫数</th>
                                    <th>予定数</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($pickingPlans as $pickingPlan) { ?>
                                    <tr>
                                        <td><?= $pickingPlan['picking_plan_picking_kbn']['name'] ?></td>
                                        <td><?= $pickingPlan['picking_plan_st']['name'] ?></td>
                                        <td><?= $pickingPlan['name'] ?></td>
                                        <td><?= ($pickingPlan['pickings'][0]['sum_picking_count'] == '') ? 0 : $pickingPlan['pickings'][0]['sum_picking_count'] ?></td>
                                        <td><?= ($pickingPlan['picking_plan_details'][0]['sum_plan_count'] == '') ? 0 : $pickingPlan['picking_plan_details'][0]['sum_plan_count'] ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>

                        <!-- End widget body -->
                    </div>

                    <!-- End content -->
                </div>

                <!-- End widget ID -->
            </div>

            <!-- End DETAILS list widget -->
        </article>





        <!-- End list widget grid row -->
    </div>

    <!-- End widget grid-->
</section>