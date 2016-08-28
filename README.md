# [台糖糖價查詢(2009-)](http://sugarprice.hol.es/)

![Image of Yaktocat](https://github.com/KazafChen/sugar-price-scraping/blob/master/overview.jpg)

##Introduction
 My parents is running a grocery store. In order to gain the net income, I created a service to scrap the price of sugar in 365 days of each year, so that we'll know when is the proper time to purchase sugar.

##Feature
* Visualized the price of sugar
* View detail information by using stylus
* Data updated daily automatically

##Query
Passing `year`, `month`, `day` arguments to `query.php` as follows:

```json
http://sugarprice.hol.es/query.php?y=2016&m=8&d=26
```

##TODO
- [ ] Add the price of another type of sugar
- [ ] Add the price of oil, gold or something else to see their relations

##Reference
* [糖價輸入-顯示查詢](http://g1.taisugar.com.tw/Sugar/Sugar_show.asp)
* Jacob Ward. 2013. Instant PHP Web Scraping. Packt Publishing.
* [Flot: Attractive JavaScript plotting for jQuery](http://www.flotcharts.org/)
* [Foundation | The most advanced responsive front-end framework in the world.](http://foundation.zurb.com/)
