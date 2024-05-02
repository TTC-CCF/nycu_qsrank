
var dropArea = document.getElementById('dropArea');
var afterDrop = document.getElementById('afterDropArea');
var previewArea = document.getElementById('previewArea');
var fileTable = document.querySelector('#previewArea table');
var fileInput = document.getElementById('fileInput');
var canImport = true;

const requiredColumns = {
    "employer": ['資料提供單位', 'Title', 'First Name', 'Last Name',  'Position', 'Industry', 'Company Name', 'Broad Subject Area', 'Main Subject', 'Location', 'Email'],
    "scholar": ['資料提供單位', 'Title', 'First Name', 'Last Name',  'Job Title', 'Department', 'Institution', 'Broad Subject Area', 'Main Subject', 'Location', 'Email'],
}

$('input[type="checkbox"]').on('change', function () {
    $('input[type="checkbox"]').not(this).prop('checked', false);
});

dropArea.addEventListener('dragover', function (e) {
    e.preventDefault();
});

dropArea.addEventListener('click', function (e) {
    fileInput.click();
});

fileInput.addEventListener('change', function (e) {
    if (fileInput.files.length > 0)
        handleFiles(fileInput.files);
});

fileTable.addEventListener('dragover', function (e) {
    e.preventDefault();
});

fileTable.addEventListener('drop', function (e) {
    e.preventDefault();

    fileInput.files = e.dataTransfer.files;
    if (fileInput.files.length > 0)
        handleFiles(fileInput.files);
});

dropArea.addEventListener('drop', function (e) {
    e.preventDefault();

    fileInput.files = e.dataTransfer.files;
    if (fileInput.files.length > 0)
        handleFiles(fileInput.files);
});

function handleFiles(files) {
    afterDrop.innerHTML = '';
    fileTable.innerHTML = '';

    var file = files[0];
    var div = document.createElement('div');
    afterDrop.appendChild(div);

    if (file.name.endsWith('.xlsx') || file.name.endsWith('.xls')) {
        div.innerHTML = '檔案名稱: ' + file.name + ', 檔案大小: ' + (file.size / 1000).toFixed(2) + ' KB';
        var mode = document.getElementById("mode").getAttribute("v");
        readFile(file, fileTable, mode);

        // add import data button
        var im_button = document.createElement('button');
        im_button.classList.add('btn', 'btn-outline-danger', 'btn-sm');
        im_button.setAttribute('data-toggle', 'modal');
        im_button.setAttribute('data-target', '#importModal');
        im_button.innerHTML = '匯入資料';

        afterDrop.appendChild(im_button);
        previewArea.style.display = 'block';
        dropArea.style.display = 'none';
    } else {
        div.classList.add('text-danger');
        div.innerHTML = '檔案格式錯誤，只支援xlsx或xls檔案';
        dropArea.style.display = 'flex';
        previewArea.style.display = 'none';
        fileTable.innerHTML = '';
    }
    // size to two fraction part
    // add redrop file button
    var re_button = document.createElement('button');
    re_button.classList.add('btn', 'btn-outline-primary', 'btn-sm');
    re_button.innerHTML = '重新上傳';
    re_button.addEventListener('click', function (e) {
        dropArea.style.display = 'flex';
        afterDrop.style.display = 'none';
        previewArea.style.display = 'none';
        fileTable.innerHTML = '';
        fileInput.value = '';
    });

    afterDrop.appendChild(re_button);

    afterDrop.style.display = 'flex';
}

function readFile(file, table, mode) {
    var reader = new FileReader();
    console.log(mode)
    reader.onload = function (e) {
        var data = new Uint8Array(e.target.result);
        var workbook = XLSX.read(data, { type: 'array' });
        var sheetName = workbook.SheetNames[0];
        var sheet = workbook.Sheets[sheetName];
        var jsonData = XLSX.utils.sheet_to_json(sheet, { header: 1 });

        var headerRow = document.createElement('thead');
        var tr = document.createElement('tr');
        headerRow.classList.add('thead-dark');

        var rowLength = jsonData[0].length;
        jsonData[0].forEach(function (cellData) {
            var th = document.createElement('th');

            if (cellData === undefined) {
                td.innerHTML = '此資料欄不得為空!';
                th.style.backgroundColor = 'red';
            } else {
                th.innerHTML = cellData;
            }
            th.setAttribute('scope', 'col');
            tr.appendChild(th);
        });
        headerRow.appendChild(tr);
        table.appendChild(headerRow);

        var tableBody = document.createElement('tbody');
        jsonData.slice(1).forEach(function (rowData) {
            let haveValue = false;
            for (var i = 0; i < rowLength; i++) {
                if (rowData[i] !== undefined && rowData[i] !== '') {
                    haveValue = true;
                    break;
                }
            }

            if (haveValue) {
                var tr = document.createElement('tr');
                for (var i = 0; i < rowLength; i++) {
                    var td = document.createElement('td');
                    if (rowData[i] === undefined || rowData[i] === '') {
                        if (jsonData[0][i] !== undefined && requiredColumns[mode].includes(jsonData[0][i])) {
                            td.style.backgroundColor = '#ff5c64';
                            td.innerHTML = '此資料欄不得為空!';
                            canImport = false;
                        }
                    }
                    else
                        td.innerHTML = rowData[i];
                    tr.appendChild(td);
                }

                tableBody.appendChild(tr);
            }
        });
        table.appendChild(tableBody);
    };

    reader.readAsArrayBuffer(file);
}

function _import() {
    if (!canImport) {
        var div = document.createElement('div');
        div.classList.add('alert', 'alert-danger');
        div.innerHTML = '<i class="fas fa-times"></i> 資料欄不得為空!';
        document.querySelector('.modal-footer').insertBefore(div, document.querySelector('.modal-footer').firstChild);
        setTimeout(function () {
            location.reload();
        }, 1500);
        return;
    }
    var header = document.querySelectorAll('#previewArea table thead th');
    var rows = document.querySelectorAll('#previewArea table tbody tr');
    var data = {};
    header.forEach(function (th) {
        data[th.innerHTML] = [];
    });

    rows.forEach(function (row) {
        var tds = row.querySelectorAll('td');
        Object.keys(data).forEach(function (key, index) {
            data[key].push(tds[index].innerHTML);
        })
    });
    var jsonData = JSON.stringify({ 'data': data, 'mode': $('input[type="checkbox"]:checked').val() });

    const csrf = document.querySelector('meta[name="_token"]').content;
    fetch('/import', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrf
        },
        body: jsonData
    })
        .then(response => response.json())
        .then(data => {
            if (data.status == 'success') {
                var div = document.createElement('div');
                div.classList.add('alert', 'alert-success');
                div.innerHTML = '<i class="fas fa-check"></i> ' + data.message;
                document.querySelector('.modal-footer').insertBefore(div, document.querySelector('.modal-footer').firstChild);
                setTimeout(function () {
                    location.reload();
                }, 1500);
            } else {
                var div = document.createElement('div');
                div.classList.add('alert', 'alert-danger');
                // with error icon
                div.innerHTML = '<i class="fas fa-times"></i> ' + data.message;
                document.querySelector('.modal-footer').insertBefore(div, document.querySelector('.modal-footer').firstChild);
                setTimeout(function () {
                    location.reload();
                }, 1500);
            }
        })
}