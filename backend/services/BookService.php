<?php
declare(strict_types=1);
require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/BookDao.php';
require_once __DIR__ . '/../dao/CategoryDao.php';
class BookService extends BaseService {
    public function __construct(?BookDao $dao=null){parent::__construct($dao ?? new BookDao(),'Book');}
    protected function validate(array $data,?int $id=null):void {
        if ($id===null) Validator::required($data,['title','author','isbn','category_id','total_copies']);
        foreach (['title'=>200,'author'=>160,'isbn'=>30] as $field=>$max) Validator::text($data,$field,$max);
        foreach (['category_id'=>1,'total_copies'=>0,'available_copies'=>0] as $field=>$min) Validator::integer($data,$field,$min);
        if (isset($data['category_id']) && !(new CategoryDao())->get_by_id((int)$data['category_id'])) throw new ValidationException('Category does not exist.');
        $current=$id ? $this->get_by_id($id) : null;
        $total=(int)($data['total_copies'] ?? ($current['total_copies'] ?? 0));
        $available=(int)($data['available_copies'] ?? ($current['available_copies'] ?? $total));
        if ($available>$total) throw new ValidationException('Available copies cannot exceed total copies.');
        if ($current && $total-$available < (int)$current['total_copies']-(int)$current['available_copies']) throw new ValidationException('Cannot remove already borrowed copies.');
        if (isset($data['isbn'])) foreach ($this->get_all() as $book) if ($book['isbn']===$data['isbn'] && (int)$book['id']!==$id) throw new ValidationException('ISBN already exists.');
    }
    public function add(array $data):array { if (!isset($data['available_copies']) && isset($data['total_copies'])) $data['available_copies']=$data['total_copies'];return parent::add($data); }
}
