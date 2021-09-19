import Cookies from 'js-cookie'

window._ = require('lodash')

/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

try {
  window.Popper = require('popper.js').default
  window.$ = window.jQuery = require('jquery')

  require('bootstrap')
} catch (e) {}

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios')

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'

// requestの設定
// Cookieからtokenを取り出す
// headerに添付
window.axios.interceptors.request.use(config => {
  const xsrfToken = Cookies.get('XSRF-TOKEN')
  confog.headers['X-XSRF-TOKEN'] = xsrfToken

  return config
})

// responseの設定
// API通信の成功、失敗でresponseの形が変わる（ので・・・）
// 成功、失敗の両方にresponse objectを入れる
// 失敗はerror responseの場合とerrorの両方を適用
window.axios.interceptors.response.use(
  response => response,
  error => error.response || error
)

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo';

// window.Pusher = require('pusher-js');

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: process.env.MIX_PUSHER_APP_KEY,
//     cluster: process.env.MIX_PUSHER_APP_CLUSTER,
//     encrypted: true
// });
