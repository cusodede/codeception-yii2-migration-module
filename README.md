1. Пример конфига в codeception.yml
```
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
2. Так же вам надо подключить dspl\tools\traits\MigrationTrait в в ваш MigrateController