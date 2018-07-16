# JSON flux wp-posts importer

Thanks to this tutorial [Creating Custom WordPress Administration Pages](https://code.tutsplus.com/tutorials/creating-custom-admin-pages-in-wordpress-1--cms-26829), which helped me a lot for the creation of this plugin

## Getting Started

* Local server environment or live server

### Prerequisites

* Tested on WP 4.9.7

* Your flux must be a JSON, and formatted like this below 
```
{
    "whateveryouwant": "whatever"
    "items": [
    {
        "title": "Title",
        "description": "Your content (html is ok)",
        "pubdate": "Thu, 21 Dec 2000 16:01:07 +0200",
    }
}
```

I do not know if it will work on previous or higher versions

### Installing



* Clone or download the repository, and put files into **/wp-content/plugins/**

```
https://github.com/natinho68/wp-fluxToPosts.git
```

* Activate the plugin
* Go to the Flux to posts page in the admin menu
* Put a feed and click on "Generate Posts"
* Your posts have been imported

## Author

[**Nathan MEYER**](https://github.com/natinho68)
