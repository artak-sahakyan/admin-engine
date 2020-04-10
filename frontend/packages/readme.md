# engine

Здесь хранятся общие компоненты для всех сайтов. Чтобы установить, выполните:

```bash
npm install https://github.com/Redvoronik/yii2-engine
```

Если npm начнёт ругаться на то что репозиторий закрыт, нужно настроить вот эту
штуку https://git-scm.com/docs/git-credential-cache так, чтобы она хранила ключи
авторизации то ли всегда, то ли минут 10-15. После этого можно повторить установку,
всё должно заработать.

## Использование

```javascript
import { CackleComments } from 'engine';

if (document.querySelector('.article')) {
    new CackleComments({ id: 36238, url: document.location.href });
}
```
