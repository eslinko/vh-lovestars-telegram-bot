# LovestarBot

LovestarBot is a Telegram bot for work with Facebook events.
The bot works in conjunction with the [vh-lovestars](https://github.com/skorikdeveloper/vh-lovestars) service

## Setting up

1) Install the composer libraries.

```bash
composer install
```

2) Copy .env.example to .env

```bash
copy cp .env.example .env
```

3) In the .env file set the link to your service API

```bash
API_URL=https://example.com/admin/telegram-api/
```

4) In the .env file set the ID of your telegram bot

```bash
BOT_ID=example:example
```

## Useful links for using the bot
Delete webhook
https://api.telegram.org/bot{BOTID}/deleteWebhook

Set webhook
https://api.telegram.org/bot{BOTID}/setWebhook?url=https://example/webhook.php

Get updates
https://api.telegram.org/bot{BOTID}/getUpdates

## License

[GPL](https://www.gnu.org/licenses/gpl-3.0.html)