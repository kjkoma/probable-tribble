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
namespace App\Controller\Api\Stocktake;

use \Exception;
use Cake\Core\Configure;
use App\Controller\Api\ApiController;

/**
 * Stocktakes API Controller
 *
 */
class ApiStocktakesController extends ApiController
{
    /**
     * 本クラスの初期化処理を行う
     *  
     * - - -
     */
    public function initialize()
    {
        parent::initialize();
        $this->_loadComponent('ModelStocktakes');
        $this->_loadComponent('ModelStocktakeDetails');
        $this->_loadComponent('ModelStocktakeTargets');
    }

    /**
     * 指定された棚卸IDの棚卸情報を取得する
     *
     */
    public function stocktake()
    {
        $data = $this->validateParameter('stocktake_id', ['post']);
        if (!$data) return;

        // 棚卸情報を取得
        $stocktake = $this->ModelStocktakes->stocktake($data['stocktake_id']);
        if (!$stocktake) {
            $this->setError('指定されたデータがありません。', 'NOT_FOUND_STOCKTAKE', $data, 404);
            return;
        }

        // 各名称を付加（ビューのselect2選択BOX用）
        $stocktake['stocktake_suser_text'] = $stocktake['stocktake_suser_name']['kname'];
        $stocktake['confirm_suser_text']   = $stocktake['stocktake_confirm_suser_name']['kname'];

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is success', ['stocktake' => $stocktake]);
    }

    /**
     * 指定された棚卸IDのサマリ情報を取得する
     *
     */
    public function summary()
    {
        $data = $this->validateParameter('stocktake_id', ['post']);
        if (!$data) return;

        // 棚卸情報を取得
        $stocktake = $this->ModelStocktakes->stocktake($data['stocktake_id']);
        if (!$stocktake) {
            $this->setError('指定されたデータがありません。', 'NOT_FOUND_STOCKTAKE', $data, 404);
            return;
        }

        // 表示用に編集
        $stocktake['stocktake_sts_name']   = $stocktake['stocktake_sts_name']['name'];
        $stocktake['stocktake_term']       = $stocktake['start_date'] . ' - ' . $stocktake['end_date'];
        $stocktake['stocktake_suser_name'] = $stocktake['stocktake_suser_name']['kname'];
        $stocktake['confirm_suser_name']   = $stocktake['stocktake_confirm_suser_name']['kname'];

        // サマリを取得
        $stocktake['area1_stocktake_count'] = $this->ModelStocktakeDetails->sumAssetStockDetails($data['stocktake_id']);
        $stocktake['area1_stock_count']     = $this->ModelStocktakeTargets->sumAssetStockTargets($data['stocktake_id']);
        $stocktake['area2_stocktake_count'] = $this->ModelStocktakeDetails->sumCountStockDetails($data['stocktake_id']);
        $stocktake['area2_stock_count']     = $this->ModelStocktakeTargets->sumCountStockTargets($data['stocktake_id']);
        $overStock = intVal($this->ModelStocktakeDetails->sumOverStocks($data['stocktake_id']))
                   + intVal($this->ModelStocktakeTargets->sumOverStockTargets($data['stocktake_id']));
        $noStock   = intVal($this->ModelStocktakeDetails->sumNoStocks($data['stocktake_id']))
                   + intVal($this->ModelStocktakeDetails->sumShortStocks($data['stocktake_id']));
        $stocktake['area3_count']           = $overStock;
        $stocktake['area4_count']           = $noStock;

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is success', ['stocktake' => $stocktake]);
    }

    /**
     * 棚卸一覧を表示する
     *
     */
    public function search()
    {
        $data = $this->validateParameter('cond', ['post']);
        if (!$data) return;

        // 棚卸一覧を取得
        $stocktakes = $this->ModelStocktakes->search($data['cond']);

        // 棚卸表示用に編集する
        foreach($stocktakes as $stocktake) {
            $stocktake['stocktake_sts_name']   = $stocktake['stocktake_sts_name']['name'];
            $stocktake['stocktake_suser_name'] = $stocktake['stocktake_suser_name']['kname'];
            $stocktake['confirm_suser_name']   = $stocktake['stocktake_confirm_suser_name']['kname'];
        }

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is success', ['stocktakes' => $stocktakes]);
    }

