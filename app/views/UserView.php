<?php

class UserView{

    
    function showCreateUSer($message = null){
        require_once "template/CreateUSer.phtml";
    }

    function showEditUSer($message = null){
        require_once "template/editUSer.phtml";
    }

    function showUsers($users){
        require_once "template/userList.phtml";
    }
}