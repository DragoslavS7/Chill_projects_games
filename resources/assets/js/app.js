// Import JQuery
window.$ = window.jQuery = require('jquery');

// Import Bootstrap js
require('bootstrap-sass/assets/javascripts/bootstrap');

// Import Moment js
require('moment');

// Import DataTables
require( 'datatables.net' )( window, $ );

window.DataTablesMyArcadeChef = require('./DataTablesMyArcadeChef');
window.ConfirmDialog = require('./ConfirmDialog');
window.Tags = require('./Tags');
