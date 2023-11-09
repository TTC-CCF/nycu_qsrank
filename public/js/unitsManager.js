let current_unit = null;
let current_data = null;
let deleteElement = null;

function show_unit(unit){
    current_unit = unit;
    let units = document.getElementById('units').value;
    let accountContainer = document.getElementById('accountContainer');
    accountContainer.innerHTML = '';

    let formElement = document.createElement('form');
    let unitTitle = document.createElement('h3');
    formElement.id = 'unitAccountForm';

    unitTitle.innerHTML = unit;
    formElement.appendChild(unitTitle);

    units = JSON.parse(units);
    let account_idx = 1;
    for (let i = 0; i < units.length; ++i) {
        if (units[i]['unit'] == unit) {
            let tmp_unit = units[i];
            let div = AccountInput(account_idx, tmp_unit['account'], tmp_unit['SN'])
            formElement.appendChild(div);
            ++account_idx;
        }

    }
    
    let addAccountBtn = document.createElement('button');
    let changePasswordBtn = document.createElement('button');

    addAccountBtn.classList.add('btn');
    addAccountBtn.classList.add('btn-primary');
    addAccountBtn.classList.add('btn-block');
    addAccountBtn.innerHTML = '新增帳號';
    addAccountBtn.type = 'button';
    addAccountBtn.onclick =  addAccount;

    changePasswordBtn.classList.add('btn');
    changePasswordBtn.classList.add('btn-danger');
    changePasswordBtn.classList.add('btn-block');
    changePasswordBtn.innerHTML = '更改單位密碼';
    changePasswordBtn.type = 'button';
    changePasswordBtn.onclick =  changePassword;


    formElement.appendChild(addAccountBtn);
    formElement.appendChild(changePasswordBtn);
    accountContainer.appendChild(formElement);
}

function AccountInput(cnt, value = '', sn){
    let inputEle = document.createElement('input');
    let div = document.createElement('div');
    let form_group1 = document.createElement('div');
    let form_group2 = document.createElement('div');
    let form_group3 = document.createElement('div');
    let deleteAccountBtn = document.createElement('button');
    let editAccountBtn = document.createElement('button');
    let label = document.createElement('label');

    div.classList.add('form-row');
    form_group1.classList.add('form-group', 'col-md-8');
    form_group2.classList.add('form-group', 'col-md-2');
    form_group3.classList.add('form-group', 'col-md-2');

    editAccountBtn.classList.add('btn', 'btn-primary', 'btn-block');
    editAccountBtn.innerHTML = '確認修改';
    editAccountBtn.type = 'button';
    editAccountBtn.addEventListener('click', event => {
        editAccount(event.target);
    });

    deleteAccountBtn.classList.add('btn', 'btn-danger', 'btn-block');
    deleteAccountBtn.innerHTML = '刪除帳號';
    deleteAccountBtn.type = 'button';
    deleteAccountBtn.addEventListener('click', event => {
        deleteAccount(event.target);
    });

    label.setAttribute('for', `account${cnt}`);
    label.innerHTML = `帳號 ${cnt}`;

    inputEle.classList.add('form-control');
    inputEle.id = `account${cnt}`;
    inputEle.name = `account${cnt}`;
    inputEle.type = 'text';
    inputEle.value = value;
    
    form_group1.appendChild(label);
    form_group1.appendChild(inputEle);

    form_group2.appendChild(document.createElement('br'));
    form_group2.appendChild(deleteAccountBtn);

    form_group3.appendChild(document.createElement('br'));
    form_group3.appendChild(editAccountBtn);

    div.appendChild(form_group1);
    div.appendChild(form_group2);
    div.appendChild(form_group3);
    div.setAttribute('SN', sn);

    return div;

}

function editAccount(element){
    let account = element.parentElement.parentElement.getElementsByTagName('input')[0].value;
    let units = document.getElementById('units').value;
    let sn = element.parentElement.parentElement.getAttribute('SN');
    let oldAccount = null;
    units = JSON.parse(units);

    for (let i = 0; i < units.length; ++i) {
        if (units[i]['SN'] == sn) {
            oldAccount = units[i]['account'];
            break;
        }
    }

    current_data = {
        'SN': sn,
        'account': account
    };

    document.getElementById('editModalLabel').innerText = `確認要將 ${oldAccount} 改為 ${account} 嗎?`;
    document.getElementById('editModal-btn').click();
}

