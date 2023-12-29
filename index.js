function showIntroduction(){
    document.getElementById('introduction').style.display="block";
    document.getElementById('reservation').style.display="none";
    document.getElementById('location').style.display="none";
}
function showReservation(){
    document.getElementById('introduction').style.display="none";
    document.getElementById('reservation').style.display="block";
    document.getElementById('location').style.display="none";
}
function showLocation(){
    document.getElementById('introduction').style.display="none";
    document.getElementById('reservation').style.display="none";
    document.getElementById('location').style.display="block";
}

function fetchAvailableTables() {
    const selectDate = document.getElementById('reservation-date').value;
    const selectTime = document.getElementById('reservation-time').value;

    // const availableTables = ['Table1', 'Table2', 'Table3', 'Table4', 'Table5'];
    fetch(`getTables.php?date=${selectDate}&time=${selectTime}`)
        .then(res => res.json())
        .then(res => {
            if (res.status == "OK") {
                const availableTablesElements = document.getElementById('available-tables');
                availableTablesElements.innerHTML = "<p>Available Tables: " + res.tables.join(", ") + "</p>";
            }
            else {
                console.log(res.message);
            }
        });
}

function showUserDetails(){
    document.getElementById('reservation-form').style.display = 'none';
    document.getElementById('details').style.display='block';
}

function backToPreviousPage(){
    document.getElementById('reservation-form').style.display = 'block';
    document.getElementById('details').style.display='none';
}

