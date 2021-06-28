// any CSS you import will output into a single css file (app.css in this case)
import './scss/app.scss';
import '@fortawesome/fontawesome-free/css/all.css';
//import 'bootstrap/dist/css/bootstrap.css';

import './js/nav';
import Filter from './js/modules/Filter';

new Filter(document.querySelector('.js-filter'));