1. Вам необходимо для этого модуля использовать:
- codeception/module-cli
- codeception/module-yii2

2. Пример конфига в codeception.yml
```
paths:
  tests: tests
  output: tests/_output
  data: tests/_data
  support: tests/_support
  envs: tests/_envs
actor_suffix: Tester
bootstrap: _bootstrap.php
modules:
  config:
    Yii2Migration:
      yiiBinPath: "tests/yii"
      populate: true
      cleanup: true
      excludeClearTables:
        - migration
        - sale_point_branding_types
        - sale_point_channels
        - sale_point_clusters
        - sale_point_head_org
        - sale_point_sale_channels
        - sale_point_directions
        - sale_point_branches
        - ref_legal_forms
        - partner_contractor_activity_roles
```
3. Пример конфига для suites:
```
actor: FunctionalTester
modules:
  enabled:
    - \Helper\Functional
    - Yii2Migration
    - Cli
    - Yii2:
        configFile: 'config/test.php'
  step_decorators: ~
```
4. Так же вам надо подключить dspl\tools\traits\MigrationTrait в ваш MigrateController