# Tasks API

## Swagger

1. Из корня проекта `test_future` запустите контейнеры: `docker compose up -d`.
2. Откройте Swagger UI: [http://localhost:8000/api/documentation](http://localhost:8000/api/documentation).
3. Выберите нужный метод, нажмите **Try it out**, заполните параметры или JSON-тело и нажмите **Execute**. Ответ сервера появится ниже.

## Проверка эндпоинтов

Начните с `POST /api/tasks`. Например, создайте задачу:

```json
{
  "title": "Купить продукты",
  "description": "Молоко и хлеб",
  "due_date": "2026-11-10T18:00:00Z",
  "status": "not_done",
  "priority": "medium",
  "category": "personal"
}
```

Скопируйте `id` из ответа и используйте его для проверки остальных методов:

- `GET /api/tasks` — список задач. Можно указать `search`, `sort` (`created_at` или `due_date`), `page` и `per_page`.
- `GET /api/tasks/{task}` — получить задачу по ID.
- `PATCH /api/tasks/{task}` — изменить только переданные поля, например `{ "title": "Купить молоко" }`. Также доступен `PUT`.
- `DELETE /api/tasks/{task}` — удалить задачу.

Успешное создание возвращает `id` и `message`, список — объект с массивом `data`, получение одной задачи — сам объект задачи, изменение и удаление — `message`. Для неверных данных сервер возвращает `422`, для отсутствующего ID — `404`.

Если вы изменили OpenAPI-аннотации, обновите Swagger-спецификацию из корня проекта: `docker compose exec php php artisan l5-swagger:generate`.
