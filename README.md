1. Модуль использует следующие модули codeception:
- codeception/module-cli
- codeception/module-yii2

2. Авторизация для composer:

    Создаем файл auth.json
    
    ```bash
    composer config gitlab-token.git.vimpelcom.ru <ДОМЕННЫЙ ЛОГИН> <ТОКЕН>
    ```
    
    Решение ошибок
    
    > fatal: unable to access '%git repositories%': server certificate verification failed. CAfile: none CRLfile: none
    
    Получим сертификат с git.vimpelcom.ru. Выполнять необходимо в контейнере где будет запускаться установка нового пакета
    
    ```bash
    openssl s_client -showcerts -servername git.vimpelcom.ru -connect git.vimpelcom.ru:443 </dev/null 2>/dev/null | sed -n -e '/BEGIN\ CERTIFICATE/,/END\ CERTIFICATE/ p'  > /usr/local/share/ca-certificates/git.vimpelcom.ru.pem
    ```
    
    Добавим полученный сертификат к остальным
    
    ```bash
    cat /usr/local/share/ca-certificates/git.vimpelcom.ru.pem | tee -a /etc/ssl/certs/ca-certificates.crt
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
5. Так же вам надо подключить dspl\tools\traits\MigrationTrait в ваш MigrateController
6. Опции:
    - populate
        Будет дропать бд и накатывает миграции перед запуском suite: unit, functional, console, etc.
    - cleanup
        Чистит таблицы после каждого теста, накатывает миграции (если есть)
    - excludeClearTables
        Список таблиц, которые не надо чистит после наката миграций. Это может касаться, например, справочников.