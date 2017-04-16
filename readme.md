# Minimal Artistic Portfolio thme

A wordpress theme for artist (painter, sculptor, video-producer, ...)


![theme screenshot Nicolas Lebrun logo](https://raw.githubusercontent.com/nclslbrn/Minimal-artist-portfolio/master/screenshot.png)


[Demo here](https://nicolas-lebrun.fr/) (It's not fake content but my own site to show my artistic works)


Note: Translation content function work with [qTranslateX](https://wordpress.org/plugins/qtranslate-x/) but static content is also translated (french/english) with a .po file

### Features
- project post type with new medium field (stored as `cartel` in post_meta) and events links
- event post type with begin and end date field and longitude/latitude to bring [Leafleft](https://github.com/Leaflet/Leaflet) maps on single event template
- event list as chronological, from newer to older, grouped by year
- contact page
- two menus (header & footer), page can be nested in a dropdown in the header navigation bar, these two menus are responsive
- image thumbnail in post content can be opened in full size on a modal with [zoom.js](https://github.com/fat/zoom.js)


### Changelog
- Update grid with flexbox
- Load google font in function.php

### Installation
- install with `npm install`
- update your change (SASS, PHP, image, fonts) with `gulp watch`
