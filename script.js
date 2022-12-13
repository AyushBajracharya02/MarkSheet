const accounttype = document.querySelector('#accounttype');

accounttype.addEventListener('change',function(e) {
    const idsection = document.querySelector('#studentoradmin');
    if(accounttype.value == 'S'){
        const studentidemelents = `
        <label for="studentid">Student ID</label>
        <input type="text" name="studentid">`;
        idsection.innerHTML = studentidemelents;
    }
    if(accounttype.value == 'A'){
        const adminidelements = `
        <label for="adminid">Admin ID</label>
        <input type="text" name="adminid">`;
        idsection.innerHTML = adminidelements;
    }
});

