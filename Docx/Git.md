# Git — проект nexora2026

**Репозиторий:** https://github.com/AleksanderKhatkov9/nexora2026.git  
**Remote:** `origin`  
**Основные ветки:** `main`, `developer`

> При push/pull входите как **AleksanderKhatkov9** (PAT или SSH).  
> Ошибка `denied to AleksanderKhatkov` — в Windows удалить старые credentials для `github.com` и войти снова.

---

## Ежедневный цикл

```bash
cd /d/D/Progrmmer/Project/Docker/nexora2026

git status
git pull
# ... правки в коде ...
git add .
git commit -m "описание изменений"
git push
```

---

## Ветки в проекте

### Посмотреть ветки

```bash
git branch          # локальные
git branch -r       # удалённые (origin/...)
git branch -vv      # локальные + tracking
```

### Переключиться на main

```bash
git switch main
git pull
```

### Переключиться на developer

```bash
git switch developer
git pull
```

### Ветка есть на GitHub, локально нет

```bash
git fetch origin
git switch developer
# или явно:
git switch -c developer origin/developer
```

### Новая ветка для задачи

```bash
git switch developer
git pull
git switch -c feature/имя-задачи
# ... работа, коммиты ...
git push -u origin feature/имя-задачи
```

---

## Скачать все изменения с сервера

```bash
git fetch --all --prune
git switch developer
git pull
git switch main
git pull
```

---

## Коммиты и отмена

```bash
git diff                    # что изменено (не в индексе)
git diff --staged           # что уже в git add
git add file.txt            # один файл
git add .                   # все изменения
git commit -m "fix: текст"

git restore file.txt        # отменить правки в файле
git restore --staged file.txt   # убрать из индекса, файл не трогать
```

Временно спрятать незакоммиченное:

```bash
git stash
git stash pop
```

---

## main разошёлся с origin (diverged)

**Слияние (merge):**

```bash
git switch main
git pull origin main
```

**Rebase (линейная история):**

```bash
git switch main
git pull --rebase origin main
```

**Сделать main как на GitHub** (локальные незапушенные коммиты на main пропадут):

```bash
git switch main
git fetch origin
git reset --hard origin/main
```

---

## Push / Pull

```bash
git push                    # текущая ветка → origin
git push -u origin developer   # первый push новой ветки
git pull                    # обновить текущую ветку
```

---

## История и диагностика

```bash
git log --oneline -15
git show <hash>
git remote -v
```

---

## Авторизация GitHub (HTTPS, Windows)

1. Панель управления → **Диспетчер учётных данных** → удалить `git:https://github.com`
2. GitHub → Settings → Developer settings → **Personal access token** (scope `repo`)
3. При `git push`: логин **AleksanderKhatkov9**, пароль — **токен**

**SSH (альтернатива):**

```bash
git remote set-url origin git@github.com:AleksanderKhatkov9/nexora2026.git
git push
```

---

## Полезные алиасы (по желанию)

```bash
git config --global alias.st status
git config --global alias.co switch
git config --global alias.br branch
```

---

## Справочник команд

| Задача | Команда |
|--------|---------|
| Статус | `git status` |
| Обновить ветку | `git pull` |
| Скачать все ветки с remote | `git fetch --all --prune` |
| Ветка только на remote | `git fetch` → `git switch имя` |
| Отправить изменения | `git push` |
| Список веток | `git branch -vv` |
| Клонировать проект | `git clone https://github.com/AleksanderKhatkov9/nexora2026.git` |

---

## Workflow

```
fetch/pull  ←  origin (GitHub)
     ↓
  developer  →  feature/xxx  →  commit  →  push  →  PR / merge
     ↓
   main
```
