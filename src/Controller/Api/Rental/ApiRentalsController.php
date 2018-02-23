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
namespace App\Controller\Api\Rental;

use \Exception;
use App\Controller\Api\ApiController;
use Cake\Core\Configure;

/**
 * Rentals API Controller
 *
 */
class ApiRentalsController extends ApiController
{
    /**
     * 本クラスの初期化処理を行う
     *  
     * - - -
     */
    public function initialize()
    {
        parent::initialize();
        $this->_loadComponent('ModelRentals');
        $this->_loadComponent('ModelAssets');
        $this->_loadComponent('ModelStocks');
    }

    /**
     * 貸出予定一覧を取得する
     *
     */
    public function plans()
    {
        if (!$this->request->is('post')) {
            $this->setError('指定されたHTTPメソッドには対応していません。', 'UNSUPPORTED_HTTP_METHOD');
            return;
        }

        // 貸出予定を取得
        $rentals = $this->ModelRentals->plans();

        // 一覧表示用に編集する
        foreach($rentals as $rental) {
            $rental['asset_id']            = $rental['asset']['id'];
            $rental['asset_type_name']     = $rental['asset']['asset_type_name']['name'];
            $rental['asset_kname']         = $rental['asset']['kname'];
            $rental['classification_name'] = $rental['asset']['classification']['kname'];
            $rental['maker_name']          = $rental['asset']['company']['kname'];
            $rental['product_name']        = $rental['asset']['product']['kname'];
            $rental['product_model_name']  = $rental['asset']['product_model']['kname'];
            $rental['serial_no']           = $rental['asset']['serial_no'];
            $rental['asset_no']            = $rental['asset']['asset_no'];
        }

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is success', ['rentals' => $rentals]);
    }

    /**
     * 貸出中一覧を取得する
     *
     */
    public function rentals()
    {
        $data = $this->validateParameter('cond', ['post']);
        if (!$data) return;

        // 貸出中一覧を取得
        $rentals = $this->ModelRentals->rentals($data['cond']);

        // 一覧表示用に編集する
        foreach($rentals as $rental) {
            $rental['asset_id']            = $rental['asset']['id'];
            $rental['classification_name'] = $rental['asset']['classification']['kname'];
            $rental['maker_name']          = $rental['asset']['company']['kname'];
            $rental['product_name']        = $rental['asset']['product']['kname'];
            $rental['product_model_name']  = $rental['asset']['product_model']['kname'];
            $rental['serial_no']           = $rental['asset']['serial_no'];
            $rental['asset_no']            = $rental['asset']['asset_no'];
            $rental['user_name']           = $rental['rental_user']['sname'] . ' ' . $rental['rental_user']['fname'];
            $rental['admin_user_name']     = $rental['rental_admin_user']['sname'] . ' ' . $rental['rental_admin_user']['fname'];
            $rental['rental_suser_name']   = $rental['rental_suser']['kname'];
        }

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is success', ['rentals' => $rentals]);
    }

    /**
     * 貸出情報を検索する
     *
     */
    public function search()
    {
        $data = $this->validateParameter('cond', ['post']);
        if (!$data) return;

        // 貸出一覧を取得
        $rentals = $this->ModelRentals->search($data['cond'], true);

        // 一覧表示用に編集する
        $list = []; $counter = 0; $limit = intVal(Configure::read('WNote.ListLimit.maxcount'));
        foreach($rentals as $rental) {
            $list[] = [
                'id'                  => $rental['id'],
                'asset_id'            => $rental['asset']['id'],
                'rental_sts_name'     => $rental['rental_sts_name']['name'],
                'rental_date'         => $rental['rental_date'],
                'user_name'           => $rental['rental_user']['sname'] . ' ' . $rental['rental_user']['fname'],
                'admin_user_name'     => $rental['rental_admin_user']['sname'] . ' ' . $rental['rental_admin_user']['fname'],
                'asset_no'            => $rental['asset']['asset_no'],
                'asset_kname'         => $rental['asset']['kname'],
                'back_date'           => $rental['back_date'],
                'back_user_name'      => $rental['rental_back_user']['sname'] . ' ' . $rental['rental_back_user']['fname'],
                'back_suser_name'     => $rental['rental_back_suser']['kname']
            ];
            $counter++;
            if ($counter > $limit) break;  // 最大500件に制限する
        }

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is success', ['rentals' => $list]);
    }

    /**
     * 資産IDより貸出履歴一覧を取得する
     *
     */
    public function listByAssetId()
    {
        $data = $this->validateParameter('asset_id', ['post']);
        if (!$data) return;

        // 貸出一覧を取得
        $rentals = $this->ModelRentals->listByAssetId($data['asset_id']);

        // 一覧用に編集する
        foreach($rentals as $rental) {
            $rental['rental_sts_name']  = $rental['rental_sts_name']['name'];
            $rental['user_name']        = $rental['rental_user']['sname'] . ' ' . $rental['rental_user']['fname'];
            $rental['admin_user_name']  = ($rental['rental_admin_user']) ? $rental['rental_admin_user']['sname'] . ' ' . $rental['rental_admin_user']['fname'] : '';
            $rental['rental_suser_name'] = $rental['rental_suser']['kname'];
            $rental['back_user_name']   = ($rental['rental_back_user']) ? $rental['rental_back_user']['sname'] . ' ' . $rental['rental_back_user']['fname'] : '';
            $rental['back_suser_name']  = ($rental['rental_back_suser']) ? $rental['rental_back_suser']['kname'] : '';
        }

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is success', ['rentals' => $rentals]);
    }

