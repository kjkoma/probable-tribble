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
namespace App\Controller\Master\General;

use App\Controller\AppController;
use Cake\Event\Event;

/**
 * Companies Controller
 *  
 * 企業マスタメンテナンス用コントローラー
 *
 */
class CompaniesController extends AppController
{
    /**
     * 本クラスの初期化処理を行う
     *
     * - - -
     * @return void
     */
    public function initialize()
    {
        parent::initialize();
        $this->_loadComponent('SysModelSnames');
        $this->_loadComponent('ModelCompanies');
    }

    /**
     * アクション実行前の処理を行う
     *
     * - - -
     * @param \Cake\Event\Event イベント
     * @return void
     */
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
    }

    /**
     * 初期画面表示
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        // 一覧データ取得
        $companies   = $this->ModelCompanies->allWithKbn();
        $company_kbn = $this->SysModelSnames->byKey('COMPANY_KBN');

        $this->set(compact('companies', 'company_kbn'));
        $this->render();
    }
}
