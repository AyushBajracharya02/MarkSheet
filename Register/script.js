const accType = document.querySelector('#accounttype');

accType.addEventListener('change',function(e){
    const classorpostion = document.querySelector('#classorposition');
    const rollnoorcontact = document.querySelector("#rollnoorcontact");
    if(accType.value == "S"){
        const classelements = `
        <label for="class">Class</label>
        <select name="class" id="">
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
            <option value="6">6</option>
            <option value="7">7</option>
            <option value="8">8</option>
            <option value="9">9</option>
            <option value="10">10</option>
        </select>
        <div class="error"></div>`;
        classorpostion.innerHTML = classelements;
        const rollnoelements = `
        <label for="rollno">Roll Number</label>
        <input type="number" name="rollno" id="">
        <div class="error"></div>`;
        rollnoorcontact.innerHTML = rollnoelements;
    }
    if(accType.value == "A"){
        const positionelements = `
        <label for="position">Position</label>
        <select name="position" id="">
            <option value="Principal">Principal</option>
            <option value="Vice-Principal">Vice-Principal</option>
            <option value="Coordinator">Coordinator</option>
        </select>
        <div class="error"></div>`;
        classorpostion.innerHTML = positionelements;
        const contactelements = `
        <label for="contact">Contact</label>
        <input type="tel" name="contact" id="">
        <div class="error"></div>`;
        rollnoorcontact.innerHTML = contactelements;
    }
});