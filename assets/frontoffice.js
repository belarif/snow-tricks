// require jQuery normally
const $ = require('jquery');

// create global $ and jQuery variables
global.$ = global.jQuery = $;

import('./styles/frontoffice/styles.css');
import('./styles/frontoffice/home.css');
import('./styles/frontoffice/trick_details.css');
import('./styles/frontoffice/edit_trick.css');
import('./js/frontoffice/trick_details');
import('./styles/frontoffice/register.css');

