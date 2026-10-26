<?php
class Transactionsapi extends DolibarrApi
{
    function ping(){
        return array('ok'=>1,'api'=>'transactionsapi');
    }
}