    /**
     * 指定された資産IDの資産を貸出予定に追加する
     *
     */
    public function add()
    {
        $data = $this->validateParameter('asset_id', ['post']);
        if (!$data) return;

        try {
            // 貸出予定に追加
            $newRental = $this->ModelRentals->addAsset($data['asset_id']);
            $this->AppError->result($newRental);

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
        $this->setResponse(true, 'your request is success', ['rental' => $newRental['data']]);
    }

    /**
     * 指定された資産IDの資産を貸出予定より削除する
     *
     */
    public function delete()
    {
        $data = $this->validateParameter('plans', ['post']);
        if (!$data) return;

        // トランザクション開始
        $this->ModelRentals->begin();

        try {
            foreach($data['plans'] as $plan) {
                // 貸出予定より削除
                $deleteRental = $this->ModelRentals->delete($plan['id']);
                $this->AppError->result($deleteRental);
                if ($this->AppError->has()) break;
            }

            // エラー判定
            if ($this->AppError->has()) {
                // ロールバック
                $this->ModelRentals->rollback();
                $this->setResponseError('your request is failure.', $this->AppError->errors());
                return;
            }

            // コミット
            $this->ModelRentals->commit();

        } catch(Exception $e) {
            // ロールバック
            $this->ModelRentals->rollback();
            $this->setError('保存時に予期せぬエラーが発生しました。管理者へお問い合わせください。', 'UNEXPECTED_EXCEPTION', $e);
            return;
        }

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is success', ['rental' => $deleteRental['data']]);
    }

    /**
     * 指定された貸出予定より貸出へ更新する
     *
     */
    public function rental()
    {
        $data = $this->validateParameter(['plans', 'rental'], ['post']);
        if (!$data) return;

        // トランザクション開始
        $this->ModelRentals->begin();

        try {
            foreach($data['plans'] as $plan) {
                // 更新対象設定
                $data['rental']['id'] = $plan['id'];

                // 貸出予定データを保存
                $updateRental = $this->ModelRentals->rental($data['rental']);
                $this->AppError->result($updateRental);

                if (!$this->AppError->has()) {
                    // 資産を貸出中に更新
                    $updateAsset = $this->ModelAssets->rental($updateRental['data']);
                    $this->AppError->result($updateAsset);
                }

                if (!$this->AppError->has()) {
                    // 在庫を更新
                    $updateStock = $this->ModelStocks->updateRental($updateAsset['data']);
                    $this->AppError->result($updateStock);
                }

                if ($this->AppError->has()) break;
            }

            // エラー判定
            if ($this->AppError->has()) {
                // ロールバック
                $this->ModelRentals->rollback();
                $this->setResponseError('your request is failure.', $this->AppError->errors());
                return;
            }

            // コミット
            $this->ModelRentals->commit();

        } catch(Exception $e) {
            // ロールバック
            $this->ModelRentals->rollback();
            $this->setError('保存時に予期せぬエラーが発生しました。管理者へお問い合わせください。', 'UNEXPECTED_EXCEPTION', $e);
            return;
        }

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is success', ['rental' => $updateRental['data']]);
    }

    /**
     * 指定された貸出資産を返却する
     *
     */
    public function back()
    {
        $data = $this->validateParameter('rental', ['post']);
        if (!$data) return;

        // トランザクション開始
        $this->ModelRentals->begin();

        try {
            foreach($data['rentals'] as $rental) {
                // 更新対象設定
                $data['rental']['id'] = $rental['id'];

                // 貸出予定データを保存
                $updateRental = $this->ModelRentals->back($data['rental']);
                $this->AppError->result($updateRental);

                if (!$this->AppError->has()) {
                    // 資産を在庫に更新
                    $updateAsset = $this->ModelAssets->back($updateRental['data']);
                    $this->AppError->result($updateAsset);
                }

                if (!$this->AppError->has()) {
                    // 在庫を更新
                    $updateStock = $this->ModelStocks->updateBack($updateAsset['data']);
                    $this->AppError->result($updateStock);
                }

                if ($this->AppError->has()) break;
            }

            // エラー判定
            if ($this->AppError->has()) {
                // ロールバック
                $this->ModelRentals->rollback();
                $this->setResponseError('your request is failure.', $this->AppError->errors());
                return;
            }

            // コミット
            $this->ModelRentals->commit();

        } catch(Exception $e) {
            // ロールバック
            $this->ModelRentals->rollback();
            $this->setError('保存時に予期せぬエラーが発生しました。管理者へお問い合わせください。', 'UNEXPECTED_EXCEPTION', $e);
            return;
        }

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is success', ['rental' => $updateRental['data']]);
    }

    /**
     * 指定された貸出予定の在庫を検証する
     *
     */
    public function validateRentalTargets()
    {
        $data = $this->validateParameter('plans', ['post']);
        if (!$data) return;

        $validate = true;

        // 在庫チェック
        foreach($data['plans'] as $plan) {
            $stock = $this->ModelStocks->stock($plan['asset_id']);
            if (!$stock || count($stock) == 0 || intVal($stock['stock_count']) < 1) {
                $validate = false;
                break;
            }
        }

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is succeed', ['validate' => $validate]);
    }

}
