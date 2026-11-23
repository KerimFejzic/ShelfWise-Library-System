<?php
declare(strict_types=1);
require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/CategoryDao.php';
class CategoryService extends BaseService {
    public function __construct(?CategoryDao $dao=null) { parent::__construct($dao ?? new CategoryDao(),'Category'); }
    protected function validate(array $data,?int $id=null):void {
        if ($id===null) Validator::required($data,['name']);
        Validator::text($data,'name',100,2);
        Validator::text($data,'description',2000,0);
        if (isset($data['name'])) foreach ($this->get_all() as $c) if (strcasecmp($c['name'],$data['name'])===0 && (int)$c['id']!==$id) throw new ValidationException('Category name already exists.');
    }
}
