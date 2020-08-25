# Установка

    composer require php7bundle/crypt-tunnel

## Yii2

Копируем файл `vendor/php7bundle/crypt/install/files/crypto.php` в `frontend/web/crypto.php`.

В файле `.env` прописываем:

```dotenv
# Директория с ключами
RSA_DIRECTORY='common/runtime/rsa'
# Пара ключей текущего приложения
RSA_HOST_DIRECTORY='common/runtime/rsa/app'
# Пара ключей корневого удостоверяющего центра
RSA_CA_DIRECTORY='common/runtime/rsa/root'
```
