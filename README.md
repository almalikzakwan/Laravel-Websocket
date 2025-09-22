# Laravel 12 WebSocket Basic (Reverb + Echo)

This repository is a **basic demo project** showcasing how to set up **Laravel Reverb** (Laravel's official WebSocket server) together with **Laravel Echo** (frontend client) for real-time event broadcasting in Laravel 12.

---

## ✨ Features

- ✅ **Laravel 12.x** – Core framework  
- ✅ **Laravel Reverb** – WebSocket server for event broadcasting  
- ✅ **Laravel Echo** – Frontend listener for real-time events  
- ✅ **Event Broadcasting** – Includes both public and private event examples  
- ✅ **Jobs & Events** – Trigger events via queued jobs  
- ✅ **Frontend Setup** – Vite + ES Modules  

---

## 📦 Requirements

- PHP **^8.2**  
- Composer **^2.7**  
- Node.js **^20.19.0 or >=22.12.0**  
- NPM or Yarn  
- Laravel 12.x  

---

## ⚡ Installation

```bash
# Clone repo
git clone https://github.com/almalikzakwan/Laravel-Websocket.git

cd Laravel-Websocket

# Install PHP dependencies
composer install

# Install JS dependencies
npm install

# Copy environment
cp .env.example .env

# Generate key
php artisan key:generate
```

---

## 🔧 Environment Setup

Add broadcasting & Reverb configuration to `.env`:

```env
BROADCAST_CONNECTION=reverb

REVERB_APP_ID=local
REVERB_APP_KEY=local
REVERB_APP_SECRET=local
REVERB_HOST=127.0.0.1
REVERB_PORT=8080
```

Make sure to configure queue driver (example: `database`):

```env
QUEUE_CONNECTION=database
```

---

## 🚀 Running the Project

Run the following in separate terminals:

```bash
# 1. Start Laravel
php artisan serve --host=0.0.0.0 --port=8000

# 2. Start Reverb WebSocket server
php artisan reverb:start

# 3. Start Vite for frontend assets
npm run dev
```

---

## 📡 Example Event

### Event Class (`app/Events/MessageSent.php`)

```php
class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    public function __construct($message)
    {
        $this->message = $message;
    }

    public function broadcastOn()
    {
        return new Channel('public-channel');
    }
}
```

### Triggering Event in Controller

```php
event(new MessageSent("Hello from Laravel WebSocket!"));
```

---

## 🎧 Frontend Listener (`resources/js/app.js`)

```js
import './bootstrap';

window.Echo.channel('public-channel')
    .listen('MessageSent', (e) => {
        console.log("📡 Event received:", e.message);
    });
```

---

## ✅ Testing

1. Run all servers (`php artisan serve`, `php artisan reverb:start`, `npm run dev`).  
2. Trigger the event via route/controller.  
3. Check browser console – real-time message should appear.  

---

## 📖 References

- [Laravel Reverb Documentation](https://laravel.com/docs/12.x/reverb)  
- [Laravel Broadcasting](https://laravel.com/docs/12.x/broadcasting)  
- [Laravel Echo](https://laravel.com/docs/12.x/broadcasting#client-side-installation)  

---

👉 This repo is intended for **demo & learning purposes** on using WebSockets with Laravel 12.
