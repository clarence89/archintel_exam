<?php

namespace Controllers;

use Services\ControllerService;

class UserPermissionController extends ControllerService
{
    protected $table = 'user_permissions';
    protected $primary_key = "user_id";
    public function __construct($fields = "*", $distinct = false)
    {
        parent::__construct(primary_key: $this->primary_key, table: $this->table, fields: $fields, distinct: $distinct);
    }
}
