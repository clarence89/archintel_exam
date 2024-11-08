<?php

namespace Controllers;

use Services\ControllerService;

class RolePermissionController extends ControllerService
{
    protected $table = 'role_permissions';
    protected $primary_key = "role_id";
    public function __construct($fields = "*", $distinct = false)
    {
        parent::__construct(primary_key: $this->primary_key, table: $this->table, fields: $fields, distinct: $distinct);
    }
}
