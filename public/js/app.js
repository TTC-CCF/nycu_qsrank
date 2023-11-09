let before_edit_string = null;
let current_block = null;
let current_sn = null;
let curr_dropdownArray = null;

let selecting = false;
let current_bsa_block = null;
let current_ms_block = null;
const prevent_click = ['BUTTON', 'I', 'INPUT', 'SELECT']

document.addEventListener('click', function(event) {
    if (current_block !== null && !prevent_click.includes(event.target.tagName))
        handle_cancel_edit(current_block);
    
    if (selecting && !prevent_click.includes(event.target.tagName))
        handle_cancel_bsa_ms_edit(current_bsa_block, current_ms_block);

})

const bsa_element = document.getElementById('BraodSubjectArea');

if (bsa_element) {
    bsa_element.addEventListener('change', (event) => {
        const ms_element = document.getElementById('MainSubject');
        const bsa = bsa_element.value;
        const ms_dict = JSON.parse(document.querySelector('#ms_dict').value);
        ms_element.innerHTML = '';
        
        for (let i = 0; i < ms_dict[bsa].length; i++) {
            const option = document.createElement('option');
            option.value = ms_dict[bsa][i];
            option.text = ms_dict[bsa][i];
            ms_element.appendChild(option);
        }
    });
}

const title_element = document.getElementById('Title');

if (title_element) {
    title_element.addEventListener('change', (event) => {
        if (title_element.value === '其他') {
            document.getElementById('OtherTitle').hidden = false;
            document.getElementById('OtherTitle').focus();
            document.getElementById('OtherTitle').required = true;
        } else {
            document.getElementById('OtherTitle').hidden = true;
            document.getElementById('OtherTitle').required = false;
        }
    });
}

function showDup() {
    let dup_btn = document.querySelector("#dup_btn");
    let table = document.querySelector("#table tbody");
    let rows =  Array.from(table.querySelectorAll('tr'));
    
    rows.forEach(function(row) {
        if (row.getAttribute("style") === null)
            row.hidden = true;
    });

    dup_btn.innerHTML = "取消";
    dup_btn.onclick = cancelDup;

}

function cancelDup(){
    let dup_btn = document.querySelector("#dup_btn");
    let table = document.querySelector("#table tbody");
    let rows =  Array.from(table.querySelectorAll('tr'));
    
    rows.forEach(function(row) {
        row.hidden = false;
    });

    dup_btn.innerHTML = '<span class="duplicate-color-block"></span>檢查重複資料';
    dup_btn.onclick = showDup;
}

function export_choosing() {
    var chooseBtn = document.getElementById('chooseBtn');
    var selectCols = document.querySelectorAll('[name="select-col"]');

    var originalBtn = chooseBtn.innerHTML;
    var btnCancel = document.createElement('button');
    var btnExport = document.createElement('button');
    var chooseAllCheckbox = document.createElement('input');
    var chooseAllLabel = document.createElement('label');
    var chooseAllDiv = document.createElement('div');
    
    btnCancel.textContent = '取消';
    btnExport.textContent = '匯出';
    chooseAllCheckbox.type = 'checkbox';
    chooseAllCheckbox.id = 'chooseAllCheckbox';
    chooseAllLabel.htmlFor = 'chooseAllCheckbox';
    chooseAllLabel.textContent = '全選';
    chooseAllLabel.style.userSelect = 'none';
    chooseAllDiv.id = 'chooseAllDiv';
    chooseAllDiv.classList.add('form-check', 'form-switch');
    chooseAllDiv.appendChild(chooseAllCheckbox);
    chooseAllDiv.appendChild(chooseAllLabel);

    btnCancel.classList.add('btn', 'btn-secondary');
    btnExport.classList.add('btn', 'btn-primary');

    btnCancel.onclick = function() {
        selectCols.forEach(selectCol => {
            selectCol.style.display = 'none';
            var inputCol = selectCol.querySelector('input');
            if (inputCol && inputCol.checked) 
                inputCol.checked = false;
        });
        chooseBtn.innerHTML = originalBtn;
        document.querySelector('#chooseAllDiv').remove();
    }
    btnExport.onclick = function() {
        var sn_list = [];
        selectCols.forEach(selectCol => {
            const inputCol = selectCol.querySelector('input');
            if (inputCol && inputCol.checked){
                sn_list.push(inputCol.getAttribute('sn'));
            }
        });

        if (sn_list.length === 0){
            var btn = document.querySelector('#errorModal-btn');
            btn.click();
        }
        else {
            var btn = document.querySelector('#exportModal-btn');
            btn.click();
        }
    }
    chooseAllCheckbox.onchange = function() {
        selectCols.forEach(selectCol => {
            var inputCol = selectCol.querySelector('input');
            if (inputCol)
                inputCol.checked = chooseAllCheckbox.checked;
        });
    }

    chooseBtn.innerHTML = '';
    chooseBtn.appendChild(btnExport);
    chooseBtn.appendChild(btnCancel);
    // append to sibling
    chooseBtn.parentNode.insertBefore(chooseAllDiv, chooseBtn.nextSibling);

    selectCols.forEach(selectCol => {
        selectCol.style.display = 'table-cell';
    });
}

