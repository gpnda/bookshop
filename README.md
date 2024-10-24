
# Тестовое задание



# Установка:
- git init .
- git remote add origin https://github.com/gpnda/bookshop.git
- git pull origin master
- composer install
- // создать копию файла .env и переименовать в .env.local  
- // скорректировать в .env.local  - закомментировать postgre, раскомментировать sqlite
- php ./bin/console doctrine:migrations:migrate
- symfony server:start
- // перейти по адресу: http://127.0.0.1:8000/book/1



