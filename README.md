Инструкция по запуску сервиса.
-------------------------------

1) Создаем локально в корне проекта файл .env. В него необходимо добавить следующие константы:
    >API_AUTH_KEY=*key_name*
    >
    >API_TELEGRAM_URL=""
    >API_WHATSAPP_URL=""
    >API_VIBER_URL=""
    >
    >API_TELEGRAM_TOKEN=""
    >API_WHATSAPP_TOKEN=""
    >API_VIBER_TOKEN=""
    
    а также поменять значение на такое:
    > QUEUE_CONNECTION=database

2) Накатить миграции через команду php artisan migrate
3) Запустить php artisan serve для запуска динамического сервера приложения
4) После это запускаем слушателя очереди php artisan queue:work
5) Обращаемся rest post запросом на url *url_name/api/message* c параметров request, который содержит массив следующего типа:
    
    >  {'contacts' : '89533333333;89521111111;85222222222','message':'Test message 2','messengers':'Telegram;Viber'}
    
    Также, необходимо добавить заголовок Authorization: Bearer *сгенерированный ключ в base64_encode, который вы укажите в файле .env API_AUTH_KEY*
6) Наблюдаем за работой разбора задач
7) Добавляем новые по необходимости