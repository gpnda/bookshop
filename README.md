
# Тестовое задание

помню что указано mysql, но пока на компах на которых работаю удобнее sqlite


# Установка:
- git clone https://github.com/gpnda/bookshop.git .
- composer install
- // создать копию файла .env и переименовать в .env.local  
- // скорректировать в .env.local  - закомментировать postgre, раскомментировать sqlite
- php ./bin/console doctrine:migrations:migrate
- php ./bin/console seed
- symfony server:start
- GET: http://127.0.0.1:8000/api/v1/books
- GET: http://127.0.0.1:8000/api/v1/book/1
- DELETE: http://127.0.0.1:8000/api/v1/book/1
- POST: http://127.0.0.1:8000/api/v1/author_create
    JSON body:
        {
            "name":"All  5 iiiii"
        }