function delete_choosing() {
    var chooseBtn = document.getElementById('chooseBtn');
    var selectCols = document.querySelectorAll('[name="select-col"]');

    var originalBtn = chooseBtn.innerHTML;
    var btnCancel = document.createElement('button');
    var btnDelete = document.createElement('button');
    var chooseAllCheckbox = document.createElement('input');
    var chooseAllLabel = document.createElement('label');
    var chooseAllDiv = document.createElement('div');
    
    btnCancel.textContent = '取消';
    btnDelete.textContent = '刪除';
    chooseAllCheckbox.type = 'checkbox';
    chooseAllCheckbox.id = 'chooseAllCheckbox';
    chooseAllLabel.htmlFor = 'chooseAllCheckbox';
    chooseAllLabel.textContent = '全選';
    chooseAllLabel.style.userSelect = 'none';
    chooseAllDiv.id = 'chooseAllDiv';
    chooseAllDiv.classList.add('form-check', 'form-switch');
    chooseAllDiv.appendChild(chooseAllCheckbox);
    chooseAllDiv.appendChild(chooseAllLabel);

    btnCancel.classList.add('btn', 'btn-secondary');
    btnDelete.classList.add('btn', 'btn-danger');

    btnCancel.onclick = function() {
        selectCols.forEach(selectCol => {
            selectCol.style.display = 'none';
            var inputCol = selectCol.querySelector('input');
            if (inputCol && inputCol.checked) 
                inputCol.checked = false;
        });
        chooseBtn.innerHTML = originalBtn;
        document.querySelector('#chooseAllDiv').remove();
    }
    btnDelete.onclick = function() {
        var sn_list = [];
        selectCols.forEach(selectCol => {
            const inputCol = selectCol.querySelector('input');
            if (inputCol && inputCol.checked){
                sn_list.push(inputCol.getAttribute('sn'));
            }
        });

        if (sn_list.length === 0){
            var btn = document.querySelector('#errorModal-btn');
            btn.click();
        }
        else {
            var btn = document.querySelector('#deleteModal-btn');
            btn.click();
        }
    }
    
    chooseAllCheckbox.onchange = function() {
        selectCols.forEach(selectCol => {
            var inputCol = selectCol.querySelector('input');
            if (inputCol)
                inputCol.checked = chooseAllCheckbox.checked;
        });
    }

    chooseBtn.innerHTML = '';
    chooseBtn.appendChild(btnDelete);
    chooseBtn.appendChild(btnCancel);
    // append to sibling
    chooseBtn.parentNode.insertBefore(chooseAllDiv, chooseBtn.nextSibling);

    selectCols.forEach(selectCol => {
        selectCol.style.display = 'table-cell';
    });
}

function _export(filename) {
    var cancel = document.querySelector('#exportModal .modal-footer .btn-secondary');
    cancel.click();

    var workbook = XLSX.utils.book_new();
    var header = [];
    var headerRow = document.querySelectorAll('thead tr th');
    headerRow.forEach(th => {
        if (th.textContent.trim() != '選擇' && th.textContent.trim() != '編號')
            header.push(th.textContent.trim());
    });
    var data = [];
    var tbody = document.querySelector('tbody');
    var rows = tbody.querySelectorAll('tr');
    rows.forEach(row => {
        var rowData = [];
        var cols = row.querySelectorAll('td');
        var inputCol = row.querySelector('input');
        if (inputCol && inputCol.checked) {
            cols.forEach(col => {
                if (!col.querySelector('input') && col.getAttribute('row') !== 'index') {
                    // check if row attribute in ['去年是否同意參與QS', '今年是否同意參與QS']
                    if (col.getAttribute('row') === '去年是否同意參與QS' || col.getAttribute('row') === '今年是否同意參與QS')
                        rowData.push(col.getAttribute('ischeck') === '0' ? '否' : (col.getAttribute('ischeck') === '1' ? '是' : '未定'));
                    else
                        rowData.push(col.textContent.trim());
                }

            });
            data.push(rowData);
        }
    });
    console.log(data)
    var worksheet = XLSX.utils.aoa_to_sheet([header, ...data]);
    XLSX.utils.book_append_sheet(workbook, worksheet);
    XLSX.writeFile(workbook, filename + '.xlsx');


}``

