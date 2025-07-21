# Meatproduction API (Laravel)

REST API для управления заказами мясной продукции с аутентификацией, документацией Swagger и SQLite.

## 🛠 Технологии

![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![Swagger](https://img.shields.io/badge/Swagger-85EA2D?style=for-the-badge&logo=Swagger&logoColor=white)

### Установка
```bash
# Клонировать репозиторий
git clone https://github.com/fomich-sb/php_laravel_test_meatmanager.git
cd php_laravel_test_meatmanager

# Установить зависимости
composer install
npm install

# Настройка окружения (скопируйте и отредактируйте .env)
cp .env.example .env

# Генерация ключа
php artisan key:generate

# Миграции и сиды
php artisan migrate --seed

# Запуск сервера
php artisan serve
Доступно на http://localhost:8080
📚 Документация API
После запуска откройте в браузере:
http://localhost:8000/api/documentation


🌐 Эндпоинты
Метод	Путь	Описание
POST	/api/register	Регистрация пользователя
POST	/api/login	Вход в систему
GET	/api/products	Список продуктов
POST	/api/orders	Создание заказа
GET	/api/orders	История заказов


📄 Лицензия
MIT License. См. LICENSE для подробностей.

Автор: Фомин Михаил
Email: fomich-sb@bk.ru
Телеграм: @fomichsb
