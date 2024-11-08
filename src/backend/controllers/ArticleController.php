<?php

namespace Controllers;

use Services\ControllerService;

class ArticleController extends ControllerService
{
    protected $table = 'article';
    protected $primary_key = "id";
    public function __construct($fields = "*", $distinct = false)
    {
        parent::__construct(primary_key: $this->primary_key, table: $this->table, fields: $fields, distinct: $distinct);
    }
}