async function del() {
    var csrf = document.querySelector('meta[name="_token"]').content;
    // get all checked input
    var sn_list = [];
    var selectCols = document.querySelectorAll('[name="select-col"]');
    selectCols.forEach(selectCol => {
        const inputCol = selectCol.querySelector('input');
        if (inputCol && inputCol.checked){
            sn_list.push(inputCol.getAttribute('sn'));
        }
    });

    await fetch('/delete', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrf
        },
        body: JSON.stringify({"sn_list":sn_list})
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            var div = document.createElement('div');
            div.classList.add('alert', 'alert-success');
            div.innerHTML = '<i class="fas fa-check"></i> ' + data.message;
            document.querySelector('.modal-footer').insertBefore(div, document.querySelector('.modal-footer').firstChild);
            setTimeout(function() {
                location.reload();
            }, 1500);
        } else {
            var div = document.createElement('div');
            div.classList.add('alert', 'alert-danger');
            // with error icon
            div.innerHTML = '<i class="fas fa-times"></i> ' + data.message;
            document.querySelector('.modal-footer').insertBefore(div, document.querySelector('.modal-footer').firstChild);
            setTimeout(function() {
                location.reload();
            }, 1500);
        }
    });
}

// editing input, select and checkbox elements
function editing(element, sn, dropdownArray = null) {
    if (selecting){
        handle_cancel_bsa_ms_edit(current_bsa_block, current_ms_block);
    }
    if (current_block !== null){
        handle_cancel_edit(current_block);             
    }
    var parent_element = element.parentNode;
    var isCheckbox = parent_element.getAttribute('row') === '今年是否同意參與QS';
    var isDate = parent_element.getAttribute('row') === '寄送Email日期';

    // setting states
    before_edit_string = isCheckbox ? (parent_element.getAttribute('isCheck')) : parent_element.textContent.trim();
    current_block = parent_element;
    curr_dropdownArray = dropdownArray;
    current_sn = sn;
    
    // mutating children elements
    var complete_button = document.createElement('button');
    var cancel_button = document.createElement('button');

    complete_button.textContent = '修改';
    cancel_button.textContent = '取消';
    complete_button.onclick = function () {
        edit_post(parent_element);
    }
    cancel_button.onclick = function() {
        handle_cancel_edit(parent_element);
    }

    if (dropdownArray !== null){
        let selectElement = document.createElement('select');
        selectElement.classList.add('editing-block');
        // create option
        for (let i = 0; i < dropdownArray.length; i++){
            unit_option = document.createElement('option');
            if (before_edit_string && dropdownArray[i] === before_edit_string){
                unit_option.selected = true;
            }
            unit_option.value = dropdownArray[i];
            unit_option.textContent = dropdownArray[i];
            selectElement.appendChild(unit_option);
        }
        
        if (parent_element.getAttribute('row') === 'Title') {
            selectElement.addEventListener('change', (event) => {
                if (selectElement.value === '其他') {
                    let inputElement = document.createElement('input');
                    inputElement.classList.add('editing-block');
                    inputElement.type = 'text';
                    inputElement.value = before_edit_string;
                    inputElement.focus = true;
                    parent_element.insertBefore(inputElement, selectElement.nextSibling);
                } else {
                    if (parent_element.querySelector('input'))
                        parent_element.querySelector('input').remove();
                }
            });
        }

        parent_element.textContent = '';
        parent_element.appendChild(selectElement);
    } 
    else if (isCheckbox) {
        let checkbox = document.createElement('input');
        checkbox.type = 'checkbox';
        checkbox.classList.add('big-checkbox');
        checkbox.checked = parent_element.getAttribute('ischeck') === '1' ? 1 : 0;

        parent_element.innerHTML = '';
        parent_element.appendChild(checkbox);
    }
    else if (isDate) {
        var dateElement = document.createElement('input');
        dateElement.classList.add('editing-block');
        dateElement.type = 'date';
        dateElement.value = before_edit_string;

        parent_element.textContent = '';
        parent_element.appendChild(dateElement);
    }
    else {
        var inputElement = document.createElement('input');
        inputElement.classList.add('editing-block');
        
        inputElement.type = 'text';
        inputElement.value = parent_element.textContent.trim();

        parent_element.textContent = '';
        parent_element.appendChild(inputElement);
        inputElement.focus();
    }
    parent_element.appendChild(complete_button);
    parent_element.appendChild(cancel_button);
}

