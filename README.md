# Nerkhsanj API: Iran free-market exchange rates in toman

Free, keyless JSON data from [Nerkhsanj (نرخ‌سنج)](https://nerkh.jahankhahan.shop/): live Tehran free-market rates for the US dollar, euro, Chinese yuan, 18k gold and the Emami gold coin, a daily archive going back to 2010, and a calculator that tells you what an item bought in the past costs today.

No API key, no sign-up. All amounts are in Iranian toman (1 toman = 10 rial). Dates are Jalali (Solar Hijri), written `1404/07/15`.

[راهنمای فارسی پایین همین صفحه است.](#راهنمای-فارسی)

## Endpoints

| Endpoint | What it returns | Updated |
|---|---|---|
| `GET https://nerkh.jahankhahan.shop/data/live.json` | Current rate of all five series | Every minute |
| `GET https://nerkh.jahankhahan.shop/data/rates.json` | Closing rate of every trading day | Three times a day |
| `GET https://nerkh.jahankhahan.shop/calc?price=…&date=…&format=json` | Replacement cost and sell price of one item | Live |

Full reference: [English](https://nerkh.jahankhahan.shop/developers/en) · [فارسی](https://nerkh.jahankhahan.shop/developers)

### Live rates

```json
{
  "date": "1405/07/13",
  "time": "19:35",
  "rates": { "dollar": 268790, "euro": 302660, "yuan": 40380, "gold": 26528700, "coin": 271160000 },
  "change": { "dollar": 0.43, "euro": -0.1, "yuan": 0.45, "gold": 0.76, "coin": 0.03 }
}
```

`time` is empty outside market hours; the rates are then the last trading day's close. `change` is the percent change against the previous day.

### Daily archive

```json
{ "series": [ { "key": "dollar", "name": "دلار", "days": [ ["1390/09/05", 1345], ["1404/07/15", 113970] ] } ] }
```

Series keys: `dollar`, `euro`, `yuan`, `gold` (one gram, 18k), `coin` (Emami). Holidays have no row; use the last earlier trading day.

### Replacement-cost calculator

```
GET https://nerkh.jahankhahan.shop/calc?price=830000&date=1404/07/15&margin=20&format=json
```

| Parameter | Meaning |
|---|---|
| `price` | Purchase price in toman (required) |
| `date` | Purchase date, Jalali (required) |
| `margin` | Profit margin in percent. If omitted, sell prices for 10, 20 and 30 percent are returned |
| `adj` | Market adjustment in percent for that item; may be negative |
| `series` | `dollar` (default), `euro`, `yuan`, `gold` or `coin` |
| `format` | `json` for data. Without it you get a readable HTML page that needs no JavaScript |

The response carries `rate_on_purchase_date`, `rate_now`, `ratio`, `replacement_cost` and `sell_prices`.

Formula: `replacement_cost = price × (rate_now ÷ rate_on_purchase_date)`.

## Examples

| File | Shows |
|---|---|
| [examples/python_example.py](examples/python_example.py) | Live rate, archive lookup, pricing many items with one request |
| [examples/node_example.mjs](examples/node_example.mjs) | The same with Node.js 18+ |
| [examples/php_example.php](examples/php_example.php) | The same with plain PHP |
| [examples/wordpress_snippet.php](examples/wordpress_snippet.php) | A `[nerkh_rate]` shortcode with one-minute caching |
| [examples/google_sheets.gs](examples/google_sheets.gs) | `=NERKH("dollar")` and `=NERKH_REPLACEMENT(830000,"1404/07/15")` |

## Fair use

- Read each endpoint at most once a minute. Rates do not change faster than that.
- To price many items, read `live.json` and `rates.json` once and multiply yourself. Do not call `/calc` once per item.
- Credit "Nerkhsanj" as the source when you show the numbers.
- Only the `/calc` JSON response allows cross-origin browser requests. Read the two data files from your server.
- The figures are exchange-rate-based estimates, not forecasts or financial advice.

The example code in this repository is released under the MIT license.

---

<div dir="rtl">

## راهنمای فارسی

[نرخ‌سنج](https://nerkh.jahankhahan.shop/) دادهٔ نرخ بازار آزاد تهران را رایگان، بدون کلید (API Key) و بدون ثبت‌نام می‌دهد: دلار آمریکا، یورو، یوان چین، هر گرم طلای ۱۸ عیار و سکهٔ امامی. همهٔ مبلغ‌ها به تومان و تاریخ‌ها شمسی است.

### نشانی‌ها

- **نرخ لحظه‌ای** (هر دقیقه): `https://nerkh.jahankhahan.shop/data/live.json`
- **آرشیو روزانه** (نرخ پایانی هر روز کاری، دلار از آذر ۱۳۹۰ و سکه از فروردین ۱۳۸۹): `https://nerkh.jahankhahan.shop/data/rates.json`
- **محاسبهٔ قیمت روز کالا**: `https://nerkh.jahankhahan.shop/calc?price=830000&date=1404/07/15&margin=20&format=json`

پارامترهای محاسبه: `price` قیمت خرید به تومان، `date` تاریخ خرید شمسی، `margin` درصد سود (اختیاری)، `adj` درصد تعدیل بازار (اختیاری)، `series` یکی از `dollar`، `euro`، `yuan`، `gold`، `coin`.

فرمول: قیمت خرید به‌روز = قیمت خرید × (نرخ امروز ÷ نرخ روز خرید).

مستندات کامل: [nerkh.jahankhahan.shop/developers](https://nerkh.jahankhahan.shop/developers)

### نمونه‌کد

پوشهٔ `examples` نمونهٔ آماده برای پایتون، Node.js، PHP، وردپرس (کد کوتاه `[nerkh_rate]`) و گوگل‌شیت دارد.

### شرایط استفاده

- هر نشانی را حداکثر دقیقه‌ای یک بار بخوانید.
- برای قیمت‌گذاری چند کالا، دو فایل داده را یک بار بخوانید و خودتان ضرب کنید؛ برای هر کالا جدا `/calc` را صدا نزنید.
- هنگام نمایش عددها، «نرخ‌سنج» را به‌عنوان منبع بیاورید.
- این عددها برآورد بر پایهٔ نرخ ارز هستند، نه پیش‌بینی یا توصیهٔ مالی.

</div>
