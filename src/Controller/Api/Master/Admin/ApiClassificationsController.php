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
namespace App\Controller\Api\Master\Admin;

use App\Controller\Api\ApiController;
use Exception;

/**
 * Classifications API Controller
 *
 */
class ApiClassificationsController extends ApiController
{
    /**
     * 本クラスの初期化処理を行う
     *  
     * - - -
     */
    public function initialize()
    {
        parent::initialize();
        $this->_loadComponent('ModelClassifications');
        $this->_loadComponent('ModelClassTree');
        $this->_loadComponent('ModelProducts');
    }

    /**
     * 指定された分類IDの分類情報を取得する
     *
     */
    public function classification()
    {
        $data = $this->validateParameter('classification_id', ['post']);
        if (!$data) return;

        // 分類を取得
        $classification = $this->ModelClassifications->get($data['classification_id']);
        if (!$classification) {
            $this->setError('指定されたデータがありません。', 'NOT_FOUND_CLASSIFICATION', $data, 404);
            return;
        }

        // 親分類を取得
        $myparent = ['parent_id' => '', 'parent_text' => ''];
        $p = $this->ModelClassTree->myparent($classification['id']);
        if (count($p)) {
            $po = $this->ModelClassifications->get($p['ancestor']);
            $myparent['parent_id']   = $p['ancestor'];
            $myparent['parent_text'] = $po['kname'];
        }

        // カテゴリIDを取得
        $category = $this->ModelClassTree->category($classification['id']);
        $category = ['category_id' => $category['category_id']];

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is success', [
            'classification' => $classification,
            'parent'         => $myparent,
            'category'       => $category,
        ]);
    }

    /**
     * 送信されたカテゴリデータを登録する
     *
     */
    public function add()
    {
        $data = $this->validateParameter('classification', ['post']);
        if (!$data) return;

        $classification = $data['classification'];
        $classification['domain_id'] = $this->AppUser->current();

        // トランザクション開始
        $this->ModelClassifications->begin();

        try {
            // 分類を保存
            $newClassification = $this->ModelClassifications->add($classification);
            $this->AppError->result($newClassification);

            // 分類階層を保存
            $classification['id'] = $newClassification['data']['id'];
            $newClassTree = ($this->AppError->has()) ? null : $this->ModelClassTree->addTree($classification);
            $this->AppError->result($newClassTree);

            // エラー判定
            if ($this->AppError->has()) {
                // ロールバック
                $this->ModelClassifications->rollback();
                $this->setResponseError('your request is failure.', $this->AppError->errors());
                return;
            }

            // コミット
            $this->ModelClassifications->commit();

        } catch(Exception $e) {
            // ロールバック
            $this->ModelClassifications->rollback();
            $this->setError('保存時に予期せぬエラーが発生しました。管理者へお問い合わせください。', 'UNEXPECTED_EXCEPTION', $e);
            return;
        }

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is success', ['classification' => $newClassification['data']]);
    }

    /**
     * 送信された分類データを更新する
     *
     */
    public function edit()
    {
        $data = $this->validateParameter('classification', ['post']);
        if (!$data) return;

        $classification  = $data['classification'];

        // トランザクション開始
        $this->ModelClassifications->begin();

        try {
            // 分類を保存
            $updateClassification = $this->ModelClassifications->save($classification);
            $this->AppError->result($updateClassification);

            // 分類階層を保存（変更時のみ）
            if ($this->ModelClassTree->isEdit($classification)) {
                $updateClassTree = ($this->AppError->has()) ? null : $this->ModelClassTree->editTree($classification);
                $this->AppError->result($updateClassTree);
            }

            // エラー判定
            if ($this->AppError->has()) {
                // ロールバック
                $this->ModelClassifications->rollback();
                $this->setResponseError('your request is failure.', $this->AppError->errors());
                return;
            }

            // コミット
            $this->ModelClassifications->commit();

        } catch(Exception $e) {
            // ロールバック
            $this->ModelClassifications->rollback();
            $this->setError('保存時に予期せぬエラーが発生しました。管理者へお問い合わせください。', 'UNEXPECTED_EXCEPTION', $e);
            return;
        }

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is success', ['classification' => $updateClassification['data']]);
    }

    /**
     * 指定された分類IDの分類データを削除する
     *
     */
    public function delete()
    {
        $data = $this->validateParameter('id', ['post']);
        if (!$data) return;

        $classificationId = $data['id'];

        // 子孫を取得
        $descendant = $this->ModelClassTree->descendant($classificationId, true);

        // トランザクション開始
        $this->ModelClassifications->begin();

        try {
            // 子孫をを含めすべて削除する
            foreach($descendant as $tree) {
                // 分類を削除(TableのDependencyを利用して依存データを削除)
                $deleteClassification = $this->ModelClassifications->delete($tree['descendant']);
                $this->AppError->result($deleteClassification);

                // エラー判定
                if ($this->AppError->has()) {
                    // ロールバック
                    $this->ModelClassifications->rollback();
                    $this->setResponseError('your request is failure.', $this->AppError->errors());
                    return;
                }
            }

            // コミット
            $this->ModelClassifications->commit();

        } catch(Exception $e) {
            // ロールバック
            $this->ModelClassifications->rollback();
            $this->setError('保存時に予期せぬエラーが発生しました。管理者へお問い合わせください。', 'UNEXPECTED_EXCEPTION', $e);
            return;
        }

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is succeed', ['classification' => $deleteClassification['data']]);
    }

    /**
     * 指定された識別子（kname）がユニークかどうかを取得する
     *
     */
    public function validateKname()
    {
        $data = $this->validateParameter('kname', ['post']);
        if (!$data) return;

        // 識別子のユニーク性をチェック
        $validate = $this->ModelClassifications->validateKname($data['kname'], $data['id']);

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is succeed', ['validate' => $validate]);
    }

    /**
     * カテゴリ配下のルート分類を取得する
     *
     */
    public function root()
    {
        $data = $this->validateParameter('category_id', ['post']);
        if (!$data) return;

        // ルート分類を取得する
        $classifications = $this->ModelClassTree->root($data['category_id']);

        // 配下分類を取得する
        foreach($classifications as $i => $classification) {
            $children = $this->ModelClassTree->tree($classification['ancestor']);
            $classifications[$i]['children'] = $children;
        }

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is succeed', ['classifications' => $classifications]);
    }

    /**
     * 指定分類配下の分類を取得する
     *
     */
    public function children()
    {
        $data = $this->validateParameter('classification_id', ['post']);
        if (!$data) return;

        // 配下分類を取得する
        $classifications = $this->ModelClassTree->tree($data['classification_id']);

        // 配下分類の配下分類を取得する
        foreach($classifications as $i => $classification) {
            $children = $this->ModelClassTree->tree($classification['descendant']);
            $classifications[$i]['children'] = $children;
        }

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is succeed', ['classifications' => $classifications]);
    }

    /**
     * 指定分類配下の分類と製品を取得する
     *
     */
    public function childrenWithProducts()
    {
        $data = $this->validateParameter('classification_id', ['post']);
        if (!$data) return;

        // 配下分類を取得する
        $classifications = $this->ModelClassTree->tree($data['classification_id']);

        // 配下の製品を取得する
        $products = $this->ModelProducts->treeNode($data['classification_id']);

        // 配下分類の配下分類を取得する
        foreach($classifications as $i => $classification) {
            $children          = $this->ModelClassTree->tree($classification['descendant']);
            $children_products = $this->ModelProducts->treeNode($classification['descendant']);
            $classifications[$i]['children'] = $children;
            $classifications[$i]['products'] = $children_products;
        }

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is succeed', ['classifications' => $classifications, 'products' => $products]);
    }


    /**
     * 分類一覧を検索する
     *
     */
    public function findList()
    {
        $data = $this->request->getData();
        if (!$data || !array_key_exists('term', $data) || !$this->request->is('post')) {
            $this->setResponse(true, 'your request is succeed but no parameter found', ['classifications' => []]);
            return;
        }

        // 配下分類を取得する
        $classificationId = array_key_exists('classification_id', $data) ? $data['classification_id'] : null;
        $classifications  = $this->ModelClassifications->find2List($data['term'], $classificationId);

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is succeed', ['classifications' => $classifications]);
    }

}