async function edit_post(parent_element){
    // post to update data
    var key = parent_element.getAttribute('row');
    var new_data = null;
    var new_data_element = parent_element.querySelector('input') ? parent_element.querySelector('input') : parent_element.querySelector('select');
    if (new_data_element.tagName === 'SELECT' || new_data_element.type !== 'checkbox') {
        new_data = new_data_element.value;
    } else {
        new_data = new_data_element.checked ? 1 : 0;
    }
    var csrf = document.querySelector('meta[name="_token"]').content;

    if (new_data !== before_edit_string){
        await fetch('/edit', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf
            },
            body: JSON.stringify({'key': key, 'new_data': new_data, 'SN': current_sn})
        });
    
        // reset state
        before_edit_string = null;
        curr_dropdownArray = null;
        current_block = null;
        current_sn = null;
        window.location.reload();
    }
    else {
        handle_cancel_edit(parent_element);
    }
    
}

function handle_cancel_edit(parent_element){
    // recover elements
    if (parent_element.querySelector('input') && parent_element.querySelector('input').type === 'checkbox') {
        let iconHtml = before_edit_string === '0' ? '<i class="fa-solid fa-times"></i>' : (before_edit_string === '1' ? '<i class="fa-solid fa-check"></i>' : '');
        parent_element.innerHTML = `${iconHtml}<button class="edit_button" onclick="editing(this, '${current_sn}')"><i class="fa-solid fa-pen-to-square"></i></button>`
    } else {
        parent_element.textContent = before_edit_string;
        if (curr_dropdownArray !== null) {
            const csn = current_sn;
            const cda = curr_dropdownArray;
            parent_element.innerHTML = '';
            parent_element.textContent = before_edit_string;
            button_element = document.createElement('button');
            button_element.classList.add('edit_button');
            button_element.onclick = function() {
                editing(this, csn, cda);
            }
            button_element.innerHTML = '<i class="fa-solid fa-pen-to-square"></i>';
            parent_element.appendChild(button_element);    
        }
        else 
            parent_element.innerHTML = `${before_edit_string}<button class="edit_button" onclick="editing(this, '${current_sn}')"><i class="fa-solid fa-pen-to-square"></i></button>`    
    }
    // reset state
    before_edit_string = null;
    curr_dropdownArray = null;
    current_block = null; 
    current_sn = null;
}

