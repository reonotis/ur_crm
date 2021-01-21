<?php

    class Pagenation{
        private $_nowID = '';
        private $_KENSUU = '' ;
        private $_firstID = '' ;
        private $_lastID = '' ;
        private $_currentNo = '' ;
        private $_prevID = '' ;
        private $_nextID = '' ;
        private $_client_ids = '' ;
        private $_session_client_id = '' ;

        function set_default($clients){
            $nowID = $clients['0'];
            $nowID = json_decode(json_encode($nowID), true);
            $this->_nowID = $nowID['id'];
            $this->_session_client_id = Session::get('search_client_id');
            if(isset($this->_session_client_id)) {
                $json_client_id = json_decode(json_encode($this->_session_client_id), true);
                $this->_client_ids = array_column( $json_client_id, 'id' );
                $this->_KENSUU = count($this->_client_ids);   // 配列件数
                $this->_firstID = current($this->_client_ids);   // 配列の最初のIDを格納
                $this->_lastID = end($this->_client_ids);   // 配列の最後のIDを格納
                $this->_currentNo = array_search($this->_nowID, $this->_client_ids) + 1;     // 何番目にいるか
                $this->_nextID = current(array_slice($this->_client_ids, $this->_currentNo , 1, true));
            }
        }

        function prevID(){
            if(isset($this->_session_client_id)) {
                $this->_prevID = current(array_slice($this->_client_ids, array_search($this->_nowID, $this->_client_ids)-1, 1, true));
                if( $this->_firstID < $this->_nowID ){
                    echo "<a href='../../client/display/". $this->_prevID ."'>前へ</a>" ;
                }
            }
        }
        function nextID(){
            if(isset($this->_session_client_id)) {
                $this->_nextID = current(array_slice($this->_client_ids, $this->_currentNo, 1, true));
                if( $this->_lastID > $this->_nowID ){
                    echo "<a href='../../client/display/". $this->_nextID ."'>次へ</a>" ;
                }
            }
        }
        function firstID(){
            if(isset($this->_session_client_id)) {
                if( $this->_firstID < $this->_nowID ){
                    echo "<a href='../../client/display/". $this->_firstID ."'><最初へ</a>" ;
                }
            }
        }
        function lastID(){
            if(isset($this->_session_client_id)) {
                if( $this->_lastID > $this->_nowID ){
                    echo "<a href='../../client/display/". $this->_lastID ."'>最後へ></a>" ;
                }
            }
        }
        function KENSUU(){
            if(isset($this->_session_client_id)) {
                echo  $this->_currentNo . "件 / ". $this->_KENSUU ."件" ;
            }
        }

    }

    $pagenation = new Pagenation;
    $pagenation->set_default($clients);
?>





<div class="container" >
    <div class="client_pagenation" >

            <?php $pagenation->firstID() ?>
            <?php $pagenation->prevID() ?>
            <?php $pagenation->KENSUU() ?>
            <?php $pagenation->nextID() ?>
            <?php $pagenation->lastID() ?>
    </div>
</div>
