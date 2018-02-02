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
 * Stocktake Details API Controller
 *
 */
class ApiStocktakeDetailsController extends ApiController
{
    /**
     * 本クラスの初期化処理を行う
     *  
     * - - -
     */
    public function initialize()
    {
        parent::initialize();
        $this->_loadComponent('ModelStocktakeDetails');
        $this->_loadComponent('ModelStocks');
    }

    /**
     * 棚卸差分の一覧を取得する（在庫が存在しない差分）
     * 
     *
     */
    public function nostocks()
    {
        $data = $this->validateParameter(['stocktake_id'], ['post']);
        if (!$data) return;

        // 棚卸差分を取得
        $unmatches = $this->ModelStocktakeDetails->nostocks($data['stocktake_id']);

        // 一覧表示用に編集
        $list = [];
        foreach($unmatches as $unmatch) {
            $list[] = [
                'stocktake_id'        => $unmatch['stocktake_id'],
                'stocktake_detail_id' => $unmatch['id'],
                'unmatch_kbn_name'    => $unmatch['stocktake_unmatch_kbn_name']['name'],
                'serial_no'           => $unmatch['serial_no'],
                'asset_no'            => $unmatch['asset_no'],
                'stocktake_kbn_name'  => $unmatch['stocktake_kbn_name']['name'],
                'correspond'          => $unmatch['correspond']
            ];
        }

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is success', ['unmatches' => $list]);
    }

    /**
     * 指定された棚卸内容を保存する（資産管理棚卸）
     *
     */
    public function save()
    {
        $data = $this->validateParameter(['stocktake_id', 'stocktakes'], ['post']);
        if (!$data) return;

        // トランザクション開始
        $this->ModelStocktakeDetails->begin();

        try {
            // 棚卸明細を保存（複数）
            $newStocktakeDetail = $this->ModelStocktakeDetails->entryAssets($data['stocktake_id'], $data['stocktakes']);
            $this->AppError->result($newStocktakeDetail);

            // エラー判定
            if ($this->AppError->has()) {
                // ロールバック
                $this->ModelStocktakeDetails->rollback();
                $this->setResponseError('your request is failure.', $this->AppError->errors());
                return;
            }

            // コミット
            $this->ModelStocktakeDetails->commit();

        } catch(Exception $e) {
            // ロールバック
            $this->ModelStocktakeDetails->rollback();
            $this->setError('保存時に予期せぬエラーが発生しました。管理者へお問い合わせください。', 'UNEXPECTED_EXCEPTION', $e);
            return;
        }

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is success', ['stocktake_id' => $data['stocktake_id']]);
    }

    /**
     * 指定された棚卸内容を保存する（数量管理棚卸）
     *
     */
    public function saveCount()
    {
        $data = $this->validateParameter(['stocktake'], ['post']);
        if (!$data) return;

        // トランザクション開始
        $this->ModelStocktakeDetails->begin();

        try {
            // 棚卸明細を保存
            if ($data['stocktake']['stocktake_detail_id'] != '') {
                $updateStocktakeDetail = $this->ModelStocktakeDetails->saveCount($data['stocktake']);
            } else {
                $updateStocktakeDetail = $this->ModelStocktakeDetails->entryCount($data['stocktake']);
            }
            $this->AppError->result($updateStocktakeDetail);

            // エラー判定
            if ($this->AppError->has()) {
                // ロールバック
                $this->ModelStocktakeDetails->rollback();
                $this->setResponseError('your request is failure.', $this->AppError->errors());
                return;
            }

            // コミット
            $this->ModelStocktakeDetails->commit();

        } catch(Exception $e) {
            // ロールバック
            $this->ModelStocktakeDetails->rollback();
            $this->setError('保存時に予期せぬエラーが発生しました。管理者へお問い合わせください。', 'UNEXPECTED_EXCEPTION', $e);
            return;
        }

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is success', ['stocktake' => $updateStocktakeDetail['data']]);
    }

    /**
     * 棚卸差分（数量差分）に対して在庫差分を更新し、棚卸差分を対応済に更新する
     *
     */
    public function updateCountUnmatches()
    {
        $data = $this->validateParameter(['stocktake_id', 'unmatches'], ['post']);
        if (!$data) return;

        $unmatches = $data['unmatches'];

        // トランザクション開始
        $this->ModelStocktakeDetails->begin();

        try {
            foreach($unmatches as $unmatch) {
                // 棚卸明細を更新
                $updateStocktakeDetail = $this->ModelStocktakeDetails->updateUnmatch($data['stocktake_id'], $unmatch);
                $this->AppError->result($updateStocktakeDetail);

                if (!$this->AppError->has()) {
                    // 在庫を更新
                    $updateStock = $this->ModelStocks->updateStocktake($updateStocktakeDetail['data']);
                    $this->AppError->result($updateStock);
                }

                // エラー判定
                if ($this->AppError->has()) {
                    break;
                }
            }

            // エラー判定
            if ($this->AppError->has()) {
                // ロールバック
                $this->ModelStocktakeDetails->rollback();
                $this->setResponseError('your request is failure.', $this->AppError->errors());
                return;
            }

            // コミット
            $this->ModelStocktakeDetails->commit();

        } catch(Exception $e) {
            // ロールバック
            $this->ModelStocktakeDetails->rollback();
            $this->setError('保存時に予期せぬエラーが発生しました。管理者へお問い合わせください。', 'UNEXPECTED_EXCEPTION', $e);
            return;
        }

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is success', ['stocktake_id' => $data['stocktake_id']]);
    }

    /**
     * 棚卸差分（在庫なし）に対して棚卸差分を対応済に更新する
     *
     */
    public function updateNostocks()
    {
        $data = $this->validateParameter(['stocktake_id', 'correspond', 'unmatches'], ['post']);
        if (!$data) return;

        $unmatches = $data['unmatches'];

        // トランザクション開始
        $this->ModelStocktakeDetails->begin();

        try {
            foreach($unmatches as $unmatch) {
                // 棚卸明細を更新
                $updateStocktakeDetail = $this->ModelStocktakeDetails->updateNostock($data['stocktake_id'], $data['correspond'], $unmatch);
                $this->AppError->result($updateStocktakeDetail);

                // エラー判定
                if ($this->AppError->has()) {
                    break;
                }
            }

            // エラー判定
            if ($this->AppError->has()) {
                // ロールバック
                $this->ModelStocktakeDetails->rollback();
                $this->setResponseError('your request is failure.', $this->AppError->errors());
                return;
            }

            // コミット
            $this->ModelStocktakeDetails->commit();

        } catch(Exception $e) {
            // ロールバック
            $this->ModelStocktakeDetails->rollback();
            $this->setError('保存時に予期せぬエラーが発生しました。管理者へお問い合わせください。', 'UNEXPECTED_EXCEPTION', $e);
            return;
        }

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is success', ['stocktake_id' => $data['stocktake_id']]);
    }
}
