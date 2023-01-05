<?php

class Tsphp_new_import
{
    public function __construct()
    {

    }

    public function tsphp_new_import()
    {
        $clear_db = new Tsphp_import_build();
        $clear_db->tsphp_unistall_DB();
    }


}