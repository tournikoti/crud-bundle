# Tournikoti CRUD Bundle

## Présentation

Ce bundle vous permet de générer des routes permettant :

 - De lister les entités
 - De voir une entité
 - De créer une entité
 - De modifier une entité
 - De supprimer une entité

## Installation

### Composer

```bash
composer require tournikoti/crud-bundle:dev-master
```

### Routes

```yaml
tournikoti_crud:
  resource: '@TournikotiCrudBundle/config/routes.yaml'
  prefix: /admin
```

### Générer un CRUD

```bash
bin/console make:admin:crud
```
