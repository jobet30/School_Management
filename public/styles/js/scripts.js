/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Other/javascript.js to edit this template
 */

$(document).ready(function () {
    const alertPlaceholder = $('#alert-placeholder');

    $('form').on('submit', function (event) {
        event.preventDefault();
        handleFormSubmit($(this));
    });

    initModals();
    initializeTooltips();
    addEventListenersToButtons();
});

function handleFormSubmit(form) {
    const actionUrl = form.attr('action');
    const formData = form.serialize();

    $.ajax({
        url: actionUrl,
        type: form.attr('method'),
        data: formData,
        dataType: 'json',
        success: function (data) {
            if (data.success) {
                showAlert('success', data.message);
                form[0].reset();
                if (data.redirect) {
                    window.location.href = data.redirect;
                }
            } else {
                showAlert('danger', data.message);
            }
        },
        error: function (jqXHR) {
            showAlert('danger', 'An unexpected error occurred: ' + jqXHR.responseText);
        }
    });
}

function showAlert(type, message) {
    const alert = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>`;
    alertPlaceholder.append(alert);
    setTimeout(() => {
        $('.alert').alert('close');
    }, 5000);
}

function initModals() {
    $('[data-toggle="modal"]').on('click', function () {
        const targetModalId = $(this).data('target');
        $(targetModalId).modal('show');
    });

    $('.modal .close').on('click', function () {
        $(this).closest('.modal').modal('hide');
    });

    $(window).on('click', function (event) {
        if ($(event.target).hasClass('modal')) {
            $(event.target).modal('hide');
        }
    });
}

function initializeTooltips() {
    $('[data-toggle="tooltip"]').tooltip();
}

function addEventListenersToButtons() {
    $('.btn-delete').on('click', function (event) {
        event.preventDefault();
        const confirmation = confirm('Are you sure you want to delete this item?');
        if (confirmation) {
            const url = $(this).data('url');
            deleteItem(url);
        }
    });

    $('.btn-toggle').on('click', function () {
        const targetId = $(this).data('target');
        toggleVisibility(targetId);
    });

    $('.btn-fetch').on('click', function () {
        const url = $(this).data('url');
        fetchData(url).then(data => {
            populateTable(data);
        });
    });
}

function deleteItem(url) {
    $.ajax({
        url: url,
        type: 'DELETE',
        success: function (data) {
            if (data.success) {
                showAlert('success', 'Item deleted successfully.');
                location.reload();
            } else {
                showAlert('danger', data.message);
            }
        },
        error: function (jqXHR) {
            showAlert('danger', 'Failed to delete item: ' + jqXHR.responseText);
        }
    });
}

function toggleVisibility(targetId) {
    const targetElement = $(targetId);
    targetElement.toggle();
}

function fetchData(url, method = 'GET') {
    return $.ajax({
        url: url,
        type: method,
        dataType: 'json'
    }).fail(function (jqXHR) {
        showAlert('danger', 'Failed to fetch data: ' + jqXHR.responseText);
    });
}

function populateSelectOptions(selectElement, options) {
    const $select = $(selectElement);
    $select.empty();
    $.each(options, function (index, option) {
        $select.append(new Option(option.name, option.id));
    });
}

function populateTable(data) {
    const tableBody = $('#data-table-body');
    tableBody.empty();
    $.each(data, function (index, item) {
        const row = `
            <tr>
                <td>${item.id}</td>
                <td>${item.name}</td>
                <td>${item.description}</td>
                <td>
                    <button class="btn btn-edit" data-url="/edit/${item.id}">Edit</button>
                    <button class="btn btn-delete" data-url="/delete/${item.id}">Delete</button>
                </td>
            </tr>`;
        tableBody.append(row);
    });
}

function clearForm(form) {
    form[0].reset();
}

function getUserInput(selector) {
    return $(selector).val();
}

function setUserInput(selector, value) {
    $(selector).val(value);
}

function highlightElement(selector) {
    $(selector).addClass('highlight');
    setTimeout(() => {
        $(selector).removeClass('highlight');
    }, 2000);
}

function toggleLoader(show) {
    if (show) {
        $('#loader').show();
    } else {
        $('#loader').hide();
    }
}
