<?php
// Offline validation smoke tests (no Composer or MySQL required).
require_once __DIR__ . '/../utils/Validator.php';
$checks=0;
Validator::required(['name'=>'Fiction'],['name']); $checks++;
Validator::integer(['total_copies'=>2],'total_copies',0); $checks++;
Validator::date(['due_at'=>'2026-11-20'],'due_at'); $checks++;
foreach ([
    fn()=>Validator::required([],['title']),
    fn()=>Validator::integer(['total_copies'=>-1],'total_copies',0),
    fn()=>Validator::date(['due_at'=>'2026-02-30'],'due_at'),
    fn()=>Validator::email(['email'=>'not-an-email'],'email'),
] as $test) {
    try { $test(); throw new RuntimeException('Invalid value was accepted.'); }
    catch (ValidationException $expected) { $checks++; }
}
echo "PASS: {$checks} validation checks completed.\n";
