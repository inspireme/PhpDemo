<?php

class GuestbookController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
    }

    /**
     * 本一覧
     */
    public function indexAction() {
        // action body
        //パラメータを取得
        $msg = $this->_request->msg;
        file_put_contents("log.txt", $msg, FILE_APPEND); //ログを出力する
        $guestbook = new Application_Model_GuestbookMapper();
        $this->view->entries = $guestbook->fetchAll();
        if ($msg === 0) {
            $this->view->msg = "delete failure";
        } else if ($msg > 0) {
            $this->view->msg = "delete success";
        }
    }

    /**
     * 本を追加・更新する画面初期
     */
    public function signAction() {
        //パラメータを取得
        $id = $this->_request->id;
        $guestbookobj = new Application_Model_Guestbook();
        if (!empty($id)) {//更新モード
            $guestbook = new Application_Model_GuestbookMapper();
            $guestbook->find($id, $guestbookobj);
            $this->view->entry = $guestbookobj;
        } else {//新規モード
            $this->view->entry = $guestbookobj;
        }
    }

    /**
     * 本を削除
     */
    public function deleteAction() {
        //パラメータを取得
        $id = $this->_request->id;
        if (!empty($id)) {
            //file_put_contents( "log.txt",$id , FILE_APPEND);//ログを出力する
            $guestbook = new Application_Model_GuestbookMapper();
            $result = $guestbook->delete($id);
        }
        //ブック一覧へリダイレクトする
        $this->_redirect('/guestbook/index?msg=' . $result);
    }

    /**
     * 本を登録する
     */
    public function saveAction() {
        //パラメータを取得する
        $comment = $this->_request->comment;
        $email = $this->_request->getPost('email');
        $id = $this->_request->getPost('id');
        file_put_contents("log.txt", $id . $email . $comment, FILE_APPEND); //ログを出力する

        $guestbook = new Application_Model_GuestbookMapper();
        $guestbookobj = new Application_Model_Guestbook();
        $guestbookobj->setComment($comment);
        $guestbookobj->setCreated(new DateTime('NOW'));
        $guestbookobj->setEmail($email);
        $guestbookobj->setId($id);
        $guestbook->save($guestbookobj);

        //ブック一覧へリダイレクトする
        $this->_redirect('/guestbook/index');
    }

}
