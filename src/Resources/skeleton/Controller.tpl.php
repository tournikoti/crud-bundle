<?= "<?php" ?>

namespace <?= $namespace ?>;

<?= $use_statements; ?>

class <?= $class_name ?> extends CRUDController
{
    public function __construct(<?= $admin_class_name;?> $admin)
    {
        $this->admin = $admin;
    }
}