<?php

namespace Tournikoti\CrudBundle\Generator;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\MakerBundle\Str;
use Symfony\Bundle\MakerBundle\Util\ClassNameDetails;
use Symfony\Bundle\MakerBundle\Util\UseStatementGenerator;
use Tournikoti\CrudBundle\Configuration\Model\Type\BooleanField;
use Tournikoti\CrudBundle\Configuration\Model\Type\DateTimeField;
use Tournikoti\CrudBundle\Configuration\Model\Type\StringField;

class ConfigurationListGenerator implements \Stringable
{
    private array $fields;

    private const FORMAT = "            ->add(new %s('%s', '%s'))";

    public function __construct(
        private EntityManagerInterface $entityManager,
        private ClassNameDetails       $entityNameDetails,
        private UseStatementGenerator  $useStatementGenerator
    )
    {
        foreach ($this->getFormFields() as $field => $type) {
            $this->fields[$field] = $this->getFieldClass($type);
            $this->useStatementGenerator->addUseStatement($this->fields[$field]);
        }
    }

    public function __toString(): string
    {
        $fields = ['$configurationList'];

        foreach ($this->fields as $field => $typeClass) {
            $fields[] = sprintf(self::FORMAT, Str::getShortClassName($typeClass), $field, Str::asHumanWords($field));
        }

        $fields[count($fields) - 1] .= ';';

        return implode(PHP_EOL, $fields) . PHP_EOL;
    }

    private function getFormFields(): array
    {
        $classMetadata = $this->entityManager->getClassMetadata($this->entityNameDetails->getFullName());

        $fields = [];
        foreach ($classMetadata->fieldMappings as $fieldName => $field) {
            if (in_array($field['type'], ['boolean', 'datetime_immutable', 'integer', 'string'])) {
                $fields[$fieldName] = $field['type'];
            }
        }

        return $fields;
    }

    private function getFieldClass(string $type): string
    {
        return match ($type) {
            'boolean' => BooleanField::class,
            'datetime_immutable' => DateTimeField::class,
            default => StringField::class,
        };
    }
}