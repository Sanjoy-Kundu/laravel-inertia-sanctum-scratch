# 🚀 লারাভেল ১৩ + ইনার্শিয়া (Inertia.js) + কাস্টম অথ (Sanctum) টাস্ক ম্যানেজমেন্ট নোটস

এই ডকুমেন্টটি আমাদের প্রজেক্টের প্রধান লার্নিং ডায়েরি বা রোডম্যাপ। আমরা কোনো রেডিমেড স্টার্টার কিট ছাড়া একদম শূন্য (Scratch) থেকে এই কাস্টম প্রজেক্টটি তৈরি করছি।

---

## 🗺️ পার্ট ১: সার্ভার-সাইড (Laravel) সেটআপ

### ধাপ ১.১: Inertia.js সার্ভার প্যাকেজ ইন্সটল
লারাভেল ব্যাকএন্ডের সাথে ইনার্শিয়ার যোগাযোগের জন্য অফিশিয়াল প্যাকেজটি নামাতে হবে।
* **ডকুমেন্টেশন সোর্স:** [Inertia.js Server-side Installation](https://inertiajs.com/server-side-setup)

* **টার্মিনাল কমান্ড:**
```bash
composer require inertiajs/inertia-laravel


### 📄   ধাপ ১.২: রুট টেমপ্লেট (`app.blade.php`) তৈরি করা
ইনার্শিয়া অ্যাপে ব্লেডের মতো প্রতিটা পেজের জন্য আলাদা আলাদা `.blade.php` ফাইল লাগে না। পুরো প্রজেক্টে এই একটা মাত্র ব্লেড ফাইলই থাকবে মেইন কন্টেইনার হিসেবে। বাকি সব পেজ আমরা বানাবো Vue ৩ দিয়ে।

* **আপনার করণীয় (Actions):**
  1. প্রথমে `resources/views/` ফোল্ডারে যান এবং সেখানে থাকা ডিফল্ট `welcome.blade.php` ফাইলটি **ডিলিট** করে দিন।
  2. ওই একই ফোল্ডারে `app.blade.php` নামে একটি নতুন খালি ফাইল তৈরি করুন।
  3. নতুন ফাইলে নিচের অফিশিয়াল কোডটুকু পেস্ট করে সেভ করুন।

* **কোড (Code):**
```html
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @vite('resources/js/app.js')
        <x-inertia::head />
    </head>
    <body>
        <x-inertia::app />
    </body>
</html>


### ⚙️  ধাপ ১.৩: ইনার্শিয়া মিডলওয়্যার জেনারেট ও কনফিগার
লারাভেলের সেশন, ফ্ল্যাশ মেসেজ এবং অথেনটিকেশন ডাটা ফ্রন্টএন্ডে (Vue ৩) শেয়ার করার জন্য এই মিডলওয়্যারটি সেটআপ করা বাধ্যতামূলক।

* **আপনার করণীয় (Actions):**
  1. প্রথমে টার্মিনালে নিচের কারিগর (Artisan) কমান্ডটি রান করে মিডলওয়্যার ফাইল তৈরি করুন।
  2. এরপর `bootstrap/app.php` ফাইলটি ওপেন করে `web` গ্রুপে মিডলওয়্যারটি যুক্ত করে দিন।

* **১. টার্মিনাল কমান্ড:**
```bash
php artisan inertia:middleware

*(এর ফলে `app/Http/Middleware/HandleInertiaRequests.php` ফাইলটি তৈরি হবে)*

  2. **`bootstrap/app.php` ফাইলে সেটআপ করার নিয়ম:** 
     * ফাইলের একদম উপরে মিডলওয়্যার ক্লাসের পাথটি ইম্পোর্ট করেছি: `use App\Http\Middleware\HandleInertiaRequests;`
     * এরপর `->withMiddleware()` ফাংশনের ভেতরে নিচের কোডের মতো করে মিডলওয়্যারটি ওয়েব গ্রুপে অ্যাপেন্ড (যুক্ত) করে দিয়েছি।

* **লারাভেল ১৩-এর মেইন কনফিগারেশন কোড (`bootstrap/app.php`):**
```php
<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\HandleInertiaRequests; // ১. এই ফাইলটি এখানে ইম্পোর্ট করা হয়েছে

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // ২. এই ওয়েব মেথডের মাধ্যমে আমরা মিডলওয়্যারটি গ্লোবাল ওয়েব গ্রুপে যুক্ত করেছি
        $middleware->web(append: [
            HandleInertiaRequests::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();


--------------------------------------------

## 🎨 পার্ট ২: ক্লায়েন্ট-সাইড (Vue 3 & Vite) সেটআপ

ডকুমেন্টেশনের গাইডলাইন অনুযায়ী ফ্রন্টএন্ড সেটআপকে দুটি ভাগে ভাগ করা হয়েছে: Prerequisites (পূর্বশর্ত) এবং Installation (ইন্সটলেশন)।

### 🎯 ধাপ ২.১: Prerequisites (পূর্বশর্ত প্যাকেজ)
Inertia-র জন্য প্রথমে আমাদের ক্লায়েন্ট-সাইড ফ্রেমওয়ার্ক (Vue) এবং তার করিসপন্ডিং Vite প্লাগইন ইন্সটল করতে হবে।
* **ডকুমেন্টেশন সোর্স:** [Inertia.js Prerequisites](https://inertiajs.com/client-side-setup)

* **টার্মিনাল কমান্ড:**
```bash
npm install vue @vitejs/plugin-vue

------------------------------------------------
### ⚙️  ধাপ ২.২: Vite কনফিগারেশন (`vite.config.js`)
লারাভেল ১৩-এর ডিফল্ট কনফিগারেশন (Tailwind ও Bunny Fonts) ঠিক রেখে ইনার্শিয়ার জন্য Vue প্লাগইনটি মার্জ (Merge) করা হয়েছে।

* **ফাইল পাথ:** `vite.config.js`
* **কোড (Code):**
```javascript
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { bunny } from 'laravel-vite-plugin/fonts';
import tailwindcss from '@tailwindcss/vite';
import vue from '@vitejs/plugin-vue'; // Vue ইম্পোর্ট

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
            fonts: [
                bunny('Instrument Sans', { weights: [400, 500, 600] }),
            ],
        }),
        tailwindcss(),
        vue(), // Vue প্লাগইন যুক্ত
    ],
    server: {
        watch: { ignored: ['**/storage/framework/views/**'] },
    },
});





