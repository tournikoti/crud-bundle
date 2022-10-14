<?= "<?php\n" ?>

namespace <?= $namespace ?>;

<?= $use_statements; ?>

class <?= $class_name ?> extends AdminCRUD
{
    public function configurationList(ConfigurationListInterface $configurationList): void
    {
        <?= $configurator_field ?>
    }

    public function getEntityClass(): string
    {
        return <?= $entity_class_name ?>::class;
    }

    public function getControllerClass(): string
    {
        return <?= $controller_class_name ?>::class;
    }

    public function getFormType(): string
    {
        return <?= $form_class_name ?>::class;
    }

    public function getRouterPrefix(): string
    {
        return '<?= $router_prefix ?>';
    }
}