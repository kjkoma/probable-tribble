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
namespace App\Controller\Component;

use Cake\Core\Configure;

/**
 * 出庫予定（PickingPlans）へのアクセス用コンポーネント
 *  
 * ※データ取得時は親コンポーネントのtableメソッドを利用し、ドメイン指定でデータ取得すること
 * 
 */
class ModelPickingPlansComponent extends AppModelComponent
{
    /** @var array $components 利用コンポーネント */
    public $components = ['ModelAssets', 'ModelPickingPlanDetails'];

    /**
     * 本クラスの初期化処理を行う
     *  
     * - - -
     * @param array $config コンフィグ
     * @return void
     */
    public function initialize(array $config)
    {
        $config['modelName'] = 'PickingPlans';
        parent::initialize($config);
    }

    /**
     * 当日依頼の出庫予定一覧を取得する
     *  
     * - - -
     * @param boolean $toArray true:配列で返す|false:ResultSetで返す（default）
     * @return array 当日の出庫予定一覧（ResultSet or Array）
     */
    public function requestToday($toArray = false)
    {
        $query = $this->modelTable->find('valid');
        $query = $this->_makeListAssociation($query);
        $query->andWhere(['plan_sts <>' => Configure::read('WNote.DB.Picking.PickingSts.cancelfix')]);
        $query->andWhere(['req_date' => $this->today()]);

        return ($toArray) ? $query->toArray() : $query->all();
    }

    /**
     * 当日の出庫予定一覧を取得する
     *  
     * - - -
     * @param boolean $toArray true:配列で返す|false:ResultSetで返す（default）
     * @return array 当日の出庫予定一覧（ResultSet or Array）
     */
    public function listToday($toArray = false)
    {
        $query = $this->modelTable->find('valid');
        $query = $this->_makeListAssociation($query);
        $query->andWhere(['plan_sts <>' => Configure::read('WNote.DB.Picking.PickingSts.cancelfix')]);
        $query->andWhere(['plan_date' => $this->today()]);

        return ($toArray) ? $query->toArray() : $query->all();
    }

    /**
     * 入庫可能な入庫予定一覧を取得する
     *  
     * - - -
     * @param \Cake\ORM\Query $query クエリビルダ
     * @return \Cake\ORM\Query クエリビルダ
     */
    private function _makeListAssociation($query)
    {
        $query = $query
            ->contain(['PickingPlanPickingKbn'])
            ->contain(['PickingPlanSts'])
            ->contain(['PickingPlanDetails' => function($q) {
                return $q
                    ->select([
                        'picking_plan_id',
                        'sum_plan_count' => $q->func()->sum('plan_count')
                    ])
                    ->group(['picking_plan_id']);
            }])
            ->contain(['Pickings' => function($q) {
                return $q
                    ->select([
                        'picking_plan_id',
                        'sum_picking_count' => $q->func()->sum('picking_count')
                    ])
                    ->group(['picking_plan_id']);
            }]);

        return $query;
    }

    /**
     * 出庫予定を取得する
     *  
     * - - -
     * @param string $planId 出庫予定ID
     * @return \App\Model\Entity\PickingPlan 出庫予定
     */
    public function plan($planId)
    {
        return $this->modelTable->find('valid')
            ->where([
                'PickingPlans.id' => $planId
            ])
            ->contain(['PickingPlanReqOrganizations' => function($q) {
                return $q
                    ->select(['id', 'kname']);
            }])
            ->contain(['PickingPlanReqUsers' => function($q) {
                return $q
                    ->select(['id', 'sname', 'fname']);
            }])
            ->contain(['PickingPlanUseOrganizations' => function($q) {
                return $q
                    ->select(['id', 'kname']);
            }])
            ->contain(['PickingPlanUseUsers' => function($q) {
                return $q
                    ->select(['id', 'sname', 'fname']);
            }])
            ->contain(['PickingPlanDlvOrganizations' => function($q) {
                return $q
                    ->select(['id', 'kname']);
            }])
            ->contain(['PickingPlanDlvUsers' => function($q) {
                return $q
                    ->select(['id', 'sname', 'fname']);
            }])
            ->contain(['PickingPlanRcvSusers' => function($q) {
                return $q
                    ->select(['id', 'kname']);
            }])
            ->contain(['PickingPlanDetails.KittingPatterns' => function($q) {
                return $q
                    ->select(['id', 'kname']);
            }])
            ->first();
    }

