[contributors-shield]: https://img.shields.io/github/contributors/hassan7303/BookLend-laravel.svg?style=for-the-badge
[contributors-url]: https://github.com/hassan7303/BookLend-laravel/graphs/contributors
[forks-shield]: https://img.shields.io/github/forks/hassan7303/BookLend-laravel.svg?style=for-the-badge&label=Fork
[forks-url]: https://github.com/hassan7303/BookLend-laravel/network/members
[stars-shield]: https://img.shields.io/github/stars/hassan7303/BookLend-laravel.svg?style=for-the-badge
[stars-url]: https://github.com/hassan7303/BookLend-laravel/stargazers
[license-shield]: https://img.shields.io/github/license/hassan7303/BookLend-laravel.svg?style=for-the-badge
[license-url]: https://github.com/hassan7303/BookLend-laravel/blob/master/LICENSE
[linkedin-shield]: https://img.shields.io/badge/-LinkedIn-blue.svg?style=for-the-badge&logo=linkedin&colorB=555
[linkedin-url]: https://www.linkedin.com/in/hassan-ali-askari-280bb530a/
[telegram-shield]: https://img.shields.io/badge/-Telegram-blue.svg?style=for-the-badge&logo=telegram&colorB=555
[telegram-url]: https://t.me/hassan7303
[instagram-shield]: https://img.shields.io/badge/-Instagram-red.svg?style=for-the-badge&logo=instagram&colorB=555
[instagram-url]: https://www.instagram.com/hasan_ali_askari
[github-shield]: https://img.shields.io/badge/-GitHub-black.svg?style=for-the-badge&logo=github&colorB=555
[github-url]: https://github.com/hassan7303
[email-shield]: https://img.shields.io/badge/-Email-orange.svg?style=for-the-badge&logo=gmail&colorB=555
[email-url]: mailto:hassanali7303@gmail.com

[![Contributors][contributors-shield]][contributors-url]
[![Forks][forks-shield]][forks-url]
[![Stargazers][stars-shield]][stars-url]
[![MIT License][license-shield]][license-url]
[![LinkedIn][linkedin-shield]][linkedin-url]
[![Telegram][telegram-shield]][telegram-url]
[![Instagram][instagram-shield]][instagram-url]
[![GitHub][github-shield]][github-url]
[![Email][email-shield]][email-url]

# Library API - Laravel + Sanctum

این پروژه یک **سیستم مدیریت کتابخانه با Laravel** است که امکان مدیریت کاربران، کتاب‌ها، نسخه‌های کتاب، امانت کتاب و جریمه‌ها را فراهم می‌کند. احراز هویت API با **Laravel Sanctum** انجام می‌شود و پیام‌های خطا به فارسی هستند.

---

## ⚡ تکنولوژی‌ها

* Laravel 12.x
* MySQL
* Laravel Sanctum (برای احراز هویت API)
* PHP 8.x
* Composer

---
## 🔐 احراز هویت با Sanctum

1. ثبت نام کاربر:

```http
POST /api/register
```

* پارامترها:

  * `full_name` (required)
  * `email` (required)
  * `password` (required)
  * `password_confirmation` (required)

2. ورود کاربر:

```http
POST /api/login
```

* پارامترها:

  * `email` (required)
  * `password` (required)
* پاسخ شامل توکن `Bearer` است که باید در هدر برای درخواست‌های محافظت شده استفاده شود:

```
Authorization: Bearer <YOUR_TOKEN>
```

3. خروج از حساب:

```http
POST /api/logout
```

* نیاز به هدر Authorization دارد.

---

## 📚 روت‌ها

### کتاب‌ها (Books)

| Method | Endpoint        | توضیح                        |
| ------ | --------------- | ---------------------------- |
| GET    | /api/books      | لیست کتاب‌ها (با pagination) |
| GET    | /api/books/{id} | مشاهده جزئیات یک کتاب        |
| POST   | /api/books      | ایجاد کتاب جدید              |
| PUT    | /api/books/{id} | ویرایش کتاب                  |
| DELETE | /api/books/{id} | حذف کتاب                     |

### نسخه‌های کتاب (Book Copies)

| Method | Endpoint                      | توضیح                               |
| ------ | ----------------------------- | ----------------------------------- |
| GET    | /api/copies                   | لیست نسخه‌های کتاب                  |
| GET    | /api/copies/{id}              | مشاهده نسخه خاص                     |
| POST   | /api/copies                   | ایجاد نسخه کتاب                     |
| PUT    | /api/copies/{id}              | ویرایش نسخه                         |
| POST   | /api/copies/{id}/library-only | قرار دادن نسخه در حالت فقط کتابخانه |

### امانت‌ها (Borrows)

| Method | Endpoint                       | توضیح                  |
| ------ | ------------------------------ | ---------------------- |
| POST   | /api/borrows                   | امانت گرفتن کتاب       |
| POST   | /api/borrows/return/{borrowId} | بازگرداندن کتاب        |
| GET    | /api/borrows/my                | مشاهده امانت‌های کاربر |

### جریمه‌ها (Fines)

| Method | Endpoint            | توضیح         |
| ------ | ------------------- | ------------- |
| GET    | /api/fines          | لیست جریمه‌ها |
| POST   | /api/fines/{id}/pay | پرداخت جریمه  |

> تمام روت‌های محافظت شده نیاز به هدر Authorization با توکن Sanctum دارند.

---

## ⚙️ دیتابیس

### Users

