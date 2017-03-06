$(document).ready(function () {

    $('.user-search-form input').on('keyup', getUsers);
    $('.user-search-form select').on('change', getUsers);

    function getUsers() {
        var value = document.querySelector('.user-search-form input').value.trim(),
            key = document.querySelector('.user-search-form select').value;
        $.ajax({
            url: '/users',
            data: {key: key, value: value},
            success: renderUsers
        });
    }

    function renderUsers(users) {
        var html = '';
        users.forEach(function (user) {
            html += '<tr>';
            html += '<td>' + user.firstname + '</td>';
            html += '<td>' + user.lastname + '</td>';
            html += '<td>' + user.nickname + '</td>';
            html += '<td>' + user.age + '</td>';
            html += '<td>' + user.created_at + '</td>';
            html += '</tr>';
        });
        $('.table tbody').html(html);
    }
});