    /**
     * 出庫予定一覧（依頼）を取得する
     *  
     * - - -
     * @param boolean $toArray true:配列で返す|false:ResultSetで返す（default）
     * @return array 出庫予定一覧（依頼）（ResultSet or Array）
     */
    public function requestPlans($toArray = false)
    {
        $query = $this->modelTable->find('validAll')
            ->where([ 'plan_sts <>' => Configure::read('WNote.DB.Picking.PickingSts.complete') ])
            ->where([ 'plan_sts <>' => Configure::read('WNote.DB.Picking.PickingSts.cancelfix') ])
            ->order([
                'req_date'    => 'DESC',
                'rcv_date'    => 'DESC',
                'arv_date'    => 'DESC',
                'req_user_id' => 'ASC',
                'plan_sts'    => 'DESC'
            ])
            ->contain(['PickingPlanPickingKbn' => function($q) {
                return $q
                    ->select(['id', 'name']);
            }])
            ->contain(['PickingPlanSts' => function($q) {
                return $q
                    ->select(['id', 'name']);
            }])
            ->contain(['PickingPlanReqUsers' => function($q) {
                return $q
                    ->select(['id', 'sname', 'fname']);
            }])
            ->contain(['PickingPlanUseUsers' => function($q) {
                return $q
                    ->select(['id', 'sname', 'fname']);
            }])
            ->contain(['PickingPlanDlvUsers' => function($q) {
                return $q
                    ->select(['id', 'sname', 'fname']);
            }])
            ->contain(['PickingPlanRcvSusers' => function($q) {
                return $q
                    ->select(['id', 'kname']);
            }])
            ->contain(['PickingPlanDetails' => function($q) {
                return $q
                    ->select(['id', 'picking_plan_id', 'apply_no']);
            }])
            ->contain(['PickingPlanDetails.Categories' => function($q) {
                return $q
                    ->select(['id', 'kname']);
            }]);

        return ($toArray) ? $query->toArray() : $query->all();
    }

    /**
     * 出庫予定を登録する
     *  
     * - - -
     * @param mixed $plan 出庫予定データ
     * @return array {result: true/false, data: 結果データ, errors: エラーデータ}
     */
    public function addNew($plan) {
        $plan['plan_sts']    = Configure::read('WNote.DB.Picking.PickingSts.not');

        return parent::add($plan);
    }

    /**
     * 出庫予定を出庫予定詳細入力、出庫予定詳細より保存する
     *  
     * - - -
     * @param array $entry 出庫予定詳細入力データ
     * @param array $detail 出庫予定詳細データ
     * @param array $asset 資産情報
     * @return array {result: true/false, data: 結果データ, errors: エラーデータ}
     */
    public function saveEntry($entry, $detail, $asset) {
        $plan = parent::get($entry['plan_id']);
        if (!$plan) {
            return parent::_invalid('指定された出庫予定が存在しないため、出庫予定を更新することはできません。', $plan);
        }

        // 出庫予定の出庫情報を更新する
        $plan['plan_date']     = $entry['plan_date'];
        $plan['name']          = (trim($entry['name']) === '') ? $asset['kname'] . ' 出庫' : trim($entry['name']);
        $plan['work_suser_id'] = $entry['work_suser_id'];
        $plan['remarks']       = $entry['remarks'];
        $plan['plan_sts']      = Configure::read('WNote.DB.Picking.PickingSts.work');

        return parent::save($plan->toArray());
    }

    /**
     * 出庫予定を出庫可能な状態に更新する（ステータスを出庫前に更新）
     *  
     * - - -
     * @param string $id 出庫予定ID
     * @return array {result: true/false, data: 結果データ, errors: エラーデータ}
     */
    public function addPicking($id) {
        $plan = parent::get($id);
        if (!$plan) {
            return parent::_invalid('指定された出庫予定が存在しないため、出庫登録することはできません。', $plan);
        }

        // 出庫予定の出庫情報を更新する
        $plan['plan_sts'] = Configure::read('WNote.DB.Picking.PickingSts.before');

        return parent::save($plan->toArray());
    }

