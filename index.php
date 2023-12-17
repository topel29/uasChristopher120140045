<?php
session_start();
if (isset($_GET['KEY'])) {
    $_SESSION['KEY'] = $_GET['KEY'];
    //reload to base
    header("Location: http://localhost/uaspemweb/");
}
if (isset($_GET['reset'])) {
    session_destroy();
    //reload to base
    header("Location: http://localhost/uaspemweb/");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Resolution for 2024</title>
    <link rel="stylesheet" href="./dist/style.css">
</head>

<body>
    <h1>My Resolution for 2024</h1>

    <form id="resolutionForm">
        <label for="goal">Goal:</label>
        <input type="text" id="goal" name="goal" required onchange="goalInputHandler(this)"><br>

        <label for="timeStart">Time Start:</label>
        <input type="date" id="timeStart" name="timeStart" required onchange="timeStartInputHandler(this)"><br>

        <label for="detail">Detail:</label>
        <textarea id="detail" name="detail" rows="4" onchange="detailInputHandler(this)" required></textarea><br>

        <label for="priority">Priority (1 to 5):</label>
        <input type="number" id="priority" name="priority" min="1" max="5" onchange="priorityInputHandler(this)"
            required><br>

        <button type="button" onclick="addResolution()">Add Resolution</button>
        <a href="http://localhost/uaspemweb?reset=1">
            <button type="button">Reset Key</button>
        </a>
    </form>

    <h2>Resolution Table</h2>
    <table id="resolutionTable">
        <thead>
            <tr>
                <th>id</th>
                <th>Goal</th>
                <th>Time Start</th>
                <th>Detail</th>
                <th>Priority</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <!-- Data will be dynamically added here -->

        </tbody>
    </table>
    <script>
        function setCookie(cname, cvalue, exdays) {
            const d = new Date();
            d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
            let expires = "expires=" + d.toUTCString();
            document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
        }
        function getCookie(cname) {
            let name = cname + "=";
            let decodedCookie = decodeURIComponent(document.cookie);
            let ca = decodedCookie.split(';');
            for (let i = 0; i < ca.length; i++) {
                let c = ca[i];
                while (c.charAt(0) == ' ') {
                    c = c.substring(1);
                }
                if (c.indexOf(name) == 0) {
                    return c.substring(name.length, c.length);
                }
            }
            return "";
        }

        function getKey() {
            var key = prompt("Masukkan private key untuk menyimpan(string):", "");
            if (key !== null && key !== "") {
                //call api
                window.location = "http://localhost/uaspemweb?KEY=" + key;
            } else {
                getKey();
            }
        }
        function deleteAllCookies() {
            const cookies = document.cookie.split(";");
            for (let i = 0; i < cookies.length; i++) {
                const cookie = cookies[i];
                const eqPos = cookie.indexOf("=");
                const name = eqPos > -1 ? cookie.substr(0, eqPos) : cookie;
                document.cookie = name + "=;expires=Thu, 01 Jan 1970 00:00:00 GMT";
            }
        }
        // Call the function when the page loads
        <?php
        if (isset($_GET['reset'])) {
            echo "deleteAllCookies();";
        }
        if (isset($_SESSION['KEY'])) {
            print("console.log('KEY: " . $_SESSION['KEY'] . "')");
        } else {
            echo "window.onload = getKey();";
        }
        ?>
    </script>
    <script>
        function goalInputHandler(thisObject) {
            setCookie("goal", thisObject.value, 1);
        }
        function timeStartInputHandler(thisObject) {
            setCookie("timeStart", thisObject.value, 1);
        }
        function detailInputHandler(thisObject) {
            setCookie("detail", thisObject.value, 1);
        }
        function priorityInputHandler(thisObject) {
            setCookie("priority", thisObject.value, 1);
        }
        function inputHandler() {
            var goal = getCookie("goal");
            var timeStart = getCookie("timeStart");
            var detail = getCookie("detail");
            var priority = getCookie("priority");
            document.getElementById("goal").value = goal;
            document.getElementById("timeStart").value = timeStart;
            document.getElementById("detail").value = detail;
            document.getElementById("priority").value = priority;
        }
        function resetInput() {
            setCookie("goal", "", 1);
            setCookie("timeStart", "", 1);
            setCookie("detail", "", 1);
            setCookie("priority", "", 1);
            inputHandler();
            console.log("clear Input");
        }
        window.onload = inputHandler();
    </script>
    <script>
        function addRow(goal, timeStart, detail, priority, id) {
            // Create a new table row
            var table = document.getElementById("resolutionTable").getElementsByTagName('tbody')[0];
            var newRow = table.insertRow(table.rows.length);

            // Insert cells with form values
            var cellid = newRow.insertCell(0);
            var cell1 = newRow.insertCell(1);
            var cell2 = newRow.insertCell(2);
            var cell3 = newRow.insertCell(3);
            var cell4 = newRow.insertCell(4);
            var cell5 = newRow.insertCell(5);

            cellid.innerHTML = id;
            cell1.innerHTML = goal;
            cell2.innerHTML = timeStart;
            cell3.innerHTML = detail;
            cell4.innerHTML = priority;
            cell5.innerHTML = `<button type="button" onclick="deleteRow(this,${id})">Delete</button>`;
        }
        function deleteRow(thisObject, id) {
            fetch('http://localhost/uaspemweb/api.php', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    "id": id
                })
            })
                .then(response => response.json())
                .then(data => {
                    console.log('Success:', data);
                    //reload
                    window.location = "http://localhost/uaspemweb/";
                })
                .catch((error) => {
                    console.error('Error:', error);
                    alert("Error: " + error);
                });
        }
        function renderResolution() {
            fetch('http://localhost/uaspemweb/api.php')
                .then(response => response.json())
                .then(data => {
                    console.log('Success:', data);
                    data.forEach(resolution => {
                        addRow(resolution.goal, resolution.start_time, resolution.detail, resolution.priority, resolution.id);
                    });
                })
                .catch((error) => {
                    console.error('Error:', error);
                });
        }
        function validateInput(goal, timeStart, detail, priority) {
            if (goal == "") {
                alert("Goal must be filled out");
                return false;
            }
            if (timeStart == "") {
                alert("Time Start must be filled out");
                return false;
            }
            if (detail == "") {
                alert("Detail must be filled out");
                return false;
            }
            if (priority == "") {
                alert("Priority must be filled out");
                return false;
            }
            return true;
        }
        function addResolution() {
            // Get form values
            var goal = document.getElementById("goal").value;
            var timeStart = document.getElementById("timeStart").value;
            var detail = document.getElementById("detail").value;
            var priority = document.getElementById("priority").value;
            console.log(goal, timeStart, detail, priority);
            if (!validateInput(goal, timeStart, detail, priority)) {
                return false;
            }
            fetch('http://localhost/uaspemweb/api.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    "goal": goal,
                    "time_start": timeStart,
                    "detail": detail,
                    "priority": priority
                })
            })
                .then(response => response.json())
                .then(data => {
                    console.log('Success:', data);
                    addRow(goal, timeStart, detail, priority);
                    resetInput();
                    //reload
                    window.location = "http://localhost/uaspemweb/";
                })
                .catch((error) => {
                    console.error('Error:', error);
                    alert("Error: " + error);
                });

        }
    </script>

    <script>
        renderResolution();
    </script>

</body>

</html>