// editing bsa and ms elements 
function editing_bsa_ms(element, sn){
    if (current_block !== null){
        handle_cancel_edit(current_block);
    }
    if (selecting){
        handle_cancel_bsa_ms_edit(current_bsa_block, current_ms_block);
    }
    var parent_element = element.parentNode;
    var bsa_element;
    var ms_element;
    if (parent_element.getAttribute('row') === "BroadSubjectArea"){
        bsa_element = parent_element;
        ms_element = parent_element.nextElementSibling;
    }
    else{
        bsa_element = parent_element.previousElementSibling;
        ms_element = parent_element;
    }

    // setting state
    selecting = true;
    before_edit_string = [bsa_element.textContent.trim(), ms_element.textContent.trim()]
    current_bsa_block = bsa_element;
    current_ms_block = ms_element;
    current_sn = sn;

    // mutating children elements
    var complete_button = document.createElement('button');
    var cancel_button = document.createElement('button');

    complete_button.textContent = '修改';
    cancel_button.textContent = '取消';
    complete_button.onclick = function () {
        edit_bsa_ms_post(bsa_element, ms_element);
    }
    cancel_button.onclick = function() {
        handle_cancel_bsa_ms_edit(bsa_element, ms_element);
    }

    bsa_element.textContent = '';
    ms_element.textContent = '';
    
    // create select block
    var bsa_select = document.createElement('select');
    bsa_select.classList.add('editing-block');
    bsa_select.id = 'bsa_select';

    // create option
    var bsa_option = document.createElement('option');
    var bsa_list = JSON.parse(document.querySelector('#bsa_list').value);
    bsa_list = Object.values(bsa_list);
    for (let i = 0; i < bsa_list.length; i++) {
        bsa_option = document.createElement('option');
        if (before_edit_string && bsa_list[i] === before_edit_string[0]){
            bsa_option.selected = true;
        }
        bsa_option.value = bsa_list[i];
        bsa_option.textContent = bsa_list[i];
        bsa_select.appendChild(bsa_option);
    }
    bsa_element.appendChild(bsa_select);

    // create select block
    var ms_select = document.createElement('select');
    ms_select.classList.add('editing-block');
    ms_select.id = 'ms_select';

    //create option
    const ms_dict = JSON.parse(document.querySelector('#ms_dict').value);

    // check bsa_list concatains before_edit_string[0] or not
    // if contains, set key to bsa_list[0], else set key to before_edit_string[0]
    var key = bsa_list.includes(before_edit_string[0]) ? before_edit_string[0] : bsa_list[0];

    for (let i = 0; i < ms_dict[key].length; i++) {
        var ms_option = document.createElement('option');
        if (before_edit_string && ms_dict[key][i] === before_edit_string[1]){
            ms_option.selected = true;
        }
        
        ms_option.value = ms_dict[key][i];
        ms_option.textContent = ms_dict[key][i];
        ms_select.appendChild(ms_option);
    }
    ms_element.appendChild(ms_select);

    // add event listener
    bsa_select.addEventListener('change', (event) => {
        const bsa = bsa_select.value;
        ms_select.innerHTML = '';
        
        for (let i = 0; i < ms_dict[bsa].length; i++) {
            const option = document.createElement('option');
            option.value = ms_dict[bsa][i];
            option.textContent = ms_dict[bsa][i];
            ms_select.appendChild(option);
        }
    });

    parent_element.appendChild(cancel_button);
    parent_element.appendChild(complete_button);
    
}

async function edit_bsa_ms_post(bsa, ms){
    // post to update data
    
    var new_data = [bsa.querySelector('select').value, ms.querySelector('select').value];
    var csrf = document.querySelector('meta[name="_token"]').content;
    
    if (new_data[0] !== before_edit_string[0] || new_data[1] !== before_edit_string[1]){
        await fetch('/edit_bsa_ms', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf
            },
            body: JSON.stringify({
                'new_bsa': new_data[0],
                'new_ms': new_data[1], 
                'SN': current_sn
            })
        });
    
        // reset state
        selecting = false;
        before_edit_string = null;
        current_bsa_block = null;
        current_ms_block = null;
        current_sn = null;
        window.location.reload();
    }
    else {
        handle_cancel_bsa_ms_edit(bsa, ms);
    }
}

function handle_cancel_bsa_ms_edit(bsa, ms){
    // recover elements
    bsa.textContent = before_edit_string[0];
    ms.textContent = before_edit_string[1];
    bsa.innerHTML = `${before_edit_string[0]}<button class="edit_button" onclick="editing_bsa_ms(this, '${current_sn}')"><i class="fa-solid fa-pen-to-square"></i></button>`
    ms.innerHTML = `${before_edit_string[1]}<button class="edit_button" onclick="editing_bsa_ms(this, '${current_sn}')"><i class="fa-solid fa-pen-to-square"></i></button>`
    
    // reset state
    before_edit_string = null;
    current_bsa_block = null;
    current_ms_block = null;
    current_sn = null;
    selecting = false;
}

async function ChangeMode(mode){
    var csrf = document.querySelector('meta[name="_token"]').content;

    await fetch('/', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrf
        },
        body: JSON.stringify({"mode":mode})
    });
    window.location.reload();
}

async function logout() {
    var csrf = document.querySelector('meta[name="_token"]').content;

    await fetch ('/logout', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrf
        }
    });
    window.location.assign('/login');
}

function AddData(mode){
    window.location.assign(`/add_${mode}`);
}

function ImportData(mode){
    window.location.assign(`/import_${mode}`)
}

async function toList(change_mode = null){
    if (change_mode)
        await ChangeMode(change_mode);
    window.location.assign('/');
}

function toUnitManager() {
    window.location.assign('/units');
}
