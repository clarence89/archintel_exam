<?php

namespace Controllers;

use Services\ControllerService;

class DirectoryController extends ControllerService
{
    protected $table = 'directory';
    protected $primary_key = "id";
    public function __construct($fields = "*", $distinct = false)
    {
        parent::__construct(primary_key: $this->primary_key, table: $this->table, fields: $fields, distinct: $distinct);
    }
}
