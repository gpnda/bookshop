
# Тестовое задание

помню что указано mysql, но пока на компах на которых работаю удобнее sqlite


# Установка:
- git init .
- git remote add origin https://github.com/gpnda/bookshop.git
- git pull origin master
- composer install
- // создать копию файла .env и переименовать в .env.local  
- // скорректировать в .env.local  - закомментировать postgre, раскомментировать sqlite
- php ./bin/console doctrine:migrations:migrate
- symfony server:start
- php ./bin/console seed
- // перейти по адресу: http://127.0.0.1:8000/books
- // перейти по адресу: http://127.0.0.1:8000/book/1
- // контроллер создания автора - пока не работает