async function editAccountPost(){
    var csrf = document.querySelector('meta[name="_token"]').content;
    document.getElementById('editModal-close').click();

    let result = await fetch('/units/account', {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrf
        },
        body: JSON.stringify(current_data)
    });

    result = await result.json();
    if (result['status'] == 'success') {
        document.getElementById('successModal-btn').click();
        document.getElementById('success-msg').innerText = result['msg'];
        updateAfterEdit(result['newAccount'], result['SN']);
    } else {
        document.getElementById('failedModal-btn').click();
        document.getElementById('failed-msg').innerText = result['msg'];
    }
}

function updateAfterEdit(newAccount, sn){
    let form = document.getElementsByTagName('form');
    let account_idx = form[0].children.length - 2;

    for (let i = 1; i <= account_idx; ++i){
        if (form[0].children[i].getAttribute('SN') == sn) {
            form[0].children[i].getElementsByTagName('input')[0].value = newAccount;
            break;
        }
    }
}

function addAccount() {
    let input = document.getElementById('newAccount');
    let btn = document.getElementById('changeAccountBtn');
    document.getElementById('addModal-btn').click();
    document.getElementById('addModalLabel').innerText = `新增 ${current_unit} 帳號`;
    input.setAttribute('placeholder', '請輸入新帳號');
    input.type = 'email';
    input.value = '';
    btn.onclick = addAccountPost;
    btn.innerText = '確認新增';
}

function changePassword() {
    let input = document.getElementById('newAccount');
    let btn = document.getElementById('changeAccountBtn');
    document.getElementById('addModal-btn').click();
    document.getElementById('addModalLabel').innerText = `更改 ${current_unit} 密碼`;
    input.setAttribute('placeholder', '請輸入新密碼');
    input.type = 'password';
    input.value = '';
    btn.onclick = changePasswordPost;
    btn.innerText = '確認更改';
}

function changePasswordPost() {
    let csrf = document.querySelector('meta[name="_token"]').content;
    let newPassword = document.getElementById('newAccount').value;
    if (newPassword == '') {
        alert('新密碼不能為空');
        return;
    }
    document.getElementById('addModal-close').click();

    fetch(`/units/password/`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrf
        },
        body: JSON.stringify({
            'unit': current_unit,
            'password': newPassword
        })
    })
    .then(response => response.json())
    .then(result => {
        if (result['status'] == 'success') {
            document.getElementById('successModal-btn').click();
            document.getElementById('success-msg').innerText = result['msg'] + '\n密碼為:' + result['password'];
        } else {
            document.getElementById('failedModal-btn').click();
            document.getElementById('failed-msg').innerText = result['msg'];
        }
    })
}

async function addAccountPost(){
    var csrf = document.querySelector('meta[name="_token"]').content;
    let newAccount = document.getElementById('newAccount').value;
    if (newAccount == '') {
        alert('新帳號不能為空');
        return;
    }
    document.getElementById('addModal-close').click();

    let result = await fetch('/units/account', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrf
        },
        body: JSON.stringify({
            'unit': current_unit,
            'account': newAccount
        })
    });

    result = await result.json();
    if (result['status'] == 'success') {
        document.getElementById('successModal-btn').click();
        document.getElementById('success-msg').innerText = result['msg'];
        updateAfterAdd(result['newAccount'], result['SN']);

    } else {
        document.getElementById('failedModal-btn').click();
        document.getElementById('failed-msg').innerText = result['msg'];
    }
}

function updateAfterAdd(newAccount, sn){
    let form = document.getElementsByTagName('form');
    let account_idx = form[0].children.length - 2;

    let div = AccountInput(account_idx, newAccount, sn);
    form[0].insertBefore(div, form[0].children[account_idx]);
}

function deleteAccount(element){
    let SN = element.parentElement.parentElement.getAttribute('SN');

    current_data = {
        'SN': SN
    };

    deleteElement = element.parentElement.parentElement;

    document.getElementById('deleteModal-btn').click();
    
}

async function deleteAccountPost(){
    var csrf = document.querySelector('meta[name="_token"]').content;
    document.getElementById('deleteModal-close').click();

    let result = await fetch('/units/account', {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrf
        },
        body: JSON.stringify(current_data)
    });

    result = await result.json();
    if (result['status'] == 'success') {
        document.getElementById('successModal-btn').click();
        document.getElementById('success-msg').innerText = result['msg'];
        updateAfterDelete();
    } else if (result['status'] == 'failed') {
        document.getElementById('failedModal-btn').click();
        document.getElementById('failed-msg').innerText = result['msg'];
    }


}

function updateAfterDelete(){
    deleteElement.remove();

    let form = document.getElementsByTagName('form');
    let account_idx = form[0].children.length - 2;

    for (let i = 1; i <= account_idx; ++i){
        let labels = form[0].children[i].getElementsByTagName('label');
        if (labels.length !== 0) {
            labels[0].innerHTML = `帳號 ${i}`;    
        }
        
    }
}