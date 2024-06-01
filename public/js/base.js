var currentDate = new Date, targetDate = new Date('2024-06-30'), dev = currentDate < targetDate;
var csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

try {
    window.axios.defaults.headers.common = {
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-TOKEN': csrfToken,
    };
} catch (e) {
    dev && console.log(e);
}

if(dev) console.log('DEV MODE ENABLED');

function getAxiosError(err) {
    let msg = 'An error occurred, try again.';
    if (err?.response?.data?.message) {
        msg = err.response.data.message;
    } else if (err?.message) {
        msg = err.message;
    }
    return msg;
}