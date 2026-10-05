// Nerkhsanj open data with Node.js 18+ (built-in fetch), no dependencies.
const BASE = 'https://nerkh.jahankhahan.shop';
const get = async (path) => (await fetch(BASE + path)).json();

// Closing rate of the last trading day that is not after `date` (holidays have no row).
const rateOn = (days, date) => {
  let found = null;
  for (const [day, value] of days) {
    if (day > date) break;
    found = value;
  }
  return found;
};

const live = await get('/data/live.json');
console.log('USD now:', live.rates.dollar, 'toman on', live.date, live.time);

const archive = Object.fromEntries((await get('/data/rates.json')).series.map((s) => [s.key, s.days]));
const then = rateOn(archive.dollar, '1404/07/15');
console.log('replacement cost of 830000 bought on 1404/07/15:', Math.round(830000 * live.rates.dollar / then));

const calc = await get('/calc?price=830000&date=1404/07/15&margin=20&format=json');
console.log('server answer:', calc.replacement_cost, 'sell price:', calc.sell_prices[0].price);
