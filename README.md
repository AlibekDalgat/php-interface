# Запуск
Перейти в директорию проекта и скачать зависимости
```
composer install   
```
Запустить приложение (порт работы указыается с командой запуска)
```
php -S localhost:8080 index.php
```
# Примеры
❗ Go-сервис должен быть уже запущен
### 1. POST /create-task
**Запрос:**
```
curl --request POST 'localhost:8080/create-task' \
--header 'Content-Type: application/x-www-form-urlencoded' \
--data 'input_data=test'
```
**Тело ответа:**
```
{"task_id":1}
```
### 2. GET /task-status/{task_id}
**Запрос:**
```
curl localhost:8080/task-status/1
```
**Тело ответа:**
```
{"status":"completed","result":"Результат: test"}
```
### Графический интерфейс
Для отправки запросов в php-сервис также имется html-страница по адресу localhost:8080  