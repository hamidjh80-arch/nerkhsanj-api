"""Nerkhsanj open data: live rates, archive lookup and replacement cost. Python 3, no dependencies."""
import json
import urllib.parse
import urllib.request

BASE = "https://nerkh.jahankhahan.shop"


def get(path):
    req = urllib.request.Request(BASE + path, headers={"User-Agent": "nerkhsanj-example"})
    with urllib.request.urlopen(req, timeout=30) as r:
        return json.load(r)


def rate_on(days, date):
    """Closing rate of the last trading day that is not after `date` (holidays have no row)."""
    found = None
    for day, value in days:
        if day > date:
            break
        found = value
    return found


live = get("/data/live.json")
print("USD now:", live["rates"]["dollar"], "toman on", live["date"], live["time"])

# Price many items with ONE request: read the archive once and multiply yourself.
archive = {s["key"]: s["days"] for s in get("/data/rates.json")["series"]}
items = [("drill", 830000, "1404/07/15"), ("grinder", 1250000, "1403/11/02")]
for name, price, date in items:
    then = rate_on(archive["dollar"], date)
    print(name, "replacement cost:", round(price * live["rates"]["dollar"] / then), "toman")

# Or let the server do it for a single item.
query = urllib.parse.urlencode({"price": 830000, "date": "1404/07/15", "margin": 20, "format": "json"})
result = get("/calc?" + query)
print("replacement cost:", result["replacement_cost"], "sell price:", result["sell_prices"][0]["price"])
