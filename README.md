Использовались php=8.3, symfnoy=7, mysql=8

1. Скачать архив.
2. Создать env.local с подключением к БД
3. Использовать команду composer update --lock
4. Использовать команду php bin/console doctrine:migrations:migrate
5. Использовать команду php bin/console doctrine:schema:update --force
6. Использовать команду php bin/console shapecode:cron:scan
7. Использовать команду php bin/console shapecode:cron:run
