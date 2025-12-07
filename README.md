# Apple Test — тестовое задание (Yii2 Advanced)

Проект реализует функционал управления объектами **“яблоки”** в backend-приложении Yii2 Advanced Template.  
Логика полностью соответствует требованиям тестового задания: хранение яблок в БД, бизнес-правила (упасть, съесть, гниение), генерация и UI в админке.

---

## Стек технологий

- PHP 8.4
- Yii2 Advanced Template
- MySQL 8
- Docker / Docker Compose
- Apache 2.4

---

## Функциональность

### Свойства яблока:

- **color** — случайный цвет при создании
- **appeared_at** — момент появления (случайный unixTimestamp)
- **fell_at** — время падения
- **status**:
  - 0 — висит на дереве
  - 1 — лежит на земле
  - 2 — гнилое
- **eaten_percent** — процент съеденной части

### Бизнес-правила:

- На дереве:
  - нельзя есть
  - гнить не может
- После падения:
  - можно есть
  - после 5 часов → автоматически становится **гнилым**
- Нельзя съесть гнилое яблоко
- Если съедено 100% — оно удаляется из БД

---

## Архитектура

Использованы подходы:
- SOLID
- Service Layer
- Repository Pattern
- Формы (FormObject)

### Структура backend:

```
backend/
  controllers/AppleController.php
  services/AppleService.php
  repositories/AppleRepository.php
  models/Apple.php
  models/AppleForm.php
  views/apple/
```

### Компоненты:

- **AppleService** — бизнес-логика (падение, еда, гниение)
- **AppleRepository** — работа с БД
- **AppleForm** — валидация ввода
- **AppleController** — web-интерфейс

---

## Docker

Проект полностью контейнеризирован.

### Запуск:

```bash
docker compose up --build -d
```

Backend открывается по адресу:

```
http://localhost:21080/
```

### Авторизация:

```
login: admin
password: admin
```

---

## Миграции

Миграции применяются автоматически контейнером.

Ручной запуск:

```bash
docker exec -it yii2-apple-test-backend-1 php yii migrate
```

---

## Примечание про красивые URL

Роутинг на момент сдачи тестового задания оставлен стандартным (`index.php?r=controller/action`), что позволило сфокусироваться на архитектуре, сервисном слое и корректной бизнес-логике.  
При необходимости ЧПУ подключаются одной строкой через `urlManager`.

---

## Контакты

Автор: Георгий (Гио)  
Email: —  
GitHub: —

---