| ستون                   | نوع                   | توضیح                        |
| ---------------------- | --------------------- | ---------------------------- |
| id                     | BIGINT AUTO_INCREMENT | شناسه کاربر                  |
| full_name              | VARCHAR(255)          | نام کامل کاربر               |
| email                  | VARCHAR(255)          | ایمیل کاربر                  |
| phone                  | VARCHAR(20)           | شماره تلفن                   |
| password               | VARCHAR               | رمز عبور هش شده              |
| status                 | VARCHAR               | وضعیت کاربر (active/blocked) |
| created_at, updated_at | TIMESTAMP             | زمان ایجاد و ویرایش          |
| remember_token         | VARCHAR(100)          | توکن حفظ جلسه                |

### Authors

| ستون                   | نوع          | توضیح               |
| ---------------------- | ------------ | ------------------- |
| id                     | BIGINT       | شناسه نویسنده       |
| name                   | VARCHAR(255) | نام نویسنده         |
| created_at, updated_at | TIMESTAMP    | زمان ایجاد و ویرایش |

### Categories

| ستون                   | نوع          | توضیح               |
| ---------------------- | ------------ | ------------------- |
| id                     | BIGINT       | شناسه دسته‌بندی     |
| name                   | VARCHAR(255) | نام دسته‌بندی       |
| created_at, updated_at | TIMESTAMP    | زمان ایجاد و ویرایش |

### Books

| ستون                   | نوع          | توضیح               |
| ---------------------- | ------------ | ------------------- |
| id                     | BIGINT       | شناسه کتاب          |
| title                  | VARCHAR(200) | عنوان کتاب          |
| author_id              | BIGINT       | مرجع نویسنده        |
| category_id            | BIGINT       | مرجع دسته‌بندی      |
| description            | TEXT         | توضیحات             |
| created_at, updated_at | TIMESTAMP    | زمان ایجاد و ویرایش |

### BookCopies

| ستون                   | نوع         | توضیح                                                                   |
| ---------------------- | ----------- | ----------------------------------------------------------------------- |
| id                     | BIGINT      | شناسه نسخه                                                              |
| book_id                | BIGINT      | مرجع کتاب                                                               |
| barcode                | VARCHAR(50) | بارکد نسخه                                                              |
| status                 | VARCHAR     | وضعیت نسخه (available, borrowed, reserved, library_only, damaged, lost) |
| created_at, updated_at | TIMESTAMP   | زمان ایجاد و ویرایش                                                     |

### Borrows

| ستون                   | نوع       | توضیح                                  |
| ---------------------- | --------- | -------------------------------------- |
| id                     | BIGINT    | شناسه امانت                            |
| user_id                | BIGINT    | مرجع کاربر                             |
| book_copy_id           | BIGINT    | مرجع نسخه کتاب                         |
| borrow_date            | DATE      | تاریخ امانت                            |
| due_date               | DATE      | تاریخ سررسید                           |
| return_date            | DATE      | تاریخ بازگشت                           |
| status                 | VARCHAR   | وضعیت امانت (borrowed, returned, late) |
| created_at, updated_at | TIMESTAMP | زمان ایجاد و ویرایش                    |

### FineRules

| ستون                   | نوع         | توضیح               |
| ---------------------- | ----------- | ------------------- |
| id                     | BIGINT      | شناسه قانون جریمه   |
| per_day_amount         | INT         | مبلغ جریمه روزانه   |
| currency               | VARCHAR(10) | واحد پول            |
| active                 | BOOLEAN     | فعال/غیرفعال        |
| created_at, updated_at | TIMESTAMP   | زمان ایجاد و ویرایش |

### Fines

| ستون                   | نوع       | توضیح               |
| ---------------------- | --------- | ------------------- |
| id                     | BIGINT    | شناسه جریمه         |
| borrow_id              | BIGINT    | مرجع امانت          |
| days_late              | INT       | تعداد روز تأخیر     |
| amount                 | INT       | مبلغ جریمه          |
| paid                   | BOOLEAN   | وضعیت پرداخت        |
| paid_date              | DATE      | تاریخ پرداخت        |
| created_at, updated_at | TIMESTAMP | زمان ایجاد و ویرایش |

### PersonalAccessTokens (Sanctum)

| ستون                   | نوع         | توضیح                 |
| ---------------------- | ----------- | --------------------- |
| id                     | BIGINT      | شناسه توکن            |
| tokenable_type         | VARCHAR     | نوع مدل مربوطه        |
| tokenable_id           | BIGINT      | شناسه مدل مربوطه      |
| name                   | TEXT        | نام توکن              |
| token                  | VARCHAR(64) | مقدار توکن            |
| abilities              | TEXT        | قابلیت‌های توکن       |
| last_used_at           | TIMESTAMP   | آخرین استفاده از توکن |
| expires_at             | TIMESTAMP   | تاریخ انقضای توکن     |
| created_at, updated_at | TIMESTAMP   | زمان ایجاد و ویرایش   |

---
---

## ⚡ نکات حرفه‌ای

* ولیدیشن‌ها با Validator facade و پیام‌های فارسی هستند.
* استفاده از ثابت‌ها (`const`) برای وضعیت‌های BookCopy و Borrow.
* API استاندارد و قابل توسعه برای مدیریت کتابخانه.
* احراز هویت با Sanctum و استفاده از توکن Bearer.
* تمام روت‌های CRUD و عملیات خاص (library-only، borrow/return) پشتیبانی می‌شوند.

---