    /**
     * 送信された棚卸データを登録する
     *
     */
    public function add()
    {
        $data = $this->validateParameter('stocktake', ['post']);
        if (!$data) return;

        $stocktake = $data['stocktake'];
        $stocktake['domain_id']     = $this->AppUser->current();
        $stocktake['stocktake_sts'] = Configure::read('WNote.DB.Stocktake.StocktakeSts.working');

        try {
            // 棚卸を保存
            $newStocktake = $this->ModelStocktakes->add($stocktake);
            $this->AppError->result($newStocktake);

            // エラー判定
            if ($this->AppError->has()) {
                $this->setResponseError('your request is failure.', $this->AppError->errors());
                return;
            }

        } catch(Exception $e) {
            $this->setError('保存時に予期せぬエラーが発生しました。管理者へお問い合わせください。', 'UNEXPECTED_EXCEPTION', $e);
            return;
        }

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is success', ['stocktake' => $newStocktake['data']]);
    }

    /**
     * 送信された棚卸データを更新する
     *
     */
    public function edit()
    {
        $data = $this->validateParameter('stocktake', ['post']);
        if (!$data) return;

        $stocktake = $data['stocktake'];

        try {
            // 棚卸を保存
            $updateStocktake = $this->ModelStocktakes->save($stocktake);
            $this->AppError->result($updateStocktake);

            // エラー判定
            if ($this->AppError->has()) {
                $this->setResponseError('your request is failure.', $this->AppError->errors());
                return;
            }

        } catch(Exception $e) {
            $this->setError('保存時に予期せぬエラーが発生しました。管理者へお問い合わせください。', 'UNEXPECTED_EXCEPTION', $e);
            return;
        }

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is success', ['stocktake' => $updateStocktake['data']]);
    }

    /**
     * 指定された棚卸IDの棚卸データを削除する
     *
     */
    public function delete()
    {
        $data = $this->validateParameter('id', ['post']);
        if (!$data) return;

        $stocktakeId = $data['id'];

        // トランザクション開始
        $this->ModelStocktakes->begin();

        try {
            // 棚卸を削除(TableのDependencyを利用して依存データを削除)
            $deleteStocktake = $this->ModelStocktakes->delete($stocktakeId);
            $this->AppError->result($deleteStocktake);

            // エラー判定
            if ($this->AppError->has()) {
                // ロールバック
                $this->ModelStocktakes->rollback();
                $this->setResponseError('your request is failure.', $this->AppError->errors());
                return;
            }

            // コミット
            $this->ModelStocktakes->commit();

        } catch(Exception $e) {
            // ロールバック
            $this->ModelStocktakes->rollback();
            $this->setError('保存時に予期せぬエラーが発生しました。管理者へお問い合わせください。', 'UNEXPECTED_EXCEPTION', $e);
            return;
        }

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is succeed', ['stocktake' => $deleteStocktake['data']]);
    }

    /**
     * 指定された棚卸IDに対して現時点での棚卸対象在庫を作成する（在庫を締める）
     *
     */
    public function fixStock()
    {
        $data = $this->validateParameter('id', ['post']);
        if (!$data) return;

        $stocktakeId = $data['id'];

        // トランザクション開始
        $this->ModelStocktakes->begin();

        try {
            // 棚卸対象在庫を作成
            $newTarget = $this->ModelStocktakeTargets->create($stocktakeId);
            $this->AppError->result($newTarget);

            if (!$this->AppError->has()) {
                // 在庫締め日を更新
                $updateStocktake = $this->ModelStocktakes->updateStockDeadline($stocktakeId);
                $this->AppError->result($updateStocktake);
            }

            // エラー判定
            if ($this->AppError->has()) {
                // ロールバック
                $this->ModelStocktakes->rollback();
                $this->setResponseError('your request is failure.', $this->AppError->errors());
                return;
            }

            // コミット
            $this->ModelStocktakes->commit();

        } catch(Exception $e) {
            // ロールバック
            $this->ModelStocktakes->rollback();
            $this->setError('保存時に予期せぬエラーが発生しました。管理者へお問い合わせください。', 'UNEXPECTED_EXCEPTION', $e);
            return;
        }

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is succeed', ['stocktake' => $updateStocktake['data']]);
    }

    /**
     * 指定された棚卸IDに対して棚卸を確定する
     *
     */
    public function fixStocktake()
    {
        $data = $this->validateParameter('id', ['post']);
        if (!$data) return;

        try {
            // 棚卸確定更新
            $updateStocktake = $this->ModelStocktakes->fix($data['id']);
            $this->AppError->result($updateStocktake);

            // エラー判定
            if ($this->AppError->has()) {
                $this->setResponseError('your request is failure.', $this->AppError->errors());
                return;
            }

        } catch(Exception $e) {
            $this->setError('保存時に予期せぬエラーが発生しました。管理者へお問い合わせください。', 'UNEXPECTED_EXCEPTION', $e);
            return;
        }

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is succeed', ['stocktake' => $updateStocktake['data']]);
    }
}
