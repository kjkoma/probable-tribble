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
 * Kitting Patterns API Controller
 *
 */
class ApiKittingPatternsController extends ApiController
{
    /**
     * 本クラスの初期化処理を行う
     *  
     * - - -
     */
    public function initialize()
    {
        parent::initialize();
        $this->_loadComponent('ModelKittingPatterns');
    }

    /**
     * キッティングパターン一覧を検索する
     *
     */
    public function findList()
    {
        $data = $this->request->getData();
        if (!$data || !array_key_exists('term', $data) || !$this->request->is('post')) {
            $this->setResponse(true, 'your request is succeed but no parameter found', ['patterns' => []]);
            return;
        }

        // パターンを取得する
        $patternKbn  = array_key_exists('pattern_kbn' , $data) ? $data['pattern_kbn']  : null;
        $patternType = array_key_exists('pattern_type', $data) ? $data['pattern_type'] : null;
        $reuseKbn    = array_key_exists('reuse_kbn'   , $data) ? $data['reuse_kbn']    : null;
        $patterns  = $this->ModelKittingPatterns->find2List($data['term'], $patternKbn, $patternType, $reuseKbn);

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is succeed', ['patterns' => $patterns]);
    }

}
