# GitHub — промпты для работы с репозиторием nexora2026

Команды Git — в [Git.md](./Git.md).  
Здесь — **готовые промпты** для Cursor / ChatGPT и контекст проекта на GitHub.

---

## Контекст проекта (вставлять в начало диалога)

```text
Проект: nexora2026 (монорепозиторий Docker + Laravel/Vue).
Репозиторий: https://github.com/AleksanderKhatkov9/nexora2026
Рабочая папка: d:\D\Progrmmer\Project\Docker\nexora2026

Ветки:
- main — стабильная, продакшен-история на GitHub
- developer — основная ветка для разработки
- feature/* — задачи от developer

Remote: origin (и upstream — тот же URL).
Аккаунт GitHub для push: AleksanderKhatkov9 (не AleksanderKhatkov).

Перед правками: git switch developer && git pull.
Коммиты: Conventional Commits (docs:, feat:, fix:).
```

---

## Промпты — ежедневная работа

### Обновить проект с GitHub

```text
В репозитории nexora2026 на ветке developer:
выполни git fetch --all, git pull, покажи git status и git log --oneline -5.
Если есть конфликты — опиши, как их разрешить, не удаляя чужие изменения.
```

### Закоммитить и отправить изменения

```text
В nexora2026 (ветка developer):
1. git status и git diff — кратко, что изменилось
2. Предложи сообщение коммита в формате Conventional Commits
3. git add, git commit, git push
Если push вернёт 403 — проверь, что используется аккаунт AleksanderKhatkov9.
```

### Переключиться на ветку с GitHub (локально её нет)

```text
В nexora2026 нужна ветка <имя> с origin.
Сделай git fetch origin и git switch <имя> (или git switch -c <имя> origin/<имя>).
Покажи git branch -vv.
```

---

## Промпты — ветки и слияние

### Создать feature-ветку

```text
В nexora2026:
- переключись на developer, сделай pull
- создай ветку feature/<краткое-имя-задачи>
- опиши, в каких папках ожидать изменения для задачи: <описание задачи>
```

### Подготовить Pull Request в main

```text
Репозиторий AleksanderKhatkov9/nexora2026.
Ветка feature/<имя> готова к ревью.

1. Убедись, что developer/main актуальны (fetch, rebase или merge по ситуации)
2. Сформулируй заголовок PR и описание (Summary + Test plan)
3. Перечисли изменённые ключевые файлы/модули
Не делай force-push в main без явного запроса.
```

### Проверить состояние main

```text
В nexora2026 проверь ветку main:
git fetch upstream, сравни main с upstream/main,
git status, git log --oneline main..upstream/main и наоборот.
Если diverged или unrelated histories — предложи безопасное выравнивание (reset --hard только с backup-веткой).
```

---

## Промпты — проблемы и диагностика

### Ошибка 403 при push

```text
git push в nexora2026 падает с:
Permission to AleksanderKhatkov9/nexora2026.git denied to AleksanderKhatkov.

1. Объясни причину (неверный аккаунт в credential manager)
2. Дай шаги для Windows: удалить git:https://github.com, войти как AleksanderKhatkov9 с PAT
3. Альтернатива: SSH remote
Не меняй git config --global user без запроса.
```

### Ветки разошлись (diverged)

```text
git status показывает: Your branch and 'origin/...' have diverged.

Ветка: <main|developer|другая>.
Покажи коммиты только локальные и только на remote.
Предложи: merge, rebase или reset --hard — с рисками для каждого варианта.
Спроси, нужно ли сохранить локальные коммиты в backup-ветку.
```

### Не могу вытянуть изменения (pull)

```text
В nexora2026 на ветке <имя> git pull не работает.
Покажи полный текст ошибки, git branch -vv, git remote -v.
Предложи пошаговое решение (fetch, switch, pull, при unrelated histories — reset с backup).
```

---

## Промпты — документация и коммиты

### Добавить/обновить документацию в Docx/

```text
Добавь в Docx/<файл>.md описание: <тема>.
Стиль как в Git.md и Docker.md: заголовки, bash-блоки, таблицы при необходимости.
Коммит: docs: <краткое описание на английийском>.
Ветка: developer.
```

### Подобрать сообщение коммита

```text
Изменения в nexora2026:
<кратко: что сделано, например: добавлен GitHub.md, исправлен docker-compose>

Предложи 3 варианта сообщения коммита (Conventional Commits, английский, до 72 символов).
```

---

## Правила для агента (кратко)

| Действие | Правило |
|----------|---------|
| Разработка | Работать в `developer`, не в `main` напрямую |
| Коммиты | `feat:`, `fix:`, `docs:`, `chore:` + понятное описание |
| Push | Только аккаунт **AleksanderKhatkov9** |
| `main` | Не `force-push`; выравнивание — через `reset --hard upstream/main` + backup |
| Секреты | Не коммитить `.env`, токены, пароли |
| PR | feature → developer или developer → main — по договорённости команды |

---

## Быстрые ссылки

| Ресурс | URL |
|--------|-----|
| Репозиторий | https://github.com/AleksanderKhatkov9/nexora2026 |
| Ветки | https://github.com/AleksanderKhatkov9/nexora2026/branches |
| Новый PR | https://github.com/AleksanderKhatkov9/nexora2026/compare |
| PAT (токены) | https://github.com/settings/tokens |

---

## Связанные файлы

- [Git.md](./Git.md) — команды Git для проекта
- [Docker.md](./Docker.md) — запуск через Docker
- [Promt.md](./Promt.md) — промпт для вёрстки из Figma