------------------------------------------------
### ⚙️  ধাপ ২.二: Vite কনফিগারেশন (`vite.config.js`)
لারাভেল ১৩-এর ডিফল্ট কনফিগারেশন (Tailwind ও Bunny Fonts) ঠিক রেখে ইনার্শিয়ার Vue প্লাগইন এবং অফিশিয়াল `inertia()` প্লাগইন সুন্দরভাবে মার্জ (Merge) করা হয়েছে।

* **ফাইল পাথ:** `vite.config.js`
* **কোড (Code):**
```javascript
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { bunny } from 'laravel-vite-plugin/fonts';
import tailwindcss from '@tailwindcss/vite';
import vue from '@vitejs/plugin-vue'; //new
import inertia from '@inertiajs/vite' //mew

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
            fonts: [
                bunny('Instrument Sans', {
                    weights: [400, 500, 600],
                }),
            ],
        }),
        tailwindcss(),
        vue(),
        inertia(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});





-------------------------------------------------
### 🚀 পরবর্তী মিশন: Initialize the Inertia app

যেহেতু আমাদের কনফিগারেশন এবং প্যাকেজ ইন্সটলেশন শেষ, এবার আমাদের ফ্রন্টএন্ডের মূল মস্তিস্ক বা বুটস্ট্র্যাপ ফাইল তৈরি করার পালা। 

ডকুমেন্টেশনের পরবর্তী ধাপ হলো **`resources/js/app.js`** ফাইলটি সেটআপ করা।

### 🧠  ধাপ ২.৪: ফ্রন্টএন্ড বুটস্ট্র্যাপ ফাইল (`resources/js/app.js`)
Inertia v2.0+ এর অফিশিয়াল গাইডলাইন অনুযায়ী একদম মিনিমাল এন্ট্রি পয়েন্ট ব্যবহার করে ফ্রন্টএন্ড অ্যাপ ইনিশিয়ালাইজ করা হয়েছে। আমাদের `vite.config.js` ফাইলের `@inertiajs/vite` প্লাগইনটি ব্যাকগ্রাউন্ডে অটোমেটিক পেজ রেজোলিউশন এবং মাউন্টিং হ্যান্ডেল করে।

* **আপনার করণীয় (Actions):** `resources/js/app.js` ফাইলে নিচের কোডটি লিখে সেভ করুন।
* **ডকুমেন্টেশন সোর্স:** [Inertia.js SInitialize the Inertia app](https://inertiajs.com/docs/v3/installation/client-side-setup)
* **কোড (Code):**
```javascript
import { createInertiaApp } from '@inertiajs/vue3'

