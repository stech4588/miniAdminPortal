import { library, config } from '@fortawesome/fontawesome-svg-core';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import '@fortawesome/fontawesome-svg-core/styles.css'
import { faStar as farStar} from '@fortawesome/free-regular-svg-icons'; // Import regular icons



import {
    faUser, faLock, faSignOutAlt, faCog, faTrash, faEye, faPen, faDownload, faPlus, faClock, faTimesCircle, faCheckCircle, faCheck, faSearch, faCartShopping, faSignIn, faSignOut, faArrowLeft, faAngleUp, faAngleDown, faTruck, faStar, faChevronLeft, faChevronRight, faBars, faHome, faUsers
} from '@fortawesome/free-solid-svg-icons';
import { faGithub, faFacebook, faInstagram, faTwitter, faGoogle, faLinkedin } from '@fortawesome/free-brands-svg-icons';

config.autoAddCss = false;

library.add(
    faUser, faLock, faSignOutAlt, faCog, faGithub, faTrash, faEye, faPen, faDownload, faPlus, faClock, faTimesCircle, faCheckCircle, faCheck, faSearch, faCartShopping, faSignIn, faSignOut, faArrowLeft,  faAngleUp, faAngleDown, faTruck, faStar, farStar, faChevronLeft, faChevronRight, faBars, faFacebook, faInstagram, faTwitter, faGoogle, faLinkedin, faHome, faUsers
);

export default {
    install(app) {
        // Register FontAwesomeIcon component globally
        app.component('FontAwesomeIcon', FontAwesomeIcon);
    }
};