    /**
     * 出庫予定を出庫に更新する
     *  
     * - - -
     * @param integer $planId 入庫予定ID
     * @return array {result: true/false, data: 結果データ, errors: エラーデータ}
     */
    public function updateComplete($planId) {
        // 出庫予定
        $plan = parent::get($planId);
        if (!$plan) {
            return $this->_invalid(['message' => '指定された入庫情報がありません。', 'data' => ['plan_id' => $planId]]);
        }

        // 出庫予定の出庫情報を更新する
        $plan['plan_sts'] = Configure::read('WNote.DB.Picking.PickingSts.complete');

        return parent::save($plan->toArray());
    }

    /**
     * 出庫予定を取消する
     *  
     * - - -
     * @param array $data 出庫予定取消データ
     * @return array {result: true/false, data: 結果データ, errors: エラーデータ}
     */
    public function cancel($data) {
        $plan = $this->modelTable->get($data['id']);
        if (!$plan) {
            return parent::_invalid('指定された出庫予定が存在しないため、出庫予定を取消することはできません。', $plan);
        }

        // 出庫予定のステータスを更新する
        $plan['plan_sts']        = Configure::read('WNote.DB.Picking.PickingSts.cancel');
        $plan['cancel_reason']   = $data['cancel_reason'];
        $plan['cancel_suser_id'] = $this->user();
        $result = parent::save($plan->toArray());

        // 出庫予定詳細のステータスを取消に更新する
        if ($result['result']) {
            $resultDetail = $this->ModelPickingPlanDetails->cancel($plan['id']);
            if (!$resultDetail['result']) {
                return $resultDetail;
            }
        }

        return $result;
    }

    /**
     * 出庫予定の取消を解除する（未出庫に戻す）
     *  
     * - - -
     * @param array $data 出庫予定取消確定データ
     * @return array {result: true/false, data: 結果データ, errors: エラーデータ}
     */
    public function cancelRestore($data) {
        $plan = $this->modelTable->get($data['id']);
        if (!$plan) {
            return parent::_invalid('指定された出庫予定が存在しないため、出庫予定を取消解除することはできません。', $plan);
        }

        // 出庫予定のステータスを更新する
        $plan['plan_date']       = null;
        $plan['name']            = null;
        $plan['work_suser_id']   = null;
        $plan['plan_sts']        = Configure::read('WNote.DB.Picking.PickingSts.not');
        $plan['cancel_reason']   = $data['cancel_reason'];
        $plan['cancel_suser_id'] = null;

        $result = parent::save($plan->toArray());

        // 出庫予定詳細のステータスを取消確定に更新する
        if ($result['result']) {
            $resultDetail = $this->ModelPickingPlanDetails->cancelRestore($plan['id']);
            if (!$resultDetail['result']) {
                return $resultDetail;
            }
        }

        return $result;
    }

    /**
     * 出庫予定を取消確定する
     *  
     * - - -
     * @param array $data 出庫予定取消確定データ
     * @return array {result: true/false, data: 結果データ, errors: エラーデータ}
     */
    public function cancelFix($data) {
        $plan = $this->modelTable->get($data['id']);
        if (!$plan) {
            return parent::_invalid('指定された出庫予定が存在しないため、出庫予定を取消確定することはできません。', $plan);
        }

        // 出庫予定のステータスを更新する
        $plan['plan_sts']        = Configure::read('WNote.DB.Picking.PickingSts.cancelfix');
        $plan['cancel_reason']   = $data['cancel_reason'];
        $result = parent::save($plan->toArray());

        // 出庫予定詳細のステータスを取消確定に更新する
        if ($result['result']) {
            $resultDetail = $this->ModelPickingPlanDetails->cancelFix($plan['id']);
            if (!$resultDetail['result']) {
                return $resultDetail;
            }
        }

        return $result;
    }

    /**
     * 指定された予定が依頼時に編集可能かどうかをチェックする
     *  
     * - - -
     * @param integer $planId 出庫予定ID
     * @return boolean true:可能/false:不可
     */
    public function validateRequestEdit($planId)
    {
        $count = $this->modelTable->find('valid')
            ->where([
                'id' => $planId,
                'plan_sts <>' => Configure::read('WNote.DB.Picking.PickingSts.not')
            ])
            ->count();

        return ($count > 0) ? false : true;
    }
}
