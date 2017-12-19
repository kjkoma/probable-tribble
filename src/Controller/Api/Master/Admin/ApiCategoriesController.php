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

use \Exception;
use App\Controller\Api\ApiController;

/**
 * Categories API Controller
 *
 */
class ApiCategoriesController extends ApiController
{
    /**
     * 本クラスの初期化処理を行う
     *  
     * - - -
     */
    public function initialize()
    {
        parent::initialize();
        $this->_loadComponent('ModelCategories');
    }

    /**
     * 指定されたカテゴリIDのカテゴリ情報を取得する
     *
     */
    public function category()
    {
        $data = $this->validateParameter('category_id', ['post']);
        if (!$data) return;

        // カテゴリを取得
        $category = $this->ModelCategories->get($data['category_id']);
        if (!$category) {
            $this->setError('指定されたデータがありません。', 'NOT_FOUND_CATEGORY', $data, 404);
            return;
        }

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is success', ['category' => $category]);
    }

    /**
     * カテゴリ一覧を取得する
     *
     */
    public function categories()
    {
        if (!$this->request->is('post')) {
            $this->setError('指定されたHTTPメソッドには対応していません。', 'UNSUPPORTED_HTTP_METHOD');
            return;
        }

        // カテゴリを取得
        $categories = $this->ModelCategories->valid();
        if (count($categories) == 0) {
            $this->setError('指定されたデータがありません。', 'NOT_FOUND_CATEGORY', $data, 404);
            return;
        }

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is success', ['categories' => $categories]);
    }

    /**
     * 送信されたカテゴリデータを登録する
     *
     */
    public function add()
    {
        $data = $this->validateParameter('category', ['post']);
        if (!$data) return;

        $category = $data['category'];
        $category['domain_id'] = $this->AppUser->current();

        try {
            // カテゴリを保存
            $newCategory = $this->ModelCategories->add($category);
            $this->AppError->result($newCategory);

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
        $this->setResponse(true, 'your request is success', ['category' => $newCategory['data']]);
    }

    /**
     * 送信されたカテゴリデータを更新する
     *
     */
    public function edit()
    {
        $data = $this->validateParameter('category', ['post']);
        if (!$data) return;

        $category  = $data['category'];

        try {
            // カテゴリを保存
            $updateCategory = $this->ModelCategories->save($category);
            $this->AppError->result($updateCategory);

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
        $this->setResponse(true, 'your request is success', ['category' => $updateCategory['data']]);
    }

    /**
     * 指定されたカテゴリIDのカテゴリデータを削除する
     *
     */
    public function delete()
    {
        $data = $this->validateParameter('id', ['post']);
        if (!$data) return;

        $category_id = $data['id'];

        // トランザクション開始
        $this->ModelCategories->begin();

        try {
            // カテゴリを削除(TableのDependencyを利用して依存データを削除)
            $deleteCategory = ($this->AppError->has()) ? null : $this->ModelCategories->delete($category_id);
            $this->AppError->result($deleteCategory);

            // エラー判定
            if ($this->AppError->has()) {
                // ロールバック
                $this->ModelCategories->rollback();
                $this->setResponseError('your request is failure.', $this->AppError->errors());
                return;
            }

            // コミット
            $this->ModelCategories->commit();

        } catch(Exception $e) {
            // ロールバック
            $this->ModelCategories->rollback();
            $this->setError('保存時に予期せぬエラーが発生しました。管理者へお問い合わせください。', 'UNEXPECTED_EXCEPTION', $e);
            return;
        }

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is succeed', ['category' => $deleteCategory['data']]);
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
        $validate = $this->ModelCategories->validateKname($data['kname'], $data['id']);

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is succeed', ['validate' => $validate]);
    }
}
