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
namespace App\Controller\Api\Asset;

use \Exception;
use App\Controller\Api\ApiController;
use Cake\Core\Configure;

/**
 * Asset Attributes API Controller
 *
 */
class ApiAssetAttributesController extends ApiController
{
    /**
     * 本クラスの初期化処理を行う
     *  
     * - - -
     */
    public function initialize()
    {
        parent::initialize();
        $this->_loadComponent('ModelAssetAttributes');
    }

    /**
     * 送信された資産属性データを編集する
     *
     */
    public function edit()
    {
        $data = $this->validateParameter('attr', ['post']);
        if (!$data) return;

        try {
            // 資産属性を保存
            $updateAttribute = $this->ModelAssetAttributes->save($data['attr']);
            $this->AppError->result($updateAttribute);

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
        $this->setResponse(true, 'your request is success', ['attr' => $updateAttribute['data']]);
    }

}

