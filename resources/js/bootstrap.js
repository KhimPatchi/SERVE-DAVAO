import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Echo (WebSocket) configuration is handled in echo.js.
 * Import it here so it initializes once on app load.
 */
import './echo';