createInertiaApp()




------------------------------------------------
---

### 🚀 পরবর্তী টেস্ট মিশন!
আমাদের ব্যাকএন্ড (Laravel) এবং ফ্রন্টএন্ড (Vue 3 + Inertia v2) এর সমস্ত কনফিগারেশন একদম অফিশিয়াল নিয়ম মেনে **১০০% শেষ**! 

এবার আমরা পরীক্ষা করে দেখব প্রজেক্টটি ঠিকঠাক রান করছে কি না। টেস্ট করার জন্য আমাদের ২টি ছোট কাজ করতে হবে:
১. `resources/js/` ফোল্ডারের ভেতর **`Pages`** নামে একটি নতুন ফোল্ডার তৈরি করুন এবং তার ভেতর **`Home.vue`** নামে একটা ফাইল বানান।
২. লারাভেলের `routes/web.php` ফাইলে গিয়ে পেজটি রিটার্ন করুন।

ফাইলটি সেভ করা হয়ে গেলে বলুন, আমরা টেস্ট কোডগুলো লিখে প্রজেক্ট ব্রাউজারে রান করব!



### 🚀 ধাপ ২.৫: প্রথম ইনার্শিয়া পেজ ও রাউট টেস্ট (The Live Test)
আমাদের ব্যাকএন্ড (Laravel 13) এবং ফ্রন্টএন্ড (Vue 3 + Inertia v2) এর সমস্ত কনফিগারেশন একদম অফিশিয়াল নিয়ম মেনে সফলভাবে শেষ করার পর, পুরো আর্কিটেকচারটি ঠিকঠাক কাজ করছে কিনা তা যাচাই করার জন্য একটি টেস্ট পেজ ও রাউট তৈরি করা হয়েছে।

-------------------------

#### 📂 ১. টেস্ট ভিউ পেজ তৈরি (`resources/js/Pages/Home.vue`)
Inertia.js ডিফল্টভাবে `Pages` ফোল্ডারের ভেতর ফ্রন্টএন্ড ফাইলগুলো খোঁজে। তাই এই ফাইলটি তৈরি করা হয়েছে।

* **আপনার করণীয় (Actions):** 1. `resources/js/` ফোল্ডারের ভেতরে `Pages` নামে একটি নতুন ফোল্ডার তৈরি করুন।
  2. তার ভেতর `Home.vue` নামে একটি ফাইল বানিয়ে নিচের কোডটি সেভ করুন।

* **কোড (Vue 3 Template):**
```vue
<template>
  <div style="text-align: center; margin-top: 100px; font-family: sans-serif;">
    <h1 style="color: #4edb8a; font-size: 3rem;">সবুজ বাতি! 🚀</h1>
    <p style="font-size: 1.2rem; color: #4a5568;">
      Laravel 13 + Inertia v2 + Vue 3 একদম ঠিকঠাক এবং রকেটের গতিতে কাজ করছে!
    </p>
  </div>
</template>

<script setup>
// এখানে ভবিষ্যতের ফ্রন্টএন্ড লজিক বা স্ক্রিপ্ট আসবে
</script>