# [台糖糖價查詢(2009-)](http://sugarprice.hol.es/)

![Image of Yaktocat](https://github.com/KazafChen/sugar-price-scraping/blob/master/overview.jpg)

##Introduction
 My parents is running a grocery store. In order to gain the net income, I created a service to scrap the price of sugar in 365 days of each year, so that we'll know when is the proper time to purchase sugar.

##Feature
* Visualize the price of sugar
* View detail information by using stylus
* update data daily automatically

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

##License
MIT License

Copyright (c) 2016 KazafChen

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
