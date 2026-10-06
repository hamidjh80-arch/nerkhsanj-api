# Nerkhsanj API: Iran free-market exchange rates in toman

Free, keyless JSON data from [Nerkhsanj (نرخ‌سنج)](https://nerkh.jahankhahan.shop/): live Tehran free-market rates for 15 series (US dollar, euro, UAE dirham, Turkish lira, British pound, Chinese yuan, Tether, Bitcoin, 18k and 24k gold, and five gold coins), a daily archive going back to 2010, intraday points, a gold calculator, and a calculator that tells you what an item bought in the past costs today.

No API key, no sign-up. All amounts are in Iranian toman (1 toman = 10 rial). Dates are Jalali (Solar Hijri), written `1404/07/15`.

[راهنمای فارسی پایین همین صفحه است.](#راهنمای-فارسی)

## Endpoints

| Endpoint | What it returns | Updated |
|---|---|---|
| `GET https://nerkh.jahankhahan.shop/data/live.json` | Current rate of all 15 series, plus the world gold ounce in USD | Every minute |
| `GET https://nerkh.jahankhahan.shop/data/intraday.json` | Today's low, high and ten-minute points per series | Every minute in market hours |
| `GET https://nerkh.jahankhahan.shop/data/rates.json` | Closing rate of every trading day | Three times a day |
| `GET https://nerkh.jahankhahan.shop/calc?price=…&date=…&format=json` | Replacement cost and sell price of one item | Live |
| `GET https://nerkh.jahankhahan.shop/gold-calculator?format=json` | Intrinsic value and premium of gold and coins; jewelry price with wage, profit and tax | Live |

Full reference: [English](https://nerkh.jahankhahan.shop/developers/en) · [فارسی](https://nerkh.jahankhahan.shop/developers)

### Live rates

```json
{
  "date": "1405/07/13",
  "time": "19:35",
  "rates": { "dollar": 268790, "euro": 302660, "yuan": 40380, "gold": 26528700, "coin": 271160000, "aed": 73403, "tether": 268649 },
  "change": { "dollar": 0.43, "euro": -0.1, "yuan": 0.45, "gold": 0.76, "coin": 0.03, "aed": -0.05, "tether": 0.06 },
  "ounce": 4129.98
}
```

The example is shortened; the real file carries all 15 keys. `time` is empty outside market hours; the rates are then the last trading day's close. `change` is the percent change against the previous day. `ounce` is the world gold price per troy ounce in US dollars.

### Daily archive

```json
{ "series": [ { "key": "dollar", "name": "دلار", "days": [ ["1390/09/05", 1345], ["1404/07/15", 113970] ] } ] }
```

| Key | Series | From (Jalali) |
|---|---|---|
| `dollar` | US dollar | 1390 |
| `euro` | Euro | 1391 |
| `aed` | UAE dirham | 1391 |
| `pound` | British pound | 1391 |
| `yuan` | Chinese yuan | 1393 |
| `lira` | Turkish lira | 1393 |
| `tether` | Tether (USDT) | 1399 |
| `bitcoin` | Bitcoin | 1399 |
| `gold` | 18k gold, per gram | 1392 |
| `gold24` | 24k gold, per gram | 1393 |
| `coin` | Emami gold coin | 1389 |
| `bahar` | Bahar Azadi gold coin | 1392 |
| `half` | Half coin | 1392 |
| `quarter` | Quarter coin | 1392 |
| `gram` | One-gram coin | 1392 |

Holidays have no row; use the last earlier trading day.

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
| `series` | `dollar` (default) or any other key from the table above, e.g. `aed` or `lira` |
| `format` | `json` for data. Without it you get a readable HTML page that needs no JavaScript |

The response carries `rate_on_purchase_date`, `rate_now`, `ratio`, `replacement_cost` and `sell_prices`.

Formula: `replacement_cost = price × (rate_now ÷ rate_on_purchase_date)`.

## Ready-made tools

- **Widget:** show live rates on any site with two lines of HTML. Builder: [nerkh.jahankhahan.shop/widget](https://nerkh.jahankhahan.shop/widget)
- **WooCommerce plugin:** keeps product prices in step with the exchange rate from each product's purchase price and date. Download: [nerkh.jahankhahan.shop/woocommerce](https://nerkh.jahankhahan.shop/woocommerce)
- **Today's rates page:** [nerkh.jahankhahan.shop/today](https://nerkh.jahankhahan.shop/today)

```html
<div data-nerkhsanj data-series="dollar,euro,gold,coin"><a href="https://nerkh.jahankhahan.shop/">نرخ‌سنج</a></div>
<script async src="https://nerkh.jahankhahan.shop/widget.js"></script>
```

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

[نرخ‌سنج](https://nerkh.jahankhahan.shop/) دادهٔ نرخ بازار آزاد تهران را رایگان، بدون کلید (API Key) و بدون ثبت‌نام می‌دهد: ۱۵ معیار شامل دلار، یورو، درهم، لیر، پوند، یوان، تتر، بیت‌کوین، طلای ۱۸ و ۲۴ عیار، سکهٔ امامی، بهار آزادی، نیم‌سکه، ربع‌سکه و سکهٔ گرمی. همهٔ مبلغ‌ها به تومان و تاریخ‌ها شمسی است.

### نشانی‌ها

- **نرخ لحظه‌ای** (هر دقیقه): `https://nerkh.jahankhahan.shop/data/live.json`
- **آرشیو روزانه** (نرخ پایانی هر روز کاری، دلار از آذر ۱۳۹۰ و سکه از فروردین ۱۳۸۹): `https://nerkh.jahankhahan.shop/data/rates.json`
- **نرخ درون‌روزی** (کمترین، بیشترین و نقطه‌های ده‌دقیقه‌ای امروز): `https://nerkh.jahankhahan.shop/data/intraday.json`
- **محاسبهٔ قیمت روز کالا**: `https://nerkh.jahankhahan.shop/calc?price=830000&date=1404/07/15&margin=20&format=json`
- **ماشین‌حساب طلا** (ارزش ذاتی و حباب طلا و سکه، قیمت با اجرت): `https://nerkh.jahankhahan.shop/gold-calculator?format=json`

پارامترهای محاسبه: `price` قیمت خرید به تومان، `date` تاریخ خرید شمسی، `margin` درصد سود (اختیاری)، `adj` درصد تعدیل بازار (اختیاری)، `series` یکی از کلیدهای جدول بالا، مثل `dollar`، `aed` یا `lira`.

فرمول: قیمت خرید به‌روز = قیمت خرید × (نرخ امروز ÷ نرخ روز خرید).

مستندات کامل: [nerkh.jahankhahan.shop/developers](https://nerkh.jahankhahan.shop/developers)

### ابزارهای آماده

- [ابزارک نمایش نرخ](https://nerkh.jahankhahan.shop/widget) برای سایت‌های دیگر با دو خط کد
- [افزونهٔ ووکامرس](https://nerkh.jahankhahan.shop/woocommerce) برای به‌روزرسانی خودکار قیمت محصولات
- [نرخ امروز](https://nerkh.jahankhahan.shop/today) همهٔ معیارها در یک صفحه

### نمونه‌کد

پوشهٔ `examples` نمونهٔ آماده برای پایتون، Node.js، PHP، وردپرس (کد کوتاه `[nerkh_rate]`) و گوگل‌شیت دارد.

### شرایط استفاده

- هر نشانی را حداکثر دقیقه‌ای یک بار بخوانید.
- برای قیمت‌گذاری چند کالا، دو فایل داده را یک بار بخوانید و خودتان ضرب کنید؛ برای هر کالا جدا `/calc` را صدا نزنید.
- هنگام نمایش عددها، «نرخ‌سنج» را به‌عنوان منبع بیاورید.
- این عددها برآورد بر پایهٔ نرخ ارز هستند، نه پیش‌بینی یا توصیهٔ مالی.

</div>
