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
namespace App\Controller\Api\Master\General;

use App\Controller\Api\ApiController;
use Exception;

/**
 * Product Models API Controller
 *
 */
class ApiProductModelsController extends ApiController
{
    /**
     * 本クラスの初期化処理を行う
     *  
     * - - -
     */
    public function initialize()
    {
        parent::initialize();
        $this->_loadComponent('ModelProductModels');
        $this->_loadComponent('ModelCpus');
    }

    /**
     * 指定されたモデルIDのモデル情報を取得する
     *
     */
    public function model()
    {
        $data = $this->validateParameter('model_id', ['post']);
        if (!$data) return;

        // モデルを取得
        $model = $this->ModelProductModels->get($data['model_id']);
        if (!$model) {
            $this->setError('指定されたデータがありません。', 'NOT_FOUND_PRODUCT_MODELS', $data, 404);
            return;
        }

        // CPU名称を取得・付加（ビューのselect2選択BOX用）
        if (!empty($model['cpu_id'])) {
            $cpu = $this->ModelCpus->get($model['cpu_id']);
            if (!$cpu) {
                $this->setError('指定されたモデルデータに関連するCPU情報がありません。', 'NOT_FOUND_CPU', $data, 404);
                return;
            }
            $model['cpu_text'] = $cpu['kname'];
        }

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is success', [
            'model' => $model
        ]);
    }

    /**
     * 送信されたモデルデータを登録する
     *
     */
    public function add()
    {
        $data = $this->validateParameter('model', ['post']);
        if (!$data) return;

        $model = $data['model'];
        $model['domain_id'] = $this->AppUser->current();

        try {
            // モデルを保存
            $newModel = $this->ModelProductModels->add($model);
            $this->AppError->result($newModel);

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
        $this->setResponse(true, 'your request is success', ['model' => $newModel['data']]);
    }

    /**
     * 送信されたモデルデータを更新する
     *
     */
    public function edit()
    {
        $data = $this->validateParameter('model', ['post']);
        if (!$data) return;

        $model = $data['model'];

        try {
            // モデルを保存
            $updateModel = $this->ModelProductModels->save($model);
            $this->AppError->result($updateModel);

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
        $this->setResponse(true, 'your request is success', ['model' => $updateModel['data']]);
    }

    /**
     * 指定されたモデルIDのモデルデータを削除する
     *
     */
    public function delete()
    {
        $data = $this->validateParameter('id', ['post']);
        if (!$data) return;

        $modelId = $data['id'];

        // トランザクション開始
        $this->ModelProductModels->begin();

        try {
            // モデルを削除(TableのDependencyを利用して依存データを削除)
            $deleteModel = $this->ModelProductModels->delete($modelId);
            $this->AppError->result($deleteModel);

            // エラー判定
            if ($this->AppError->has()) {
                // ロールバック
                $this->ModelProductModels->rollback();
                $this->setResponseError('your request is failure.', $this->AppError->errors());
                return;
            }

            // コミット
            $this->ModelProductModels->commit();

        } catch(Exception $e) {
            // ロールバック
            $this->ModelProductModels->rollback();
            $this->setError('保存時に予期せぬエラーが発生しました。管理者へお問い合わせください。', 'UNEXPECTED_EXCEPTION', $e);
            return;
        }

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is succeed', ['model' => $deleteModel['data']]);
    }

    /**
     * 指定された製品のモデル一覧を取得する
     *
     */
    public function modelsInProduct()
    {
        $data = $this->validateParameter('product_id', ['post']);
        if (!$data) return;

        // モデル一覧を取得する
        $models = $this->ModelProductModels->getByProductId($data['product_id']);
        $models = $this->ModelProductModels->makeDatatableArray($models);

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is succeed', ['models' => $models]);
    }

    /**
     * モデル一覧を検索する
     *
     */
    public function findList()
    {
        $data = $this->request->getData();
        if (!$data || !array_key_exists('term', $data) || !$this->request->is('post')) {
            $this->setResponse(true, 'your request is succeed but no parameter found', ['models' => []]);
            return;
        }

        // モデルを取得する（製品指定がある場合は製品で絞り込む）
        $productId = array_key_exists('product_id', $data) ? $data['product_id'] : null;
        $models  = $this->ModelProductModels->find2List($data['term'], $productId);

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is succeed', ['models' => $models]);
    }


    /**************************************************************************/
    /** 検証用メソッド                                                        */
    /**************************************************************************/
    /**
     * 指定されたモデルと製品の関係に妥当性があるかどうかを検証する
     *
     */
    public function validateModelAndProduct()
    {
        $data = $this->validateParameter('model_id', ['post']);
        if (!$data) return;

        $productId = (array_key_exists('product_id', $data) && !empty($data['product_id'])) ? $data['product_id'] : '';

        // 指定されたモデルIDと製品IDのデータが存在するかどうかをチェック
        $validate = $this->ModelProductModels->validateModelAndProduct($data['model_id'], $productId);

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is succeed', ['validate' => $validate]);
    }

}
