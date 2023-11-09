const addStatus = document.getElementById('addStatus');

// check if addStatus containes 'Success'
if (addStatus.value.includes('Success')) {
    const addbtn = document.getElementById('addModal-Success-btn');
    addbtn.click();
}

if (addStatus.value.includes('Failed')) {
    const addbtn = document.getElementById('addModal-Failed-btn');
    addbtn.click();
}