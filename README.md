# SMG Test Laravel Project

Этот проект представляет собой веб-приложение на Laravel, которое выполняет работу с API. Реализована единая логика для работы с различными сущностями (products, recipes, users, posts).

## ТЗ
Используя сайт https://dummyjson.com/docs/products
сделать по апи добавление, получение с сохранением в базе всех продуктов "iPhone"
Желательно сделать так, чтобы можно было быстро сделать потом и для recipes, posts, users


## Требования
- **PHP**: Версия 8.2 или выше

## Установка и запуск

### 1. Клонирование репозитория
```bash
git clone https://github.com/KapetanVodichka/SMG_Test_Laravel.git
cd SMG_Test_Laravel
```

### 2. Установка зависимостей
```bash
composer install
```

### 3. Копирование .env и генерация ключа приложения
```bash
copy .env.example .env  # Windows
cp .env.example .env   # Linux/Mac
```
```bash
php artisan key:generate
```

### 4. Создание и миграция базы данных
При выполнении команды миграции терминал может указать на отсутствие файла \`database/database.sqlite\` и предложить его создать. Выберите \`yes\` после команды:
```bash
php artisan migrate
```

### 5. Запуск приложения
```bash
php artisan serve
```

Приложение будет доступно по адресу [http://127.0.0.1:8000](http://127.0.0.1:8000).

## API Эндпоинты
- **GET \`/api/{entityType}\`**: Получить все записи указанного типа сущности (\`products\`, \`recipes\`, \`users\`, \`posts\`).
- **POST \`/api/{entityType}\`**: Создать новую сущность через внешний API.
- **GET \`/api/{entityType}/fetch\`**: Получить данные из внешнего API и сохранить их в базе данных.
