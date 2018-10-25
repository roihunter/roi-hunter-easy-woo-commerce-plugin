# Google tracking

## Remarketing
Example of the remarketing snippet for each page (Product detail, Category and Cart), initialization part is common.
It's important to use **conversion id** with prefix `AW-`.
```html
<!-- Global Site Tag (gtag.js)
Conversion ID: 123456789
-->
<script async src="https://www.googletagmanager.com/gtag/js?id=AW-123456789"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'AW-123456789');
  
  // Product detail
  gtag('event', 'view_item', {
    send_to: 'AW-123456789',
    dynx_itemid: 123456,
    dynx_pagetype: 'offerdetail',
    dynx_totalvalue: 9.99,
  });
  
  // Category
  gtag('event', 'view_item', {
    send_to: 'AW-123456789',
    dynx_itemid: [123, 456],
    dynx_pagetype: 'searchresults',
    dynx_totalvalue: 9.99,
  });
  
  // Cart page + add to cart hook
  gtag('event', 'add_to_cart', {
    send_to: 'AW-123456789',
    dynx_itemid: [123, 456],
    dynx_pagetype: 'conversionintent',
    dynx_totalvalue: 9.99,
  });
</script>
```

## Conversions
Example of the conversion tracker for Checkout page. It's important to use **conversion id** and **conversion label** with prefix `AW-` separated by `/` in the parameter **send_to**.
```html
<!-- Global Site Tag (gtag.js)
Conversion ID: 123456789
Conversion label: aaaaaa_bbbbb
-->
<script async src="https://www.googletagmanager.com/gtag/js?id=AW-123456789"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'AW-123456789');
  
  // Checkout ("Thank you" page)
  gtag('event', 'purchase', {
    send_to: 'AW-123456789/aaaaaa_bbbbb',
    value: 9.99,
    currency: 'USD',
    transaction_id: 123456789, // Order ID
    dynx_itemid: [123, 456],
    dynx_pagetype: 'conversion',
    dynx_totalvalue: 9.99,
  });
</script>  
```
