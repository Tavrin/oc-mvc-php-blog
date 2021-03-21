document.addEventListener('DOMContentLoaded', (event) => {
    let alertList = document.querySelectorAll('.flash');
    alertList.forEach(function (alert) {
        if (alert.querySelectorAll('.flash-close').length > 0) {
            let button = alert.querySelectorAll('.flash-close')[0];
            button.addEventListener('click', addCloseEvent);
        }
    })
})

function addCloseEvent(e) {
    console.log(e.target.parentElement);
    e.currentTarget.parentNode.classList.add('d-n')
}