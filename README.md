Почему не используем модуль Db?

В случае модуля db, вам надо делать дамп. Обычно дамп мы делаем с нашей локальной базы, в которой может меняться состояние, например, изменили структуру при помощи миграций или руками, забыли и теперь структура локальной бд отличается от структуры прома. Тесты будут не совсем корректны. 

В случае миграций, они каждый раз будут по-новой накатываться и будут исключать влияние локальных изменений.

В некоторых практиках хранят дамп в самой репе - это скорей всего вкусовые предпочтения, но с другой стороны, с ростом проекта будет быстрее накатить дамп, чем все миграции. Можно переделать создание дампа через команду докера, но опять же, если вы локальные миграции будете накатывать, все равно есть риск, что в локальной базе будут аномалии, хотя ci/cd нас дополнительно защищает.

1. Модуль использует следующие модули codeception:
   - codeception/module-cli
   - codeception/module-yii2
   
2. Установка:
    ```
   composer require dspl/codeception-yii2-migraion-module --dev
    ```
   
3. Пример конфига в codeception.yml
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
4. Пример конфига для suites:
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
5. Так же вам надо подключить tools\traits\MigrationTrait в ваш MigrateController
6. Опции:
    - populate: 
        Будет дропать бд и накатывать миграции перед запуском suite: unit, functional, console, etc.
    - cleanup: 
        Чистит таблицы после каждого теста, накатывает миграции (если есть)
    - excludeClearTables: 
        Список таблиц, которые не надо чистить после наката миграций. Это может касаться, например, справочников.
        Так же не надо чистить migration таблицу, в противном случае миграции будут накатывать повторно и выдавать ошибку.