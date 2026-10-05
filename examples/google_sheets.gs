/**
 * Nerkhsanj in Google Sheets (Extensions > Apps Script, paste, save).
 *   =NERKH("dollar")                      live rate in toman
 *   =NERKH_REPLACEMENT(830000,"1404/07/15")  today's replacement cost of an item
 * Results are cached for one minute.
 */
function nerkhFetch_(path) {
  var cache = CacheService.getScriptCache();
  var hit = cache.get(path);
  if (hit) return JSON.parse(hit);
  var text = UrlFetchApp.fetch('https://nerkh.jahankhahan.shop' + path).getContentText();
  cache.put(path, text, 60);
  return JSON.parse(text);
}

function NERKH(series) {
  return nerkhFetch_('/data/live.json').rates[series || 'dollar'];
}

function NERKH_REPLACEMENT(price, jalaliDate, series) {
  var path = '/calc?format=json&price=' + encodeURIComponent(price) + '&date=' + encodeURIComponent(jalaliDate) + '&series=' + (series || 'dollar');
  return nerkhFetch_(path).replacement_cost;
}
